<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('domains')
            ->latest()
            ->paginate(15);

        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            // Crear el tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'email' => $request->email,
                'data' => [
                    'description' => $request->description,
                    'created_by' => 'admin',
                    'admin_created' => true,
                    'admin_email' => 'admin@admincentral.com'
                ]
            ]);

            // Crear el dominio
            $domain = $request->domain . '.localhost';
            $tenant->domains()->create([
                'domain' => $domain
            ]);

            // Crear usuario administrador en el tenant
            // Run tenant migrations first
            $this->runTenantMigrations($tenant);
            $this->createTenantAdminUser($tenant);

            return redirect()->route('admin.tenants.index')
                ->with('success', "Tenant '{$tenant->name}' creado exitosamente con dominio {$domain} y usuario admin@admincentral.com");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el tenant: ' . $e->getMessage());
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('domains');
        
        $tenant_info = [
            'database_name' => 'tenant' . $tenant->id,
            'created_at' => $tenant->created_at,
            'updated_at' => $tenant->updated_at,
            'domains_count' => $tenant->domains->count(),
            'data' => $tenant->data ?? [],
            'admin_email' => $tenant->data['admin_email'] ?? 'admin@admincentral.com'
        ];

        return view('admin.tenants.show', compact('tenant', 'tenant_info'));
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $tenant->update([
                'name' => $request->name,
                'email' => $request->email,
                'data' => array_merge($tenant->data ?? [], [
                    'description' => $request->description,
                    'updated_by' => 'admin'
                ])
            ]);

            return redirect()->route('admin.tenants.show', $tenant)
                ->with('success', 'Tenant actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el tenant: ' . $e->getMessage());
        }
    }

    public function destroy(Tenant $tenant)
    {
        try {
            $tenant_name = $tenant->name;
            $tenant->delete();

            return redirect()->route('admin.tenants.index')
                ->with('success', "Tenant '{$tenant_name}' eliminado exitosamente");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el tenant: ' . $e->getMessage());
        }
    }

    /**
     * Create admin user for the tenant
     */
    private function createTenantAdminUser(Tenant $tenant)
    {
        // Run code in tenant context
        $tenant->run(function () {
            // Create admin user in tenant database
            $adminUser = User::create([
                'name' => 'Administrador',
                'email' => 'admin@admincentral.com',
                'password' => Hash::make('Admin\$2026'),
                'email_verified_at' => now(),
            ]);

            // If VitalAccess is available, assign superadmin role
            if (method_exists($adminUser, 'assignRole') && class_exists('Kaely\\\\Access\\\\Models\\\\Role')) {
                try {
                    // Create superadmin role if it doesn't exist
                    $superAdminRole = \\Kaely\\Access\\Models\\Role::firstOrCreate([
                        'slug' => 'superadmin'
                    ], [
                        'name' => 'Super Administrator',
                        'description' => 'Full access to all system features',
                        'level' => 100,
                        'is_active' => true,
                        'category_id' => null
                    ]);

                    // Assign superadmin role to admin user
                    $adminUser->assignRole($superAdminRole);
                } catch (\Exception $e) {
                    // Role assignment failed, but user was created
                }
            }
        });
    }
}

    /**
     * Run migrations for the tenant
     */
    private function runTenantMigrations(Tenant $tenant)
    {
        $tenant->run(function () {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--force' => true,
                ]);
                
                // Also run tenant-specific migrations if they exist
                \Illuminate\Support\Facades\Artisan::call('tenants:migrate', [
                    '--tenants' => [tenant()->getTenantKey()],
                    '--force' => true,
                ]);
            } catch (\Exception $e) {
                // Migration failed, log it but don't break tenant creation
                \Illuminate\Support\Facades\Log::warning('Tenant migrations failed: ' . $e->getMessage());
            }
        });
    }
}
