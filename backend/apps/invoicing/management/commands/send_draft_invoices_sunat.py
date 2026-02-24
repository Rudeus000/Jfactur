"""
Envía a SUNAT todas las facturas/boletas en borrador (draft) de una fecha dada.
Se usa al cierre del día para declarar automáticamente los comprobantes emitidos.

Uso:
  python manage.py send_draft_invoices_sunat --clave-sol=MODDATOS
  python manage.py send_draft_invoices_sunat --date=2026-02-19 --clave-sol=tu_clave
  CLAVE_SOL=tu_clave python manage.py send_draft_invoices_sunat

Para programar al cierre del día (cron en el servidor), ejemplo a las 23:30:
  30 23 * * * cd /ruta/proyecto && CLAVE_SOL=xxx python manage.py send_draft_invoices_sunat
"""
import os
from datetime import date

from django.core.management.base import BaseCommand

from apps.invoicing.models import Invoice
from apps.invoicing.sunat_service import send_invoice_to_sunat


class Command(BaseCommand):
    help = 'Envía a SUNAT todas las facturas/boletas en borrador de la fecha indicada (por defecto hoy).'

    def add_arguments(self, parser):
        parser.add_argument(
            '--date',
            type=str,
            default=None,
            help='Fecha de emisión (YYYY-MM-DD). Por defecto hoy.',
        )
        parser.add_argument(
            '--clave-sol',
            type=str,
            help='Clave SOL. Si no se indica, se usa la variable de entorno CLAVE_SOL.',
        )
        parser.add_argument(
            '--company-id',
            type=str,
            help='UUID de la empresa. Si no se indica, se envían todas las empresas con borradores.',
        )
        parser.add_argument(
            '--dry-run',
            action='store_true',
            help='Solo listar los comprobantes que se enviarían, sin enviar.',
        )

    def handle(self, *args, **options):
        fecha = options.get('date')
        if fecha:
            try:
                from datetime import datetime
                dt = datetime.strptime(fecha, '%Y-%m-%d').date()
            except ValueError:
                self.stderr.write(self.style.ERROR('Formato de fecha inválido. Use YYYY-MM-DD.'))
                return
        else:
            dt = date.today()

        clave_sol = options.get('clave_sol') or os.environ.get('CLAVE_SOL')
        company_id = options.get('company_id')
        dry_run = options.get('dry_run')

        qs = Invoice.objects.filter(status='draft', fecha_emision=dt).order_by('company_id', 'serie', 'numero')
        if company_id:
            qs = qs.filter(company_id=company_id)

        invoices = list(qs)
        if not invoices:
            self.stdout.write(self.style.WARNING(f'No hay facturas/boletas en borrador para la fecha {dt}.'))
            return

        if dry_run:
            self.stdout.write(f'Se enviarían {len(invoices)} comprobante(s) a SUNAT para {dt}:')
            for inv in invoices:
                self.stdout.write(f'  {inv.tipo_documento} {inv.serie}-{inv.numero} ({inv.company.razon_social})')
            return

        if not clave_sol:
            self.stderr.write(
                self.style.ERROR('Indique la clave SOL: variable de entorno CLAVE_SOL o --clave-sol=...')
            )
            return

        ok = 0
        err = 0
        for invoice in invoices:
            result = send_invoice_to_sunat(invoice, clave_sol)
            if result.get('respuesta') == 'ok' and result.get('cod_sunat') == '0':
                self.stdout.write(self.style.SUCCESS(f'{invoice.serie}-{invoice.numero}: Aceptado por SUNAT.'))
                ok += 1
            else:
                self.stdout.write(
                    self.style.ERROR(
                        f'{invoice.serie}-{invoice.numero}: {result.get("mensaje", "Error")}'
                    )
                )
                err += 1

        self.stdout.write(f'Resumen: {ok} enviado(s) correctamente, {err} error(es).')
