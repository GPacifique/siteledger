<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'database',
        'business_type',
        'email',
        'contact_email',
        'contact_phone',
        'phone',
        'address',
        'description',
        'logo_path',
        'timezone',
        'currency',
        'locale',
        'max_users',
        'max_concurrent_sessions',
        'trial_ends_at',
        'features',
        'enforce_2fa',
        'session_timeout',
        'last_backup_at',
        'settings',
        'status',
        'subscription_plan',
        'subscription_expires_at',
        'created_by',
    ];

    protected $casts = [
        'settings' => 'json',
        'features' => 'json',
        'subscription_expires_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'last_backup_at' => 'datetime',
        'enforce_2fa' => 'boolean',
        'session_timeout' => 'integer',
        'max_users' => 'integer',
        'max_concurrent_sessions' => 'integer',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_INACTIVE = 'inactive';

    const BUSINESS_TYPES = [
        'construction' => 'Construction Company',
        'consulting' => 'Consulting',
        'manufacturing' => 'Manufacturing',
        'retail' => 'Retail',
        'service' => 'Service Provider',
        'other' => 'Other',
    ];

    const SUBSCRIPTION_PLANS = [
        'basic' => 'Basic',
        'professional' => 'Professional',
        'enterprise' => 'Enterprise',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
                    ->withPivot('role', 'is_admin', 'created_at')
                    ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany(TenantInvitation::class);
    }

    public function businessAdminPermissions()
    {
        return $this->hasMany(BusinessAdminPermission::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function hasActiveSubscription(): bool
    {
        // Check if trial is still active
        if ($this->trial_ends_at && $this->trial_ends_at > now()) {
            return true;
        }

        // Check if subscription is active
        return $this->subscription_expires_at && $this->subscription_expires_at > now();
    }

    public function isInTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at > now();
    }

    public function hasFeature(string $feature): bool
    {
        return data_get($this->features, $feature, false) === true;
    }

    public function getBusinessTypeLabel(): string
    {
        return self::BUSINESS_TYPES[$this->business_type] ?? 'Unknown';
    }

    public function getSubscriptionPlanLabel(): string
    {
        return self::SUBSCRIPTION_PLANS[$this->subscription_plan] ?? 'Unknown';
    }

    /**
     * Execute a callback within this tenant's context
     */
    public function execute(callable $callable): mixed
    {
        // Temporary fallback: directly execute the callback.
        // When `spatie/laravel-multitenancy` is installed this should
        // use the package's context switching behaviour instead.
        return $callable();
    }

    /**
     * Get tenant configuration setting
     */
    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    /**
     * Set tenant configuration setting
     */
    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->update(['settings' => $settings]);
    }
}
