import uuid
from datetime import date, datetime, timedelta
from decimal import Decimal

from django.core.management.base import BaseCommand
from django.utils import timezone

from apps.core.models import (
    SubscriptionPlan,
    Company,
    CompanySubscription,
    InvoiceUsageLog,
    Role,
    User,
)
from apps.catalog.models import Customer, Supplier, ProductCategory, Product
from apps.inventory.models import Warehouse, WarehouseLocation, StockQuant
from apps.invoicing.models import InvoiceSeries, Invoice, InvoiceLine


# UUIDs fijos para datos de prueba reproducibles
UUID_PLAN = uuid.UUID('00000000-0000-0000-0000-000000000001')
UUID_COMPANY = uuid.UUID('00000000-0000-0000-0000-000000000002')
UUID_ROLE = uuid.UUID('00000000-0000-0000-0000-000000000003')
UUID_USER = uuid.UUID('00000000-0000-0000-0000-000000000004')
UUID_CUSTOMER = uuid.UUID('00000000-0000-0000-0000-000000000005')
UUID_SUPPLIER = uuid.UUID('00000000-0000-0000-0000-000000000006')
UUID_CATEGORY = uuid.UUID('00000000-0000-0000-0000-000000000007')
UUID_PRODUCT_1 = uuid.UUID('00000000-0000-0000-0000-000000000008')
UUID_PRODUCT_2 = uuid.UUID('00000000-0000-0000-0000-000000000009')
UUID_WAREHOUSE = uuid.UUID('00000000-0000-0000-0000-00000000000a')
UUID_LOCATION = uuid.UUID('00000000-0000-0000-0000-00000000000b')
UUID_SERIE_F = uuid.UUID('00000000-0000-0000-0000-00000000000c')
UUID_SERIE_B = uuid.UUID('00000000-0000-0000-0000-00000000000d')
UUID_INVOICE = uuid.UUID('00000000-0000-0000-0000-00000000000e')
UUID_LINE = uuid.UUID('00000000-0000-0000-0000-00000000000f')
UUID_QUANT_1 = uuid.UUID('00000000-0000-0000-0000-000000000010')
UUID_QUANT_2 = uuid.UUID('00000000-0000-0000-0000-000000000011')

# Productos adicionales estilo demodb (2).sql - más INSERTs para base no vacía
PRODUCTOS_DEMO = [
    {'sku': 'ART001', 'nombre': 'ABONO 50KG', 'unidad': 'KGM', 'precio': '45.00', 'costo': '25.00'},
    {'sku': 'ART002', 'nombre': 'ALBENDAZOL 1LT', 'unidad': 'LTR', 'precio': '30.00', 'costo': '15.00'},
    {'sku': 'ART003', 'nombre': 'ALIMENT CAT 20KG', 'unidad': 'KGM', 'precio': '85.00', 'costo': '50.00'},
    {'sku': 'ART004', 'nombre': 'CHAPA BOLA (YALE)', 'unidad': 'NIU', 'precio': '120.00', 'costo': '70.00'},
    {'sku': 'ART005', 'nombre': 'CAMBIO SIM', 'unidad': 'NIU', 'precio': '5.00', 'costo': '2.00'},
    {'sku': 'ART006', 'nombre': 'SIM BITEL', 'unidad': 'NIU', 'precio': '10.00', 'costo': '5.00'},
    {'sku': 'ART007', 'nombre': 'GRANHUMUS 50KG', 'unidad': 'KGM', 'precio': '150.00', 'costo': '90.00'},
    {'sku': 'ART008', 'nombre': 'HUMUS LÍQUIDO 1LT', 'unidad': 'KGM', 'precio': '25.00', 'costo': '12.00'},
    {'sku': 'ART009', 'nombre': 'BITEL - ILIMITADO 29.90', 'unidad': 'NIU', 'precio': '29.90', 'costo': '15.00'},
    {'sku': 'ART010', 'nombre': 'BITEL - ILIMITADO 39.90', 'unidad': 'NIU', 'precio': '39.90', 'costo': '20.00'},
    {'sku': 'ART011', 'nombre': 'SIM REGALO', 'unidad': 'NIU', 'precio': '0.00', 'costo': '0.00'},
    {'sku': 'ART012', 'nombre': 'RECARGA', 'unidad': 'NIU', 'precio': '5.00', 'costo': '2.50'},
    {'sku': 'ART013', 'nombre': 'ABONO ORGANICO', 'unidad': 'KGM', 'precio': '55.00', 'costo': '30.00'},
]


