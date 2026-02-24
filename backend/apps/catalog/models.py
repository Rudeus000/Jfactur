import uuid
from django.db import models


class Customer(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='customers'
    )
    tipo_documento = models.CharField(max_length=10, default='6')  # 6=RUC, 1=DNI
    numero_documento = models.CharField(max_length=20)
    razon_social = models.CharField(max_length=200)
    nombre_comercial = models.CharField(max_length=200, blank=True)
    direccion = models.CharField(max_length=255, blank=True)
    email = models.EmailField(blank=True)
    telefono = models.CharField(max_length=20, blank=True)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'customers'
        verbose_name = 'Cliente'
        verbose_name_plural = 'Clientes'
        unique_together = [('company', 'tipo_documento', 'numero_documento')]

    def __str__(self):
        return self.razon_social


class Supplier(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='suppliers'
    )
    tipo_documento = models.CharField(max_length=10, default='6')
    numero_documento = models.CharField(max_length=20)
    razon_social = models.CharField(max_length=200)
    nombre_comercial = models.CharField(max_length=200, blank=True)
    direccion = models.CharField(max_length=255, blank=True)
    email = models.EmailField(blank=True)
    telefono = models.CharField(max_length=20, blank=True)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'suppliers'
        verbose_name = 'Proveedor'
        verbose_name_plural = 'Proveedores'
        unique_together = [('company', 'tipo_documento', 'numero_documento')]

    def __str__(self):
        return self.razon_social


class ProductCategory(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='product_categories'
    )
    name = models.CharField(max_length=100)
    parent = models.ForeignKey(
        'self',
        on_delete=models.CASCADE,
        null=True,
        blank=True,
        related_name='children'
    )
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'product_categories'
        verbose_name = 'Categoría de producto'
        verbose_name_plural = 'Categorías de productos'
        unique_together = [('company', 'name')]

    def __str__(self):
        return self.name


class Product(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='products'
    )
    sku = models.CharField(max_length=50, blank=True)
    barcode = models.CharField(max_length=50, blank=True)
    nombre = models.CharField(max_length=200)
    descripcion = models.TextField(blank=True)
    unidad_medida = models.CharField(max_length=10, default='NIU')
    precio_venta = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    costo_unitario = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    costo_promedio = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    afecto_igv = models.BooleanField(default=True)
    codigo_tipo_afectacion = models.CharField(max_length=10, default='10')
    category = models.ForeignKey(
        ProductCategory,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='products'
    )
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'products'
        verbose_name = 'Producto'
        verbose_name_plural = 'Productos'
        unique_together = [('company', 'sku')]

    def __str__(self):
        return self.nombre
