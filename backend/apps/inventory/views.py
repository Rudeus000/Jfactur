from django.utils import timezone
from rest_framework import viewsets, status
from rest_framework.decorators import action
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response

from .models import Warehouse, WarehouseLocation, StockQuant, StockMovement, StockTransfer, StockTransferLine
from .serializers import (
    WarehouseSerializer, WarehouseLocationSerializer, StockQuantSerializer,
    StockMovementSerializer, StockTransferSerializer, StockTransferWriteSerializer,
)


def filter_by_company(queryset, request):
    if request.user.is_superuser:
        return queryset
    if request.user.company_id:
        return queryset.filter(company_id=request.user.company_id)
    return queryset.none()


class WarehouseViewSet(viewsets.ModelViewSet):
    serializer_class = WarehouseSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Warehouse.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class StockQuantViewSet(viewsets.ModelViewSet):
    serializer_class = StockQuantSerializer
    permission_classes = [IsAuthenticated]

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()

    def get_queryset(self):
        qs = filter_by_company(StockQuant.objects.all(), self.request)
        product_id = self.request.query_params.get('product_id')
        warehouse_id = self.request.query_params.get('warehouse_id')
        if product_id:
            qs = qs.filter(product_id=product_id)
        if warehouse_id:
            qs = qs.filter(warehouse_id=warehouse_id)
        return qs


class StockMovementViewSet(viewsets.ModelViewSet):
    serializer_class = StockMovementSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = filter_by_company(StockMovement.objects.all(), self.request)
        product_id = self.request.query_params.get('product_id')
        warehouse_id = self.request.query_params.get('warehouse_id')
        date_from = self.request.query_params.get('date_from')
        date_to = self.request.query_params.get('date_to')
        if product_id:
            qs = qs.filter(product_id=product_id)
        if warehouse_id:
            qs = qs.filter(warehouse_id=warehouse_id)
        if date_from:
            qs = qs.filter(date__gte=date_from)
        if date_to:
            qs = qs.filter(date__lte=date_to)
        return qs.order_by('-date', '-created_at')

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id, created_by=self.request.user)
        else:
            serializer.save(created_by=self.request.user)


class StockTransferViewSet(viewsets.ModelViewSet):
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(StockTransfer.objects.all(), self.request)

    def get_serializer_class(self):
        if self.action in ('create', 'update', 'partial_update'):
            return StockTransferWriteSerializer
        return StockTransferSerializer

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id, created_by=self.request.user)
        else:
            serializer.save(created_by=self.request.user)

    @action(detail=True, methods=['post'])
    def validate(self, request, pk=None):
        """Aprueba el traspaso: descuenta en origen, suma en destino y crea 2 movimientos (Kardex) por línea."""
        transfer = self.get_object()
        if transfer.status != 'pending':
            return Response(
                {'detail': 'Solo se puede validar un traspaso en estado Pendiente.'},
                status=status.HTTP_400_BAD_REQUEST
            )
        company_id = transfer.company_id
        ref = str(transfer.id)
        mov_date = timezone.now()
        for line in transfer.lines.all().select_related('product'):
            product_id = line.product_id
            qty = line.quantity
            wo = transfer.warehouse_origin
            wd = transfer.warehouse_dest
            quant_origin, _ = StockQuant.objects.get_or_create(
                company_id=company_id, product_id=product_id, warehouse=wo,
                defaults={'quantity': 0}
            )
            if quant_origin.quantity < qty:
                return Response(
                    {'detail': f'Stock insuficiente en origen para {line.product.nombre}: tiene {quant_origin.quantity}, se requieren {qty}.'},
                    status=status.HTTP_400_BAD_REQUEST
                )
            quant_origin.quantity -= qty
            quant_origin.save(update_fields=['quantity', 'updated_at'])
            qty_after_origin = quant_origin.quantity
            quant_dest, _ = StockQuant.objects.get_or_create(
                company_id=company_id, product_id=product_id, warehouse=wd,
                defaults={'quantity': 0}
            )
            quant_dest.quantity += qty
            quant_dest.save(update_fields=['quantity', 'updated_at'])
            qty_after_dest = quant_dest.quantity
            StockMovement.objects.create(
                company_id=company_id, product_id=product_id, warehouse=wo,
                movement_type='transfer', quantity=-qty, quantity_after=qty_after_origin,
                reference=ref, reference_model='transfer', reference_id=transfer.id,
                date=mov_date, created_by=request.user,
            )
            StockMovement.objects.create(
                company_id=company_id, product_id=product_id, warehouse=wd,
                movement_type='transfer', quantity=qty, quantity_after=qty_after_dest,
                reference=ref, reference_model='transfer', reference_id=transfer.id,
                date=mov_date, created_by=request.user,
            )
        transfer.status = 'approved'
        transfer.validated_by = request.user
        transfer.validated_at = mov_date
        transfer.save(update_fields=['status', 'validated_by', 'validated_at', 'updated_at'])
        return Response(StockTransferSerializer(transfer).data)
