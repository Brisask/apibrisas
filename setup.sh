#!/bin/bash

echo "🚀 Configurando el proyecto API Brisas con Laravel y PostgreSQL..."

# Verificar si Docker está disponible
if ! command -v docker &> /dev/null; then
    echo "❌ Docker no está instalado. Por favor instala Docker para continuar."
    exit 1
fi

# Levantar servicios de base de datos
echo "🐘 Iniciando PostgreSQL y Redis..."
docker compose up -d

# Esperar a que PostgreSQL esté listo
echo "⏳ Esperando a que PostgreSQL esté disponible..."
sleep 10

# Instalar dependencias PHP usando Docker
echo "📦 Instalando dependencias de Composer..."
docker run --rm -v "$(pwd)":/app composer install

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones de base de datos..."
docker run --rm -v "$(pwd)":/app --network apibrisas_apibrisas_network -w /app php:8.4-cli bash -c "
    apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql
    php artisan migrate --force
"

# Instalar dependencias de Node.js
echo "📦 Instalando dependencias de Node.js..."
docker run --rm -v "$(pwd)":/app -w /app node:18-alpine npm install

echo "✅ ¡Configuración completada!"
echo ""
echo "🔧 Para desarrollo, ejecuta:"
echo "   docker compose up -d        # Levantar servicios"
echo "   docker run --rm -v \$(pwd):/app -p 8001:8001 --network apibrisas_apibrisas_network -w /app php:8.4-cli bash -c \"apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql && php artisan serve --host=0.0.0.0 --port=8001\""
echo ""
echo "🌐 La aplicación estará disponible en: http://localhost:8001"
echo "🐘 PostgreSQL estará disponible en: localhost:5432"
echo "🔴 Redis estará disponible en: localhost:6379"