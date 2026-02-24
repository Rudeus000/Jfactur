from rest_framework import serializers
from .models import Warehouse, WarehouseLocation, StockQuant, StockMovement, StockTransfer, StockTransferLine


class WarehouseLocationSerializer(serializers.ModelSerializer):
    class Meta:
        model = WarehouseLocation
        fields = ['id', 'name', 'code', 'is_active', 'created_at', 'updated_at']


class WarehouseSerializer(serializers.ModelSerializer):
    locations = WarehouseLocationSerializer(many=True, read_only=True)

    class Meta:
        model = Warehouse
        fields = ['id', 'name', 'code', 'branch', 'is_active', 'locations', 'created_at', 'updated_at']


class StockQuantSerializer(serializers.ModelSerializer):
    product_name = serializers.CharField(source='product.nombre', read_only=True)
    product_sku = serializers.CharField(source='product.sku', read_only=True)
    product_unit = serializers.CharField(source='product.unidad_medida', read_only=True)
    warehouse_name = serializers.CharField(source='warehouse.name', read_only=True)

    class Meta:
        model = StockQuant
        fields = [
            'id', 'product', 'product_name', 'product_sku', 'product_unit', 'warehouse', 'warehouse_name',
            'location', 'quantity', 'reserved_quantity',
            'created_at', 'updated_at',
        ]


class StockMovementSerializer(serializers.ModelSerializer):
    product_name = serializers.CharField(source='product.nombre', read_only=True)
    warehouse_name = serializers.CharField(source='warehouse.name', read_only=True)

    class Meta:
        model = StockMovement
        fields = [
            'id', 'product', 'product_name', 'warehouse', 'warehouse_name', 'movement_type',
            'quantity', 'quantity_after', 'reference', 'reference_model', 'reference_id',
            'date', 'unit_cost', 'total_cost', 'notes', 'created_by', 'created_at',
        ]


class StockTransferLineSerializer(serializers.ModelSerializer):
    product_name = serializers.CharField(source='product.nombre', read_only=True)

    class Meta:
        model = StockTransferLine
        fields = ['id', 'line_number', 'product', 'product_name', 'quantity', 'created_at']


class StockTransferSerializer(serializers.ModelSerializer):
    lines = StockTransferLineSerializer(many=True, read_only=True)
    warehouse_origin_name = serializers.CharField(source='warehouse_origin.name', read_only=True)
    warehouse_dest_name = serializers.CharField(source='warehouse_dest.name', read_only=True)

    class Meta:
        model = StockTransfer
        fields = [
            'id', 'company', 'warehouse_origin', 'warehouse_origin_name', 'warehouse_dest', 'warehouse_dest_name',
            'date', 'status', 'notes', 'lines', 'created_by', 'validated_by', 'validated_at',
            'created_at', 'updated_at',
        ]
        read_only_fields = ['status', 'validated_by', 'validated_at']


class StockTransferWriteSerializer(serializers.ModelSerializer):
    lines = StockTransferLineSerializer(many=True, required=False)

    class Meta:
        model = StockTransfer
        fields = [
            'id', 'company', 'warehouse_origin', 'warehouse_dest', 'date', 'notes', 'lines',
        ]

    def create(self, validated_data):
        lines_data = validated_data.pop('lines', [])
        transfer = StockTransfer.objects.create(**validated_data, created_by=self.context['request'].user)
        for i, line_data in enumerate(lines_data, start=1):
            product = line_data.get('product')
            product_id = product.id if hasattr(product, 'id') else product
            StockTransferLine.objects.create(
                transfer=transfer,
                line_number=line_data.get('line_number', i),
                product_id=product_id,
                quantity=line_data['quantity'],
            )
        return transfer

    def update(self, instance, validated_data):
        if instance.status != 'pending':
            validated_data.pop('lines', None)
            validated_data.pop('warehouse_origin', None)
            validated_data.pop('warehouse_dest', None)
            validated_data.pop('date', None)
        lines_data = validated_data.pop('lines', None)
        for attr, value in validated_data.items():
            setattr(instance, attr, value)
        instance.save()
        if lines_data is not None and instance.status == 'pending':
            instance.lines.all().delete()
            for i, line_data in enumerate(lines_data, start=1):
                product = line_data.get('product')
                product_id = product.id if hasattr(product, 'id') else product
                StockTransferLine.objects.create(
                    transfer=instance,
                    line_number=line_data.get('line_number', i),
                    product_id=product_id,
                    quantity=line_data['quantity'],
                )
        return instance
