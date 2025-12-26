<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api-multitenant.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // This is where we register the middleware aliases.
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.permissions' => \App\Http\Middleware\CheckUserPermissions::class,
            // Multi-tenant middleware
            'resolve.tenant' => \App\Http\Middleware\ResolveTenantMiddleware::class,
            'tenant.scope' => \App\Http\Middleware\TenantDatabaseScopeMiddleware::class,
            'tenant.security' => \App\Http\Middleware\TenantSecurityMiddleware::class,
            'tenant.access' => \App\Http\Middleware\EnsureTenantAccess::class,
            'tenant.auth' => \App\Http\Middleware\TenantAwareAuthentication::class,
            'tenant.data' => \App\Http\Middleware\TenantDataMiddleware::class,
        ]);

        // You can also add global middleware here if needed, for example:
        // Global security headers
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();