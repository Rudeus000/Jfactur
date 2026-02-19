"""
Contabilidad: plan de cuentas, moneda, bancos, caja (apertura/cierre), gastos.
Módulo contabilidad (Jfactur): tipos de cuenta, cuentas, monedas, bancos, cajas, gastos.
"""
import uuid
from django.db import models


class Currency(models.Model):
    """Moneda (PEN, USD, etc.)."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='currencies'
    )
    code = models.CharField(max_length=5)  # PEN, USD
    name = models.CharField(max_length=50)
    symbol = models.CharField(max_length=10, blank=True)
    is_default = models.BooleanField(default=False)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'currencies'
        verbose_name = 'Moneda'
        verbose_name_plural = 'Monedas'
        unique_together = [('company', 'code')]

    def __str__(self):
        return f"{self.code} - {self.name}"


class AccountType(models.Model):
    """Tipo de cuenta (Activo, Pasivo, Patrimonio, Ingreso, Gasto)."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='account_types'
    )
    code = models.CharField(max_length=20)
    name = models.CharField(max_length=100)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'account_types'
        verbose_name = 'Tipo de cuenta'
        verbose_name_plural = 'Tipos de cuenta'
        unique_together = [('company', 'code')]

    def __str__(self):
        return f"{self.code} - {self.name}"


class Account(models.Model):
    """Cuenta del plan contable (libro de cuentas)."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='accounts'
    )
    account_type = models.ForeignKey(
        AccountType,
        on_delete=models.PROTECT,
        related_name='accounts'
    )
    code = models.CharField(max_length=20)
    name = models.CharField(max_length=200)
    parent = models.ForeignKey(
        'self',
        on_delete=models.CASCADE,
        null=True,
        blank=True,
        related_name='children'
    )
    order = models.PositiveIntegerField(default=0)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'accounts'
        verbose_name = 'Cuenta (plan contable)'
        verbose_name_plural = 'Cuentas (libro de cuentas)'
        unique_together = [('company', 'code')]
        ordering = ['order', 'code']

    def __str__(self):
        return f"{self.code} - {self.name}"


class Bank(models.Model):
    """Banco (para cuentas bancarias y detracción)."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='banks'
    )
    name = models.CharField(max_length=100)
    code = models.CharField(max_length=20, blank=True)
    account_number = models.CharField(max_length=50, blank=True, help_text='Número de cuenta en el banco')
    cci = models.CharField(max_length=30, blank=True)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'banks'
        verbose_name = 'Banco'
        verbose_name_plural = 'Bancos'

    def __str__(self):
        return self.name


class CashRegister(models.Model):
    """Caja (punto de venta / caja física)."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='cash_registers'
    )
    name = models.CharField(max_length=100)
    code = models.CharField(max_length=20, blank=True)
    warehouse = models.ForeignKey(
        'inventory.Warehouse',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='cash_registers'
    )
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'cash_registers'
        verbose_name = 'Caja'
        verbose_name_plural = 'Cajas'

    def __str__(self):
        return self.name


class CashRegisterOpening(models.Model):
    """Apertura de caja."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    cash_register = models.ForeignKey(
        CashRegister,
        on_delete=models.CASCADE,
        related_name='openings'
    )
    opened_at = models.DateTimeField()
    opening_balance = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    opened_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        related_name='cash_openings'
    )
    notes = models.TextField(blank=True)
    closed_at = models.DateTimeField(null=True, blank=True)
    closing_balance = models.DecimalField(max_digits=14, decimal_places=2, null=True, blank=True)
    closed_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='cash_closings'
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'cash_register_openings'
        verbose_name = 'Apertura de caja'
        verbose_name_plural = 'Aperturas de caja'
        ordering = ['-opened_at']

    def __str__(self):
        return f"{self.cash_register.name} - {self.opened_at.date()}"


class CashRegisterClosing(models.Model):
    """Cierre de caja: desglose por medio de pago, destino del dinero, validación por supervisor."""
    DESTINATION_TYPE = [
        ('cash_register', 'Otra caja'),
        ('bank', 'Banco'),
        ('none', 'Sin destino'),
    ]
    VALIDATION_STATUS = [
        ('pending', 'Pendiente'),
        ('validated', 'Validado'),
    ]
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    opening = models.OneToOneField(
        CashRegisterOpening,
        on_delete=models.CASCADE,
        related_name='closing'
    )
    closed_at = models.DateTimeField()
    closing_balance = models.DecimalField(max_digits=14, decimal_places=2)
    total_sales = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    total_payments_in = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    total_payments_out = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    cash_total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    card_total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    other_total = models.DecimalField(max_digits=14, decimal_places=2, default=0)
    destination_type = models.CharField(max_length=20, default='none', choices=DESTINATION_TYPE)
    destination_cash_register = models.ForeignKey(
        CashRegister,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='closings_deposited_here'
    )
    destination_bank = models.ForeignKey(
        Bank,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='closings_deposited_here'
    )
    validation_status = models.CharField(max_length=20, default='pending', choices=VALIDATION_STATUS)
    validated_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='cash_closings_validated'
    )
    validated_at = models.DateTimeField(null=True, blank=True)
    closed_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        related_name='cash_register_closings'
    )
    notes = models.TextField(blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'cash_register_closings'
        verbose_name = 'Cierre de caja'
        verbose_name_plural = 'Cierres de caja'

    def __str__(self):
        return f"Cierre {self.opening}"


class ExpenseType(models.Model):
    """Tipo de gasto."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='expense_types'
    )
    code = models.CharField(max_length=20, blank=True)
    name = models.CharField(max_length=100)
    account = models.ForeignKey(
        Account,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='expense_types'
    )
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'expense_types'
        verbose_name = 'Tipo de gasto'
        verbose_name_plural = 'Tipos de gasto'

    def __str__(self):
        return self.name


class Expense(models.Model):
    """Gasto registrado."""
    id = models.UUIDField(primary_key=True, default=uuid.uuid4, editable=False)
    company = models.ForeignKey(
        'core.Company',
        on_delete=models.CASCADE,
        related_name='expenses'
    )
    expense_type = models.ForeignKey(
        ExpenseType,
        on_delete=models.PROTECT,
        related_name='expenses'
    )
    account = models.ForeignKey(
        Account,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='expenses'
    )
    date = models.DateField()
    amount = models.DecimalField(max_digits=14, decimal_places=2)
    currency = models.ForeignKey(
        Currency,
        on_delete=models.PROTECT,
        null=True,
        blank=True,
        related_name='expenses'
    )
    description = models.CharField(max_length=500, blank=True)
    reference = models.CharField(max_length=100, blank=True)
    cash_register = models.ForeignKey(
        CashRegister,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='expenses'
    )
    created_by = models.ForeignKey(
        'core.User',
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        related_name='expenses_created'
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'expenses'
        verbose_name = 'Gasto'
        verbose_name_plural = 'Gastos'
        ordering = ['-date', '-created_at']

    def __str__(self):
        return f"{self.expense_type.name} - {self.date} - {self.amount}"
