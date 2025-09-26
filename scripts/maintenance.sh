#!/bin/bash

# PPDB Backend - Maintenance & Troubleshooting Script
# Script untuk maintenance dan troubleshooting deployment

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

APP_DIR="/var/www/ppdb-backend"

show_menu() {
    echo "=== PPDB Backend Maintenance Menu ==="
    echo "1. Check system status"
    echo "2. Update application"
    echo "3. Clear all caches"
    echo "4. Reset permissions"
    echo "5. View logs"
    echo "6. Backup database"
    echo "7. Restart services"
    echo "8. Check disk space"
    echo "9. Monitor processes"
    echo "10. Fix common issues"
    echo "0. Exit"
    echo "================================="
}

check_status() {
    print_status "Checking system status..."

    echo "=== Service Status ==="
    sudo systemctl status nginx --no-pager -l | head -3
    sudo systemctl status php8.2-fpm --no-pager -l | head -3
    sudo systemctl status postgresql --no-pager -l | head -3
    sudo systemctl status redis-server --no-pager -l | head -3
    sudo systemctl status supervisor --no-pager -l | head -3

    echo ""
    echo "=== Application Status ==="
    cd $APP_DIR
    php artisan --version

    echo ""
    echo "=== Database Connection ==="
    php artisan migrate:status | head -5

    echo ""
    echo "=== Queue Status ==="
    sudo supervisorctl status ppdb-worker:*

    echo ""
    echo "=== Disk Usage ==="
    df -h /var/www

    print_success "Status check complete!"
}

update_application() {
    print_status "Updating application..."

    cd $APP_DIR

    # Put application in maintenance mode
    php artisan down --message="Updating application..." --allow=127.0.0.1

    # Pull latest changes
    git pull origin master

    # Update dependencies
    composer install --no-dev --optimize-autoloader --no-interaction
    npm ci --production=false

    # Run migrations
    php artisan migrate --force

    # Build assets
    npm run build

    # Clear and cache
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    # Restart services
    sudo systemctl reload nginx
    sudo systemctl restart php8.2-fpm
    sudo supervisorctl restart ppdb-worker:*

    # Bring application back up
    php artisan up

    print_success "Application updated successfully!"
}

clear_caches() {
    print_status "Clearing all caches..."

    cd $APP_DIR

    # Clear Laravel caches
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan event:clear

    # Clear compiled views
    php artisan view:clear

    # Clear Redis cache
    redis-cli FLUSHALL

    # Clear opcache
    sudo systemctl restart php8.2-fpm

    # Rebuild caches for production
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    print_success "All caches cleared and rebuilt!"
}

reset_permissions() {
    print_status "Resetting file permissions..."

    sudo chown -R $USER:www-data $APP_DIR
    sudo chmod -R 755 $APP_DIR
    sudo chmod -R 775 $APP_DIR/storage
    sudo chmod -R 775 $APP_DIR/bootstrap/cache

    # Fix specific Laravel directories
    find $APP_DIR/storage -type f -exec chmod 664 {} \;
    find $APP_DIR/storage -type d -exec chmod 775 {} \;
    find $APP_DIR/bootstrap/cache -type f -exec chmod 664 {} \;
    find $APP_DIR/bootstrap/cache -type d -exec chmod 775 {} \;

    print_success "Permissions reset successfully!"
}

view_logs() {
    echo "=== Log Options ==="
    echo "1. Laravel application logs"
    echo "2. Nginx error logs"
    echo "3. Nginx access logs"
    echo "4. PHP-FPM logs"
    echo "5. PostgreSQL logs"
    echo "6. Queue worker logs"
    echo "7. System logs"

    read -p "Select log to view (1-7): " log_choice

    case $log_choice in
        1)
            print_status "Laravel application logs (last 50 lines):"
            tail -f -n 50 $APP_DIR/storage/logs/laravel.log
            ;;
        2)
            print_status "Nginx error logs (last 50 lines):"
            sudo tail -f -n 50 /var/log/nginx/error.log
            ;;
        3)
            print_status "Nginx access logs (last 50 lines):"
            sudo tail -f -n 50 /var/log/nginx/access.log
            ;;
        4)
            print_status "PHP-FPM logs (last 50 lines):"
            sudo tail -f -n 50 /var/log/php8.2-fpm.log
            ;;
        5)
            print_status "PostgreSQL logs (last 50 lines):"
            sudo tail -f -n 50 /var/log/postgresql/postgresql-*-main.log
            ;;
        6)
            print_status "Queue worker logs (last 50 lines):"
            tail -f -n 50 $APP_DIR/storage/logs/worker.log
            ;;
        7)
            print_status "System logs (last 50 lines):"
            sudo journalctl -f -n 50
            ;;
        *)
            print_error "Invalid option!"
            ;;
    esac
}

