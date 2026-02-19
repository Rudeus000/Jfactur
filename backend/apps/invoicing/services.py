from django.db import transaction
from .models import InvoiceSeries


def get_next_invoice_number(company_id, tipo_documento, serie):
    """Reserva y devuelve el siguiente número de la serie de forma atómica."""
    with transaction.atomic():
        inv_series = InvoiceSeries.objects.select_for_update().filter(
            company_id=company_id,
            tipo_documento=tipo_documento,
            serie=serie,
            is_active=True,
        ).first()
        if not inv_series:
            return None
        numero = inv_series.next_number
        inv_series.next_number = numero + 1
        inv_series.last_number = numero
        inv_series.save(update_fields=['next_number', 'last_number', 'updated_at'])
        return numero
