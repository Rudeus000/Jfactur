from django.urls import path, include
from rest_framework.routers import DefaultRouter
from . import views

router = DefaultRouter()
router.register(r'customers', views.CustomerViewSet, basename='customer')
router.register(r'suppliers', views.SupplierViewSet, basename='supplier')
router.register(r'categories', views.ProductCategoryViewSet, basename='category')
router.register(r'products', views.ProductViewSet, basename='product')

urlpatterns = [
    path('', include(router.urls)),
]
