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

            // If user doesn't have a current_tenant_id, assign them to the first tenant
            if (empty($user->current_tenant_id)) {
                $user->current_tenant_id = $user->tenants()->first()?->id ?? 1;
                $user->save();
            }

            // Bind the current tenant to the app container for use throughout the request
            if ($user->current_tenant_id) {
                $tenant = app('App\Models\Tenant')::find($user->current_tenant_id);
                if ($tenant) {
                    app()->instance('currentTenant', $tenant);
                }
            }
        }

        return $next($request);
    }
}
