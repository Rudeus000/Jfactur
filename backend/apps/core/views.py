from rest_framework import generics, status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response

from django.db.models import Q
from django.utils import timezone
from .models import Company, Branch, Announcement
from .serializers import (
    CompanySerializer, CompanyUpdateSerializer,
    BranchSerializer,
    UserMeSerializer, UserMeUpdateSerializer,
    AnnouncementSerializer,
)
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


class CompanyDetailAPIView(generics.RetrieveUpdateAPIView):
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = Company.objects.filter(is_active=True)
        if not self.request.user.is_superuser and self.request.user.company_id:
            qs = qs.filter(id=self.request.user.company_id)
        return qs

    def get_serializer_class(self):
        if self.request.method in ('PUT', 'PATCH'):
            return CompanyUpdateSerializer
        return CompanySerializer


class BranchListCreateAPIView(generics.ListCreateAPIView):
    """Lista y crea sucursales de la empresa del usuario."""
    serializer_class = BranchSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = Branch.objects.filter(is_active=True)
        if not self.request.user.is_superuser and self.request.user.company_id:
            qs = qs.filter(company_id=self.request.user.company_id)
        elif not self.request.user.is_superuser:
            qs = qs.none()
        return qs.order_by('name')

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id)
        else:
            serializer.save()


class UserMeAPIView(generics.RetrieveUpdateAPIView):
    permission_classes = [IsAuthenticated]

    def get_object(self):
        return self.request.user

    def get_serializer_class(self):
        if self.request.method in ('PUT', 'PATCH'):
            return UserMeUpdateSerializer
        return UserMeSerializer


class AnnouncementListCreateAPIView(generics.ListCreateAPIView):
    """
    GET: lista anuncios activos de la empresa (opcional ?branch_id= para filtrar por sucursal).
    Se devuelven los de toda la empresa (branch null) más los de la sucursal indicada.
    POST: crear anuncio (company y created_by se asignan por usuario).
    """
    serializer_class = AnnouncementSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        now = timezone.now()
        qs = Announcement.objects.filter(is_active=True).filter(
            Q(valid_until__isnull=True) | Q(valid_until__gte=now)
        )
        if not self.request.user.is_superuser and self.request.user.company_id:
            qs = qs.filter(company_id=self.request.user.company_id)
        elif not self.request.user.is_superuser:
            qs = qs.none()
        branch_id = self.request.query_params.get('branch_id')
        if branch_id:
            qs = qs.filter(Q(branch_id__isnull=True) | Q(branch_id=branch_id))
        return qs.order_by('-is_pinned', '-created_at')

    def perform_create(self, serializer):
        if self.request.user.company_id:
            serializer.save(company_id=self.request.user.company_id, created_by=self.request.user)
        else:
            serializer.save(created_by=self.request.user)


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
