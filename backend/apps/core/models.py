import uuid
from django.db import models
from django.contrib.auth.models import AbstractBaseUser, BaseUserManager, PermissionsMixin


class SubscriptionPlan(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    name = models.CharField(max_length=100)
    max_invoices_per_month = models.PositiveIntegerField(default=100)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'subscription_plans'
        verbose_name = 'Plan de suscripción'
        verbose_name_plural = 'Planes de suscripción'

    def __str__(self):
        return self.name


class Company(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    ruc = models.CharField(max_length=11, unique=True)
    razon_social = models.CharField(max_length=200)
    nombre_comercial = models.CharField(max_length=200, blank=True)
    domicilio_fiscal = models.CharField(max_length=255, blank=True)
    ubigeo = models.CharField(max_length=6, blank=True)
    tipo_contribuyente = models.CharField(max_length=50, blank=True)
    estado_sunat = models.CharField(max_length=50, blank=True)
    condicion_sunat = models.CharField(max_length=50, blank=True)
    subdomain = models.CharField(max_length=63, blank=True)
    logo_url = models.URLField(blank=True, max_length=500)
    primary_color = models.CharField(max_length=7, blank=True)
    secondary_color = models.CharField(max_length=7, blank=True)
    # Tipo de negocio: abarrotes, ferretería, farmacia, telecom, etc. Sistema multi-rubro.
    tipo_negocio = models.CharField(max_length=80, blank=True)
    # SUNAT: usuario SOL (Clave SOL), establecimiento anexo, IGV según empresa
    usuario_sol = models.CharField(max_length=50, blank=True)
    codigo_sunat = models.CharField(max_length=10, default='0000', help_text='Establecimiento anexo para XML')
    igv_porcentaje = models.DecimalField(max_digits=5, decimal_places=2, default=18.00)
    codigo_pais = models.CharField(max_length=3, default='PE')
    departamento = models.CharField(max_length=100, blank=True)
    provincia = models.CharField(max_length=100, blank=True)
    distrito = models.CharField(max_length=100, blank=True)
    plan = models.ForeignKey(
        SubscriptionPlan,
        on_delete=models.SET_NULL,
        null=True,
        related_name='companies'
    )
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'companies'
        verbose_name = 'Empresa'
        verbose_name_plural = 'Empresas'

    def __str__(self):
        return self.razon_social


class CompanySubscription(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        Company,
        on_delete=models.CASCADE,
        related_name='subscriptions'
    )
    plan = models.ForeignKey(
        SubscriptionPlan,
        on_delete=models.CASCADE,
        related_name='company_subscriptions'
    )
    started_at = models.DateTimeField()
    expires_at = models.DateTimeField(null=True, blank=True)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'company_subscriptions'
        verbose_name = 'Suscripción de empresa'
        verbose_name_plural = 'Suscripciones de empresas'

    def __str__(self):
        return f"{self.company.razon_social} - {self.plan.name}"


class InvoiceUsageLog(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        Company,
        on_delete=models.CASCADE,
        related_name='invoice_usage_logs'
    )
    year_month = models.CharField(max_length=7)  # YYYY-MM
    count = models.PositiveIntegerField(default=0)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'invoice_usage_logs'
        verbose_name = 'Registro de uso de facturas'
        verbose_name_plural = 'Registros de uso de facturas'
        unique_together = [('company', 'year_month')]

    def __str__(self):
        return f"{self.company.razon_social} {self.year_month}: {self.count}"


class Role(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        Company,
        on_delete=models.CASCADE,
        related_name='roles'
    )
    name = models.CharField(max_length=100)
    permissions = models.JSONField(default=dict, blank=True)
    is_system_role = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'roles'
        verbose_name = 'Rol'
        verbose_name_plural = 'Roles'
        unique_together = [('company', 'name')]

    def __str__(self):
        return f"{self.company.razon_social} - {self.name}"


class DigitalCertificate(models.Model):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        Company,
        on_delete=models.CASCADE,
        related_name='digital_certificates'
    )
    certificate_type = models.CharField(max_length=50)
    pfx_file_path = models.CharField(max_length=500, blank=True)
    password_encrypted = models.CharField(max_length=500, blank=True)
    expiration_date = models.DateField(null=True, blank=True)
    issuer = models.CharField(max_length=255, blank=True)
    subject_cn = models.CharField(max_length=255, blank=True)
    is_active = models.BooleanField(default=True)
    days_before_expiry_alert = models.PositiveIntegerField(default=30)
    alert_sent = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'digital_certificates'
        verbose_name = 'Certificado digital'
        verbose_name_plural = 'Certificados digitales'

    def __str__(self):
        return f"{self.company.razon_social} - {self.certificate_type}"


class UserManager(BaseUserManager):
    def create_user(self, email, password=None, **extra_fields):
        if not email:
            raise ValueError('El email es obligatorio')
        email = self.normalize_email(email)
        user = self.model(email=email, **extra_fields)
        user.set_password(password)
        user.save(using=self._db)
        return user

    def create_superuser(self, email, password=None, **extra_fields):
        extra_fields.setdefault('is_staff', True)
        extra_fields.setdefault('is_superuser', True)
        return self.create_user(email, password, **extra_fields)


class User(AbstractBaseUser, PermissionsMixin):
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    email = models.EmailField(unique=True)
    company = models.ForeignKey(
        Company,
        on_delete=models.CASCADE,
        related_name='users',
        null=True,
        blank=True
    )
    role = models.ForeignKey(
        Role,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='users'
    )
    first_name = models.CharField(max_length=150, blank=True)
    last_name = models.CharField(max_length=150, blank=True)
    tipo_documento = models.CharField(max_length=10, blank=True)
    numero_documento = models.CharField(max_length=20, blank=True)
    phone = models.CharField(max_length=20, blank=True)
    default_warehouse = models.ForeignKey(
        'inventory.Warehouse',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='default_for_users'
    )
    is_active = models.BooleanField(default=True)
    is_staff = models.BooleanField(default=False)
    date_joined = models.DateTimeField(auto_now_add=True)

    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS = []

    objects = UserManager()

    class Meta:
        db_table = 'users'
        verbose_name = 'Usuario'
        verbose_name_plural = 'Usuarios'

    def __str__(self):
        return self.email
