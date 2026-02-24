from django.urls import path, include
from rest_framework.routers import DefaultRouter
from . import views

router = DefaultRouter()
router.register(r'purchases', views.PurchaseViewSet, basename='purchase')
router.register(r'purchase-payments', views.PurchasePaymentViewSet, basename='purchasepayment')

urlpatterns = [
    path('', include(router.urls)),
]
