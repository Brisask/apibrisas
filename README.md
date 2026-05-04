# 🏢 ApiBrisas

<div align="center">

![ApiBrisas Logo](https://img.shields.io/badge/ApiBrisas-Multi%20Tenant%20API-green?style=for-the-badge&logo=laravel)

**Advanced Multi-Tenant Laravel API Platform**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue?style=flat&logo=php)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue?style=flat&logo=postgresql)](https://postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-7-red?style=flat&logo=redis)](https://redis.io)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat&logo=docker)](https://docker.com)
[![Multi-Tenant](https://img.shields.io/badge/Multi--Tenant-Stancl-orange?style=flat)](https://tenancyforlaravel.com)

[🚀 **Quick Start**](#🚀-inicio-rápido) • [🏢 **Multi-Tenancy**](#🏢-arquitectura-multi-tenant) • [🐳 **Docker Setup**](#🐳-instalación-con-docker) • [📚 **API Docs**](#📚-documentación-de-api)

</div>

---

## 🌟 **Características Principales**

### 🏢 **Arquitectura Multi-Tenant Avanzada**
- **🔒 Aislamiento Total**: Base de datos dedicada por tenant
- **🌐 Detección por Dominio**: Routing automático basado en subdominios
- **⚡ Performance Optimizada**: Cache Redis por tenant
- **🔄 Migraciones Automáticas**: Setup dinámico de nuevos tenants
- **📊 Gestión Centralizada**: Panel de administración para todos los tenants

### 🛠️ **Stack Tecnológico Enterprise**
- **🐘 PostgreSQL 15**: Base de datos robusta y escalable
- **🔴 Redis 7**: Cache de alta performance y sessions
- **🚀 Laravel 11.x**: Framework moderno y seguro
- **📦 Stancl/Tenancy v3.10**: Multi-tenancy de clase mundial
- **🐳 Docker**: Containerización completa
- **🔧 PHP 8.4**: Latest features y performance

### 🔐 **Seguridad y Control de Acceso**
- **🛡️ VitalAccess RBAC**: Control granular de permisos
- **🔑 Autenticación JWT**: Tokens seguros y escalables
- **🌊 Rate Limiting**: Protección contra abuse
- **🔒 Data Encryption**: Datos sensibles cifrados
- **📝 Audit Logging**: Trazabilidad completa

### ⚡ **Performance y Escalabilidad**
- **🚄 Query Optimization**: Consultas optimizadas por tenant
- **📈 Auto-scaling**: Arquitectura preparada para crecer
- **💾 Intelligent Caching**: Cache multi-layer por tenant
- **🔄 Load Balancing**: Distribución eficiente de carga
- **📊 Monitoring**: Métricas y alertas integradas

---

## 🛠️ **Arquitectura del Sistema**

<table>
<tr>
<td align="center" width="150px">
<img src="https://laravel.com/img/logomark.min.svg" width="40" height="40" alt="Laravel"/>
<br><strong>Laravel 11</strong>
<br><sub>API Framework</sub>
</td>
<td align="center" width="150px">
<img src="https://www.postgresql.org/media/img/about/press/elephant.png" width="40" height="40" alt="PostgreSQL"/>
<br><strong>PostgreSQL 15</strong>
<br><sub>Database</sub>
</td>
<td align="center" width="150px">
<img src="https://redis.io/images/redis-white.png" width="40" height="40" alt="Redis"/>
<br><strong>Redis 7</strong>
<br><sub>Cache & Sessions</sub>
</td>
<td align="center" width="150px">
<img src="https://www.docker.com/wp-content/uploads/2022/03/Moby-logo.png" width="40" height="40" alt="Docker"/>
<br><strong>Docker</strong>
<br><sub>Containerization</sub>
</td>
</tr>
</table>

### 🏗️ **Arquitectura Multi-Tenant**

```
🌐 ApiBrisas Platform
├── 🏢 Central Database
│   ├── tenants (metadata)
│   ├── domains (routing)
│   └── central_users (admin)
├── 🏠 Tenant Databases
│   ├── tenant_abc (isolated)
│   ├── tenant_xyz (isolated)
│   └── tenant_... (auto-created)
└── 🔴 Redis Layers
    ├── central_cache
    └── tenant_specific_cache
```

---

## 🚀 **Inicio Rápido**

### 📋 **Prerrequisitos**
- Docker & Docker Compose
- Git (opcional)
- 8GB RAM mínimo
- Puertos 8001, 5432, 6379 disponibles

### ⚡ **Instalación Automática (Recomendada)**

```bash
# 1️⃣ Clonar repositorio
git clone https://github.com/Brisask/apibrisas.git
cd apibrisas

# 2️⃣ Ejecutar setup automático
chmod +x setup.sh
./setup.sh

# 3️⃣ Verificar instalación
curl http://localhost:8001/api/status
```

### 🔧 **Instalación Manual**

```bash
# 1️⃣ Levantar servicios
docker-compose up -d

# 2️⃣ Instalar dependencias
docker run --rm -v "$(pwd)":/app composer install

# 3️⃣ Configurar ambiente
cp .env.example .env
php artisan key:generate

# 4️⃣ Ejecutar migraciones
docker run --rm -v "$(pwd)":/app --network apibrisas_apibrisas_network \
  -w /app php:8.4-cli bash -c "
    apt-get update && apt-get install -y libpq-dev && 
    docker-php-ext-install pdo_pgsql && 
    php artisan migrate
  "

# 5️⃣ Iniciar servidor
docker run --rm -v "$(pwd)":/app -p 8001:8001 \
  --network apibrisas_apibrisas_network -w /app \
  php:8.4-cli bash -c "
    apt-get update && apt-get install -y libpq-dev && 
    docker-php-ext-install pdo_pgsql && 
    php artisan serve --host=0.0.0.0 --port=8001
  "
```

🎉 **¡Listo!** Accede a: `http://localhost:8001`

---

## 🐳 **Instalación con Docker**

### 🔥 **Setup Completo con Docker Compose**

```bash
# Clonar y ejecutar
git clone https://github.com/Brisask/apibrisas.git
cd apibrisas

# Levantar stack completo
docker-compose up -d

# Verificar servicios
docker-compose ps
```

### 📊 **Servicios Incluidos**
- **🌐 ApiBrisas API**: `http://localhost:8001`
- **🐘 PostgreSQL**: Puerto `5432`
- **🔴 Redis**: Puerto `6379`
- **📧 Mailhog** (opcional): Puerto `8025`

### 🛠️ **Comandos Docker Útiles**

```bash
# Ver logs
docker-compose logs -f

# Ejecutar comandos Artisan
docker-compose exec app php artisan [comando]

# Acceder a PostgreSQL
docker exec -it apibrisas_postgres psql -U postgres -d apibrisas

# Reiniciar servicios
docker-compose restart

# Limpiar todo (CUIDADO)
docker-compose down -v
```

---

## 🏢 **Arquitectura Multi-Tenant**

### 🎯 **Características Clave**

- **📍 Detección Automática**: Por dominio/subdomain
- **🔒 Aislamiento Total**: Datos, cache, archivos, colas
- **⚡ Performance**: Cache optimizado por tenant
- **🔄 Auto-setup**: Base de datos y migraciones automáticas

### 🚀 **Crear un Tenant**

#### Via API:
```bash
curl -X POST http://localhost:8001/api/tenants \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Empresa Demo",
    "domain": "empresa-demo.localhost",
    "email": "admin@empresa-demo.com"
  }'
```

#### Via Tinker:
```php
// Acceder a tinker
docker-compose exec app php artisan tinker

// Crear tenant
$tenant = \Stancl\Tenancy\Database\Models\Tenant::create([
    'name' => 'Empresa ABC'
]);

// Asignar dominio
$tenant->domains()->create([
    'domain' => 'empresa-abc.localhost'
]);

// Ver tenants
\Stancl\Tenancy\Database\Models\Tenant::with('domains')->get();
```

### 🌐 **Configuración de Dominios**

Para desarrollo local, editar `/etc/hosts`:

```bash
# Linux/Mac: /etc/hosts
# Windows: C:\Windows\System32\drivers\etc\hosts
127.0.0.1 empresa-abc.localhost
127.0.0.1 empresa-xyz.localhost
127.0.0.1 cliente-demo.localhost
```

**Acceso:**
- **Central**: http://localhost:8001
- **Tenant ABC**: http://empresa-abc.localhost:8001
- **Tenant XYZ**: http://empresa-xyz.localhost:8001

---

## 📚 **Documentación de API**

### 🔌 **Endpoints Principales**

#### 🏢 **Gestión de Tenants (Central)**
```bash
# Listar tenants
GET /api/tenants

# Crear tenant
POST /api/tenants
{
  "name": "Empresa Demo",
  "domain": "demo.localhost",
  "email": "admin@demo.com"
}

# Obtener tenant
GET /api/tenants/{id}

# Actualizar tenant
PUT /api/tenants/{id}

# Eliminar tenant
DELETE /api/tenants/{id}
```

#### 👥 **API por Tenant**
```bash
# Información del tenant actual
GET /api/tenant-info
# Host: empresa-demo.localhost

# Usuarios del tenant
GET /api/users
# Host: empresa-demo.localhost

# Crear usuario en tenant
POST /api/users
# Host: empresa-demo.localhost
{
  "name": "Usuario Demo",
  "email": "usuario@demo.com",
  "password": "password"
}
```

#### 🔐 **Autenticación**
```bash
# Login (por tenant)
POST /api/auth/login
# Host: empresa-demo.localhost
{
  "email": "admin@demo.com",
  "password": "password"
}

# Logout
POST /api/auth/logout
# Authorization: Bearer {token}

# Refresh token
POST /api/auth/refresh
# Authorization: Bearer {token}
```

### 📊 **Respuestas de API**

#### ✅ **Éxito**
```json
{
  "success": true,
  "data": {
    "id": "uuid",
    "name": "Empresa Demo",
    "domain": "demo.localhost"
  },
  "message": "Tenant created successfully"
}
```

#### ❌ **Error**
```json
{
  "success": false,
  "message": "Tenant not found",
  "errors": {
    "domain": ["Domain already exists"]
  }
}
```

---

## 🔧 **Configuración Avanzada**

### ⚙️ **Variables de Entorno**

<details>
<summary><strong>🌐 Configuración Principal</strong></summary>

```bash
# 🏢 Aplicación
APP_NAME=ApiBrisas
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.brisask.com

# 🐘 PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=apibrisas
DB_USERNAME=postgres
DB_PASSWORD=secure_password

# 🔴 Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

# 🏢 Tenancy
TENANCY_DATABASE_PREFIX=tenant_
TENANCY_CACHE_PREFIX=tenant_cache_

# 📧 Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525

# 🔐 JWT Authentication
JWT_SECRET=your_secret_key
JWT_TTL=60
JWT_REFRESH_TTL=20160
```
</details>

<details>
<summary><strong>🏗️ Estructura de Archivos</strong></summary>

```
apibrisas/
├── 📁 app/
│   ├── Http/Controllers/
│   │   ├── Api/TenantApiController.php
│   │   ├── TenantController.php
│   │   └── AdminController.php
│   ├── Models/
│   │   ├── Tenant.php
│   │   └── User.php
│   └── Providers/
│       └── TenancyServiceProvider.php
├── 📁 config/
│   ├── tenancy.php          # Configuración multi-tenant
│   ├── database.php         # Conexiones de BD
│   └── cache.php           # Configuración de cache
├── 📁 database/
│   ├── migrations/         # Migraciones centrales
│   └── migrations/tenant/  # Migraciones por tenant
├── 📁 routes/
│   ├── api.php            # Rutas centrales
│   ├── tenant.php         # Rutas por tenant
│   └── web.php            # Rutas web
└── 📁 docker/
    └── docker-compose.yml  # Orquestación
```
</details>

<details>
<summary><strong>🔧 Configuración de Tenancy</strong></summary>

```php
// config/tenancy.php
return [
    'tenant_model' => \App\Models\Tenant::class,
    'id_generator' => \Stancl\Tenancy\UUIDGenerator::class,
    
    'database' => [
        'prefix' => 'tenant_',
        'suffix' => '',
        'template_tenant_connection' => null,
    ],
    
    'cache' => [
        'prefix' => 'tenant_cache_',
    ],
    
    'filesystem' => [
        'suffix' => '',
        'disks' => ['local', 'public'],
    ],
    
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixes' => ['default', 'cache', 'session'],
    ],
];
```
</details>

---

## 🧪 **Testing y Desarrollo**

### 🔍 **Testing del Sistema**

```bash
# Ejecutar todos los tests
docker-compose exec app php artisan test

# Tests específicos
docker-compose exec app php artisan test --filter TenantTest

# Coverage report
docker-compose exec app php artisan test --coverage
```

### 🛠️ **Comandos de Desarrollo**

```bash
# Crear migración central
php artisan make:migration create_central_table

# Crear migración de tenant
php artisan make:migration create_tenant_table --path=database/migrations/tenant

# Ejecutar migraciones en todos los tenants
php artisan tenants:artisan migrate

# Limpiar cache de todos los tenants
php artisan tenants:artisan cache:clear

# Crear modelo
php artisan make:model Product -m

# Crear controlador
php artisan make:controller Api/ProductController --api
```

### 📊 **Monitoreo y Debug**

```bash
# Ver logs en tiempo real
docker-compose logs -f app

# Logs de PostgreSQL
docker-compose logs postgres

# Logs de Redis
docker-compose logs redis

# Monitor de queries (en desarrollo)
php artisan tinker
>>> DB::listen(fn($query) => dump($query->sql));
```

---

## 🤝 **Contribuir**

### 🌟 **¿Cómo Contribuir?**

1. **🍴 Fork** del repositorio
2. **🌿 Feature branch** (`git checkout -b feature/amazing-feature`)
3. **💻 Desarrollo** con tests
4. **✅ Testing** (`php artisan test`)
5. **📝 Commit** (`git commit -m 'Add amazing feature'`)
6. **🚀 Push** (`git push origin feature/amazing-feature`)
7. **🎯 Pull Request**

### 📋 **Estándares**
- **PSR-12** para código PHP
- **Tests** obligatorios para nuevas features
- **Documentation** actualizada
- **Backward compatibility** mantenida

---

## 📄 **Licencia y Soporte**

Este proyecto es software propietario de **Brisask Organization**.

### 📞 **Soporte Técnico**
- **📧 Email**: support@brisask.com
- **💬 Discord**: [Brisask Community](https://discord.gg/brisask)
- **📚 Wiki**: [Documentation Portal](https://docs.brisask.com)
- **🐛 Issues**: [GitHub Issues](https://github.com/Brisask/apibrisas/issues)

### 🎯 **Roadmap 2024**

- [ ] 🔐 **OAuth2 Integration**: Google, GitHub, Microsoft SSO
- [ ] 📱 **GraphQL API**: Endpoint GraphQL completo
- [ ] 🤖 **AI Integration**: Tenant analytics con IA
- [ ] 📊 **Advanced Monitoring**: Métricas y alertas
- [ ] 🌐 **Multi-Region**: Deploy en múltiples regiones
- [ ] 🔄 **Event Sourcing**: Sistema de eventos
- [ ] 📈 **Auto-scaling**: Kubernetes deployment
- [ ] 🛡️ **Security Audit**: Penetration testing

**© 2024 Brisask Organization. Todos los derechos reservados.**

---

<div align="center">

### 💝 **¡Gracias por usar ApiBrisas!**

**Construido con ❤️ por el equipo de [Brisask](https://github.com/Brisask)**

[![⭐ Star](https://img.shields.io/github/stars/Brisask/apibrisas?style=social)](https://github.com/Brisask/apibrisas)
[![🐛 Issues](https://img.shields.io/badge/-Report%20Bug-red?style=flat&logo=github)](https://github.com/Brisask/apibrisas/issues)
[![💡 Feature Request](https://img.shields.io/badge/-Request%20Feature-blue?style=flat&logo=github)](https://github.com/Brisask/apibrisas/issues)

**🚀 Join the Multi-Tenant Revolution!**

</div>