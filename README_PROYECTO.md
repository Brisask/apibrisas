# API Brisas - Laravel con PostgreSQL

Este es un proyecto Laravel configurado para usar PostgreSQL como base de datos principal y Redis para caché.

## 🚀 Inicio Rápido

### Prerrequisitos
- Docker y Docker Compose instalados
- Git (opcional, para control de versiones)

### Instalación Automática
```bash
./setup.sh
```

### Instalación Manual

1. **Levantar servicios de base de datos:**
```bash
docker-compose up -d
```

2. **Instalar dependencias PHP:**
```bash
docker run --rm -v "$(pwd)":/app composer install
```

3. **Ejecutar migraciones:**
```bash
docker run --rm -v "$(pwd)":/app --network apibrisas_apibrisas_network -w /app \
  php:8.3-cli bash -c "
    apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql
    php artisan migrate
  "
```

4. **Instalar dependencias de frontend:**
```bash
docker run --rm -v "$(pwd)":/app -w /app node:18-alpine npm install
```

## 🔧 Desarrollo

### Iniciar el servidor de desarrollo:
```bash
docker run --rm -v "$(pwd)":/app -p 8001:8001 \
  --network apibrisas_apibrisas_network -w /app \
  php:8.4-cli bash -c "apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql && php artisan serve --host=0.0.0.0 --port=8001"
```

### Ejecutar comandos Artisan:
```bash
docker run --rm -v "$(pwd)":/app --network apibrisas_apibrisas_network \
  -w /app php:8.3-cli php artisan [comando]
```

### Acceder a Tinker:
```bash
docker run --rm -it -v "$(pwd)":/app --network apibrisas_apibrisas_network \
  -w /app php:8.3-cli php artisan tinker
```

## 📊 Base de Datos

### Configuración
- **Motor:** PostgreSQL 15
- **Host:** localhost:5432
- **Base de datos:** apibrisas
- **Usuario:** postgres
- **Contraseña:** password

### Conexión directa a PostgreSQL:
```bash
docker exec -it apibrisas_postgres psql -U postgres -d apibrisas
```

### Crear nueva migración:
```bash
docker run --rm -v "$(pwd)":/app -w /app php:8.3-cli \
  php artisan make:migration create_nombre_tabla
```

### Ejecutar migraciones:
```bash
docker run --rm -v "$(pwd)":/app --network apibrisas_apibrisas_network \
  -w /app php:8.3-cli php artisan migrate
```

## 🔄 Cache (Redis)

Redis está configurado y disponible en localhost:6379

### Limpiar caché:
```bash
docker run --rm -v "$(pwd)":/app --network apibrisas_apibrisas_network \
  -w /app php:8.3-cli php artisan cache:clear
```

## 🧪 Testing

### Ejecutar tests:
```bash
docker run --rm -v "$(pwd)":/app --network apibrisas_apibrisas_network \
  -w /app php:8.3-cli php artisan test
```

## 📝 Estructura del Proyecto

```
apibrisas/
├── app/                    # Código de la aplicación
├── config/                 # Archivos de configuración
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   ├── seeders/           # Seeders para datos de prueba
│   └── factories/         # Factory para modelos
├── routes/                # Definición de rutas
├── resources/             # Vistas, assets, etc.
├── tests/                 # Tests unitarios y de feature
├── docker-compose.yml     # Configuración de servicios
├── setup.sh              # Script de instalación automática
└── .env                  # Variables de entorno
```

## 🔧 Variables de Entorno

Las principales variables configuradas en `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=apibrisas
DB_USERNAME=postgres
DB_PASSWORD=password
```

## 📚 Comandos Útiles

### Ver estado de los servicios:
```bash
docker-compose ps
```

### Ver logs de PostgreSQL:
```bash
docker-compose logs postgres
```

### Parar servicios:
```bash
docker-compose down
```

### Reiniciar servicios:
```bash
docker-compose restart
```

## 🚀 URLs del Proyecto

- **Aplicación:** http://localhost:8001
- **PostgreSQL:** localhost:5432
- **Redis:** localhost:6379

## 🐛 Solución de Problemas

### Si hay errores de permisos:
```bash
sudo chown -R $USER:$USER .
```

### Si PostgreSQL no conecta:
```bash
docker-compose down
docker-compose up -d
# Espera unos segundos y vuelve a intentar
```

### Limpiar volúmenes (CUIDADO: Elimina todos los datos):
```bash
docker-compose down -v
```