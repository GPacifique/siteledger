<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use App\Services\RbacFilterService;
use Inertia\Inertia;

class UserController extends Controller
{
    protected RbacFilterService $rbacFilterService;

    public function __construct(RbacFilterService $rbacFilterService)
    {
        $this->rbacFilterService = $rbacFilterService;
    }

    /**
     * Display a paginated listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        $filteredUsers = $this->rbacFilterService->filterUsers($query)->get();

        return Inertia::render('Admin/Users', [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('email_verified_at', '!=', null)->count(),
            'users' => $filteredUsers,
            'filterContext' => $this->rbacFilterService->getFilterContext(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::pluck('name'); // list of role names for the form
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        // Determine primary role (first selected role or 'user' as default)
        $primaryRole = !empty($data['roles']) ? $data['roles'][0] : 'user';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $primaryRole, // Set primary role column
        ]);

        if (!empty($data['roles'])) {
            $user->assignRole($data['roles']);

            // If admin role is assigned, ensure they have all permissions
            if (in_array('admin', $data['roles'])) {
                $this->ensureAdminFullPermissions($user);
            }
        } else {
            // Assign default user role if no roles selected
            $user->assignRole('user');
        }

        return redirect()->route('users.index')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name');
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Update primary role column (first selected role or keep existing)
        if (!empty($data['roles'])) {
            $user->role = $data['roles'][0];
        }

        $user->save();

        // sync roles (replace current roles with provided ones, or remove if none)
        $roles = $data['roles'] ?? [];
        $user->syncRoles($roles);

        // If admin role is assigned, ensure they have all permissions
        if (in_array('admin', $roles)) {
            $this->ensureAdminFullPermissions($user);
        }

        return redirect()->route('users.show', $user)
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion by default (optional)
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully.');
    }

    /**
     * Ensure admin user has all available permissions
     */
    private function ensureAdminFullPermissions(User $user): void
    {
        try {
            // Get the admin role
            $adminRole = Role::firstOrCreate(['name' => 'admin']);

            // Get all available permissions
            $allPermissions = Permission::all();

            // If no permissions exist yet, create the basic ones
            if ($allPermissions->isEmpty()) {
                $this->createBasicPermissions();
                $allPermissions = Permission::all();
            }

            // Sync all permissions to admin role
            $adminRole->syncPermissions($allPermissions->pluck('name'));

            // Ensure user has admin role
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
            }

            // Clear permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        } catch (\Throwable $e) {
            // Log but don't fail user creation
            report($e);
        }
    }

    /**
     * Create basic permissions if none exist
     */
    private function createBasicPermissions(): void
    {
        $basicPermissions = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
            'expenses.view', 'expenses.create', 'expenses.edit', 'expenses.delete',
            'incomes.view', 'incomes.create', 'incomes.edit', 'incomes.delete',
            'payments.view', 'payments.create', 'payments.edit', 'payments.delete',
            'reports.view', 'reports.generate', 'reports.export',
            'employees.view', 'employees.create', 'employees.edit', 'employees.delete',
            'workers.view', 'workers.create', 'workers.edit', 'workers.delete',
            'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
            'settings.view', 'settings.edit',
        ];

        foreach ($basicPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
