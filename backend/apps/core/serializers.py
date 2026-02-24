from rest_framework import serializers
from .models import Company, User, Branch, Announcement


class BranchSerializer(serializers.ModelSerializer):
    class Meta:
        model = Branch
        fields = ['id', 'name', 'code', 'address', 'is_active', 'company', 'created_at', 'updated_at']
        read_only_fields = ['id', 'company', 'created_at', 'updated_at']
        extra_kwargs = {'company': {'required': False}}


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
        read_only_fields = [
            'id', 'ruc', 'razon_social', 'ubigeo', 'tipo_contribuyente', 'estado_sunat', 'condicion_sunat',
            'subdomain', 'plan', 'is_active', 'created_at', 'updated_at',
        ]


class CompanyUpdateSerializer(serializers.ModelSerializer):
    """Solo para actualizar logo y datos de presentación (tickets, facturas)."""
    class Meta:
        model = Company
        fields = ['logo_url', 'nombre_comercial', 'domicilio_fiscal', 'primary_color', 'secondary_color']


class UserMeSerializer(serializers.ModelSerializer):
    company = CompanySerializer(read_only=True)
    default_branch_name = serializers.SerializerMethodField()

    class Meta:
        model = User
        fields = [
            'id', 'email', 'company', 'first_name', 'last_name',
            'tipo_documento', 'numero_documento', 'phone',
            'default_branch', 'default_branch_name', 'default_warehouse', 'default_cash_register', 'is_active',
        ]
        read_only_fields = ['id', 'email', 'company', 'first_name', 'last_name', 'tipo_documento', 'numero_documento', 'phone', 'is_active']

    def get_default_branch_name(self, obj):
        if obj.default_branch_id:
            return obj.default_branch.name
        return None


class UserMeUpdateSerializer(serializers.ModelSerializer):
    """Solo para actualizar sucursal/almacén/caja por defecto del usuario."""
    class Meta:
        model = User
        fields = ['default_branch', 'default_warehouse', 'default_cash_register']


class AnnouncementSerializer(serializers.ModelSerializer):
    branch_name = serializers.SerializerMethodField()

    class Meta:
        model = Announcement
        fields = [
            'id', 'company', 'branch', 'branch_name', 'title', 'content',
            'created_by', 'is_pinned', 'valid_until', 'is_active',
            'created_at', 'updated_at',
        ]
        read_only_fields = ['id', 'company', 'created_by', 'created_at', 'updated_at']
        extra_kwargs = {'company': {'required': False}}

    def get_branch_name(self, obj):
        return obj.branch.name if obj.branch_id else None
