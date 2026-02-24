# Generated manually for closing desglose, destino, validación

import django.db.models.deletion
from django.conf import settings
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('accounting', '0001_initial'),
        migrations.swappable_dependency(settings.AUTH_USER_MODEL),
    ]

    operations = [
        migrations.AddField(
            model_name='cashregisterclosing',
            name='cash_total',
            field=models.DecimalField(decimal_places=2, default=0, max_digits=14),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='card_total',
            field=models.DecimalField(decimal_places=2, default=0, max_digits=14),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='other_total',
            field=models.DecimalField(decimal_places=2, default=0, max_digits=14),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='destination_type',
            field=models.CharField(choices=[('cash_register', 'Otra caja'), ('bank', 'Banco'), ('none', 'Sin destino')], default='none', max_length=20),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='destination_cash_register',
            field=models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='closings_deposited_here', to='accounting.cashregister'),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='destination_bank',
            field=models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='closings_deposited_here', to='accounting.bank'),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='validation_status',
            field=models.CharField(choices=[('pending', 'Pendiente'), ('validated', 'Validado')], default='pending', max_length=20),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='validated_by',
            field=models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='cash_closings_validated', to=settings.AUTH_USER_MODEL),
        ),
        migrations.AddField(
            model_name='cashregisterclosing',
            name='validated_at',
            field=models.DateTimeField(blank=True, null=True),
        ),
    ]
