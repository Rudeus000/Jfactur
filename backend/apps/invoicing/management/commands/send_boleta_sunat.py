"""
Intenta enviar una boleta a SUNAT (homologación o producción).

Usuario de prueba SUNAT (homologación / BETA):
  SUNAT proporciona credenciales para probar sin certificado registrado:
  - Usuario SOL: MODDATOS  (el usuario enviado es [RUC]+MODDATOS, ej. 20100000001MODDATOS)
  - Clave SOL:   MODDATOS
  Ref: https://orientacion.sunat.gob.pe/12-pautas-servicio-beta

Con load_sample_data la empresa demo ya tiene usuario_sol=MODDATOS. Para enviar en beta:
  python manage.py send_boleta_sunat --clave-sol=MODDATOS
(Necesitas además un certificado digital .pfx para firmar el XML; en beta no tiene que estar registrado en SUNAT.)

Uso:
  CLAVE_SOL=tu_clave python manage.py send_boleta_sunat
  python manage.py send_boleta_sunat --clave-sol=tu_clave [--company-id=UUID] [--prod]
"""
import os
import tempfile
from decimal import Decimal

from django.core.management.base import BaseCommand

from apps.core.models import Company
from apps.invoicing.models import Invoice, InvoiceLine, InvoiceSeries
from apps.invoicing.services import get_next_invoice_number
from apps.invoicing.sunat_service import (
    build_ubl_invoice_xml,
    sign_xml_with_pfx,
    send_bill_to_sunat,
    get_sunat_ws_url,
)


