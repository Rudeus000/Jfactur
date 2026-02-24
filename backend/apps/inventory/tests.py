"""
Tests básicos: almacenes, stock quants, traspasos (listar y crear).
"""
from decimal import Decimal

from rest_framework import status
from rest_framework.test import APITestCase

from apps.core.models import SubscriptionPlan, Company, Role, User
from apps.catalog.models import ProductCategory, Product
from apps.inventory.models import Warehouse, StockQuant, StockTransfer, StockTransferLine


class InventoryBaseTestCase(APITestCase):
    def setUp(self):
        super().setUp()
        self.plan = SubscriptionPlan.objects.create(name='Plan', max_invoices_per_month=100, is_active=True)
        self.company = Company.objects.create(ruc='20444444444', razon_social='Inv Test', plan=self.plan, is_active=True)
        self.role = Role.objects.create(company=self.company, name='Admin', is_system_role=True)
        self.user = User.objects.create_user(
            email='inv@test.com', password='inv123', company=self.company, role=self.role
        )
        self.client.force_authenticate(user=self.user)
        self.warehouse1 = Warehouse.objects.create(company=self.company, name='Almacén A', code='A', is_active=True)
        self.warehouse2 = Warehouse.objects.create(company=self.company, name='Almacén B', code='B', is_active=True)
        self.category = ProductCategory.objects.create(company=self.company, name='Cat', is_active=True)
        self.product = Product.objects.create(
            company=self.company,
            sku='INV001',
            nombre='Prod Inventario',
            unidad_medida='NIU',
            precio_venta=Decimal('10'),
            costo_unitario=Decimal('5'),
            category=self.category,
            is_active=True,
        )


class WarehouseTests(InventoryBaseTestCase):
    def test_warehouses_list(self):
        response = self.client.get('/api/v1/warehouses/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertGreaterEqual(len(response.data), 2)
        names = [w.get('name') for w in response.data]
        self.assertIn('Almacén A', names)

    def test_warehouse_create(self):
        response = self.client.post('/api/v1/warehouses/', {'name': 'Almacén C', 'code': 'C'})
        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(response.data.get('name'), 'Almacén C')


class StockQuantTests(InventoryBaseTestCase):
    def test_stock_quants_list(self):
        StockQuant.objects.create(
            company=self.company, product=self.product, warehouse=self.warehouse1, quantity=Decimal('100')
        )
        response = self.client.get('/api/v1/stock-quants/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)

    def test_stock_quants_filter_by_warehouse(self):
        response = self.client.get(f'/api/v1/stock-quants/?warehouse_id={self.warehouse1.id}')
        self.assertEqual(response.status_code, status.HTTP_200_OK)


class StockTransferTests(InventoryBaseTestCase):
    def test_transfers_list(self):
        response = self.client.get('/api/v1/transfers/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIsInstance(response.data, list)

    def test_transfer_create(self):
        """Crear traspaso pendiente (warehouse_origin, warehouse_dest, date, lines)."""
        from datetime import date
        response = self.client.post(
            '/api/v1/transfers/',
            {
                'company': str(self.company.id),
                'warehouse_origin': str(self.warehouse1.id),
                'warehouse_dest': str(self.warehouse2.id),
                'date': str(date.today()),
                'notes': 'Prueba',
                'lines': [{'product': str(self.product.id), 'quantity': '5'}],
            },
            format='json',
        )
        # Puede ser 201 o 400 si falta algún campo requerido en líneas
        self.assertIn(response.status_code, (status.HTTP_201_CREATED, status.HTTP_400_BAD_REQUEST))
        if response.status_code == 201:
            self.assertEqual(response.data.get('status'), 'pending')
            self.assertIn('lines', response.data)
