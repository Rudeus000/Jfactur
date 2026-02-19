"""
Tests básicos: facturas, cotizaciones, reportes (dashboard, aged), ticket.
"""
from decimal import Decimal
from datetime import date

from rest_framework import status
from rest_framework.test import APITestCase

from apps.core.models import SubscriptionPlan, Company, Role, User
from apps.catalog.models import Customer, ProductCategory, Product
from apps.inventory.models import Warehouse
from apps.invoicing.models import (
    InvoiceSeries, Invoice, InvoiceLine,
    Quote, QuoteLine,
)


class InvoicingBaseTestCase(APITestCase):
    def setUp(self):
        super().setUp()
        self.plan = SubscriptionPlan.objects.create(name='Plan', max_invoices_per_month=100, is_active=True)
        self.company = Company.objects.create(ruc='20777777777', razon_social='Inv Test', plan=self.plan, is_active=True)
        self.role = Role.objects.create(company=self.company, name='Admin', is_system_role=True)
        self.user = User.objects.create_user(
            email='inv@test.com', password='inv123', company=self.company, role=self.role
        )
        self.client.force_authenticate(user=self.user)
        self.warehouse = Warehouse.objects.create(company=self.company, name='Alm', code='1', is_active=True)
        self.customer = Customer.objects.create(
            company=self.company,
            tipo_documento='6',
            numero_documento='20100000002',
            razon_social='Cliente Factura',
        )
        self.category = ProductCategory.objects.create(company=self.company, name='Cat', is_active=True)
        self.product = Product.objects.create(
            company=self.company,
            sku='FAC001',
            nombre='Producto Factura',
            unidad_medida='NIU',
            precio_venta=Decimal('10'),
            costo_unitario=Decimal('5'),
            category=self.category,
            is_active=True,
        )
        self.serie = InvoiceSeries.objects.create(
            company=self.company,
            warehouse=self.warehouse,
            tipo_documento='01',
            serie='F001',
            next_number=1,
            last_number=0,
            is_active=True,
            is_default=True,
        )


class InvoiceTests(InvoicingBaseTestCase):
    def test_invoices_list(self):
        Invoice.objects.create(
            company=self.company,
            tipo_documento='01',
            serie='F001',
            numero=1,
            fecha_emision=date.today(),
            customer=self.customer,
            cliente_tipo_documento='6',
            cliente_numero_documento=self.customer.numero_documento,
            cliente_razon_social=self.customer.razon_social,
            subtotal=Decimal('8.47'),
            igv_total=Decimal('1.53'),
            total=Decimal('10'),
            status='draft',
            warehouse=self.warehouse,
            created_by=self.user,
        )
        response = self.client.get('/api/v1/invoices/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertGreaterEqual(len(response.data), 1)

    def test_invoice_ticket(self):
        inv = Invoice.objects.create(
            company=self.company,
            tipo_documento='03',
            serie='B001',
            numero=1,
            fecha_emision=date.today(),
            customer=self.customer,
            cliente_tipo_documento='6',
            cliente_numero_documento=self.customer.numero_documento,
            cliente_razon_social=self.customer.razon_social,
            subtotal=Decimal('10'),
            igv_total=Decimal('1.80'),
            total=Decimal('11.80'),
            status='draft',
            warehouse=self.warehouse,
            created_by=self.user,
        )
        InvoiceLine.objects.create(
            invoice=inv,
            line_number=1,
            product=self.product,
            descripcion=self.product.nombre,
            cantidad=Decimal('1'),
            valor_unitario=Decimal('10'),
            valor_venta=Decimal('10'),
            codigo_tipo_afectacion='10',
            igv_monto=Decimal('1.80'),
            importe_total=Decimal('11.80'),
        )
        response = self.client.get(f'/api/v1/invoices/{inv.id}/ticket/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn(b'03', response.content)  # tipo_documento 03 = Boleta
        self.assertIn(b'Total', response.content)


class ReportTests(InvoicingBaseTestCase):
    def test_dashboard(self):
        response = self.client.get('/api/v1/reports/dashboard/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn('ventas_hoy', response.data)
        self.assertIn('total_por_cobrar', response.data)
        self.assertIn('alertas', response.data)

    def test_aged_receivable(self):
        response = self.client.get('/api/v1/reports/aged-receivable/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn('as_of', response.data)
        self.assertIn('data', response.data)

    def test_aged_receivable_with_date(self):
        response = self.client.get('/api/v1/reports/aged-receivable/?as_of=2025-02-18')
        self.assertEqual(response.status_code, status.HTTP_200_OK)

    def test_aged_payable(self):
        response = self.client.get('/api/v1/reports/aged-payable/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn('data', response.data)

    def test_libro_ventas_requires_dates(self):
        response = self.client.get('/api/v1/reports/libro-ventas/')
        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)

    def test_libro_ventas_ok(self):
        response = self.client.get(
            '/api/v1/reports/libro-ventas/?date_from=2025-01-01&date_to=2025-12-31'
        )
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn('data', response.data)


class QuoteTests(InvoicingBaseTestCase):
    def test_quotes_list(self):
        Quote.objects.create(
            company=self.company,
            customer=self.customer,
            date=date.today(),
            cliente_numero_documento=self.customer.numero_documento,
            cliente_razon_social=self.customer.razon_social,
            subtotal=Decimal('10'),
            igv_total=Decimal('1.80'),
            total=Decimal('11.80'),
            status='draft',
            created_by=self.user,
        )
        response = self.client.get('/api/v1/quotes/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertGreaterEqual(len(response.data), 1)