class Command(BaseCommand):
    help = 'Envía una boleta de prueba a SUNAT para verificar si el servicio recibe el comprobante.'

    def add_arguments(self, parser):
        parser.add_argument(
            '--company-id',
            type=str,
            help='UUID de la empresa. Por defecto la primera empresa activa.',
        )
        parser.add_argument(
            '--clave-sol',
            type=str,
            help='Clave SOL (si no se usa la variable de entorno CLAVE_SOL).',
        )
        parser.add_argument(
            '--prod',
            action='store_true',
            help='Usar URL de producción en lugar de homologación (SUNAT_USE_BETA=False).',
        )
        parser.add_argument(
            '--solo-conexion',
            action='store_true',
            help='Solo verificar que el servidor SUNAT responde (no envía comprobante).',
        )

    def handle(self, *args, **options):
        company_id = options.get('company_id')
        clave_sol = options.get('clave_sol') or os.environ.get('CLAVE_SOL')
        use_prod = options.get('prod')
        solo_conexion = options.get('solo_conexion')

        if solo_conexion:
            self._check_connection(use_prod)
            return

        if not clave_sol:
            self.stderr.write(
                self.style.ERROR(
                    'Indique la clave SOL: variable de entorno CLAVE_SOL o opción --clave-sol=...'
                )
            )
            return

        company = None
        if company_id:
            company = Company.objects.filter(id=company_id, is_active=True).first()
            if not company:
                self.stderr.write(self.style.ERROR(f'Empresa no encontrada: {company_id}'))
                return
        else:
            company = Company.objects.filter(is_active=True).first()
            if not company:
                self.stderr.write(self.style.ERROR('No hay ninguna empresa activa.'))
                return

        self.stdout.write(f'Empresa: {company.razon_social} (RUC {company.ruc})')

        usuario_sol = (company.usuario_sol or '').strip()
        if not usuario_sol:
            self.stderr.write(
                self.style.ERROR(
                    'La empresa no tiene usuario SOL configurado. '
                    'Configúrelo en Admin o en la tabla companies (usuario_sol).'
                )
            )
            return

        cert = company.digital_certificates.filter(is_active=True).first()
        if not cert or not cert.pfx_file_path:
            self.stderr.write(
                self.style.ERROR(
                    'La empresa no tiene certificado digital activo con ruta PFX. '
                    'Agregue uno en Admin (Certificados digitales) con pfx_file_path apuntando al .pfx.'
                )
            )
            return
        if not os.path.isfile(cert.pfx_file_path):
            self.stderr.write(
                self.style.ERROR(f'El archivo del certificado no existe: {cert.pfx_file_path}')
            )
            return

        invoice = Invoice.objects.filter(
            company=company, tipo_documento='03'
        ).order_by('-fecha_emision', '-numero').first()

        if not invoice:
            from datetime import date
            from apps.catalog.models import Customer, Product
            from apps.inventory.models import Warehouse
            series = InvoiceSeries.objects.filter(
                company=company, tipo_documento='03', is_active=True
            ).first()
            if not series:
                self.stderr.write(
                    self.style.ERROR('No hay serie de boleta (03) activa para esta empresa.')
                )
                return
            customer = Customer.objects.filter(company=company).first()
            warehouse = series.warehouse
            product = Product.objects.filter(company=company, is_active=True).first()
            if not customer or not warehouse:
                self.stderr.write(self.style.ERROR('Faltan cliente o almacén para crear la boleta.'))
                return
            numero = get_next_invoice_number(company.id, '03', series.serie)
            if numero is None:
                self.stderr.write(self.style.ERROR('No se pudo obtener número para la boleta.'))
                return
            invoice = Invoice.objects.create(
                company=company,
                tipo_documento='03',
                serie=series.serie,
                numero=numero,
                fecha_emision=date.today(),
                customer=customer,
                cliente_tipo_documento=customer.tipo_documento or '1',
                cliente_numero_documento=customer.numero_documento or '',
                cliente_razon_social=customer.razon_social or 'Cliente Prueba',
                cliente_direccion=customer.direccion or '',
                subtotal=Decimal('10.00'),
                igv_total=Decimal('1.80'),
                total=Decimal('11.80'),
                status='draft',
                warehouse=warehouse,
            )
            InvoiceLine.objects.create(
                invoice=invoice,
                line_number=1,
                product=product,
                descripcion=product.nombre if product else 'Item prueba SUNAT',
                cantidad=Decimal('1'),
                valor_unitario=Decimal('10.00'),
                valor_venta=Decimal('10.00'),
                codigo_tipo_afectacion='10',
                igv_monto=Decimal('1.80'),
                importe_total=Decimal('11.80'),
            )
            self.stdout.write(f'Boleta de prueba creada: {invoice.serie}-{invoice.numero}')
        else:
            self.stdout.write(f'Usando boleta existente: {invoice.serie}-{invoice.numero}')

        from django.conf import settings as django_settings
        if use_prod:
            ruta_ws = getattr(django_settings, 'SUNAT_WS_BILL_PROD', '')
        else:
            ruta_ws = get_sunat_ws_url()
        self.stdout.write(f'URL SUNAT: {ruta_ws or "(homologación por defecto)"}')

        try:
            xml_bytes = build_ubl_invoice_xml(company, invoice)
        except Exception as e:
            self.stderr.write(self.style.ERROR(f'Error al generar XML UBL: {e}'))
            return

        try:
            signed_bytes = sign_xml_with_pfx(xml_bytes, cert.pfx_file_path, clave_sol)
        except Exception as e:
            self.stderr.write(self.style.ERROR(f'Error al firmar XML: {e}'))
            return

        nombre_archivo = f"{invoice.serie}-{invoice.numero}"
        with tempfile.NamedTemporaryFile(suffix='.xml', delete=False) as f:
            f.write(signed_bytes)
            xml_path = f.name
        try:
            result = send_bill_to_sunat(
                ruc=company.ruc,
                usuario_sol=usuario_sol,
                clave_sol=clave_sol,
                xml_path=xml_path,
                nombre_archivo=nombre_archivo,
                ruta_ws=ruta_ws,
            )
        finally:
            try:
                os.unlink(xml_path)
            except OSError:
                pass
            zip_path = xml_path + '.ZIP'
            if os.path.isfile(zip_path):
                try:
                    os.unlink(zip_path)
                except OSError:
                    pass

        invoice.sunat_response_code = result.get('cod_sunat', '')
        invoice.sunat_response_message = result.get('mensaje', '')
        if result.get('respuesta') == 'ok':
            invoice.status = 'accepted' if result.get('cod_sunat') == '0' else 'rejected'
            if result.get('cdr_path') and os.path.isfile(result['cdr_path']):
                invoice.cdr_path = result['cdr_path']
        else:
            invoice.status = 'rejected'
        invoice.save(update_fields=['sunat_response_code', 'sunat_response_message', 'status', 'cdr_path', 'updated_at'])

        self.stdout.write('')
        self.stdout.write(self.style.SUCCESS('--- Respuesta SUNAT ---'))
        self.stdout.write(f"  respuesta: {result.get('respuesta')}")
        self.stdout.write(f"  cod_sunat: {result.get('cod_sunat')}")
        self.stdout.write(f"  mensaje:   {result.get('mensaje')}")
        self.stdout.write(f"  estado factura: {invoice.status}")
        if result.get('respuesta') == 'ok' and result.get('cod_sunat') == '0':
            self.stdout.write(self.style.SUCCESS('SUNAT recibió y aceptó el comprobante (CDR 0).'))
        elif result.get('respuesta') == 'ok':
            self.stdout.write(self.style.WARNING('SUNAT respondió pero con código distinto de 0 (revisar mensaje).'))
        else:
            self.stdout.write(self.style.ERROR('SUNAT no aceptó el envío o hubo error de conexión.'))

    def _check_connection(self, use_prod):
        """Solo verifica que el endpoint SUNAT responda (petición SOAP mínima)."""
        import requests
        from django.conf import settings as django_settings
        if use_prod:
            ruta_ws = getattr(django_settings, 'SUNAT_WS_BILL_PROD', '')
            label = 'Producción'
        else:
            ruta_ws = get_sunat_ws_url()
            label = 'Homologación (beta)'
        if not ruta_ws:
            self.stderr.write(self.style.ERROR('No hay URL SUNAT configurada (SUNAT_WS_BILL_BETA/PROD).'))
            return
        self.stdout.write(f'Comprobando conexión a SUNAT ({label}): {ruta_ws}')
        soap_minimal = '''<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
<soapenv:Body><ser:sendBill xmlns:ser="http://service.sunat.gob.pe"><fileName>test.zip</fileName><contentFile>eA==</contentFile></ser:sendBill></soapenv:Body>
</soapenv:Envelope>'''
        headers = {'Content-Type': 'text/xml; charset="utf-8"', 'Accept': 'text/xml'}
        try:
            r = requests.post(ruta_ws, data=soap_minimal.encode('utf-8'), headers=headers, timeout=15)
            self.stdout.write(f'HTTP estado: {r.status_code}')
            if r.status_code == 200:
                self.stdout.write(self.style.SUCCESS('El servidor SUNAT respondió (200). La URL es accesible.'))
                if 'faultcode' in (r.text or '') or 'faultstring' in (r.text or ''):
                    self.stdout.write('(La respuesta puede ser un error SOAP por credenciales/archivo inválido; la conexión funciona.)')
            else:
                self.stdout.write(self.style.WARNING(f'Respuesta: {r.text[:300] if r.text else ""}'))
        except requests.RequestException as e:
            self.stderr.write(self.style.ERROR(f'Error de conexión: {e}'))
