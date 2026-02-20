from datetime import datetime
from decimal import Decimal

from django.db.models import Sum, Count
from django.http import HttpResponse

from rest_framework import viewsets, status
from rest_framework.decorators import action
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from rest_framework.views import APIView

from .models import InvoiceSeries, Invoice, InvoiceLine, Quote, QuoteLine, CustomerPayment
from .serializers import (
    InvoiceSeriesSerializer, InvoiceSerializer, InvoiceWriteSerializer,
    QuoteSerializer, QuoteWriteSerializer,
    CustomerPaymentSerializer, CustomerPaymentWriteSerializer,
)
from .sunat_service import send_invoice_to_sunat


def filter_by_company(queryset, request):
    if request.user.is_superuser:
        return queryset
    if request.user.company_id:
        return queryset.filter(company_id=request.user.company_id)
    return queryset.none()


class InvoiceSeriesViewSet(viewsets.ModelViewSet):
    serializer_class = InvoiceSeriesSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(InvoiceSeries.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class InvoiceViewSet(viewsets.ModelViewSet):
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Invoice.objects.all(), self.request)

    def get_serializer_class(self):
        if self.action == 'create':
            return InvoiceWriteSerializer
        return InvoiceSerializer

    def perform_create(self, serializer):
        serializer.save()

    @action(detail=True, methods=['post'], url_path='send-sunat')
    def send_sunat(self, request, pk=None):
        """
        Envía la factura a SUNAT (Jfactur).
        Body: { "clave_sol": "clave SOL", "certificado_password": "opcional, si difiere" }
        """
        invoice = self.get_object()
        clave_sol = request.data.get('clave_sol') or request.query_params.get('clave_sol')
        if not clave_sol:
            return Response(
                {'error': 'clave_sol es obligatoria en el body o como query param.'},
                status=status.HTTP_400_BAD_REQUEST
            )
        cert_password = request.data.get('certificado_password') or clave_sol
        result = send_invoice_to_sunat(invoice, clave_sol, cert_password)
        if result.get('respuesta') == 'error' and not result.get('cod_sunat'):
            return Response(
                {'error': result.get('mensaje', 'Error al enviar a SUNAT')},
                status=status.HTTP_400_BAD_REQUEST
            )
        return Response({
            'respuesta': result.get('respuesta'),
            'cod_sunat': result.get('cod_sunat'),
            'mensaje': result.get('mensaje'),
            'invoice_status': invoice.status,
        }, status=status.HTTP_200_OK)

    @action(detail=True, methods=['get'], url_path='ticket')
    def ticket(self, request, pk=None):
        """Ticket/volante HTML de la factura/boleta (formato representación impresa, con logo empresa)."""
        invoice = self.get_object()
        company = invoice.company
        tipo_label = 'FACTURA' if invoice.tipo_documento == '01' else 'BOLETA DE VENTA'
        hora = getattr(invoice, 'hora_emision', None) or ''
        if hora and len(str(hora)) > 8:
            hora = str(hora)[:8]
        logo_html = ''
        if company and getattr(company, 'logo_url', None) and company.logo_url.strip():
            logo_html = f'<img src="{company.logo_url}" alt="Logo" style="max-width: 120px; max-height: 80px; display: block; margin: 0 auto 0.5rem;" />'
        company_block = ''
        if company:
            company_block = f'''
  <div style="text-align: center; margin-bottom: 0.75rem;">
    {logo_html}
    <p style="margin: 0; font-weight: bold;">{company.nombre_comercial or company.razon_social}</p>
    <p style="margin: 0.2rem 0; font-size: 0.9em;">{company.razon_social}</p>
    <p style="margin: 0; font-size: 0.85em;">RUC: {company.ruc}</p>
    <p style="margin: 0.2rem 0; font-size: 0.85em;">{company.domicilio_fiscal or ""}</p>
  </div>'''
        lines_html = ''.join(
            f'<tr><td style="border:1px solid #ddd; padding:4px;">{l.descripcion or (l.product.sku if l.product else "-")}</td>'
            f'<td style="border:1px solid #ddd; padding:4px; text-align:right;">{l.cantidad}</td>'
            f'<td style="border:1px solid #ddd; padding:4px; text-align:right;">{l.valor_unitario}</td>'
            f'<td style="border:1px solid #ddd; padding:4px; text-align:right;">{l.importe_total}</td></tr>'
            for l in invoice.invoice_lines.all()
        )
        html = f'''
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>{tipo_label} {invoice.serie}-{invoice.numero}</title></head>
<body style="font-family: Arial, sans-serif; max-width: 320px; margin: 1rem auto; font-size: 12px;">
  {company_block}
  <div style="text-align: center; border: 1px solid #333; padding: 0.5rem; margin-bottom: 0.75rem;">
    <p style="margin: 0; font-weight: bold;">{tipo_label}</p>
    <p style="margin: 0.2rem 0;">{invoice.serie}-{invoice.numero}</p>
  </div>
  <p><b>Fecha:</b> {invoice.fecha_emision} {hora}</p>
  <p><b>Cliente:</b> {invoice.cliente_razon_social or "—"}</p>
  <p><b>RUC/DNI:</b> {invoice.cliente_numero_documento or "—"}</p>
  <p><b>Dirección:</b> {invoice.cliente_direccion or "—"}</p>
  <hr/>
  <table style="width:100%; border-collapse: collapse; font-size: 11px;">
    <tr style="background: #f0f0f0;"><th style="border:1px solid #ddd; padding:4px; text-align:left;">Descripción</th><th style="border:1px solid #ddd; padding:4px;">Cant</th><th style="border:1px solid #ddd; padding:4px;">P.Unit</th><th style="border:1px solid #ddd; padding:4px;">Total</th></tr>
    {lines_html}
  </table>
  <hr/>
  <p>Subtotal: S/ {invoice.subtotal}</p>
  <p>IGV: S/ {invoice.igv_total}</p>
  <p><b>Total: S/ {invoice.total}</b></p>
  <p style="font-size: 10px; color: #666; margin-top: 1rem;">Representación impresa del comprobante electrónico. Consulte en SUNAT si corresponde.</p>
</body>
</html>'''
        return HttpResponse(html, content_type='text/html; charset=utf-8')


