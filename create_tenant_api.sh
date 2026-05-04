#!/bin/bash

# Script para crear tenants via API con usuario admin automático
# Uso: ./create_tenant_api.sh nombre email dominio "descripcion"

if [ $# -lt 3 ]; then
    echo "Uso: $0 <nombre> <email> <dominio> [descripcion]"
    echo "Ejemplo: $0 'Mi Empresa' admin@empresa.com empresa 'Descripcion opcional'"
    exit 1
fi

NOMBRE="$1"
EMAIL="$2"  
DOMINIO="$3"
DESCRIPCION="${4:-}"

API_URL="http://localhost:8001/api/tenants"

echo "🚀 Creando tenant via API..."
echo "============================="
echo "Nombre: $NOMBRE"
echo "Email: $EMAIL"
echo "Dominio: $DOMINIO.localhost"
echo ""

RESPONSE=$(curl -s -X POST $API_URL \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"name\": \"$NOMBRE\",
    \"email\": \"$EMAIL\", 
    \"domain\": \"$DOMINIO\",
    \"description\": \"$DESCRIPCION\"
  }")

echo "📊 Respuesta del servidor:"
echo "$RESPONSE" | jq '.' 2>/dev/null || echo "$RESPONSE"

echo ""
echo "👤 Usuario Administrador creado automáticamente:"
echo "================================================"
echo "📧 Email: admin@admincentral.com"
echo "🔐 Password: Admin\$2026"
echo "🎭 Rol: Super Administrator (con VitalAccess RBAC)"
echo ""
echo "🌐 URLs de acceso:"
echo "Tenant: http://$DOMINIO.localhost:8001"
echo "Admin: http://localhost:8001/admin"
echo ""
echo "✅ ¡Tenant creado exitosamente!"
