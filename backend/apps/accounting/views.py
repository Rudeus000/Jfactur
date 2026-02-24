from django.db import models
from django.db.models import Sum
from django.http import HttpResponse
from django.utils import timezone
from rest_framework import viewsets, status
from rest_framework.decorators import action
from rest_framework.permissions import IsAuthenticated
from rest_framework.views import APIView
from rest_framework.response import Response

from .models import (
    Currency, AccountType, Account, Bank,
    CashRegister, CashRegisterOpening, CashRegisterClosing,
    ExpenseType, Expense,
)
from .serializers import (
    CurrencySerializer, AccountTypeSerializer, AccountSerializer,
    BankSerializer, CashRegisterSerializer, CashRegisterOpeningSerializer,
    CashRegisterClosingSerializer, ExpenseTypeSerializer, ExpenseSerializer,
)


def filter_by_company(queryset, request):
    if request.user.is_superuser:
        return queryset
    if request.user.company_id:
        return queryset.filter(company_id=request.user.company_id)
    return queryset.none()


class CurrencyViewSet(viewsets.ModelViewSet):
    serializer_class = CurrencySerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Currency.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class AccountTypeViewSet(viewsets.ModelViewSet):
    serializer_class = AccountTypeSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(AccountType.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class AccountViewSet(viewsets.ModelViewSet):
    serializer_class = AccountSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Account.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class BankViewSet(viewsets.ModelViewSet):
    serializer_class = BankSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Bank.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class CashRegisterViewSet(viewsets.ModelViewSet):
    serializer_class = CashRegisterSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(CashRegister.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class CashRegisterOpeningViewSet(viewsets.ModelViewSet):
    serializer_class = CashRegisterOpeningSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        if self.request.user.is_superuser:
            return CashRegisterOpening.objects.all()
        if self.request.user.company_id:
            return CashRegisterOpening.objects.filter(
                cash_register__company_id=self.request.user.company_id
            )
        return CashRegisterOpening.objects.none()

    def perform_create(self, serializer):
        serializer.save(opened_by=self.request.user)

    @action(detail=True, methods=['get'], url_path='closure-summary')
    def closure_summary(self, request, pk=None):
        """Resumen para cierre: ventas y cobros vinculados a esta apertura."""
        from apps.invoicing.models import Invoice, CustomerPayment
        from decimal import Decimal

        opening = self.get_object()
        total_sales = (
            Invoice.objects.filter(cash_opening_id=opening.id)
            .aggregate(s=Sum('total'))['s'] or Decimal('0')
        )
        payments = (
            CustomerPayment.objects.filter(cash_opening_id=opening.id)
            .aggregate(
                total=Sum('amount'),
                cash=Sum('amount', filter=models.Q(payment_method__icontains='efectivo')),
                card=Sum('amount', filter=models.Q(payment_method__icontains='tarjeta')),
            )
        )
        total_payments_in = payments['total'] or Decimal('0')
        cash_total = payments['cash'] or Decimal('0')
        card_total = payments['card'] or Decimal('0')
        other_total = total_payments_in - cash_total - card_total
        if other_total < 0:
            other_total = Decimal('0')
        return Response({
            'opening_id': str(opening.id),
            'opening_balance': float(opening.opening_balance),
            'total_sales': float(total_sales),
            'total_payments_in': float(total_payments_in),
            'breakdown': {
                'cash_total': float(cash_total),
                'card_total': float(card_total),
                'other_total': float(other_total),
            },
        })


class CashRegisterClosingViewSet(viewsets.ModelViewSet):
    serializer_class = CashRegisterClosingSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        if self.request.user.is_superuser:
            return CashRegisterClosing.objects.all()
        if self.request.user.company_id:
            return CashRegisterClosing.objects.filter(
                opening__cash_register__company_id=self.request.user.company_id
            )
        return CashRegisterClosing.objects.none()

    def perform_create(self, serializer):
        serializer.save(closed_by=self.request.user)

    @action(detail=True, methods=['post'])
    def validate(self, request, pk=None):
        """Valida el cierre de caja (supervisor)."""
        closing = self.get_object()
        if closing.validation_status == 'validated':
            return Response(
                {'detail': 'Este cierre ya está validado.'},
                status=status.HTTP_400_BAD_REQUEST
            )
        closing.validation_status = 'validated'
        closing.validated_by = request.user
        closing.validated_at = timezone.now()
        closing.save()
        serializer = CashRegisterClosingSerializer(closing)
        return Response(serializer.data)

    @action(detail=True, methods=['get'], url_path='ticket')
    def ticket(self, request, pk=None):
        """Ticket HTML del cierre de caja."""
        closing = self.get_object()
        opening = closing.opening
        cr = opening.cash_register
        html = f'''
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Cierre de caja</title></head>
<body style="font-family: monospace; max-width: 320px; margin: 1rem;">
  <h3 style="text-align: center;">CIERRE DE CAJA</h3>
  <p><b>Caja:</b> {cr.name}</p>
  <p><b>Apertura:</b> {opening.opened_at}</p>
  <p><b>Cierre:</b> {closing.closed_at}</p>
  <hr/>
  <p>Saldo apertura: S/ {opening.opening_balance}</p>
  <p>Ventas: S/ {closing.total_sales}</p>
  <p>Cobros: S/ {closing.total_payments_in}</p>
  <p>Egresos: S/ {closing.total_payments_out}</p>
  <p><b>Efectivo:</b> S/ {closing.cash_total}</p>
  <p><b>Tarjeta:</b> S/ {closing.card_total}</p>
  <p><b>Otros:</b> S/ {closing.other_total}</p>
  <hr/>
  <p><b>Saldo cierre:</b> S/ {closing.closing_balance}</p>
  <p>Estado: {closing.get_validation_status_display()}</p>
  <p>{closing.notes or ''}</p>
</body>
</html>'''
        return HttpResponse(html, content_type='text/html; charset=utf-8')


class ExpenseTypeViewSet(viewsets.ModelViewSet):
    serializer_class = ExpenseTypeSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(ExpenseType.objects.all(), self.request)

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class ExpenseViewSet(viewsets.ModelViewSet):
    serializer_class = ExpenseSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return filter_by_company(Expense.objects.all(), self.request)

    def perform_create(self, serializer):
        data = {}
        if self.request.user.company_id:
            data['company_id'] = self.request.user.company_id
        data['created_by_id'] = self.request.user.id
        serializer.save(**data)


class BalanceReport(APIView):
    """Balance: listado de cuentas (saldo 0 por defecto; sin partidas contables aún)."""
    permission_classes = [IsAuthenticated]

    def get(self, request):
        qs = filter_by_company(Account.objects.all(), request).filter(is_active=True).select_related('account_type')
        rows = []
        for acc in qs:
            rows.append({
                'id': str(acc.id),
                'code': acc.code,
                'name': acc.name,
                'account_type': acc.account_type.name,
                'balance': 0,
                'order': acc.order,
            })
        return Response({'data': rows})