class QuoteViewSet(viewsets.ModelViewSet):
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Quote.objects.all(), self.request)

    def get_serializer_class(self):
        if self.action in ('create', 'update', 'partial_update'):
            return QuoteWriteSerializer
        return QuoteSerializer

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id, created_by=self.request.user)
        else:
            serializer.save(created_by=self.request.user)

    @action(detail=True, methods=['post'], url_path='convert-to-invoice')
    def convert_to_invoice(self, request, pk=None):
        """Convierte la cotización en factura: crea Invoice desde Quote y vincula quote.invoice, quote.status=accepted."""
        from .models import InvoiceSeries
        quote = self.get_object()
        if quote.invoice_id:
            return Response(
                {'error': 'Esta cotización ya tiene una factura asociada.'},
                status=status.HTTP_400_BAD_REQUEST
            )
        company_id = quote.company_id or request.user.company_id
        if not company_id:
            return Response(
                {'error': 'No se puede determinar la empresa.'},
                status=status.HTTP_400_BAD_REQUEST
            )
        tipo_documento = request.data.get('tipo_documento', '01')
        serie = request.data.get('serie', '')
        warehouse_id = request.data.get('warehouse_id') or (quote.lines.first() and None)
        series_qs = InvoiceSeries.objects.filter(company_id=company_id, is_active=True, tipo_documento=tipo_documento)
        if serie:
            series_qs = series_qs.filter(serie=serie)
        inv_series = series_qs.first()
        if not inv_series:
            return Response(
                {'error': 'No hay serie activa para el tipo/serie indicado.'},
                status=status.HTTP_400_BAD_REQUEST
            )
        serie = inv_series.serie
        from .services import get_next_invoice_number
        numero = get_next_invoice_number(company_id, tipo_documento, serie)
        if numero is None:
            return Response(
                {'error': 'No se pudo obtener el siguiente número de factura.'},
                status=status.HTTP_400_BAD_REQUEST
            )
        invoice = Invoice.objects.create(
            company_id=company_id,
            tipo_documento=tipo_documento,
            serie=serie,
            numero=numero,
            fecha_emision=quote.date,
            customer=quote.customer,
            cliente_tipo_documento=quote.cliente_tipo_documento or '',
            cliente_numero_documento=quote.cliente_numero_documento or '',
            cliente_razon_social=quote.cliente_razon_social or '',
            cliente_direccion=quote.cliente_direccion or '',
            subtotal=quote.subtotal,
            igv_total=quote.igv_total,
            total=quote.total,
            status='draft',
            warehouse_id=warehouse_id or inv_series.warehouse_id,
            created_by=request.user,
        )
        for line in quote.lines.all().order_by('line_number'):
            InvoiceLine.objects.create(
                invoice=invoice,
                line_number=line.line_number,
                product=line.product,
                descripcion=line.description,
                cantidad=line.quantity,
                valor_unitario=line.unit_price,
                valor_venta=line.subtotal,
                codigo_tipo_afectacion='10',
                igv_monto=line.igv_amount,
                importe_total=line.total,
            )
        quote.invoice = invoice
        quote.status = 'accepted'
        quote.save(update_fields=['invoice', 'status', 'updated_at'])
        from .serializers import InvoiceSerializer
        return Response(InvoiceSerializer(invoice).data, status=status.HTTP_201_CREATED)


