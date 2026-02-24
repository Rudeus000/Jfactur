"""
Tests básicos: clientes, productos (listar y crear).
"""
from decimal import Decimal

from rest_framework import status
from rest_framework.test import APITestCase

from apps.core.models import SubscriptionPlan, Company, Role, User
from apps.catalog.models import Customer, ProductCategory, Product


class CatalogBaseTestCase(APITestCase):
    def setUp(self):
        super().setUp()
        self.plan = SubscriptionPlan.objects.create(name='Plan', max_invoices_per_month=100, is_active=True)
        self.company = Company.objects.create(ruc='20333333333', razon_social='Cat Test', plan=self.plan, is_active=True)
        self.role = Role.objects.create(company=self.company, name='Admin', is_system_role=True)
        self.user = User.objects.create_user(
            email='cat@test.com', password='cat123', company=self.company, role=self.role
        )
        self.client.force_authenticate(user=self.user)


class CustomerTests(CatalogBaseTestCase):
    def test_customers_list(self):
        Customer.objects.create(
            company=self.company,
            tipo_documento='6',
            numero_documento='20100000002',
            razon_social='Cliente Uno',
        )
        response = self.client.get('/api/v1/customers/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertGreaterEqual(len(response.data), 1)

    def test_customer_create(self):
        response = self.client.post('/api/v1/customers/', {
            'tipo_documento': '1',
            'numero_documento': '12345678',
            'razon_social': 'Juan Pérez',
            'direccion': 'Calle 123',
        })
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(response.data.get('razon_social'), 'Juan Pérez')
        self.assertEqual(response.data.get('numero_documento'), '12345678')


class ProductTests(CatalogBaseTestCase):
    def setUp(self):
        super().setUp()
        self.category = ProductCategory.objects.create(
            company=self.company, name='Cat Test', is_active=True
        )

    def test_products_list(self):
        Product.objects.create(
            company=self.company,
            sku='SKU001',
            nombre='Producto Test',
            unidad_medida='NIU',
            precio_venta=Decimal('10.00'),
            costo_unitario=Decimal('5.00'),
            category=self.category,
            is_active=True,
        )
        response = self.client.get('/api/v1/products/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertGreaterEqual(len(response.data), 1)

    def test_product_create(self):
        response = self.client.post('/api/v1/products/', {
            'sku': 'SKU002',
            'nombre': 'Otro Producto',
            'unidad_medida': 'NIU',
            'precio_venta': '25.50',
            'costo_unitario': '12.00',
            'category': str(self.category.id),
            'afecto_igv': True,
            'codigo_tipo_afectacion': '10',
        })
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(response.data.get('nombre'), 'Otro Producto')
        self.assertEqual(response.data.get('sku'), 'SKU002')
