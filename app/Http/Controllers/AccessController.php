<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kaely\Access\Models\Role;
use Kaely\Access\Models\Permission;
use Kaely\Access\Models\Module;
use Kaely\Access\Models\RoleCategory;
use App\Models\User;

class AccessController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'total_modules' => Module::count(),
            'total_users' => User::count(),
        ];

        $recent_roles = Role::with('category')
            ->latest()
            ->take(5)
            ->get();

        $active_permissions = Permission::where('is_active', true)
            ->with('modules')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.access.dashboard', compact('stats', 'recent_roles', 'active_permissions'));
    }

    public function roles()
    {
        $roles = Role::with(['category', 'permissions', 'users'])
            ->paginate(15);

        $categories = RoleCategory::where('is_active', true)->get();

        return view('admin.access.roles', compact('roles', 'categories'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:access_roles,id',
            'scope_type' => 'nullable|string',
            'scope_id' => 'nullable|string',
        ]);

        try {
            $role = Role::findOrFail($request->role_id);
            $user->assignRole($role, $request->scope_type, $request->scope_id);

            return redirect()->back()->with('success', "Role '{$role->name}' assigned successfully!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
