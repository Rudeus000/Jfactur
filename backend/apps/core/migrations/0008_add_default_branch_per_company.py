# Data migration: crea una sucursal "Principal" para cada empresa que no tenga ninguna

from django.db import migrations


def create_default_branches(apps, schema_editor):
    Company = apps.get_model('core', 'Company')
    Branch = apps.get_model('core', 'Branch')
    for company in Company.objects.filter(is_active=True):
        if not Branch.objects.filter(company=company).exists():
            Branch.objects.create(company=company, name='Principal', code='001', is_active=True)


def noop(apps, schema_editor):
    pass


class Migration(migrations.Migration):

    dependencies = [
        ('core', '0007_add_announcements'),
    ]

    operations = [
        migrations.RunPython(create_default_branches, noop),
    ]
