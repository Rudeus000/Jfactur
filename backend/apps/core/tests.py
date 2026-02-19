"""
Tests básicos: autenticación JWT, empresas, usuario actual.
"""
from rest_framework import status
from rest_framework.test import APITestCase

from .models import SubscriptionPlan, Company, Role, User


class BaseTestCaseWithUser(APITestCase):
    """Crea plan, empresa, rol y usuario con company para usar en tests que requieren auth."""

    def setUp(self):
        super().setUp()
        self.plan = SubscriptionPlan.objects.create(
            name='Plan Test',
            max_invoices_per_month=100,
            is_active=True,
        )
        self.company = Company.objects.create(
            ruc='20111111111',
            razon_social='Empresa Test SAC',
            nombre_comercial='Test',
            plan=self.plan,
            is_active=True,
        )
        self.role = Role.objects.create(
            company=self.company,
            name='Admin',
            is_system_role=True,
        )
        self.user = User.objects.create_user(
            email='test@test.com',
            password='testpass123',
            company=self.company,
            role=self.role,
        )
        self.client.force_authenticate(user=self.user)


class AuthTests(APITestCase):
    """Token JWT: obtener y refrescar."""

    def setUp(self):
        super().setUp()
        self.plan = SubscriptionPlan.objects.create(name='Plan', max_invoices_per_month=100, is_active=True)
        self.company = Company.objects.create(ruc='20222222222', razon_social='C', plan=self.plan, is_active=True)
        self.role = Role.objects.create(company=self.company, name='R', is_system_role=True)
        self.user = User.objects.create_user(
            email='auth@test.com', password='authpass123', company=self.company, role=self.role
        )

    def test_obtain_token_ok(self):
        response = self.client.post('/api/v1/auth/token/', {'email': 'auth@test.com', 'password': 'authpass123'})
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn('access', response.data)
        self.assertIn('refresh', response.data)

    def test_obtain_token_bad_password(self):
        response = self.client.post('/api/v1/auth/token/', {'email': 'auth@test.com', 'password': 'wrong'})
        self.assertEqual(response.status_code, status.HTTP_401_UNAUTHORIZED)

    def test_refresh_token(self):
        resp = self.client.post('/api/v1/auth/token/', {'email': 'auth@test.com', 'password': 'authpass123'})
        refresh = resp.data['refresh']
        response = self.client.post('/api/v1/auth/token/refresh/', {'refresh': refresh})
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertIn('access', response.data)


class CompanyTests(BaseTestCaseWithUser):
    """Listado y detalle de empresas (filtrado por usuario)."""

    def test_companies_list_authenticated(self):
        response = self.client.get('/api/v1/companies/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertGreaterEqual(len(response.data), 1)
        rucs = [c.get('ruc') for c in response.data]
        self.assertIn(self.company.ruc, rucs)

    def test_company_detail(self):
        response = self.client.get(f'/api/v1/companies/{self.company.id}/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data['ruc'], self.company.ruc)
        self.assertEqual(response.data['razon_social'], self.company.razon_social)


class UserMeTests(BaseTestCaseWithUser):
    """Usuario actual."""

    def test_users_me(self):
        response = self.client.get('/api/v1/users/me/')
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get('email'), self.user.email)
        self.assertEqual(response.data.get('email'), self.user.email)
        self.assertTrue(response.data.get('company') or response.data.get('company_id'))
