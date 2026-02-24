from rest_framework import viewsets
from rest_framework.permissions import IsAuthenticated

from .models import Customer, Supplier, ProductCategory, Product
from .serializers import (
    CustomerSerializer,
    SupplierSerializer,
    ProductCategorySerializer,
    ProductSerializer,
)


def filter_by_company(queryset, request):
    if request.user.is_superuser:
        return queryset
    if request.user.company_id:
        return queryset.filter(company_id=request.user.company_id)
    return queryset.none()


class CustomerViewSet(viewsets.ModelViewSet):
    serializer_class = CustomerSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Customer.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class SupplierViewSet(viewsets.ModelViewSet):
    serializer_class = SupplierSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Supplier.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class ProductCategoryViewSet(viewsets.ModelViewSet):
    serializer_class = ProductCategorySerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(ProductCategory.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class ProductViewSet(viewsets.ModelViewSet):
    serializer_class = ProductSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Product.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()
