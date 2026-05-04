# 🏢 Tenancy for Laravel - API Brisas

## ✅ Configuración Completada

El paquete **stancl/tenancy v3.10.0** está instalado y configurado correctamente en el proyecto.

### 📋 Archivos Creados/Modificados:

1. **config/tenancy.php** - Configuración principal de tenancy
2. **routes/tenant.php** - Rutas específicas para tenants
3. **app/Providers/TenancyServiceProvider.php** - Proveedor de servicios de tenancy
4. **bootstrap/providers.php** - Registro del TenancyServiceProvider
5. **Migraciones de tenancy** ejecutadas:
   - `tenants` - Tabla para almacenar tenants
   - `domains` - Tabla para dominios de tenants
   - `tenant_user_impersonation_tokens` - Tokens para impersonación

## 🚀 Uso Básico

### Crear un Tenant por Línea de Comandos

```bash
# Conectar a contenedor PHP
docker run --rm -it -v "$(pwd)":/app --network apibrisas_apibrisas_network -w /app \
  php:8.4-cli bash -c "apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql && bash"

# Dentro del contenedor:
php artisan tinker

# Crear tenant con dominio
$tenant = \Stancl\Tenancy\Database\Models\Tenant::create();
$tenant->domains()->create(['domain' => 'empresa1.apibrisas.test']);

# Ver tenant creado
\Stancl\Tenancy\Database\Models\Tenant::with('domains')->get();
```

### Crear Tenant via API (Ejemplo)

Crear un controlador para gestionar tenants:

```php
// app/Http/Controllers/TenantController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Tenant;

class TenantController extends Controller
{
    public function create(Request $request)
    {
        $tenant = Tenant::create();
        $tenant->domains()->create([
            'domain' => $request->domain
        ]);
        
        return response()->json([
            'tenant_id' => $tenant->id,
            'domain' => $request->domain,
            'message' => 'Tenant created successfully'
        ]);
    }
}
```

### Rutas de Tenant

Las rutas en `routes/tenant.php` se ejecutan únicamente cuando se accede desde un dominio de tenant:

```php
// routes/tenant.php - Ya configurado
Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return 'Tenant ID: ' . tenant('id');
    });
    
    Route::get('/api/tenant-info', function () {
        return response()->json([
            'tenant_id' => tenant('id'),
            'domain' => request()->getHost()
        ]);
    });
});
```

## 🗄️ Base de Datos

### Configuración Automática

Cuando se crea un tenant:
1. Se crea automáticamente una base de datos específica para el tenant
2. Se ejecutan las migraciones en la base de datos del tenant
3. Cada tenant tiene datos completamente separados

### Estructura de Bases de Datos

```
apibrisas (base de datos central)
├── tenants (tabla central)
├── domains (tabla central)
└── tenant_user_impersonation_tokens

tenant_xxx (base de datos por tenant)
├── users
├── cache_table
├── jobs
└── ... (todas las migraciones futuras)
```

## 🔧 Configuración de Dominios

### Para Desarrollo Local

Editar `/etc/hosts` (Linux/Mac) o `C:\Windows\System32\drivers\etc\hosts` (Windows):

```
127.0.0.1 empresa1.localhost
127.0.0.1 empresa2.localhost
127.0.0.1 cliente1.localhost
```

Luego acceder a:
- **Central:** http://localhost:8001
- **Tenant 1:** http://empresa1.localhost:8001
- **Tenant 2:** http://empresa2.localhost:8001

## 📝 Ejemplos de Uso

### 1. Crear Tenants con Datos Iniciales

```php
// En tinker o controlador
$tenant = Tenant::create(['name' => 'Empresa ABC']);
$tenant->domains()->create(['domain' => 'empresa-abc.localhost']);

// Ejecutar dentro del contexto del tenant
tenancy()->initialize($tenant);

// Crear usuario específico del tenant
App\Models\User::create([
    'name' => 'Admin Empresa ABC',
    'email' => 'admin@empresa-abc.com',
    'password' => bcrypt('password')
]);
```

### 2. Middleware Automático

El middleware `InitializeTenancyByDomain` se ejecuta automáticamente y:
- Detecta el tenant por el dominio
- Cambia la conexión de base de datos
- Aísla cache, archivos, y colas por tenant

### 3. API Multi-Tenant

```php
// routes/api.php (rutas centrales)
Route::post('/tenants', [TenantController::class, 'create']);
Route::get('/tenants', [TenantController::class, 'index']);

// routes/tenant.php (rutas por tenant)
Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/users', function () {
        return response()->json(App\Models\User::all());
    });
});
```

## 🧪 Testing de Tenancy

```bash
# Verificar que tenancy funciona
curl http://localhost:8001/
# Respuesta: "This is your multi-tenant application. The id of the current tenant is null"

# Después de configurar un tenant y dominio:
curl -H "Host: empresa1.localhost" http://localhost:8001/
# Respuesta: "This is your multi-tenant application. The id of the current tenant is {tenant-uuid}"
```

## 🔐 Configuración de Seguridad

### Dominios Centrales

En `config/tenancy.php` están configurados los dominios centrales:
```php
'central_domains' => [
    '127.0.0.1',
    'localhost',
],
```

Estos dominios NO ejecutan rutas de tenant.

## 📚 Recursos Adicionales

- **Documentación:** https://tenancyforlaravel.com/
- **GitHub:** https://github.com/stancl/tenancy
- **Ejemplos:** https://tenancyforlaravel.com/docs/v3/quickstart

## ⚡ Comandos Útiles

```bash
# Limpiar cache de tenancy
php artisan tenancy:migrate-fresh --seed

# Listar tenants
php artisan tinker
>>> \Stancl\Tenancy\Database\Models\Tenant::with('domains')->get()

# Ejecutar comando en todos los tenants
php artisan tenants:artisan migrate

# Crear migración que se aplicará a tenants
php artisan make:migration create_products_table
```