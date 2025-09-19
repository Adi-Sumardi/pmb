#!/bin/bash

# Laravel Application Deployment Script for Cloudflare
# This script prepares your Laravel application for deployment behind Cloudflare

echo "🚀 Starting Laravel deployment for Cloudflare..."

# Check if running in production environment
if [ "$APP_ENV" != "production" ]; then
    echo "⚠️  Warning: Not in production environment. Set APP_ENV=production"
fi

# 1. Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 2. Install/update dependencies
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Generate optimized autoloader
echo "⚡ Optimizing autoloader..."
composer dump-autoload --optimize

# 4. Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# 5. Cache configurations for production
echo "💾 Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Optimize Laravel for production
echo "🔧 Optimizing Laravel..."
php artisan optimize

# 7. Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 8. Create storage link if not exists
echo "🔗 Creating storage link..."
php artisan storage:link

# 9. Clear and warm up OPcache (if available)
if php -m | grep -q opcache; then
    echo "🚀 OPcache detected, optimizing..."
    # This would typically be done by your web server restart
fi

# 10. Verify Cloudflare configuration
echo "☁️  Verifying Cloudflare configuration..."
php artisan tinker --execute="
if (config('trustedproxy.proxies')) {
    echo 'Trusted proxies configured: ' . count(config('trustedproxy.proxies')) . ' ranges' . PHP_EOL;
} else {
    echo 'Warning: Trusted proxies not configured!' . PHP_EOL;
}
"

# 11. Test database connection
echo "🔌 Testing database connection..."
php artisan migrate:status

# 12. Test cache functionality
echo "💾 Testing cache functionality..."
php artisan cache:clear
php artisan tinker --execute="
Cache::put('deployment_test', 'success', 60);
echo Cache::get('deployment_test', 'failed') . PHP_EOL;
"

echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Post-deployment checklist:"
echo "   1. Update your domain DNS to point to Cloudflare"
echo "   2. Configure Cloudflare SSL/TLS to Full (strict)"
echo "   3. Enable Cloudflare security features (DDoS protection, WAF)"
echo "   4. Set up Cloudflare page rules for static assets caching"
echo "   5. Configure Cloudflare Analytics and monitoring"
echo "   6. Test your application thoroughly"
echo ""
echo "🔧 Cloudflare recommended settings:"
echo "   - SSL/TLS: Full (strict)"
echo "   - Always Use HTTPS: On"
echo "   - HSTS: Enabled"
echo "   - Automatic HTTPS Rewrites: On"
echo "   - Opportunistic Encryption: On"
echo "   - TLS 1.3: Enabled"
echo ""
echo "🚀 Your Laravel application is ready for Cloudflare!"