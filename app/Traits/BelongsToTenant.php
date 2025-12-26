<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

trait BelongsToTenant
{
    use UsesTenantConnection;

    /**
     * Boot the trait.
     */
    protected static function bootBelongsToTenant(): void
    {
        // Automatically set tenant_id when creating models
        static::creating(function ($model) {
            if (!$model->tenant_id) {
                $tenantId = null;

                // Try to get from app container first
                if (app()->bound('currentTenant')) {
                    $currentTenant = app('currentTenant');
                    if ($currentTenant) {
                        $tenantId = $currentTenant->id;
                    }
                }

                // Fallback to authenticated user's tenant
                if (!$tenantId && auth()->check()) {
                    $user = auth()->user();
                    if ($user->current_tenant_id) {
                        $tenantId = $user->current_tenant_id;
                    } else if ($user->tenants()->exists()) {
                        $tenantId = $user->tenants()->first()->id;
                    }
                }

                // Last resort: use default tenant
                if (!$tenantId) {
                    $tenantId = 1;
                }

                $model->tenant_id = $tenantId;
            }
        });

        // Add global scope to automatically filter by current tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenantId = null;

            if (app()->bound('currentTenant')) {
                $currentTenant = app('currentTenant');
                if ($currentTenant) {
                    $tenantId = $currentTenant->id;
                }
            }

            if (!$tenantId && auth()->check()) {
                $user = auth()->user();
                if ($user->current_tenant_id) {
                    $tenantId = $user->current_tenant_id;
                }
            }

            if ($tenantId) {
                $builder->where('tenant_id', $tenantId);
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope a query to only include models for a specific tenant.
     */
    public function scopeForTenant(Builder $query, $tenantId): Builder
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Check if the model belongs to the current tenant.
     */
    public function belongsToCurrentTenant(): bool
    {
        if (app()->bound('currentTenant')) {
            $currentTenant = app('currentTenant');
            return $currentTenant && $this->tenant_id === $currentTenant->id;
        }
        return true; // If no tenant context, allow access
    }
}
