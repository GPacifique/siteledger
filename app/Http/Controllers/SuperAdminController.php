<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    /**
     * Ensure only super admin can access these routes
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->is_super_admin) {
                abort(403, 'Unauthorized. Super Admin access required.');
            }
            return $next($request);
        });
    }

    /**
     * Super Admin Dashboard
     */
    public function dashboard()
    {
        // System-wide statistics
        $totalUsers = User::count();
        $totalTenants = Tenant::count();
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();
        $superAdmins = User::where('is_super_admin', true)->count();
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        // All users for assignment form
        $allUsers = User::orderBy('name')->get();

        // All tenants for assignment form and overview
        $allTenants = Tenant::with('users')->orderBy('name')->get();

        // Recent users for display
        $recentUsers = User::latest('created_at')->limit(10)->get();

        // Tenants overview
        $tenants = Tenant::with('users')->latest('created_at')->limit(10)->get();

        // System health
        $systemHealth = [
            'total_users' => $totalUsers,
            'total_tenants' => $totalTenants,
            'total_roles' => $totalRoles,
            'total_permissions' => $totalPermissions,
            'super_admins' => $superAdmins,
            'admin_users' => $adminUsers,
        ];

        return view('super-admin.dashboard', compact(
            'totalUsers',
            'totalTenants',
            'totalRoles',
            'totalPermissions',
            'superAdmins',
            'adminUsers',
            'recentUsers',
            'tenants',
            'allUsers',
            'allTenants',
            'systemHealth'
        ));
    }

    /**
     * List all users with admin capabilities
     */
    public function users()
    {
        $users = User::with('roles', 'tenants')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('super-admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        $user->load(['roles', 'tenants', 'permissions']);
        $allRoles = Role::all();
        $allTenants = Tenant::all();

        return view('super-admin.users.show', compact('user', 'allRoles', 'allTenants'));
    }

    /**
     * Update user roles
     */
    public function updateUserRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'is_super_admin' => 'boolean',
        ]);

        // Update super admin status
        $user->update([
            'is_super_admin' => $request->is_super_admin ?? false,
        ]);

        // Sync roles
        $roleIds = $request->roles ?? [];
        $user->syncRoles(Role::whereIn('id', $roleIds)->pluck('name')->toArray());

        return redirect()->back()->with('success', 'User roles updated successfully');
    }

    /**
     * List all tenants
     */
    public function tenants()
    {
        $tenants = Tenant::with(['users' => function ($query) {
            $query->limit(5);
        }])
            ->withCount('users')
            ->latest('created_at')
            ->paginate(20);

        return view('super-admin.tenants.index', compact('tenants'));
    }

    /**
     * Show create tenant form
     */
    public function createTenant()
    {
        return view('super-admin.tenants.create');
    }

    /**
     * Store a new tenant
     */
    public function storeTenant(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain',
            'email' => 'nullable|email|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'business_type' => 'nullable|string|in:construction,consulting,manufacturing,retail,service,other',
        ]);

        $tenant = Tenant::create([
            'name' => $validated['name'],
            'domain' => $validated['domain'],
            'email' => $validated['email'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'business_type' => $validated['business_type'] ?? 'other',
            'status' => Tenant::STATUS_ACTIVE,
            'subscription_plan' => 'basic',
            'timezone' => 'Africa/Kigali',
            'currency' => 'RWF',
            'locale' => 'en',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('super-admin.tenants.show', $tenant)
            ->with('success', 'Tenant created successfully');
    }

    /**
     * Show tenant details
     */
    public function showTenant(Tenant $tenant)
    {
        $tenant->load(['users']);
        $usersCount = $tenant->users()->count();
        $admins = $tenant->users()->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        return view('super-admin.tenants.show', compact('tenant', 'usersCount', 'admins'));
    }

    /**
     * Manage roles and permissions
     */
    public function roles()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->paginate(20);

        return view('super-admin.roles.index', compact('roles'));
    }

    /**
     * Show role details
     */
    public function showRole(Role $role)
    {
        $role->load('permissions', 'users');
        $allPermissions = Permission::all();

        return view('super-admin.roles.show', compact('role', 'allPermissions'));
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $request->permissions ?? [];
        $role->syncPermissions(Permission::whereIn('id', $permissionIds)->pluck('name')->toArray());

        return redirect()->back()->with('success', 'Role permissions updated successfully');
    }

    /**
     * List all permissions
     */
    public function permissions()
    {
        $permissions = Permission::with('roles')
            ->withCount('roles', 'users')
            ->orderBy('name')
            ->paginate(30);

        return view('super-admin.permissions.index', compact('permissions'));
    }

    /**
     * System logs and audit
     */
    public function audit()
    {
        // Get recent activity (you can integrate with an audit log table if needed)
        $recentUsers = User::latest('updated_at')->limit(20)->get();
        $recentTenants = Tenant::latest('updated_at')->limit(20)->get();

        return view('super-admin.audit.index', compact('recentUsers', 'recentTenants'));
    }

    /**
     * System settings
     */
    public function settings()
    {
        return view('super-admin.settings.index');
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        // Validate and update system settings
        $request->validate([
            'app_name' => 'string|max:255',
            'app_description' => 'string',
        ]);

        // Store settings (you can use a settings table or config)
        // For now, just return success
        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    /**
     * Assign tenant to user
     */
    public function assignTenantToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tenant_id' => 'required|exists:tenants,id',
            'role' => 'nullable|string|max:50',
            'is_admin' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($request->user_id);
        $tenant = Tenant::findOrFail($request->tenant_id);

        // Check if user already has this tenant
        if ($user->tenants()->where('tenant_id', $tenant->id)->exists()) {
            return redirect()->back()->with('error', 'User already assigned to this tenant');
        }

        // Assign tenant to user with optional role and admin flag
        $role = $request->input('role') ?: 'member';
        $isAdmin = (bool) $request->input('is_admin', false);
        $user->addToTenant($tenant->id, $role, $isAdmin);

        // Set as current tenant if user doesn't have one
        if (!$user->current_tenant_id) {
            $user->update(['current_tenant_id' => $tenant->id]);
        }

        return redirect()->back()->with('success', "User {$user->name} assigned to tenant {$tenant->name} as {$role}" . ($isAdmin ? ' (admin)' : ''));
    }

    /**
     * Remove tenant from user
     */
    public function removeTenantFromUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $tenant = Tenant::findOrFail($request->tenant_id);

        // Remove tenant from user
        $user->tenants()->detach($tenant->id);

        // If this was the current tenant, reset to first available
        if ($user->current_tenant_id == $tenant->id) {
            $user->update(['current_tenant_id' => $user->tenants()->first()?->id]);
        }

        return redirect()->back()->with('success', "User {$user->name} removed from tenant {$tenant->name}");
    }
}

