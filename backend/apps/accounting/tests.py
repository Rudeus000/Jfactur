"""
Tests básicos: cajas, aperturas, cierres, resumen de cierre.
"""
from decimal import Decimal
from datetime import date, datetime
from unittest.mock import patch

from django.utils import timezone
from rest_framework import status
from rest_framework.test import APITestCase

from apps.core.models import SubscriptionPlan, Company, Role, User
from apps.inventory.models import Warehouse
from apps.accounting.models import (
    CashRegister, CashRegisterOpening, CashRegisterClosing,
    Bank,
)


class AccountingBaseTestCase(APITestCase):
    def setUp(self):
        super().setUp()
        self.plan = SubscriptionPlan.objects.create(name='Plan', max_invoices_per_month=100, is_active=True)
        self.company = Company.objects.create(ruc='20555555555', razon_social='Acc Test', plan=self.plan, is_active=True)
        self.role = Role.objects.create(company=self.company, name='Admin', is_system_role=True)
        self.user = User.objects.create_user(
            email='acc@test.com', password='acc123', company=self.company, role=self.role
        )
        self.client.force_authenticate(user=self.user)
        self.warehouse = Warehouse.objects.create(company=self.company, name='Alm', code='1', is_active=True)
        self.cash_register = CashRegister.objects.create(
            company=self.company, name='Caja 1', code='C1', is_active=True
        )


class CashRegisterTests(AccountingBaseTestCase):
    def test_cash_registers_list(self):
        response = self.client.get('/api/v1/cash-registers/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertGreaterEqual(len(response.data), 1)

    def test_cash_openings_list(self):
        CashRegisterOpening.objects.create(
            cash_register=self.cash_register,
            opened_at=timezone.now(),
            opening_balance=Decimal('0'),
            opened_by=self.user,
        )
        response = self.client.get('/api/v1/cash-openings/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)


class CashClosingTests(AccountingBaseTestCase):
    def test_closure_summary(self):
        opening = CashRegisterOpening.objects.create(
            cash_register=self.cash_register,
            opened_at=timezone.now(),
            opening_balance=Decimal('100'),
            opened_by=self.user,
        )
        response = self.client.get(f'/api/v1/cash-openings/{opening.id}/closure-summary/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn('opening_id', response.data)
        self.assertIn('total_sales', response.data)
        self.assertIn('breakdown', response.data)

    def test_closing_ticket(self):
        opening = CashRegisterOpening.objects.create(
            cash_register=self.cash_register,
            opened_at=timezone.now(),
            opening_balance=Decimal('0'),
            opened_by=self.user,
        )
        closing = CashRegisterClosing.objects.create(
            opening=opening,
            closed_at=timezone.now(),
            closing_balance=Decimal('500'),
            total_sales=Decimal('400'),
            total_payments_in=Decimal('0'),
            total_payments_out=Decimal('0'),
            cash_total=Decimal('500'),
            card_total=Decimal('0'),
            other_total=Decimal('0'),
            closed_by=self.user,
        )
        response = self.client.get(f'/api/v1/cash-closings/{closing.id}/ticket/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn(b'CIERRE DE CAJA', response.content)