class CustomerPaymentViewSet(viewsets.ModelViewSet):
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(CustomerPayment.objects.all(), self.request)

    def get_serializer_class(self):
        if self.action == 'create':
            return CustomerPaymentWriteSerializer
        return CustomerPaymentSerializer

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


def _report_queryset_invoices(request):
    qs = Invoice.objects.filter(company_id=request.user.company_id) if request.user.company_id else Invoice.objects.none()
    if request.user.is_superuser:
        qs = Invoice.objects.all()
    return qs


class LibroVentasReport(APIView):
    """Libro electrónico de ventas: facturas/boletas en rango de fechas (formato SUNAT/contabilidad)."""
    permission_classes = [IsAuthenticated]

    def get(self, request):
        date_from = request.query_params.get('date_from')
        date_to = request.query_params.get('date_to')
        if not date_from or not date_to:
            return Response(
                {'error': 'Indique date_from y date_to (YYYY-MM-DD).'},
                status=status.HTTP_400_BAD_REQUEST
            )
        qs = _report_queryset_invoices(request).filter(
            fecha_emision__gte=date_from,
            fecha_emision__lte=date_to,
        ).order_by('fecha_emision', 'serie', 'numero')
        rows = []
        for inv in qs:
            company = inv.company
            rows.append({
                'id': str(inv.id),
                'ruc': company.ruc,
                'tipo_documento': inv.tipo_documento,
                'serie': inv.serie,
                'numero': inv.numero,
                'fecha_emision': str(inv.fecha_emision),
                'cliente_tipo_doc': inv.cliente_tipo_documento,
                'cliente_numero_doc': inv.cliente_numero_documento,
                'cliente_razon_social': inv.cliente_razon_social,
                'moneda': 'PEN',
                'subtotal': float(inv.subtotal),
                'igv_total': float(inv.igv_total),
                'total': float(inv.total),
                'status': inv.status,
                'sunat_codigo': inv.sunat_response_code,
                'sunat_mensaje': inv.sunat_response_message,
            })
        return Response({'date_from': date_from, 'date_to': date_to, 'count': len(rows), 'data': rows})


class VentasPorClienteReport(APIView):
    """Ventas agregadas por cliente en rango de fechas."""
    permission_classes = [IsAuthenticated]

    def get(self, request):
        date_from = request.query_params.get('date_from')
        date_to = request.query_params.get('date_to')
        if not date_from or not date_to:
            return Response(
                {'error': 'Indique date_from y date_to (YYYY-MM-DD).'},
                status=status.HTTP_400_BAD_REQUEST
            )
        qs = _report_queryset_invoices(request).filter(
            fecha_emision__gte=date_from,
            fecha_emision__lte=date_to,
        )
        agg = qs.values('cliente_numero_documento', 'cliente_razon_social').annotate(
            total_ventas=Sum('total'),
            cantidad_comprobantes=Count('id'),
        )
        rows = [{'cliente_numero_documento': x['cliente_numero_documento'], 'cliente_razon_social': x['cliente_razon_social'], 'total_ventas': float(x['total_ventas']), 'cantidad_comprobantes': x['cantidad_comprobantes']} for x in agg]
        return Response({'date_from': date_from, 'date_to': date_to, 'data': rows})


