<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Ensure tenant_id is set in the data array for multi-tenant models
     */
    protected function ensureTenantId(array $data): array
    {
        // If tenant_id is already set, return as is
        if (isset($data['tenant_id']) && $data['tenant_id']) {
            return $data;
        }

        $tenantId = null;

        // Try to get tenant_id from current tenant context (set by middleware)
        if (app()->bound('currentTenant')) {
            $currentTenant = app('currentTenant');
            if ($currentTenant) {
                $tenantId = $currentTenant->id;
            }
        }

        // Fallback: get tenant_id from authenticated user
        if (!$tenantId && Auth::check()) {
            $user = Auth::user();
            if ($user->current_tenant_id) {
                $tenantId = $user->current_tenant_id;
            } else if ($user->tenants()->exists()) {
                // If user doesn't have current_tenant_id set, use their first tenant
                $tenantId = $user->tenants()->first()->id;
            }
        }

        // Last resort: use default tenant (id = 1)
        if (!$tenantId) {
            $tenantId = 1;
        }

        $data['tenant_id'] = $tenantId;
        return $data;
    }
}
