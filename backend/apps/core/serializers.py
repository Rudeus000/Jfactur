from rest_framework import serializers
from .models import Company, User


class CompanySerializer(serializers.ModelSerializer):
    class Meta:
        model = Company
        fields = [
            'id', 'ruc', 'razon_social', 'nombre_comercial', 'domicilio_fiscal',
            'ubigeo', 'tipo_contribuyente', 'estado_sunat', 'condicion_sunat',
            'subdomain', 'logo_url', 'primary_color', 'secondary_color',
'tipo_negocio', 'usuario_sol', 'codigo_sunat', 'igv_porcentaje', 'codigo_pais',
            'departamento', 'provincia', 'distrito', 'plan', 'is_active', 'created_at', 'updated_at',
]
        read_only_fields = fields


class UserMeSerializer(serializers.ModelSerializer):
    company = CompanySerializer(read_only=True)

    class Meta:
        model = User
        fields = [
            'id', 'email', 'company', 'first_name', 'last_name',
            'tipo_documento', 'numero_documento', 'phone',
            'default_warehouse', 'is_active',
        ]