class Command(BaseCommand):
    help = 'Carga datos de prueba (estilo demodb): empresa demo, usuario admin@demo.com, clientes, productos, facturas y stock. Ejecutar después de migrate.'

    def handle(self, *args, **options):
        now = timezone.now()
        today = date.today()
        ym = today.strftime('%Y-%m')

        # 1 SubscriptionPlan
        plan, _ = SubscriptionPlan.objects.update_or_create(
            id=UUID_PLAN,
            defaults={
                'name': 'Plan Demo',
                'max_invoices_per_month': 100,
                'is_active': True,
            },
        )
        self.stdout.write('SubscriptionPlan OK')

        # 1 Company (usuario_sol MODDATOS = usuario de prueba SUNAT para homologación)
        company, _ = Company.objects.update_or_create(
            id=UUID_COMPANY,
            defaults={
                'ruc': '20100000001',
                'razon_social': 'Empresa Demo SAC',
                'nombre_comercial': 'Demo',
                'domicilio_fiscal': 'Av. Demo 123',
                'usuario_sol': 'MODDATOS',  # Usuario prueba SUNAT homologación (clave SOL = MODDATOS)
                'codigo_sunat': '0000',
                'igv_porcentaje': 18,
                'plan_id': plan.id,
                'is_active': True,
            },
        )
        self.stdout.write('Company OK')

        # 1 CompanySubscription
        CompanySubscription.objects.update_or_create(
            company=company,
            plan=plan,
            defaults={
                'started_at': now - timedelta(days=365),
                'expires_at': now + timedelta(days=365),
                'is_active': True,
            },
        )
        self.stdout.write('CompanySubscription OK')

        # 1 InvoiceUsageLog (mes actual)
        InvoiceUsageLog.objects.update_or_create(
            company=company,
            year_month=ym,
            defaults={'count': 1},
        )
        self.stdout.write('InvoiceUsageLog OK')

        # 1 Role
        role, _ = Role.objects.update_or_create(
            id=UUID_ROLE,
            defaults={
                'company': company,
                'name': 'Administrador',
                'permissions': {},
                'is_system_role': True,
            },
        )
        self.stdout.write('Role OK')

        # 1 User admin@demo.com / admin123
        user, created = User.objects.update_or_create(
            id=UUID_USER,
            defaults={
                'email': 'admin@demo.com',
                'company': company,
                'role': role,
                'first_name': 'Admin',
                'last_name': 'Demo',
                'tipo_documento': '1',
                'numero_documento': '00000000',
                'is_active': True,
                'is_staff': True,
                'is_superuser': True,
            },
        )
        user.set_password('admin123')
        user.save()
        self.stdout.write('User admin@demo.com OK')

        # 1 Customer
        Customer.objects.update_or_create(
            id=UUID_CUSTOMER,
            defaults={
                'company': company,
                'tipo_documento': '6',
                'numero_documento': '20100000002',
                'razon_social': 'Cliente Demo SAC',
                'direccion': 'Calle Cliente 456',
                'email': 'cliente@demo.com',
                'is_active': True,
            },
        )
        # Clientes adicionales (estilo demodb)
        Customer.objects.update_or_create(
            company=company,
            tipo_documento='6',
            numero_documento='20100000010',
            defaults={
                'razon_social': 'Farmacia San José EIRL',
                'direccion': 'Jr. Salud 100',
                'email': 'ventas@farmaciasanjose.com',
                'telefono': '014567890',
                'is_active': True,
            },
        )
        Customer.objects.update_or_create(
            company=company,
            tipo_documento='1',
            numero_documento='45678901',
            defaults={
                'razon_social': 'Juan Pérez (DNI)',
                'direccion': 'Av. Los Pinos 200',
                'telefono': '987654321',
                'is_active': True,
            },
        )
        self.stdout.write('Customers OK')

        # 1 Supplier
        Supplier.objects.update_or_create(
            id=UUID_SUPPLIER,
            defaults={
                'company': company,
                'tipo_documento': '6',
                'numero_documento': '20100000003',
                'razon_social': 'Proveedor Demo SAC',
                'is_active': True,
            },
        )
        self.stdout.write('Supplier OK')

        # 1 ProductCategory
        category, _ = ProductCategory.objects.update_or_create(
            id=UUID_CATEGORY,
            defaults={
                'company': company,
                'name': 'General',
                'is_active': True,
            },
        )
        self.stdout.write('ProductCategory OK')

        # 2 Products
        Product.objects.update_or_create(
            id=UUID_PRODUCT_1,
            defaults={
                'company': company,
                'sku': 'PROD001',
                'nombre': 'Producto Uno',
                'unidad_medida': 'NIU',
                'precio_venta': Decimal('10.00'),
                'costo_unitario': Decimal('5.00'),
                'afecto_igv': True,
                'codigo_tipo_afectacion': '10',
                'category': category,
                'is_active': True,
            },
        )
        Product.objects.update_or_create(
            id=UUID_PRODUCT_2,
            defaults={
                'company': company,
                'sku': 'PROD002',
                'nombre': 'Producto Dos',
                'unidad_medida': 'NIU',
                'precio_venta': Decimal('20.00'),
                'costo_unitario': Decimal('10.00'),
                'afecto_igv': True,
                'codigo_tipo_afectacion': '10',
                'category': category,
                'is_active': True,
            },
        )
        # Productos adicionales estilo demodb (2).sql
        for i, p in enumerate(PRODUCTOS_DEMO):
            Product.objects.update_or_create(
                company=company,
                sku=p['sku'],
                defaults={
                    'nombre': p['nombre'],
                    'unidad_medida': p['unidad'],
                    'precio_venta': Decimal(p['precio']),
                    'costo_unitario': Decimal(p['costo']),
                    'costo_promedio': Decimal(p['costo']),
                    'afecto_igv': True,
                    'codigo_tipo_afectacion': '10',
                    'category': category,
                    'is_active': True,
                },
            )
        self.stdout.write('Products OK (base + estilo demodb)')

        # 1 Warehouse, 1 WarehouseLocation
        warehouse, _ = Warehouse.objects.update_or_create(
            id=UUID_WAREHOUSE,
            defaults={
                'company': company,
                'name': 'Almacén Principal',
                'code': 'ALM01',
                'is_active': True,
            },
        )
        location, _ = WarehouseLocation.objects.update_or_create(
            id=UUID_LOCATION,
            defaults={
                'warehouse': warehouse,
                'name': 'Estante A',
                'code': 'A1',
                'is_active': True,
            },
        )
        self.stdout.write('Warehouse OK')

        # 2 InvoiceSeries (F001 y B001)
        serie_f, _ = InvoiceSeries.objects.update_or_create(
            id=UUID_SERIE_F,
            defaults={
                'company': company,
                'warehouse': warehouse,
                'tipo_documento': '01',
                'serie': 'F001',
                'next_number': 2,
                'last_number': 1,
                'is_active': True,
                'is_default': True,
            },
        )
        InvoiceSeries.objects.update_or_create(
            id=UUID_SERIE_B,
            defaults={
                'company': company,
                'warehouse': warehouse,
                'tipo_documento': '03',
                'serie': 'B001',
                'next_number': 1,
                'last_number': 0,
                'is_active': True,
                'is_default': False,
            },
        )
        self.stdout.write('InvoiceSeries OK')

        # 1 Invoice con 1 InvoiceLine (accepted)
        customer = Customer.objects.get(id=UUID_CUSTOMER)
        product1 = Product.objects.get(id=UUID_PRODUCT_1)
        invoice, inv_created = Invoice.objects.update_or_create(
            id=UUID_INVOICE,
            defaults={
                'company': company,
                'tipo_documento': '01',
                'serie': 'F001',
                'numero': 1,
                'fecha_emision': today,
                'customer': customer,
                'cliente_tipo_documento': customer.tipo_documento,
                'cliente_numero_documento': customer.numero_documento,
                'cliente_razon_social': customer.razon_social,
                'cliente_direccion': customer.direccion,
                'subtotal': Decimal('8.47'),
                'igv_total': Decimal('1.53'),
                'total': Decimal('10.00'),
                'status': 'accepted',
                'warehouse': warehouse,
                'created_by': user,
            },
        )
        if inv_created:
            InvoiceLine.objects.update_or_create(
                id=UUID_LINE,
                defaults={
                    'invoice': invoice,
                    'line_number': 1,
                    'product': product1,
                    'descripcion': 'Producto Uno',
                    'cantidad': Decimal('1'),
                    'valor_unitario': Decimal('8.47'),
                    'valor_venta': Decimal('8.47'),
                    'codigo_tipo_afectacion': '10',
                    'igv_monto': Decimal('1.53'),
                    'importe_total': Decimal('10.00'),
                },
            )
        self.stdout.write('Invoice OK')

        # 2 StockQuant
        StockQuant.objects.update_or_create(
            id=UUID_QUANT_1,
            defaults={
                'company': company,
                'product': product1,
                'warehouse': warehouse,
                'location': location,
                'quantity': Decimal('100'),
                'reserved_quantity': Decimal('0'),
            },
        )
        product2 = Product.objects.get(id=UUID_PRODUCT_2)
        StockQuant.objects.update_or_create(
            id=UUID_QUANT_2,
            defaults={
                'company': company,
                'product': product2,
                'warehouse': warehouse,
                'location': location,
                'quantity': Decimal('50'),
                'reserved_quantity': Decimal('0'),
            },
        )
        # Stock para productos estilo demodb
        for p in PRODUCTOS_DEMO:
            prod = Product.objects.filter(company=company, sku=p['sku']).first()
            if prod:
                StockQuant.objects.update_or_create(
                    company=company,
                    product=prod,
                    warehouse=warehouse,
                    defaults={
                        'location': location,
                        'quantity': Decimal('30'),
                        'reserved_quantity': Decimal('0'),
                    },
                )
        self.stdout.write('StockQuant OK')

        # Facturas adicionales (F001-2 y F001-3) para que la base no esté vacía
        customer2 = Customer.objects.filter(company=company, numero_documento='20100000010').first()
        if customer2:
            inv2, c2 = Invoice.objects.get_or_create(
                company=company,
                tipo_documento='01',
                serie='F001',
                numero=2,
                defaults={
                    'fecha_emision': today,
                    'customer': customer2,
                    'cliente_tipo_documento': customer2.tipo_documento,
                    'cliente_numero_documento': customer2.numero_documento,
                    'cliente_razon_social': customer2.razon_social,
                    'cliente_direccion': customer2.direccion or '',
                    'subtotal': Decimal('72.03'),
                    'igv_total': Decimal('12.97'),
                    'total': Decimal('85.00'),
                    'status': 'accepted',
                    'warehouse': warehouse,
                    'created_by': user,
                },
            )
            if c2:
                InvoiceLine.objects.get_or_create(
                    invoice=inv2,
                    line_number=1,
                    defaults={
                        'product': Product.objects.get(company=company, sku='ART003'),
                        'descripcion': 'ALIMENT CAT 20KG',
                        'cantidad': Decimal('1'),
                        'valor_unitario': Decimal('72.03'),
                        'valor_venta': Decimal('72.03'),
                        'codigo_tipo_afectacion': '10',
                        'igv_monto': Decimal('12.97'),
                        'importe_total': Decimal('85.00'),
                    },
                )
            inv3, c3 = Invoice.objects.get_or_create(
                company=company,
                tipo_documento='01',
                serie='F001',
                numero=3,
                defaults={
                    'fecha_emision': today,
                    'customer': customer,
                    'cliente_tipo_documento': customer.tipo_documento,
                    'cliente_numero_documento': customer.numero_documento,
                    'cliente_razon_social': customer.razon_social,
                    'cliente_direccion': customer.direccion,
                    'subtotal': Decimal('25.42'),
                    'igv_total': Decimal('4.58'),
                    'total': Decimal('30.00'),
                    'status': 'draft',
                    'warehouse': warehouse,
                    'created_by': user,
                },
            )
            if c3:
                InvoiceLine.objects.get_or_create(
                    invoice=inv3,
                    line_number=1,
                    defaults={
                        'product': Product.objects.get(company=company, sku='ART002'),
                        'descripcion': 'ALBENDAZOL 1LT',
                        'cantidad': Decimal('1'),
                        'valor_unitario': Decimal('25.42'),
                        'valor_venta': Decimal('25.42'),
                        'codigo_tipo_afectacion': '10',
                        'igv_monto': Decimal('4.58'),
                        'importe_total': Decimal('30.00'),
                    },
                )
        # Ajustar siguiente número de la serie F001
        InvoiceSeries.objects.filter(id=UUID_SERIE_F).update(next_number=4, last_number=3)
        self.stdout.write('Invoices adicionales OK')

        self.stdout.write(self.style.SUCCESS('Datos de prueba cargados (estilo demodb). Login: admin@demo.com / admin123'))
