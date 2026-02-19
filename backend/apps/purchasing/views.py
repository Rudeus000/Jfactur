from django.utils import timezone
from rest_framework import viewsets
from rest_framework.permissions import IsAuthenticated

from .models import Purchase, PurchaseLine, PurchasePayment
from .serializers import (
    PurchaseSerializer, PurchaseWriteSerializer,
    PurchasePaymentSerializer, PurchasePaymentWriteSerializer,
)


def filter_by_company(queryset, request):
    if request.user.is_superuser:
        return queryset
    if request.user.company_id:
        return queryset.filter(company_id=request.user.company_id)
    return queryset.none()


def _apply_purchase_to_stock(purchase, created_by=None):
    """Crea movimientos de stock y actualiza StockQuant al confirmar la compra."""
    from apps.inventory.models import StockMovement, StockQuant
    if not purchase.warehouse_id:
        return
    company_id = purchase.company_id
    ref = purchase.number or str(purchase.id)
    mov_date = timezone.now()
    for line in purchase.lines.all().select_related('product'):
        if not line.product_id:
            continue
        qty = line.quantity
        warehouse = purchase.warehouse
        quant, _ = StockQuant.objects.get_or_create(
            company_id=company_id,
            product_id=line.product_id,
            warehouse=warehouse,
            defaults={'quantity': 0}
        )
        new_qty = quant.quantity + qty
        quant.quantity = new_qty
        quant.save(update_fields=['quantity', 'updated_at'])
        unit_cost = line.unit_price
        total_cost = line.total
        StockMovement.objects.create(
            company_id=company_id,
            product_id=line.product_id,
            warehouse=warehouse,
            movement_type='purchase',
            quantity=qty,
            quantity_after=new_qty,
            reference=ref,
            reference_model='purchase',
            reference_id=purchase.id,
            date=mov_date,
            unit_cost=unit_cost,
            total_cost=total_cost,
            created_by=created_by,
        )


class PurchaseViewSet(viewsets.ModelViewSet):
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Purchase.objects.all(), self.request)

    def get_serializer_class(self):
        if self.action == 'create' or self.action == 'update' or self.action == 'partial_update':
            return PurchaseWriteSerializer
        return PurchaseSerializer

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id, created_by=self.request.user)
        else:
            serializer.save(created_by=self.request.user)

    def perform_update(self, serializer):
        old_status = serializer.instance.status
        serializer.save()
        if serializer.instance.status == 'confirmed' and old_status != 'confirmed':
            _apply_purchase_to_stock(serializer.instance, created_by=self.request.user)


class PurchasePaymentViewSet(viewsets.ModelViewSet):
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(PurchasePayment.objects.all(), self.request)

    def get_serializer_class(self):
        if self.action == 'create':
            return PurchasePaymentWriteSerializer
        return PurchasePaymentSerializer

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()
