#!/bin/bash

# SiteLedger Deployment Script for PHP 8.2 Servers
# This script handles deployment on servers with PHP 8.2.x

echo "🚀 Starting SiteLedger deployment for PHP 8.2..."

# Step 1: Clear all Laravel caches
echo "📝 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Step 2: Remove composer.lock to allow package resolution
echo "🔄 Removing composer.lock for fresh dependency resolution..."
if [ -f "composer.lock" ]; then
    rm composer.lock
    echo "✅ composer.lock removed"
fi

# Step 3: Install dependencies with PHP 8.2 compatible versions
echo "📦 Installing PHP 8.2 compatible dependencies..."
composer install --no-dev --optimize-autoloader

# Step 4: Generate application key if needed
echo "🔑 Checking application key..."
if grep -q "APP_KEY=base64:" .env; then
    echo "✅ Application key already exists"
else
    php artisan key:generate --force
    echo "✅ Application key generated"
fi

# Step 5: Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Step 6: Seed database if needed
echo "🌱 Seeding database..."
php artisan db:seed --force

# Step 7: Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Step 8: Set proper permissions
echo "🔒 Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache public

# Step 9: Cache optimizations for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 10: Build frontend assets
echo "🎨 Building frontend assets..."
if command -v npm &> /dev/null; then
    npm ci --production
    npm run build
    echo "✅ Frontend assets built"
else
    echo "⚠️  npm not found, skipping frontend build"
fi

echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Configure your web server to point to the 'public' directory"
echo "2. Set up SSL certificate"
echo "3. Configure environment variables in .env"
echo "4. Test the application"
echo ""
echo "🌐 Your SiteLedger application is ready!"