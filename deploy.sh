#!/bin/bash

# =============================================================================
# LARAVEL CLOUD DEPLOYMENT SCRIPT
# =============================================================================
# This script runs automatically on Laravel Cloud deployments
# to ensure RBAC permissions are properly set up

echo "🚀 Laravel Cloud Deployment - Setting up RBAC..."

# Run migrations first
echo "📊 Running migrations..."
php artisan migrate --force

# Seed roles and permissions
echo "🛡️ Seeding roles and permissions..."
php artisan db:seed --class=RolePermissionSeeder --force

# Create admin users
echo "👤 Creating admin users..."
php artisan db:seed --class=AdminUserSeeder --force

# Fix any permission issues
echo "🔧 Fixing admin permissions..."
php artisan admin:fix-permissions

# Clear all caches
echo "🧹 Clearing caches..."
php artisan permission:cache-reset
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Laravel Cloud deployment complete!"
echo "🔐 Admin credentials:"
echo "   Email: admin@siteledger.com"
echo "   Password: admin123"
echo ""
echo "   Email: gashumba@siteledger.com" 
echo "   Password: password"