# Generated manually for StockTransfer and StockTransferLine

import django.db.models.deletion
import uuid
from django.conf import settings
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('catalog', '0001_initial'),
        ('core', '0004_company_codigo_pais_company_codigo_sunat_and_more'),
        ('inventory', '0002_stockmovement'),
        migrations.swappable_dependency(settings.AUTH_USER_MODEL),
    ]

    operations = [
        migrations.CreateModel(
            name='StockTransfer',
            fields=[
                ('id', models.UUIDField(default=uuid.uuid4, editable=False, primary_key=True, serialize=False)),
                ('date', models.DateField()),
                ('status', models.CharField(choices=[('pending', 'Pendiente'), ('approved', 'Aprobado'), ('rejected', 'Rechazado')], default='pending', max_length=20)),
                ('notes', models.TextField(blank=True)),
                ('created_at', models.DateTimeField(auto_now_add=True)),
                ('updated_at', models.DateTimeField(auto_now=True)),
                ('company', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, related_name='stock_transfers', to='core.company')),
                ('created_by', models.ForeignKey(null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='stock_transfers_created', to=settings.AUTH_USER_MODEL)),
                ('validated_by', models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='stock_transfers_validated', to=settings.AUTH_USER_MODEL)),
                ('validated_at', models.DateTimeField(blank=True, null=True)),
                ('warehouse_dest', models.ForeignKey(on_delete=django.db.models.deletion.PROTECT, related_name='transfers_in', to='inventory.warehouse')),
                ('warehouse_origin', models.ForeignKey(on_delete=django.db.models.deletion.PROTECT, related_name='transfers_out', to='inventory.warehouse')),
            ],
            options={
                'db_table': 'stock_transfers',
                'ordering': ['-date', '-created_at'],
            },
        ),
        migrations.CreateModel(
            name='StockTransferLine',
            fields=[
                ('id', models.UUIDField(default=uuid.uuid4, editable=False, primary_key=True, serialize=False)),
                ('line_number', models.PositiveIntegerField()),
                ('quantity', models.DecimalField(decimal_places=4, max_digits=14)),
                ('created_at', models.DateTimeField(auto_now_add=True)),
                ('updated_at', models.DateTimeField(auto_now=True)),
                ('product', models.ForeignKey(on_delete=django.db.models.deletion.PROTECT, related_name='transfer_lines', to='catalog.product')),
                ('transfer', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, related_name='lines', to='inventory.stocktransfer')),
            ],
            options={
                'db_table': 'stock_transfer_lines',
                'unique_together': {('transfer', 'line_number')},
            },
        ),
    ]