class VentasPorProductoReport(APIView):
    """Ventas agregadas por producto en rango de fechas (desde líneas de factura)."""
    permission_classes = [IsAuthenticated]

    def get(self, request):
        date_from = request.query_params.get('date_from')
        date_to = request.query_params.get('date_to')
        if not date_from or not date_to:
            return Response(
                {'error': 'Indique date_from y date_to (YYYY-MM-DD).'},
                status=status.HTTP_400_BAD_REQUEST
            )
        qs = InvoiceLine.objects.filter(
            invoice__company_id=request.user.company_id,
            invoice__fecha_emision__gte=date_from,
            invoice__fecha_emision__lte=date_to,
        ) if request.user.company_id else InvoiceLine.objects.none()
        if request.user.is_superuser:
            qs = InvoiceLine.objects.filter(
                invoice__fecha_emision__gte=date_from,
                invoice__fecha_emision__lte=date_to,
            )
        agg = qs.values('product__id', 'product__nombre', 'product__sku').annotate(
            cantidad_vendida=Sum('cantidad'),
            total_venta=Sum('importe_total'),
            veces_vendido=Count('id'),
        )
        rows = []
        for x in agg:
            rows.append({
                'product_id': str(x['product__id']) if x['product__id'] else None,
                'product_nombre': x['product__nombre'] or 'Sin producto',
                'product_sku': x['product__sku'] or '',
                'cantidad_vendida': float(x['cantidad_vendida']),
                'total_venta': float(x['total_venta']),
                'veces_vendido': x['veces_vendido'],
            })
        return Response({'date_from': date_from, 'date_to': date_to, 'data': rows})


class AgedReceivableReport(APIView):
    """Saldos por cobrar por cliente, en columnas 0-30, 31-60, 61+ días (desde fecha emisión)."""
    permission_classes = [IsAuthenticated]

    def get(self, request):
        as_of_str = request.query_params.get('as_of')
        as_of = datetime.strptime(as_of_str, '%Y-%m-%d').date() if as_of_str else datetime.now().date()
        qs = _report_queryset_invoices(request).exclude(status='draft')
        rows_by_client = {}
        for inv in qs.select_related('company'):
            paid = inv.payments.aggregate(t=Sum('amount'))['t'] or Decimal('0')
            balance = inv.total - paid
            if balance <= 0:
                continue
            key = (inv.cliente_numero_documento or '', inv.cliente_razon_social or 'Sin nombre')
            if key not in rows_by_client:
                rows_by_client[key] = {
                    'cliente_numero_documento': key[0],
                    'cliente_razon_social': key[1],
                    'total': Decimal('0'),
                    'days_0_30': Decimal('0'),
                    'days_31_60': Decimal('0'),
                    'days_61_plus': Decimal('0'),
                }
            days = (as_of - inv.fecha_emision).days
            if days <= 30:
                bucket = 'days_0_30'
            elif days <= 60:
                bucket = 'days_31_60'
            else:
                bucket = 'days_61_plus'
            rows_by_client[key]['total'] += balance
            rows_by_client[key][bucket] += balance
        data = [
            {
                'cliente_numero_documento': r['cliente_numero_documento'],
                'cliente_razon_social': r['cliente_razon_social'],
                'total': float(r['total']),
                'days_0_30': float(r['days_0_30']),
                'days_31_60': float(r['days_31_60']),
                'days_61_plus': float(r['days_61_plus']),
            }
            for r in rows_by_client.values()
        ]
        return Response({'as_of': str(as_of), 'data': data})


