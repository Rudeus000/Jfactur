from rest_framework import serializers
from .models import Customer, Supplier, ProductCategory, Product


class CustomerSerializer(serializers.ModelSerializer):
    class Meta:
        model = Customer
        fields = [
            'id', 'tipo_documento', 'numero_documento', 'razon_social', 'nombre_comercial',
            'direccion', 'email', 'telefono', 'is_active', 'created_at', 'updated_at',
        ]


class SupplierSerializer(serializers.ModelSerializer):
    class Meta:
        model = Supplier
        fields = [
            'id', 'tipo_documento', 'numero_documento', 'razon_social', 'nombre_comercial',
            'direccion', 'email', 'telefono', 'is_active', 'created_at', 'updated_at',
        ]


class ProductCategorySerializer(serializers.ModelSerializer):
    class Meta:
        model = ProductCategory
        fields = ['id', 'name', 'parent', 'is_active', 'created_at', 'updated_at']


class ProductSerializer(serializers.ModelSerializer):
    class Meta:
        model = Product
        fields = [
            'id', 'sku', 'barcode', 'nombre', 'descripcion', 'unidad_medida',
            'precio_venta', 'costo_unitario', 'costo_promedio', 'afecto_igv',
            'codigo_tipo_afectacion', 'category', 'is_active', 'created_at', 'updated_at',
        ]
