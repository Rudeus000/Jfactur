from django.urls import path, include
from rest_framework.routers import DefaultRouter
from . import views

router = DefaultRouter()
router.register(r'currencies', views.CurrencyViewSet, basename='currency')
router.register(r'account-types', views.AccountTypeViewSet, basename='accounttype')
router.register(r'accounts', views.AccountViewSet, basename='account')
router.register(r'banks', views.BankViewSet, basename='bank')
router.register(r'cash-registers', views.CashRegisterViewSet, basename='cashregister')
router.register(r'cash-openings', views.CashRegisterOpeningViewSet, basename='cashopening')
router.register(r'cash-closings', views.CashRegisterClosingViewSet, basename='cashclosing')
router.register(r'expense-types', views.ExpenseTypeViewSet, basename='expensetype')
router.register(r'expenses', views.ExpenseViewSet, basename='expense')

urlpatterns = [
    path('', include(router.urls)),
    path('reports/balance/', views.BalanceReport.as_view()),
]
