<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\Expense;
use App\Observers\ExpenseObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure indexed string columns stay within MySQL index size limits
        Schema::defaultStringLength(191);
        Vite::prefetch(concurrency: 3);

        // Customize the password reset URL to use our Blade route
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return url(route('password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], false));
        });

        // Register model observers for CPM modules
        if (class_exists(Expense::class) && class_exists(ExpenseObserver::class)) {
            Expense::observe(ExpenseObserver::class);
        }
    }
}
