from django.contrib import admin
from .models import Warehouse, WarehouseLocation, StockQuant, StockMovement


class WarehouseLocationInline(admin.TabularInline):
    model = WarehouseLocation
    extra = 0


@admin.register(Warehouse)
class WarehouseAdmin(admin.ModelAdmin):
    list_display = ('name', 'code', 'company', 'is_active', 'created_at')
    list_filter = ('is_active',)
    search_fields = ('name', 'code')
    raw_id_fields = ('company',)
    inlines = [WarehouseLocationInline]


@admin.register(WarehouseLocation)
class WarehouseLocationAdmin(admin.ModelAdmin):
    list_display = ('name', 'code', 'warehouse', 'is_active')
    list_filter = ('is_active', 'warehouse')
    raw_id_fields = ('warehouse',)


@admin.register(StockQuant)
class StockQuantAdmin(admin.ModelAdmin):
    list_display = ('product', 'warehouse', 'quantity', 'reserved_quantity', 'company', 'updated_at')
    list_filter = ('warehouse',)
    search_fields = ('product__nombre', 'product__sku')
    raw_id_fields = ('company', 'product', 'warehouse', 'location')


@admin.register(StockMovement)
class StockMovementAdmin(admin.ModelAdmin):
    list_display = ('product', 'warehouse', 'movement_type', 'quantity', 'quantity_after', 'reference', 'date', 'company')
    list_filter = ('movement_type', 'warehouse', 'date')
    search_fields = ('reference', 'product__nombre', 'notes')
    raw_id_fields = ('company', 'product', 'warehouse', 'created_by')
    date_hierarchy = 'date'
