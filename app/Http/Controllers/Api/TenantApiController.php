<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class TenantApiController extends Controller
{
    /**
     * List all tenants
     */
    public function index(): JsonResponse
    {
        try {
            $tenants = Tenant::with('domains')
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $tenants,
                'message' => 'Tenants retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tenants: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new tenant
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'domain' => 'required|string|max:255|unique:domains,domain',
                'description' => 'nullable|string|max:1000',
            ]);

            // Create tenant
            $tenant = Tenant::create([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'data' => [
                    'description' => $validated['description'] ?? null,
                    'created_via' => 'api',
                    'created_at_api' => now()->toISOString(),
                    'admin_created' => true,
                    'admin_email' => 'admin@admincentral.com',
                    'admin_password' => 'Admin$2026'
                ]
            ]);

            // Create domain
            $domain = $validated['domain'] . '.localhost';
            $tenant->domains()->create([
                'domain' => $domain
            ]);

            // Run tenant migrations first
            $this->runTenantMigrations($tenant);

            // Create admin user for the tenant
            $this->createTenantAdminUser($tenant);

            // Load domain relationship
            $tenant->load('domains');

            return response()->json([
                'success' => true,
                'data' => $tenant,
                'admin_credentials' => [
                    'email' => 'admin@admincentral.com',
                    'password' => 'Admin$2026',
                    'note' => 'Default admin user created automatically'
                ],
                'message' => "Tenant '{$tenant->name}' created successfully with domain {$domain} and admin user"
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific tenant
     */
    public function show(Tenant $tenant): JsonResponse
    {
        try {
            $tenant->load('domains');
            
            $tenant_info = [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'data' => $tenant->data,
                'database_name' => 'tenant' . $tenant->id,
                'domains' => $tenant->domains,
                'domains_count' => $tenant->domains->count(),
                'admin_email' => $tenant->data['admin_email'] ?? 'admin@admincentral.com',
                'created_at' => $tenant->created_at,
                'updated_at' => $tenant->updated_at,
            ];

            return response()->json([
                'success' => true,
                'data' => $tenant_info,
                'message' => 'Tenant retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a tenant
     */
    public function update(Request $request, Tenant $tenant): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'description' => 'nullable|string|max:1000',
            ]);

            $tenant->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'data' => array_merge($tenant->data ?? [], [
                    'description' => $validated['description'] ?? null,
                    'updated_via' => 'api',
                    'updated_at_api' => now()->toISOString()
                ])
            ]);

            $tenant->load('domains');

            return response()->json([
                'success' => true,
                'data' => $tenant,
                'message' => 'Tenant updated successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a tenant
     */
    public function destroy(Tenant $tenant): JsonResponse
    {
        try {
            $tenant_name = $tenant->name;
            $tenant->delete();

            return response()->json([
                'success' => true,
                'message' => "Tenant '{$tenant_name}' deleted successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting tenant: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run migrations for the tenant
     */
    private function runTenantMigrations(Tenant $tenant)
    {
        $tenant->run(function () {
            try {
                // Run basic Laravel migrations
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--force' => true,
                ]);
                
                // Run tenant-specific migrations (VitalAccess, etc.)
                \Illuminate\Support\Facades\Artisan::call('tenants:migrate', [
                    '--tenants' => [tenant()->getTenantKey()],
                    '--force' => true,
                ]);
            } catch (\Exception $e) {
                // Migration failed, log it
                \Illuminate\Support\Facades\Log::warning('API Tenant migrations failed: ' . $e->getMessage());
            }
        });
    }

    /**
     * Create admin user for the tenant
     */
    private function createTenantAdminUser(Tenant $tenant)
    {
        // Run code in tenant context
        $tenant->run(function () {
            try {
                // Create admin user in tenant database
                $adminUser = User::create([
                    'name' => 'Administrador',
                    'email' => 'admin@admincentral.com',
                    'password' => Hash::make('Admin$2026'),
                    'email_verified_at' => now(),
                ]);

                // If VitalAccess is available, assign superadmin role
                if (method_exists($adminUser, 'assignRole') && class_exists('Kaely\Access\Models\Role')) {
                    try {
                        // Create superadmin role if it doesn't exist
                        $superAdminRole = \Kaely\Access\Models\Role::firstOrCreate([
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
                        \Illuminate\Support\Facades\Log::warning('Failed to assign superadmin role: ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to create admin user: ' . $e->getMessage());
                throw $e;
            }
        });
    }
}