backup_database() {
    print_status "Creating database backup..."

    BACKUP_DIR="/var/backups/ppdb"
    DATE=$(date +%Y%m%d_%H%M%S)

    sudo mkdir -p $BACKUP_DIR

    # Create database backup
    sudo -u postgres pg_dump ppdb_production > $BACKUP_DIR/manual_backup_$DATE.sql

    # Create application backup
    sudo tar -czf $BACKUP_DIR/app_backup_$DATE.tar.gz -C /var/www ppdb-backend

    print_success "Backup created: $BACKUP_DIR/manual_backup_$DATE.sql"
    print_success "App backup created: $BACKUP_DIR/app_backup_$DATE.tar.gz"
}

restart_services() {
    print_status "Restarting all services..."

    sudo systemctl restart nginx
    sudo systemctl restart php8.2-fpm
    sudo systemctl restart postgresql
    sudo systemctl restart redis-server
    sudo systemctl restart supervisor

    print_success "All services restarted!"
}

check_disk_space() {
    print_status "Checking disk space..."

    echo "=== Disk Usage ==="
    df -h

    echo ""
    echo "=== Large Files in /var/log ==="
    sudo find /var/log -type f -size +100M -exec ls -lh {} \; | head -10

    echo ""
    echo "=== Storage Directory Usage ==="
    du -sh $APP_DIR/storage/*

    echo ""
    echo "=== Database Size ==="
    sudo -u postgres psql -d ppdb_production -c "SELECT pg_size_pretty(pg_database_size('ppdb_production')) as database_size;"
}

monitor_processes() {
    print_status "Monitoring system processes..."

    echo "=== Top Processes ==="
    top -b -n 1 | head -20

    echo ""
    echo "=== Memory Usage ==="
    free -h

    echo ""
    echo "=== PHP-FPM Processes ==="
    ps aux | grep php-fpm | head -10

    echo ""
    echo "=== Active Connections ==="
    ss -tuln | grep -E '(:80|:443|:5432|:6379)'
}

fix_common_issues() {
    print_status "Fixing common issues..."

    cd $APP_DIR

    # Fix storage permissions
    reset_permissions

    # Clear all caches
    clear_caches

    # Regenerate app key if needed
    if ! grep -q "APP_KEY=base64:" .env; then
        print_warning "Regenerating APP_KEY..."
        php artisan key:generate --force
    fi

    # Fix composer autoload
    composer dump-autoload --optimize

    # Check for missing storage directories
    mkdir -p storage/framework/cache
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/logs
    mkdir -p bootstrap/cache

    # Fix Redis connection
    redis-cli ping > /dev/null || sudo systemctl restart redis-server

    # Test database connection
    php artisan migrate:status > /dev/null || print_error "Database connection failed!"

    # Restart queue workers
    sudo supervisorctl restart ppdb-worker:*

    print_success "Common issues fixed!"
}

# Main menu loop
while true; do
    show_menu
    read -p "Select option (0-10): " choice

    case $choice in
        1) check_status ;;
        2) update_application ;;
        3) clear_caches ;;
        4) reset_permissions ;;
        5) view_logs ;;
        6) backup_database ;;
        7) restart_services ;;
        8) check_disk_space ;;
        9) monitor_processes ;;
        10) fix_common_issues ;;
        0)
            print_success "Goodbye!"
            exit 0
            ;;
        *)
            print_error "Invalid option! Please select 0-10."
            ;;
    esac

    echo ""
    read -p "Press Enter to continue..."
    clear
done
