<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Baseline security headers
            \App\Http\Middleware\SecurityHeaders::class,
            // Multitenancy middleware - only for authenticated routes
            // \Spatie\Multitenancy\Http\Middleware\NeedsTenant::class,
            // \App\Http\Middleware\EnsureTenantAccess::class,
        ],

        'api' => [
            \Illuminate\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These can be assigned to groups or used individually.
     */
    protected $routeMiddleware = [
        // Laravel defaults
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // ✅ Spatie role/permission middleware
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        'role.redirect' => \App\Http\Middleware\RoleRedirectMiddleware::class,

        // Enhanced Multi-tenant middleware
        'resolve.tenant' => \App\Http\Middleware\ResolveTenantMiddleware::class,
        'tenant.scope' => \App\Http\Middleware\TenantDatabaseScopeMiddleware::class,
        'tenant.security' => \App\Http\Middleware\TenantSecurityMiddleware::class,
        'tenant.access' => \App\Http\Middleware\EnsureTenantAccess::class,
        'tenant.auth' => \App\Http\Middleware\TenantAwareAuthentication::class,
        'tenant.data' => \App\Http\Middleware\TenantDataMiddleware::class,
    ];
}
