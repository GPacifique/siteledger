<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CompanyStaffController extends Controller
{
    /**
     * Get the current tenant for the authenticated user.
     */
    protected function getCurrentTenant()
    {
        $user = Auth::user();

        // Check if user has a current tenant set
        if ($user->current_tenant_id) {
            return Tenant::find($user->current_tenant_id);
        }

        // Fall back to the first tenant they belong to
        return $user->tenants()->first();
    }

    /**
     * Check if the current user is an admin for the current tenant.
     */
    protected function ensureTenantAdmin()
    {
        $user = Auth::user();
        $tenant = $this->getCurrentTenant();

        if (!$tenant) {
            abort(403, 'You do not belong to any company.');
        }

        if (!$user->isAdminForTenant($tenant->id) && !$user->isSuperAdmin()) {
            abort(403, 'You are not authorized to manage staff for this company.');
        }

        return $tenant;
    }

    /**
     * Display a listing of company staff.
     */
    public function index()
    {
        $tenant = $this->ensureTenantAdmin();

        $staff = User::whereHas('tenants', function ($query) use ($tenant) {
            $query->where('tenant_id', $tenant->id);
        })->with(['tenants' => function ($query) use ($tenant) {
            $query->where('tenant_id', $tenant->id);
        }])->get()->map(function ($user) use ($tenant) {
            $pivot = $user->tenants->first()?->pivot;
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $pivot?->role ?? 'user',
                'is_admin' => $pivot?->is_admin ?? false,
                'joined_at' => $pivot?->created_at,
                'status' => $user->status ?? 'active',
                'email_verified_at' => $user->email_verified_at,
            ];
        });

        return view('company.staff.index', [
            'staff' => $staff,
            'company' => $tenant,
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Show the form for inviting a new staff member.
     */
    public function create()
    {
        $tenant = $this->ensureTenantAdmin();

        return view('company.staff.create', [
            'company' => $tenant,
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $tenant = $this->ensureTenantAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|in:' . implode(',', array_keys($this->getAvailableRoles())),
            'is_admin' => 'boolean',
        ]);

        // Create the user (only store fields that exist in users table)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        // Add user to the tenant with their role
        $user->addToTenant(
            $tenant->id,
            $data['role'],
            $request->boolean('is_admin')
        );

        // Assign Spatie role if it exists
        if (\Spatie\Permission\Models\Role::where('name', $data['role'])->exists()) {
            $user->assignRole($data['role']);
        }

        return redirect()->route('company.staff.index')
            ->with('success', 'Staff member added successfully.');
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        $tenant = $this->ensureTenantAdmin();

        if (!$staff->belongsToTenant($tenant->id)) {
            abort(404, 'Staff member not found in your company.');
        }

        $pivot = $staff->tenants()->where('tenant_id', $tenant->id)->first()?->pivot;

        return view('company.staff.show', [
            'staffMember' => $staff,
            'pivot' => $pivot,
            'company' => $tenant,
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        $tenant = $this->ensureTenantAdmin();

        if (!$staff->belongsToTenant($tenant->id)) {
            abort(404, 'Staff member not found in your company.');
        }

        $pivot = $staff->tenants()->where('tenant_id', $tenant->id)->first()?->pivot;

        return view('company.staff.edit', [
            'staffMember' => $staff,
            'pivot' => $pivot,
            'company' => $tenant,
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, User $staff)
    {
        $tenant = $this->ensureTenantAdmin();

        if (!$staff->belongsToTenant($tenant->id)) {
            abort(404, 'Staff member not found in your company.');
        }

        if ($staff->id === Auth::id() && $request->has('is_admin') && !$request->boolean('is_admin')) {
            return back()->withErrors(['is_admin' => 'You cannot remove your own admin privileges.']);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($staff->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|in:' . implode(',', array_keys($this->getAvailableRoles())),
            'is_admin' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $staff->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
        ]);

        if (!empty($data['password'])) {
            $staff->update(['password' => Hash::make($data['password'])]);
        }

        $staff->updateTenantRole(
            $tenant->id,
            $data['role'],
            $request->boolean('is_admin')
        );

        $staff->syncRoles([$data['role']]);

        return redirect()->route('company.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified staff member from the company.
     */
    public function destroy(User $staff)
    {
        $tenant = $this->ensureTenantAdmin();

        if (!$staff->belongsToTenant($tenant->id)) {
            abort(404, 'Staff member not found in your company.');
        }

        if ($staff->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot remove yourself from the company.']);
        }

        $staff->removeFromTenant($tenant->id);

        return redirect()->route('company.staff.index')
            ->with('success', 'Staff member removed from company successfully.');
    }

    /**
     * Toggle admin status for a staff member.
     */
    public function toggleAdmin(User $staff)
    {
        $tenant = $this->ensureTenantAdmin();

        if (!$staff->belongsToTenant($tenant->id)) {
            abort(404, 'Staff member not found in your company.');
        }

        if ($staff->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot change your own admin status.']);
        }

        $currentAdminStatus = $staff->isAdminForTenant($tenant->id);
        $currentRole = $staff->getRoleForTenant($tenant->id);

        $staff->updateTenantRole($tenant->id, $currentRole, !$currentAdminStatus);

        $message = $currentAdminStatus
            ? 'Admin privileges removed from staff member.'
            : 'Admin privileges granted to staff member.';

        return back()->with('success', $message);
    }

    /**
     * Get available roles for staff assignment.
     */
    protected function getAvailableRoles(): array
    {
        return [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'accountant' => 'Accountant',
            'employee' => 'Employee',
            'user' => 'User',
            'viewer' => 'Viewer',
        ];
    }
}
