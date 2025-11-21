#!/bin/bash

# Composer Update Script for PHP 8.2 Compatibility
# This script updates composer dependencies to be compatible with PHP 8.2

echo "🔧 Updating composer dependencies for PHP 8.2 compatibility..."

# Remove the lock file
rm -f composer.lock

# Clear composer cache
composer clear-cache

# Update composer to latest version
composer self-update

# Install/update dependencies with proper constraints
echo "📦 Installing dependencies..."
composer update --with-dependencies --prefer-stable

# If that fails, try with specific version constraints
if [ $? -ne 0 ]; then
    echo "⚠️  Standard update failed, trying with specific constraints..."
    
    # Remove problematic packages and reinstall compatible versions
    composer remove --dev pestphp/pest pestphp/pest-plugin-laravel phpunit/phpunit --no-update
    
    # Add compatible versions
    composer require --dev "pestphp/pest:^3.3" --no-update
    composer require --dev "pestphp/pest-plugin-laravel:^3.0" --no-update  
    composer require --dev "phpunit/phpunit:^11.0" --no-update
    
    # Update with new constraints
    composer update --with-dependencies
fi

# Dump autoloader
composer dump-autoload --optimize

echo "✅ Composer dependencies updated for PHP 8.2!"