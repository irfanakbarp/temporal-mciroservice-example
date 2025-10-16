from django.urls import path
from logs.views import LogCreateView

urlpatterns = [
    path('logs/', LogCreateView.as_view(), name='log-create'),
]
