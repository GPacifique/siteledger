#!/bin/bash

# SiteLedger Deployment Fix Script
echo "🔧 SiteLedger Deployment Fix Starting..."

# Clear all caches first
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Check current migration status
echo "📊 Checking migration status..."
php artisan migrate:status

# Mark existing tables as migrated (if they exist)
echo "✅ Marking existing migrations as completed..."

# Check if order_items table exists and mark migration as run
php artisan tinker --execute="
if (Schema::hasTable('order_items')) {
    DB::table('migrations')->updateOrInsert(
        ['migration' => '2025_10_31_124100_create_order_items_table'],
        ['batch' => 1]
    );
    echo 'order_items migration marked as completed\n';
}
"

# Check if orders table exists and mark migration as run
php artisan tinker --execute="
if (Schema::hasTable('orders')) {
    DB::table('migrations')->updateOrInsert(
        ['migration' => '2025_10_31_124000_create_orders_table'],
        ['batch' => 1]
    );
    echo 'orders migration marked as completed\n';
}
"

# Check if products table exists and mark migration as run
php artisan tinker --execute="
if (Schema::hasTable('products')) {
    DB::table('migrations')->updateOrInsert(
        ['migration' => '2025_10_31_150000_create_products_table'],
        ['batch' => 1]
    );
    echo 'products migration marked as completed\n';
}
"

# Check if worker_payments table exists and mark migration as run
php artisan tinker --execute="
if (Schema::hasTable('worker_payments')) {
    DB::table('migrations')->updateOrInsert(
        ['migration' => '2025_11_03_120000_create_worker_payments_table'],
        ['batch' => 1]
    );
    echo 'worker_payments migration marked as completed\n';
}
"

# Now run migrations safely (only new ones will run)
echo "🚀 Running remaining migrations..."
php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize

echo "✅ Deployment fix completed successfully!"
echo "🌟 SiteLedger is ready to use!"