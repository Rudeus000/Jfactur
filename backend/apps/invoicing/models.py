import uuid
from django.db import models


class InvoiceSeries(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='invoice_series'
    )
    warehouse = models.ForeignKey(
        'inventory.Warehouse',
        on_delete=models.CASCADE,
        related_name='invoice_series'
    )
    tipo_documento = models.CharField(max_length=10)  # 01=Factura, 03=Boleta
    serie = models.CharField(max_length=10)
    next_number = models.PositiveIntegerField(default=1)
    last_number = models.PositiveIntegerField(default=0)
    is_active = models.BooleanField(default=True)
    is_default = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'invoice_series'
        verbose_name = 'Serie de comprobante'
        verbose_name_plural = 'Series de comprobantes'
        unique_together = [('company', 'tipo_documento', 'serie')]

    def __str__(self):
        return f"{self.serie} (next: {self.next_number})"


class Invoice(models.Model):
    STATUS_CHOICES = [
        ('draft', 'Borrador'),
        ('sent', 'Enviado'),
        ('accepted', 'Aceptado'),
        ('rejected', 'Rechazado'),
    ]
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='invoices'
    )
    tipo_documento = models.CharField(max_length=10)
    serie = models.CharField(max_length=10)
    numero = models.PositiveIntegerField()
    fecha_emision = models.DateField()
    hora_emision = models.TimeField(null=True, blank=True)
    customer = models.ForeignKey(
        'catalog.Customer',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='invoices'
    )
    cliente_tipo_documento = models.CharField(max_length=10, blank=True)
    cliente_numero_documento = models.CharField(max_length=20, blank=True)
    cliente_razon_social = models.CharField(max_length=200, blank=True)
    cliente_direccion = models.CharField(max_length=255, blank=True)
    subtotal = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    igv_total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    status = models.CharField(max_length=20, default='draft', choices=STATUS_CHOICES)
    xml_path = models.CharField(max_length=500, blank=True)
    cdr_path = models.CharField(max_length=500, blank=True)
    pdf_path = models.CharField(max_length=500, blank=True)
    sunat_response_code = models.CharField(max_length=10, blank=True)
    sunat_response_message = models.TextField(blank=True)
    warehouse = models.ForeignKey(
        'inventory.Warehouse',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='invoices'
    )
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='invoices_created'
    )
    cash_opening = models.ForeignKey(
        'accounting.CashRegisterOpening',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='invoices'
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'invoices'
        verbose_name = 'Factura / Boleta'
        verbose_name_plural = 'Facturas / Boletas'
        unique_together = [('company', 'tipo_documento', 'serie', 'numero')]

    def __str__(self):
        return f"{self.tipo_documento} {self.serie}-{self.numero}"


class InvoiceLine(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    invoice = models.ForeignKey(
        Invoice,
        on_delete=models.CASCADE,
        related_name='invoice_lines'
    )
    line_number = models.PositiveIntegerField()
    product = models.ForeignKey(
        'catalog.Product',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='invoice_lines'
    )
    descripcion = models.CharField(max_length=500)
    cantidad = models.DecimalField(max_digits=14, decimal_places=4)
    valor_unitario = models.DecimalField(max_digits=14, decimal_places=4)
    valor_venta = models.DecimalField(max_digits=14, decimal_places=2)
    codigo_tipo_afectacion = models.CharField(
        max_length=10, default='10',
        help_text='Catálogo 07 SUNAT: 10=Gravado, 20=Exonerado, 30=Inafecto, 40=Gratuito'
    )
    igv_monto = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    importe_total = models.DecimalField(max_digits=14, decimal_places=2)

    class Meta:
        db_table = 'invoice_lines'
        verbose_name = 'Línea de factura'
        verbose_name_plural = 'Líneas de factura'
        unique_together = [('invoice', 'line_number')]

    def __str__(self):
        return f"Line {self.line_number}: {self.descripcion}"


class Quote(models.Model):
    """Cotización (presupuesto antes de la venta)."""
    STATUS_CHOICES = [
        ('draft', 'Borrador'),
        ('sent', 'Enviada'),
        ('accepted', 'Aceptada'),
        ('rejected', 'Rechazada'),
        ('expired', 'Vencida'),
    ]
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='quotes'
    )
    customer = models.ForeignKey(
        'catalog.Customer',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='quotes'
    )
    number = models.CharField(max_length=30, blank=True)
    date = models.DateField()
    valid_until = models.DateField(null=True, blank=True)
    cliente_tipo_documento = models.CharField(max_length=10, blank=True)
    cliente_numero_documento = models.CharField(max_length=20, blank=True)
    cliente_razon_social = models.CharField(max_length=200, blank=True)
    cliente_direccion = models.CharField(max_length=255, blank=True)
    subtotal = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    igv_total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    status = models.CharField(max_length=20, default='draft', choices=STATUS_CHOICES)
    notes = models.TextField(blank=True)
    invoice = models.ForeignKey(
        Invoice,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='quotes'
    )
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='quotes_created'
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'quotes'
        verbose_name = 'Cotización'
        verbose_name_plural = 'Cotizaciones'
        ordering = ['-date', '-created_at']

    def __str__(self):
        return f"Cotización {self.number or self.id}"


class QuoteLine(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    quote = models.ForeignKey(Quote, on_delete=models.CASCADE, related_name='lines')
    line_number = models.PositiveIntegerField()
    product = models.ForeignKey(
        'catalog.Product',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='quote_lines'
    )
    description = models.CharField(max_length=500)
    quantity = models.DecimalField(max_digits=14, decimal_places=4)
    unit_price = models.DecimalField(max_digits=14, decimal_places=4)
    subtotal = models.DecimalField(max_digits=14, decimal_places=2)
    igv_amount = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    total = models.DecimalField(max_digits=14, decimal_places=2)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'quote_lines'
        verbose_name = 'Línea de cotización'
        verbose_name_plural = 'Líneas de cotización'
        unique_together = [('quote', 'line_number')]

    def __str__(self):
        return f"Línea {self.line_number}: {self.description}"


class CustomerPayment(models.Model):
    """Cobro / abono del cliente (cuentas por cobrar)."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='customer_payments'
    )
    invoice = models.ForeignKey(
        Invoice,
        on_delete=models.CASCADE,
        related_name='payments'
    )
    date = models.DateField()
    amount = models.DecimalField(max_digits=14, decimal_places=2)
    payment_method = models.CharField(max_length=50, blank=True)
    reference = models.CharField(max_length=100, blank=True)
    bank = models.ForeignKey(
        'accounting.Bank',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='customer_payments'
    )
    cash_register = models.ForeignKey(
        'accounting.CashRegister',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='customer_payments'
    )
    cash_opening = models.ForeignKey(
        'accounting.CashRegisterOpening',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='customer_payments'
    )
    notes = models.TextField(blank=True)
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='customer_payments_created'
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'customer_payments'
        verbose_name = 'Cobro (cuentas por cobrar)'
        verbose_name_plural = 'Cobros (cuentas por cobrar)'
        ordering = ['-date', '-created_at']

    def __str__(self):
        return f"Cobro {self.amount} - {self.invoice}"
