<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RequireEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:require-verification {--force : Force verification reset for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Require email verification for users who were auto-verified during registration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');

        if ($force) {
            if (!$this->confirm('This will reset email verification for ALL users. Are you sure?')) {
                $this->info('Operation cancelled.');
                return 0;
            }

            $count = User::whereNotNull('email_verified_at')->count();
            User::query()->update(['email_verified_at' => null]);

            $this->info("Reset email verification for {$count} users.");
            $this->warn("All users will need to verify their email addresses before accessing the system.");

        } else {
            // Only affect users created in the last hour (likely auto-verified during registration)
            $recentUsers = User::where('created_at', '>=', now()->subHour())
                              ->whereNotNull('email_verified_at')
                              ->get();

            if ($recentUsers->isEmpty()) {
                $this->info('No recently auto-verified users found.');
                return 0;
            }

            $count = $recentUsers->count();
            $recentUsers->each(function ($user) {
                $user->update(['email_verified_at' => null]);
                $user->sendEmailVerificationNotification();
            });

            $this->info("Reset email verification for {$count} recently registered users.");
            $this->info("Verification emails have been sent to these users.");
        }

        return 0;
    }
}
