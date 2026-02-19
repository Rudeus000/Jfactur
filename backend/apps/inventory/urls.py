from django.urls import path, include
from rest_framework.routers import DefaultRouter
from . import views

router = DefaultRouter()
router.register(r'warehouses', views.WarehouseViewSet, basename='warehouse')
router.register(r'stock-quants', views.StockQuantViewSet, basename='stockquant')
router.register(r'stock-movements', views.StockMovementViewSet, basename='stockmovement')
router.register(r'transfers', views.StockTransferViewSet, basename='stocktransfer')

urlpatterns = [
    path('', include(router.urls)),
]
