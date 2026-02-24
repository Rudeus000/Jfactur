"""
Compras a proveedores y cuentas por pagar (pagos).
Compras y cuentas por pagar (Jfactur).
"""
import uuid
from django.db import models
from django.db.models import Sum


class Purchase(models.Model):
    """Compra a proveedor."""
    STATUS_CHOICES = [
        ('draft', 'Borrador'),
        ('confirmed', 'Confirmada'),
        ('cancelled', 'Anulada'),
    ]
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='purchases'
    )
    supplier = models.ForeignKey(
        'catalog.Supplier',
        on_delete=models.PROTECT,
        related_name='purchases'
    )
    warehouse = models.ForeignKey(
        'inventory.Warehouse',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='purchases'
    )
    number = models.CharField(max_length=30, blank=True)  # Número interno o documento
    date = models.DateField()
    due_date = models.DateField(null=True, blank=True)
    subtotal = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    igv_total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    status = models.CharField(max_length=20, default='draft', choices=STATUS_CHOICES)
    provider_document_type = models.CharField(max_length=10, blank=True)  # 01, 03, etc.
    provider_document_series = models.CharField(max_length=10, blank=True)
    provider_document_number = models.CharField(max_length=20, blank=True)
    notes = models.TextField(blank=True)
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='purchases_created'
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'purchases'
        verbose_name = 'Compra'
        verbose_name_plural = 'Compras'
        ordering = ['-date', '-created_at']

    def __str__(self):
        return f"Compra {self.number or self.id} - {self.supplier.razon_social}"

    def get_amount_paid(self):
        return self.payments.aggregate(t=Sum('amount'))['t'] or 0

    def get_balance_due(self):
        return self.total - self.get_amount_paid()


class PurchaseLine(models.Model):
    """Línea de compra."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    purchase = models.ForeignKey(
        Purchase,
        on_delete=models.CASCADE,
        related_name='lines'
    )
    line_number = models.PositiveIntegerField()
    product = models.ForeignKey(
        'catalog.Product',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='purchase_lines'
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
        db_table = 'purchase_lines'
        verbose_name = 'Línea de compra'
        verbose_name_plural = 'Líneas de compra'
        unique_together = [('purchase', 'line_number')]

    def __str__(self):
        return f"Línea {self.line_number}: {self.description}"


class PurchasePayment(models.Model):
    """Pago a proveedor (cuentas por pagar)."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='purchase_payments'
    )
    purchase = models.ForeignKey(
        Purchase,
        on_delete=models.CASCADE,
        related_name='payments'
    )
    date = models.DateField()
    amount = models.DecimalField(max_digits=14, decimal_places=2)
    payment_method = models.CharField(max_length=50, blank=True)  # Efectivo, Transferencia, etc.
    reference = models.CharField(max_length=100, blank=True)
    bank = models.ForeignKey(
        'accounting.Bank',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='purchase_payments'
    )
    cash_register = models.ForeignKey(
        'accounting.CashRegister',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='purchase_payments'
    )
    notes = models.TextField(blank=True)
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='purchase_payments_created'
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'purchase_payments'
        verbose_name = 'Pago a proveedor'
        verbose_name_plural = 'Pagos a proveedores (cuentas por pagar)'
        ordering = ['-date', '-created_at']

    def __str__(self):
        return f"Pago {self.amount} - {self.purchase}"


# Fix: Purchase.amount_paid uses Sum - need to add the import for Sum in the property.
# Actually we can't use models.Sum inside a property like that - we need to do it in a method or in the serializer. Let me add a method instead.