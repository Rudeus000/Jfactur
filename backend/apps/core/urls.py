from django.urls import path
from rest_framework_simplejwt.views import TokenObtainPairView, TokenRefreshView

from . import views

urlpatterns = [
    path('auth/token/', TokenObtainPairView.as_view(), name='token_obtain_pair'),
    path('auth/token/refresh/', TokenRefreshView.as_view(), name='token_refresh'),
    path('companies/', views.CompanyListAPIView.as_view(), name='company-list'),
    path('companies/<uuid:pk>/', views.CompanyDetailAPIView.as_view(), name='company-detail'),
    path('branches/', views.BranchListCreateAPIView.as_view(), name='branch-list'),
    path('announcements/', views.AnnouncementListCreateAPIView.as_view(), name='announcement-list'),
    path('users/me/', views.UserMeAPIView.as_view(), name='user-me'),
    path('dni-lookup/', views.dni_lookup, name='dni-lookup'),
]
