from django.urls import path, include
from rest_framework.routers import DefaultRouter
from . import views

router = DefaultRouter()
router.register(r'invoice-series', views.InvoiceSeriesViewSet, basename='invoiceseries')
router.register(r'invoices', views.InvoiceViewSet, basename='invoice')
router.register(r'quotes', views.QuoteViewSet, basename='quote')
router.register(r'customer-payments', views.CustomerPaymentViewSet, basename='customerpayment')

urlpatterns = [
    path('', include(router.urls)),
    path('reports/libro-ventas/', views.LibroVentasReport.as_view()),
    path('reports/ventas-por-cliente/', views.VentasPorClienteReport.as_view()),
    path('reports/ventas-por-producto/', views.VentasPorProductoReport.as_view()),
    path('reports/aged-receivable/', views.AgedReceivableReport.as_view()),
    path('reports/aged-payable/', views.AgedPayableReport.as_view()),
    path('reports/dashboard/', views.DashboardReport.as_view()),
]
