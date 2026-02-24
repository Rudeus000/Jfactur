from django.contrib import admin
from .models import Customer, Supplier, ProductCategory, Product


@admin.register(Customer)
class CustomerAdmin(admin.ModelAdmin):
    list_display = ('numero_documento', 'razon_social', 'nombre_comercial', 'company', 'is_active', 'created_at')
    list_filter = ('is_active', 'tipo_documento')
    search_fields = ('razon_social', 'numero_documento', 'email')
    raw_id_fields = ('company',)


@admin.register(Supplier)
class SupplierAdmin(admin.ModelAdmin):
    list_display = ('numero_documento', 'razon_social', 'company', 'is_active', 'created_at')
    list_filter = ('is_active', 'tipo_documento')
    search_fields = ('razon_social', 'numero_documento')
    raw_id_fields = ('company',)


@admin.register(ProductCategory)
class ProductCategoryAdmin(admin.ModelAdmin):
    list_display = ('name', 'company', 'parent', 'is_active', 'created_at')
    list_filter = ('is_active',)
    search_fields = ('name',)
    raw_id_fields = ('company', 'parent')


@admin.register(Product)
class ProductAdmin(admin.ModelAdmin):
    list_display = ('sku', 'nombre', 'precio_venta', 'unidad_medida', 'company', 'category', 'is_active', 'created_at')
    list_filter = ('is_active', 'afecto_igv', 'unidad_medida')
    search_fields = ('nombre', 'sku', 'barcode')
    raw_id_fields = ('company', 'category')
    list_editable = ('precio_venta',)
