from django.contrib import admin
from .models import Purchase, PurchaseLine, PurchasePayment


class PurchaseLineInline(admin.TabularInline):
    model = PurchaseLine
    extra = 0
    raw_id_fields = ('product',)


class PurchasePaymentInline(admin.TabularInline):
    model = PurchasePayment
    extra = 0
    raw_id_fields = ('bank', 'cash_register', 'created_by')


@admin.register(Purchase)
class PurchaseAdmin(admin.ModelAdmin):
    list_display = ('number', 'supplier', 'date', 'total', 'status', 'company')
    list_filter = ('status', 'date')
    search_fields = ('number', 'supplier__razon_social')
    raw_id_fields = ('company', 'supplier', 'warehouse', 'created_by')
    inlines = [PurchaseLineInline, PurchasePaymentInline]
    date_hierarchy = 'date'


@admin.register(PurchaseLine)
class PurchaseLineAdmin(admin.ModelAdmin):
    list_display = ('purchase', 'line_number', 'description', 'quantity', 'unit_price', 'total')
    list_filter = ('purchase__company',)
    raw_id_fields = ('purchase', 'product')


@admin.register(PurchasePayment)
class PurchasePaymentAdmin(admin.ModelAdmin):
    list_display = ('purchase', 'date', 'amount', 'payment_method', 'company')
    list_filter = ('date',)
    raw_id_fields = ('company', 'purchase', 'bank', 'cash_register', 'created_by')
    date_hierarchy = 'date'
