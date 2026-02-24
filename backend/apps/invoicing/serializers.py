from rest_framework import serializers
from .models import InvoiceSeries, Invoice, InvoiceLine, Quote, QuoteLine, CustomerPayment
from .services import get_next_invoice_number


class InvoiceLineSerializer(serializers.ModelSerializer):
    class Meta:
        model = InvoiceLine
        fields = [
            'id', 'line_number', 'product', 'descripcion', 'cantidad',
            'valor_unitario', 'valor_venta', 'codigo_tipo_afectacion',
            'igv_monto', 'importe_total',
        ]


class InvoiceLineWriteSerializer(serializers.ModelSerializer):
    """Para crear factura: line_number es opcional (se asigna por orden)."""
    line_number = serializers.IntegerField(required=False)

    class Meta:
        model = InvoiceLine
        fields = [
            'id', 'line_number', 'product', 'descripcion', 'cantidad',
            'valor_unitario', 'valor_venta', 'codigo_tipo_afectacion',
            'igv_monto', 'importe_total',
        ]


class InvoiceSerializer(serializers.ModelSerializer):
    invoice_lines = InvoiceLineSerializer(many=True, read_only=True)

    class Meta:
        model = Invoice
        fields = [
            'id', 'tipo_documento', 'serie', 'numero', 'fecha_emision', 'hora_emision',
            'customer', 'cliente_tipo_documento', 'cliente_numero_documento',
            'cliente_razon_social', 'cliente_direccion', 'subtotal', 'igv_total', 'total',
            'status', 'xml_path', 'cdr_path', 'pdf_path', 'warehouse', 'created_by',
            'cash_opening', 'invoice_lines', 'created_at', 'updated_at',
        ]
        read_only_fields = ['numero']

    def create(self, validated_data):
        company_id = validated_data.get('company_id') or self.context['request'].user.company_id
        tipo_documento = validated_data.get('tipo_documento')
        serie = validated_data.get('serie')
        numero = get_next_invoice_number(company_id, tipo_documento, serie)
        if numero is None:
            raise serializers.ValidationError(
                {'serie': 'No existe serie activa para este tipo y serie.'}
            )
        validated_data['numero'] = numero
        if not validated_data.get('company_id'):
            validated_data['company_id'] = company_id
        return super().create(validated_data)


class InvoiceWriteSerializer(serializers.ModelSerializer):
    invoice_lines = InvoiceLineWriteSerializer(many=True, required=False)

    class Meta:
        model = Invoice
        fields = [
            'id', 'tipo_documento', 'serie', 'fecha_emision', 'hora_emision',
            'customer', 'cliente_tipo_documento', 'cliente_numero_documento',
            'cliente_razon_social', 'cliente_direccion', 'subtotal', 'igv_total', 'total',
            'status', 'warehouse', 'invoice_lines',
        ]

    def create(self, validated_data):
        lines_data = validated_data.pop('invoice_lines', [])
        company_id = self.context['request'].user.company_id
        if not company_id:
            raise serializers.ValidationError('Usuario sin empresa asignada.')
        tipo_documento = validated_data.get('tipo_documento')
        serie = validated_data.get('serie')
        numero = get_next_invoice_number(company_id, tipo_documento, serie)
        if numero is None:
            raise serializers.ValidationError(
                {'serie': 'No existe serie activa para este tipo y serie.'}
            )
        validated_data['numero'] = numero
        validated_data['company_id'] = company_id
        validated_data['created_by_id'] = self.context['request'].user.id
        invoice = Invoice.objects.create(**validated_data)
        for i, line_data in enumerate(lines_data, start=1):
            line_data.pop('id', None)
            line_data['invoice_id'] = invoice.id
            line_data['line_number'] = line_data.get('line_number') or i
            InvoiceLine.objects.create(**line_data)
        return invoice


class InvoiceSeriesSerializer(serializers.ModelSerializer):
    class Meta:
        model = InvoiceSeries
        fields = [
            'id', 'company', 'warehouse', 'tipo_documento', 'serie',
            'next_number', 'last_number', 'is_active', 'is_default',
            'created_at', 'updated_at',
        ]


class QuoteLineSerializer(serializers.ModelSerializer):
    class Meta:
        model = QuoteLine
        fields = [
            'id', 'line_number', 'product', 'description', 'quantity', 'unit_price',
            'subtotal', 'igv_amount', 'total'
        ]


class QuoteSerializer(serializers.ModelSerializer):
    lines = QuoteLineSerializer(many=True, read_only=True)

    class Meta:
        model = Quote
        fields = [
            'id', 'company', 'customer', 'number', 'date', 'valid_until',
            'cliente_tipo_documento', 'cliente_numero_documento', 'cliente_razon_social',
            'cliente_direccion', 'subtotal', 'igv_total', 'total', 'status', 'notes',
            'invoice', 'lines', 'created_by', 'created_at'
        ]


class QuoteWriteSerializer(serializers.ModelSerializer):
    lines = QuoteLineSerializer(many=True, required=False)

    class Meta:
        model = Quote
        fields = [
            'id', 'company', 'customer', 'number', 'date', 'valid_until',
            'cliente_tipo_documento', 'cliente_numero_documento', 'cliente_razon_social',
            'cliente_direccion', 'subtotal', 'igv_total', 'total', 'status', 'notes', 'lines'
        ]

    def create(self, validated_data):
        lines_data = validated_data.pop('lines', [])
        quote = Quote.objects.create(**validated_data)
        for i, line in enumerate(lines_data, start=1):
            QuoteLine.objects.create(quote=quote, line_number=i, **line)
        return quote


class CustomerPaymentSerializer(serializers.ModelSerializer):
    invoice_display = serializers.SerializerMethodField()

    def get_invoice_display(self, obj):
        return str(obj.invoice) if obj.invoice else ''

    class Meta:
        model = CustomerPayment
        fields = [
            'id', 'company', 'invoice', 'invoice_display', 'date', 'amount', 'payment_method',
            'reference', 'bank', 'cash_register', 'cash_opening', 'notes', 'created_by', 'created_at'
        ]


class CustomerPaymentWriteSerializer(serializers.ModelSerializer):
    class Meta:
        model = CustomerPayment
        fields = [
            'id', 'company', 'invoice', 'date', 'amount', 'payment_method',
            'reference', 'bank', 'cash_register', 'cash_opening', 'notes'
        ]

    def create(self, validated_data):
        validated_data['created_by_id'] = self.context['request'].user.id
        return super().create(validated_data)
