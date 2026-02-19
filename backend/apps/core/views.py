from rest_framework import generics, status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response

from .models import Company
from .serializers import CompanySerializer, UserMeSerializer
from .dni_lookup import consultar_dni


class CompanyListAPIView(generics.ListAPIView):
    serializer_class = CompanySerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = Company.objects.filter(is_active=True)
        if not self.request.user.is_superuser:
            if self.request.user.company_id:
                qs = qs.filter(id=self.request.user.company_id)
            else:
                qs = qs.none()
        return qs


class CompanyDetailAPIView(generics.RetrieveAPIView):
    serializer_class = CompanySerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = Company.objects.filter(is_active=True)
        if not self.request.user.is_superuser and self.request.user.company_id:
            qs = qs.filter(id=self.request.user.company_id)
        return qs


class UserMeAPIView(generics.RetrieveAPIView):
    serializer_class = UserMeSerializer
    permission_classes = [IsAuthenticated]

    def get_object(self):
        return self.request.user


@api_view(["GET"])
@permission_classes([IsAuthenticated])
def dni_lookup(request):
    """
    Consulta nombre de la persona por DNI (para facturas, clientes, etc.).
    GET /api/v1/dni-lookup/?dni=12345678
    Devuelve: dni, nombres, apellido_paterno, apellido_materno, razon_social.
    Configure DNI_LOOKUP_API_URL en .env para integrar con RENIEC o su proveedor.
    """
    dni = request.query_params.get("dni", "").strip()
    if not dni:
        return Response(
            {"error": "Indique el parámetro dni (ej: ?dni=12345678)"},
            status=status.HTTP_400_BAD_REQUEST,
        )
    result = consultar_dni(dni)
    if result.get("error") and "razon_social" not in result:
        return Response(result, status=status.HTTP_400_BAD_REQUEST)
    return Response(result)
