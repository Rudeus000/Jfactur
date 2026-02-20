import uuid
from django.db import models


class Warehouse(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='warehouses'
    )
    branch = models.ForeignKey(
        'core.Branch',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='warehouses'
    )
    name = models.CharField(max_length=100)
    code = models.CharField(max_length=20, blank=True)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'warehouses'
        verbose_name = 'Almacén'
        verbose_name_plural = 'Almacenes'

    def __str__(self):
        return self.name


class WarehouseLocation(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    warehouse = models.ForeignKey(
        Warehouse,
        on_delete=models.CASCADE,
        related_name='locations'
    )
    name = models.CharField(max_length=100)
    code = models.CharField(max_length=20, blank=True)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'warehouse_locations'
        verbose_name = 'Ubicación en almacén'
        verbose_name_plural = 'Ubicaciones en almacén'

    def __str__(self):
        return f"{self.warehouse.name} - {self.name}"


class StockQuant(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='stock_quants'
    )
    product = models.ForeignKey(
        'catalog.Product',
        on_delete=models.CASCADE,
        related_name='stock_quants'
    )
    warehouse = models.ForeignKey(
        Warehouse,
        on_delete=models.CASCADE,
        related_name='stock_quants'
    )
    location = models.ForeignKey(
        WarehouseLocation,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='stock_quants'
    )
    quantity = models.DecimalField(max_digits=14, decimal_places=4, default=0)
    reserved_quantity = models.DecimalField(max_digits=14, decimal_places=4, default=0)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'stock_quants'
        verbose_name = 'Stock (cantidad por almacén)'
        verbose_name_plural = 'Stocks'
        constraints = [
            models.UniqueConstraint(
                fields=['company', 'product', 'warehouse'],
                name='unique_quant_per_warehouse',
            ),
        ]

    def __str__(self):
        return f"{self.product.nombre} @ {self.warehouse.name}: {self.quantity}"


class StockMovement(models.Model):
    """Movimiento de stock (Kardex): entrada, salida, ajuste."""
    MOVEMENT_TYPES = [
        ('in', 'Entrada'),
        ('out', 'Salida'),
        ('adjust', 'Ajuste'),
        ('purchase', 'Compra'),
        ('sale', 'Venta'),
        ('transfer', 'Traspaso'),
    ]
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='stock_movements'
    )
    product = models.ForeignKey(
        'catalog.Product',
        on_delete=models.CASCADE,
        related_name='stock_movements'
    )
    warehouse = models.ForeignKey(
        Warehouse,
        on_delete=models.CASCADE,
        related_name='stock_movements'
    )
    movement_type = models.CharField(max_length=20, choices=MOVEMENT_TYPES)
    quantity = models.DecimalField(max_digits=14, decimal_places=4)  # positivo=entrada, negativo=salida
    quantity_after = models.DecimalField(max_digits=14, decimal_places=4, null=True, blank=True)
    reference = models.CharField(max_length=100, blank=True)  # F001-1, OC-001, etc.
    reference_model = models.CharField(max_length=50, blank=True)  # invoice, purchase, adjustment
    reference_id = models.UUIDField(null=True, blank=True)
    date = models.DateTimeField()
    unit_cost = models.DecimalField(max_digits=14, decimal_places=4, null=True, blank=True)
    total_cost = models.DecimalField(max_digits=14, decimal_places=2, null=True, blank=True)
    notes = models.TextField(blank=True)
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='stock_movements_created'
    )
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'stock_movements'
        verbose_name = 'Movimiento de stock (Kardex)'
        verbose_name_plural = 'Movimientos de stock (Kardex)'
        ordering = ['-date', '-created_at']

    def __str__(self):
        return f"{self.movement_type} {self.product.nombre} {self.quantity} @ {self.warehouse.name}"


class StockTransfer(models.Model):
    """Traspaso entre almacenes (pendiente → aprobado/rechazado por otro usuario)."""
    STATUS_CHOICES = [
        ('pending', 'Pendiente'),
        ('approved', 'Aprobado'),
        ('rejected', 'Rechazado'),
    ]
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='stock_transfers'
    )
    warehouse_origin = models.ForeignKey(
        Warehouse,
        on_delete=models.PROTECT,
        related_name='transfers_out'
    )
    warehouse_dest = models.ForeignKey(
        Warehouse,
        on_delete=models.PROTECT,
        related_name='transfers_in'
    )
    date = models.DateField()
    status = models.CharField(max_length=20, default='pending', choices=STATUS_CHOICES)
    notes = models.TextField(blank=True)
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        related_name='stock_transfers_created'
    )
    validated_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='stock_transfers_validated'
    )
    validated_at = models.DateTimeField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'stock_transfers'
        verbose_name = 'Traspaso entre almacenes'
        verbose_name_plural = 'Traspasos entre almacenes'
        ordering = ['-date', '-created_at']

    def __str__(self):
        return f"Traspaso {self.warehouse_origin.name} → {self.warehouse_dest.name} ({self.date})"


class StockTransferLine(models.Model):
    """Línea de un traspaso."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    transfer = models.ForeignKey(
        StockTransfer,
        on_delete=models.CASCADE,
        related_name='lines'
    )
    line_number = models.PositiveIntegerField()
    product = models.ForeignKey(
        'catalog.Product',
        on_delete=models.PROTECT,
        related_name='transfer_lines'
    )
    quantity = models.DecimalField(max_digits=14, decimal_places=4)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'stock_transfer_lines'
        verbose_name = 'Línea de traspaso'
        verbose_name_plural = 'Líneas de traspaso'
        unique_together = [('transfer', 'line_number')]

    def __str__(self):
        return f"{self.transfer} - L{self.line_number}: {self.product.nombre} {self.quantity}"