class AgedPayableReport(APIView):
    """Saldos por pagar por proveedor, columnas 0-30, 31-60, 61+ días (desde fecha vencimiento o fecha doc)."""
    permission_classes = [IsAuthenticated]

    def get(self, request):
        from apps.purchasing.models import Purchase
        as_of_str = request.query_params.get('as_of')
        as_of = datetime.strptime(as_of_str, '%Y-%m-%d').date() if as_of_str else datetime.now().date()
        qs = Purchase.objects.filter(company_id=request.user.company_id).exclude(status='cancelled') if request.user.company_id else Purchase.objects.none()
        if request.user.is_superuser:
            qs = Purchase.objects.exclude(status='cancelled')
        rows_by_supplier = {}
        for pur in qs.select_related('supplier'):
            balance = pur.get_balance_due()
            if balance <= 0:
                continue
            due = pur.due_date or pur.date
            key = (pur.supplier.ruc or '', pur.supplier.razon_social or 'Sin nombre')
            if key not in rows_by_supplier:
                rows_by_supplier[key] = {
                    'supplier_ruc': key[0],
                    'supplier_razon_social': key[1],
                    'total': Decimal('0'),
                    'days_0_30': Decimal('0'),
                    'days_31_60': Decimal('0'),
                    'days_61_plus': Decimal('0'),
                }
            days = (as_of - due).days
            if days <= 30:
                bucket = 'days_0_30'
            elif days <= 60:
                bucket = 'days_31_60'
            else:
                bucket = 'days_61_plus'
            rows_by_supplier[key]['total'] += balance
            rows_by_supplier[key][bucket] += balance
        data = [
            {
                'supplier_ruc': r['supplier_ruc'],
                'supplier_razon_social': r['supplier_razon_social'],
                'total': float(r['total']),
                'days_0_30': float(r['days_0_30']),
                'days_31_60': float(r['days_31_60']),
                'days_61_plus': float(r['days_61_plus']),
            }
            for r in rows_by_supplier.values()
        ]
        return Response({'as_of': str(as_of), 'data': data})


class DashboardReport(APIView):
    """Resumen: ventas del día, facturas pendientes SUNAT, total por cobrar/pagar, alertas (certificado, stock bajo)."""
    permission_classes = [IsAuthenticated]

    def get(self, request):
        from apps.purchasing.models import Purchase
        today = datetime.now().date()
        company_id = getattr(request.user, 'company_id', None)
        if not company_id and not request.user.is_superuser:
            return Response({'ventas_hoy': 0, 'facturas_pendientes_sunat': 0, 'total_por_cobrar': 0, 'total_por_pagar': 0, 'alertas': []})

        inv_qs = Invoice.objects.filter(fecha_emision=today)
        if company_id:
            inv_qs = inv_qs.filter(company_id=company_id)
        ventas_hoy = inv_qs.aggregate(t=Sum('total'))['t'] or Decimal('0')

        pendientes = Invoice.objects.filter(status__in=('draft', 'sent'))
        if company_id:
            pendientes = pendientes.filter(company_id=company_id)
        facturas_pendientes_sunat = pendientes.count()

        total_por_cobrar = Decimal('0')
        for inv in _report_queryset_invoices(request).exclude(status='draft'):
            paid = inv.payments.aggregate(t=Sum('amount'))['t'] or Decimal('0')
            total_por_cobrar += (inv.total - paid)

        total_por_pagar = Decimal('0')
        pur_qs = Purchase.objects.filter(company_id=company_id).exclude(status='cancelled') if company_id else Purchase.objects.none()
        if request.user.is_superuser:
            pur_qs = Purchase.objects.exclude(status='cancelled')
        for pur in pur_qs:
            total_por_pagar += pur.get_balance_due()

        alertas = []
        if company_id:
            from apps.core.models import Company
            company = Company.objects.filter(id=company_id).first()
            if company:
                certs = company.digital_certificates.filter(is_active=True)
                if not certs.exists():
                    alertas.append({'tipo': 'certificado', 'mensaje': 'No hay certificado digital activo.'})

        return Response({
            'ventas_hoy': float(ventas_hoy),
            'facturas_pendientes_sunat': facturas_pendientes_sunat,
            'total_por_cobrar': float(total_por_cobrar),
            'total_por_pagar': float(total_por_pagar),
            'alertas': alertas,
        })
