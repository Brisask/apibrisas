<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::count(), // Todos los tenants se consideran activos por defecto
            'total_domains' => Domain::count(),
            'databases' => Tenant::count(), // Una BD por tenant
        ];

        $recent_tenants = Tenant::with('domains')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_tenants'));
    }

    public function system()
    {
        $system_info = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'database' => [
                'connection' => config('database.default'),
                'host' => config('database.connections.pgsql.host'),
                'database' => config('database.connections.pgsql.database'),
            ],
            'cache' => [
                'default' => config('cache.default'),
            ],
            'session' => [
                'driver' => config('session.driver'),
            ],
            'tenancy' => [
                'enabled' => true,
                'tenant_model' => config('tenancy.tenant_model'),
                'domain_model' => config('tenancy.domain_model'),
            ]
        ];

        return view('admin.system', compact('system_info'));
    }
}
