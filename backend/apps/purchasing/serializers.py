from rest_framework import serializers
from .models import Purchase, PurchaseLine, PurchasePayment


class PurchaseLineSerializer(serializers.ModelSerializer):
    class Meta:
        model = PurchaseLine
        fields = [
            'id', 'line_number', 'product', 'description', 'quantity', 'unit_price',
            'subtotal', 'igv_amount', 'total'
        ]


class PurchasePaymentSerializer(serializers.ModelSerializer):
    class Meta:
        model = PurchasePayment
        fields = [
            'id', 'purchase', 'date', 'amount', 'payment_method', 'reference',
            'bank', 'cash_register', 'notes', 'created_by'
        ]


class PurchaseSerializer(serializers.ModelSerializer):
    lines = PurchaseLineSerializer(many=True, read_only=True)
    payments = PurchasePaymentSerializer(many=True, read_only=True)
    amount_paid = serializers.SerializerMethodField()
    balance_due = serializers.SerializerMethodField()

    class Meta:
        model = Purchase
        fields = [
            'id', 'company', 'supplier', 'warehouse', 'number', 'date', 'due_date',
            'subtotal', 'igv_total', 'total', 'status', 'provider_document_type',
            'provider_document_series', 'provider_document_number', 'notes',
            'lines', 'payments', 'amount_paid', 'balance_due', 'created_by', 'created_at'
        ]

    def get_amount_paid(self, obj):
        return obj.get_amount_paid()

    def get_balance_due(self, obj):
        return obj.get_balance_due()


class PurchaseWriteSerializer(serializers.ModelSerializer):
    lines = PurchaseLineSerializer(many=True, required=False)

    class Meta:
        model = Purchase
        fields = [
            'id', 'company', 'supplier', 'warehouse', 'number', 'date', 'due_date',
            'subtotal', 'igv_total', 'total', 'status', 'provider_document_type',
            'provider_document_series', 'provider_document_number', 'notes', 'lines'
        ]

    def create(self, validated_data):
        lines_data = validated_data.pop('lines', [])
        purchase = Purchase.objects.create(**validated_data)
        for i, line in enumerate(lines_data, start=1):
            PurchaseLine.objects.create(purchase=purchase, line_number=i, **line)
        return purchase


class PurchasePaymentWriteSerializer(serializers.ModelSerializer):
    class Meta:
        model = PurchasePayment
        fields = [
            'id', 'company', 'purchase', 'date', 'amount', 'payment_method', 'reference',
            'bank', 'cash_register', 'notes'
        ]

    def create(self, validated_data):
        validated_data['created_by_id'] = self.context['request'].user.id
        return super().create(validated_data)
