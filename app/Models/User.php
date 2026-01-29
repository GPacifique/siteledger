<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, Authorizable;

    /**
     * Proxy for Spatie's hasPermissionTo for compatibility.
     */
    public function hasPermissionTo($permission, $guardName = null)
    {
        return $this->checkPermissionTo($permission, $guardName);
    }

    /**
     * If you use a guard other than 'web', set it here. Otherwise 'web' is used by default.
     *
     * @var string
     */
    protected $guard_name = 'web';

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'is_super_admin',
        'email_verified_at',
        'current_tenant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
        'status' => 'string',
    ];

    /**
     * The tenants that the user belongs to.
     */
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users')
                    ->withPivot('role', 'is_admin', 'created_at')
                    ->withTimestamps();
    }

    /**
     * Get the current tenant for this user.
     */
    public function currentTenant()
    {
        if (app()->bound('currentTenant')) {
            return app('currentTenant');
        }

        // If no current tenant context, return the first tenant
        return $this->tenants()->first();
    }

    /**
     * Check if user belongs to a specific tenant.
     */
    public function belongsToTenant($tenantId): bool
    {
        return $this->tenants()->where('tenant_id', $tenantId)->exists();
    }

    /**
     * Get user's role for a specific tenant.
     */
    public function getRoleForTenant($tenantId): ?string
    {
        $pivot = $this->tenants()->where('tenant_id', $tenantId)->first();
        return $pivot ? $pivot->pivot->role : null;
    }

    /**
     * Get user's role for a specific tenant (alias method)
     */
    public function getTenantRole($tenantId): ?string
    {
        return $this->getRoleForTenant($tenantId);
    }

    /**
     * Get all business permissions for a specific tenant
     */
    public function getBusinessPermissions(int $tenantId): array
    {
        return $this->businessAdminPermissions()
                   ->forUserInTenant($this->id, $tenantId)
                   ->valid()
                   ->pluck('permission')
                   ->toArray();
    }

    /**
     * Check if user is admin for a specific tenant.
     */
    public function isAdminForTenant($tenantId): bool
    {
        $pivot = $this->tenants()->where('tenant_id', $tenantId)->first();
        return $pivot ? $pivot->pivot->is_admin : false;
    }

    /**
     * Check if user is a super admin (can manage all tenants).
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    /**
     * Add user to a tenant with a specific role.
     */
    public function addToTenant($tenantId, string $role = 'user', bool $isAdmin = false): void
    {
        $this->tenants()->syncWithoutDetaching([
            $tenantId => [
                'role' => $role,
                'is_admin' => $isAdmin,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Remove user from a tenant.
     */
    public function removeFromTenant($tenantId): void
    {
        $this->tenants()->detach($tenantId);
    }

    /**
     * Update user's role for a tenant.
     */
    public function updateTenantRole($tenantId, string $role, bool $isAdmin = false): void
    {
        $this->tenants()->updateExistingPivot($tenantId, [
            'role' => $role,
            'is_admin' => $isAdmin,
            'updated_at' => now(),
        ]);
    }

    /**
     * Get all tenants where user has admin privileges.
     */
    public function adminTenants()
    {
        return $this->tenants()->wherePivot('is_admin', true);
    }

    /**
     * Scope to get users for a specific tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        });
    }

    /**
     * Business admin relationships and methods
     */
    public function businessAdminPermissions()
    {
        return $this->hasMany(BusinessAdminPermission::class);
    }

    public function invitationsSent()
    {
        return $this->hasMany(UserInvitation::class, 'invited_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if user has specific business admin permission
     */
    public function hasBusinessPermission(string $permission, int $tenantId, array $constraints = []): bool
    {
        $query = $this->businessAdminPermissions()
                     ->forUserInTenant($this->id, $tenantId)
                     ->forPermission($permission)
                     ->valid();

        if (!empty($constraints)) {
            $query->where(function ($q) use ($constraints) {
                foreach ($constraints as $key => $value) {
                    $q->where("constraints->{$key}", $value);
                }
            });
        }

        return $query->exists();
    }

    /**
     * Grant business admin permission
     */
    public function grantBusinessPermission(string $permission, int $tenantId, array $constraints = [], ?\DateTime $expiresAt = null): BusinessAdminPermission
    {
        return BusinessAdminPermission::create([
            'user_id' => $this->id,
            'tenant_id' => $tenantId,
            'permission' => $permission,
            'constraints' => $constraints,
            'granted_at' => now(),
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Revoke business admin permission
     */
    public function revokeBusinessPermission(string $permission, int $tenantId): bool
    {
        return $this->businessAdminPermissions()
                   ->forUserInTenant($this->id, $tenantId)
                   ->forPermission($permission)
                   ->delete() > 0;
    }

    /**
     * Check if user can invite users to tenant
     */
    public function canInviteUsers(int $tenantId): bool
    {
        return $this->isAdminForTenant($tenantId) ||
               $this->hasBusinessPermission('invite_users', $tenantId);
    }

    /**
     * Check if user can manage other users in tenant
     */
    public function canManageUsers(int $tenantId): bool
    {
        return $this->isAdminForTenant($tenantId) ||
               $this->hasBusinessPermission('manage_users', $tenantId);
    }

    /**
     * Check if user can assign roles in tenant
     */
    public function canAssignRoles(int $tenantId, ?string $targetRole = null): bool
    {
        if ($this->isAdminForTenant($tenantId)) {
            return true;
        }

        $hasPermission = $this->hasBusinessPermission('assign_roles', $tenantId);

        if ($targetRole && $hasPermission) {
            // Check if user has constraint limiting which roles they can assign
            $permission = $this->businessAdminPermissions()
                              ->forUserInTenant($this->id, $tenantId)
                              ->forPermission('assign_roles')
                              ->valid()
                              ->first();

            if ($permission && $permission->hasConstraint('allowed_roles')) {
                return in_array($targetRole, $permission->getConstraint('allowed_roles', []));
            }
        }

        return $hasPermission;
    }

    /**
     * Send invitation to join tenant
     */
    public function inviteUserToTenant(
        int $tenantId,
        string $email,
        string $role = 'user',
        bool $isAdmin = false,
        array $permissions = [],
        array $metadata = []
    ): ?UserInvitation {
        // Check if user can invite
        if (!$this->canInviteUsers($tenantId)) {
            throw new \Exception('User does not have permission to invite users');
        }

        // Check if user can assign the specified role
        if (!$this->canAssignRoles($tenantId, $role)) {
            throw new \Exception("User does not have permission to assign role: {$role}");
        }

        // Check for existing pending invitation
        $existing = UserInvitation::where('tenant_id', $tenantId)
                                  ->where('email', $email)
                                  ->where('status', 'pending')
                                  ->first();

        if ($existing) {
            throw new \Exception('Pending invitation already exists for this email');
        }

        // Create invitation
        $invitation = UserInvitation::create([
            'tenant_id' => $tenantId,
            'invited_by' => $this->id,
            'email' => $email,
            'role' => $role,
            'is_admin' => $isAdmin,
            'permissions' => $permissions,
            'metadata' => $metadata,
        ]);

        // Log the invitation
        AuditLog::create([
            'tenant_id' => $tenantId,
            'action' => 'user_invited',
            'description' => "User {$this->name} invited {$email} to join as {$role}",
            'metadata' => [
                'invitation_id' => $invitation->id,
                'invited_email' => $email,
                'role' => $role,
                'is_admin' => $isAdmin,
            ],
            'severity' => 'medium',
        ]);

        return $invitation;
    }
}
