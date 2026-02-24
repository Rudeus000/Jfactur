from django.contrib import admin
from .models import (
    Currency, AccountType, Account, Bank,
    CashRegister, CashRegisterOpening, CashRegisterClosing,
    ExpenseType, Expense,
)


@admin.register(Currency)
class CurrencyAdmin(admin.ModelAdmin):
    list_display = ('code', 'name', 'symbol', 'company', 'is_default', 'is_active')
    list_filter = ('is_active', 'is_default')
    search_fields = ('code', 'name')
    raw_id_fields = ('company',)


@admin.register(AccountType)
class AccountTypeAdmin(admin.ModelAdmin):
    list_display = ('code', 'name', 'company', 'is_active')
    list_filter = ('is_active',)
    search_fields = ('code', 'name')
    raw_id_fields = ('company',)


@admin.register(Account)
class AccountAdmin(admin.ModelAdmin):
    list_display = ('code', 'name', 'account_type', 'company', 'parent', 'order', 'is_active')
    list_filter = ('is_active', 'account_type')
    search_fields = ('code', 'name')
    raw_id_fields = ('company', 'account_type', 'parent')
    list_editable = ('order',)


@admin.register(Bank)
class BankAdmin(admin.ModelAdmin):
    list_display = ('name', 'code', 'account_number', 'company', 'is_active')
    list_filter = ('is_active',)
    search_fields = ('name', 'account_number')
    raw_id_fields = ('company',)


class CashRegisterOpeningInline(admin.TabularInline):
    model = CashRegisterOpening
    extra = 0
    raw_id_fields = ('opened_by', 'closed_by')


@admin.register(CashRegister)
class CashRegisterAdmin(admin.ModelAdmin):
    list_display = ('name', 'code', 'company', 'warehouse', 'is_active')
    list_filter = ('is_active',)
    search_fields = ('name', 'code')
    raw_id_fields = ('company', 'warehouse')


@admin.register(CashRegisterOpening)
class CashRegisterOpeningAdmin(admin.ModelAdmin):
    list_display = ('cash_register', 'opened_at', 'opening_balance', 'opened_by', 'closed_at', 'closing_balance')
    list_filter = ('cash_register', 'opened_at')
    raw_id_fields = ('cash_register', 'opened_by', 'closed_by')
    date_hierarchy = 'opened_at'


@admin.register(CashRegisterClosing)
class CashRegisterClosingAdmin(admin.ModelAdmin):
    list_display = ('opening', 'closed_at', 'closing_balance', 'total_sales', 'closed_by')
    raw_id_fields = ('opening', 'closed_by')


@admin.register(ExpenseType)
class ExpenseTypeAdmin(admin.ModelAdmin):
    list_display = ('name', 'code', 'company', 'account', 'is_active')
    list_filter = ('is_active',)
    search_fields = ('name', 'code')
    raw_id_fields = ('company', 'account')


@admin.register(Expense)
class ExpenseAdmin(admin.ModelAdmin):
    list_display = ('expense_type', 'date', 'amount', 'currency', 'company', 'description', 'created_by')
    list_filter = ('expense_type', 'date')
    search_fields = ('description', 'reference')
    raw_id_fields = ('company', 'expense_type', 'account', 'currency', 'cash_register', 'created_by')
    date_hierarchy = 'date'
