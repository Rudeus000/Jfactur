from rest_framework import serializers
from .models import (
    Currency, AccountType, Account, Bank,
    CashRegister, CashRegisterOpening, CashRegisterClosing,
    ExpenseType, Expense,
)


class CurrencySerializer(serializers.ModelSerializer):
    class Meta:
        model = Currency
        fields = ['id', 'code', 'name', 'symbol', 'is_default', 'is_active', 'company']


class AccountTypeSerializer(serializers.ModelSerializer):
    class Meta:
        model = AccountType
        fields = ['id', 'code', 'name', 'is_active', 'company']


class AccountSerializer(serializers.ModelSerializer):
    account_type_name = serializers.CharField(source='account_type.name', read_only=True)

    class Meta:
        model = Account
        fields = ['id', 'code', 'name', 'account_type', 'account_type_name', 'parent', 'order', 'is_active', 'company']


class BankSerializer(serializers.ModelSerializer):
    class Meta:
        model = Bank
        fields = ['id', 'name', 'code', 'account_number', 'cci', 'is_active', 'company']


class CashRegisterSerializer(serializers.ModelSerializer):
    class Meta:
        model = CashRegister
        fields = ['id', 'name', 'code', 'warehouse', 'is_active', 'company']


class CashRegisterOpeningSerializer(serializers.ModelSerializer):
    class Meta:
        model = CashRegisterOpening
        fields = [
            'id', 'cash_register', 'opened_at', 'opening_balance', 'opened_by',
            'notes', 'closed_at', 'closing_balance', 'closed_by'
        ]


class CashRegisterClosingSerializer(serializers.ModelSerializer):
    class Meta:
        model = CashRegisterClosing
        fields = [
            'id', 'opening', 'closed_at', 'closing_balance', 'total_sales',
            'total_payments_in', 'total_payments_out',
            'cash_total', 'card_total', 'other_total',
            'destination_type', 'destination_cash_register', 'destination_bank',
            'validation_status', 'validated_by', 'validated_at',
            'closed_by', 'notes', 'created_at', 'updated_at'
        ]
        read_only_fields = ['validation_status', 'validated_by', 'validated_at']


class ExpenseTypeSerializer(serializers.ModelSerializer):
    class Meta:
        model = ExpenseType
        fields = ['id', 'code', 'name', 'account', 'is_active', 'company']


class ExpenseSerializer(serializers.ModelSerializer):
    expense_type_name = serializers.CharField(source='expense_type.name', read_only=True)

    class Meta:
        model = Expense
        fields = [
            'id', 'expense_type', 'expense_type_name', 'account', 'date', 'amount',
            'currency', 'description', 'reference', 'cash_register', 'company', 'created_by'
        ]
