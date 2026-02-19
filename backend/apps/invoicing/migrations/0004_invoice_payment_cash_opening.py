# Generated manually for Invoice and CustomerPayment cash_opening FK

import django.db.models.deletion
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('accounting', '0002_closing_desglose_destino_validacion'),
        ('invoicing', '0003_customerpayment_quote_quoteline'),
    ]

    operations = [
        migrations.AddField(
            model_name='invoice',
            name='cash_opening',
            field=models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='invoices', to='accounting.cashregisteropening'),
        ),
        migrations.AddField(
            model_name='customerpayment',
            name='cash_opening',
            field=models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='customer_payments', to='accounting.cashregisteropening'),
        ),
    ]
