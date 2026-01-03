<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set current tenant for authenticated users
        if (auth()->check()) {
            $user = auth()->user();

            // Ensure current_tenant_id references a tenant the user belongs to
            $firstUserTenantId = $user->tenants()->first()?->id;

            if (empty($user->current_tenant_id) || !$user->belongsToTenant($user->current_tenant_id)) {
                // Only set current_tenant_id if the user actually has tenants
                if ($firstUserTenantId) {
                    $user->current_tenant_id = $firstUserTenantId;
                    $user->save();
                }
            }

            // Bind the current tenant to the app container only if the user belongs to it
            if (!empty($user->current_tenant_id) && $user->belongsToTenant($user->current_tenant_id)) {
                $tenant = app('App\Models\Tenant')::find($user->current_tenant_id);
                if ($tenant) {
                    app()->instance('currentTenant', $tenant);
                }
            }
        }

        return $next($request);
    }
}
