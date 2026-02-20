# Almacén pertenece a una sucursal (opcional)

import django.db.models.deletion
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('core', '0006_branch_and_user_default_branch'),
        ('inventory', '0003_stocktransfer_stocktransferline'),
    ]

    operations = [
        migrations.AddField(
            model_name='warehouse',
            name='branch',
            field=models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.SET_NULL, related_name='warehouses', to='core.branch'),
        ),
    ]
