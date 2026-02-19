from django.contrib import admin
from .models import InvoiceSeries, Invoice, InvoiceLine, Quote, QuoteLine, CustomerPayment


class InvoiceLineInline(admin.TabularInline):
    model = InvoiceLine
    extra = 0
    raw_id_fields = ('product',)
    fields = ('line_number', 'product', 'descripcion', 'cantidad', 'valor_unitario', 'valor_venta', 'codigo_tipo_afectacion', 'igv_monto', 'importe_total')


@admin.register(InvoiceSeries)
class InvoiceSeriesAdmin(admin.ModelAdmin):
    list_display = ('serie', 'tipo_documento', 'company', 'warehouse', 'next_number', 'last_number', 'is_active', 'is_default')
    list_filter = ('is_active', 'tipo_documento')
    search_fields = ('serie',)
    raw_id_fields = ('company', 'warehouse')


@admin.register(Invoice)
class InvoiceAdmin(admin.ModelAdmin):
    list_display = ('tipo_documento', 'serie', 'numero', 'cliente_razon_social', 'total', 'status', 'sunat_response_code', 'fecha_emision', 'company')
    list_filter = ('status', 'tipo_documento', 'fecha_emision')
    search_fields = ('cliente_razon_social', 'cliente_numero_documento', 'serie', 'numero', 'sunat_response_code')
    raw_id_fields = ('company', 'customer', 'warehouse', 'created_by')
    inlines = [InvoiceLineInline]
    date_hierarchy = 'fecha_emision'
    fieldsets = (
        (None, {'fields': ('company', 'tipo_documento', 'serie', 'numero', 'fecha_emision', 'hora_emision', 'customer')}),
        ('Cliente (snapshot)', {'fields': ('cliente_tipo_documento', 'cliente_numero_documento', 'cliente_razon_social', 'cliente_direccion')}),
        ('Totales', {'fields': ('subtotal', 'igv_total', 'total', 'status')}),
        ('SUNAT', {
            'fields': ('sunat_response_code', 'sunat_response_message', 'xml_path', 'cdr_path', 'pdf_path'),
            'description': 'Respuesta y archivos tras enviar a SUNAT (POST /api/v1/invoices/<id>/send-sunat/).',
        }),
        ('Otros', {'fields': ('warehouse', 'created_by')}),
    )


class QuoteLineInline(admin.TabularInline):
    model = QuoteLine
    extra = 0
    raw_id_fields = ('product',)


@admin.register(Quote)
class QuoteAdmin(admin.ModelAdmin):
    list_display = ('number', 'customer', 'date', 'total', 'status', 'company')
    list_filter = ('status', 'date')
    search_fields = ('number', 'cliente_razon_social')
    raw_id_fields = ('company', 'customer', 'invoice', 'created_by')
    inlines = [QuoteLineInline]
    date_hierarchy = 'date'


@admin.register(CustomerPayment)
class CustomerPaymentAdmin(admin.ModelAdmin):
    list_display = ('invoice', 'date', 'amount', 'payment_method', 'company')
    list_filter = ('date',)
    raw_id_fields = ('company', 'invoice', 'bank', 'cash_register', 'created_by')
    date_hierarchy = 'date'


@admin.register(InvoiceLine)
class InvoiceLineAdmin(admin.ModelAdmin):
    list_display = ('invoice', 'line_number', 'descripcion', 'cantidad', 'valor_unitario', 'codigo_tipo_afectacion', 'igv_monto', 'importe_total')
    list_filter = ('invoice__tipo_documento', 'codigo_tipo_afectacion')
    search_fields = ('descripcion',)
    raw_id_fields = ('invoice', 'product')
