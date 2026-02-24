from django.contrib import admin
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from .models import (
    SubscriptionPlan,
    Company,
    CompanySubscription,
    Branch,
    Announcement,
    InvoiceUsageLog,
    Role,
    DigitalCertificate,
    User,
)


@admin.register(SubscriptionPlan)
class SubscriptionPlanAdmin(admin.ModelAdmin):
    list_display = ('name', 'max_invoices_per_month', 'is_active', 'created_at')
    list_filter = ('is_active',)
    search_fields = ('name',)


@admin.register(Company)
class CompanyAdmin(admin.ModelAdmin):
    list_display = ('ruc', 'razon_social', 'nombre_comercial', 'usuario_sol', 'codigo_sunat', 'igv_porcentaje', 'tipo_negocio', 'is_active', 'created_at')
    list_filter = ('is_active', 'tipo_negocio')
    search_fields = ('ruc', 'razon_social', 'nombre_comercial', 'usuario_sol')
    raw_id_fields = ('plan',)
    fieldsets = (
        (None, {'fields': ('ruc', 'razon_social', 'nombre_comercial', 'domicilio_fiscal', 'ubigeo', 'tipo_contribuyente', 'estado_sunat', 'condicion_sunat')}),
        ('SUNAT / Facturación electrónica', {
            'fields': ('usuario_sol', 'codigo_sunat', 'igv_porcentaje', 'codigo_pais', 'departamento', 'provincia', 'distrito'),
            'description': 'Datos para emisión electrónica y XML UBL (clave SOL se envía al enviar a SUNAT).',
        }),
        ('Negocio y plan', {'fields': ('tipo_negocio', 'subdomain', 'logo_url', 'primary_color', 'secondary_color', 'plan', 'is_active')}),
    )


@admin.register(CompanySubscription)
class CompanySubscriptionAdmin(admin.ModelAdmin):
    list_display = ('company', 'plan', 'started_at', 'expires_at', 'is_active')
    list_filter = ('is_active',)
    raw_id_fields = ('company', 'plan')


@admin.register(Branch)
class BranchAdmin(admin.ModelAdmin):
    list_display = ('name', 'code', 'company', 'is_active')
    list_filter = ('is_active', 'company')
    search_fields = ('name', 'code')
    raw_id_fields = ('company',)


@admin.register(Announcement)
class AnnouncementAdmin(admin.ModelAdmin):
    list_display = ('title', 'company', 'branch', 'is_pinned', 'is_active', 'valid_until', 'created_at')
    list_filter = ('is_active', 'is_pinned', 'company')
    search_fields = ('title', 'content')
    raw_id_fields = ('company', 'branch', 'created_by')
    date_hierarchy = 'created_at'


@admin.register(InvoiceUsageLog)
class InvoiceUsageLogAdmin(admin.ModelAdmin):
    list_display = ('company', 'year_month', 'count', 'created_at')
    list_filter = ('year_month',)
    raw_id_fields = ('company',)


@admin.register(Role)
class RoleAdmin(admin.ModelAdmin):
    list_display = ('name', 'company', 'is_system_role', 'created_at')
    list_filter = ('is_system_role',)
    search_fields = ('name',)
    raw_id_fields = ('company',)


@admin.register(DigitalCertificate)
class DigitalCertificateAdmin(admin.ModelAdmin):
    list_display = ('company', 'certificate_type', 'expiration_date', 'is_active')
    list_filter = ('is_active', 'certificate_type')
    raw_id_fields = ('company',)


@admin.register(User)
class UserAdmin(BaseUserAdmin):
    list_display = ('email', 'first_name', 'last_name', 'company', 'role', 'is_active', 'is_staff')
    list_filter = ('is_active', 'is_staff', 'company')
    search_fields = ('email', 'first_name', 'last_name', 'numero_documento')
    ordering = ('email',)
    filter_horizontal = ()
    raw_id_fields = ('company', 'role', 'default_warehouse')
    fieldsets = (
        (None, {'fields': ('email', 'password')}),
        ('Datos', {'fields': ('first_name', 'last_name', 'tipo_documento', 'numero_documento', 'phone')}),
        ('Empresa', {'fields': ('company', 'role', 'default_warehouse')}),
        ('Permisos', {'fields': ('is_active', 'is_staff', 'is_superuser')}),
        ('Fechas', {'fields': ('last_login', 'date_joined')}),
    )
    add_fieldsets = (
        (None, {'classes': ('wide',), 'fields': ('email', 'password1', 'password2')}),
        ('Datos', {'fields': ('first_name', 'last_name', 'tipo_documento', 'numero_documento', 'phone')}),
        ('Empresa', {'fields': ('company', 'role', 'default_warehouse')}),
        ('Permisos', {'fields': ('is_active', 'is_staff', 'is_superuser')}),
    )
