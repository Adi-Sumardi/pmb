#!/bin/bash

# PPDB Backend - Clean Setup Script
# Script untuk menghapus instalasi lama dan setup fresh

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

print_status "=== PPDB Backend Clean Setup ==="

# Menghapus folder lama di home directory
print_status "Cleaning up home directory..."
cd ~

if [ -d "ppdb-backend" ]; then
    print_warning "Removing old ppdb-backend directory..."
    rm -rf ppdb-backend
fi

if [ -d "backups" ]; then
    print_warning "Removing old backups directory..."
    rm -rf backups
fi

if [ -f "backup-db.sh" ]; then
    print_warning "Removing old backup-db.sh script..."
    rm -f backup-db.sh
fi

# Menghapus aplikasi lama di /var/www jika ada
if [ -d "/var/www/ppdb-backend" ]; then
    print_warning "Removing old application directory..."
    sudo rm -rf /var/www/ppdb-backend
fi

# Menghapus konfigurasi nginx lama
print_status "Cleaning up old nginx configurations..."
sudo rm -f /etc/nginx/sites-enabled/default
sudo rm -f /etc/nginx/sites-available/ppdb*
sudo rm -f /etc/nginx/sites-enabled/ppdb*

# Menghapus supervisor config lama
if [ -f "/etc/supervisor/conf.d/ppdb-worker.conf" ]; then
    print_warning "Removing old supervisor configuration..."
    sudo rm -f /etc/supervisor/conf.d/ppdb-worker.conf
    sudo supervisorctl reread
    sudo supervisorctl update
fi

# Reset database (opsional - akan ditanya)
read -p "Do you want to drop the existing database? (y/N): " drop_db
if [[ $drop_db =~ ^[Yy]$ ]]; then
    print_warning "This will delete ALL data in the database!"

    # Clean up PostgreSQL database
    print_status "Attempting to clean PostgreSQL database..."

    if sudo -u postgres psql <<EOF 2>/dev/null
DROP DATABASE IF EXISTS ppdb_production;
DROP USER IF EXISTS ppdb_user;
EOF
    then
        print_success "PostgreSQL database cleaned up successfully!"
    else
        print_warning "Could not clean database automatically. You may need to clean it manually:"
        print_warning "sudo -u postgres psql"
        print_warning "DROP DATABASE IF EXISTS ppdb_production;"
        print_warning "DROP USER IF EXISTS ppdb_user;"
        print_warning "\\q"
    fi
fi

# Clear Redis cache
print_status "Clearing Redis cache..."
redis-cli FLUSHALL

# Clear logs lama
print_status "Clearing old logs..."
sudo rm -f /var/log/nginx/access.log*
sudo rm -f /var/log/nginx/error.log*
sudo systemctl restart nginx

# Restart semua services untuk clean state
print_status "Restarting services for clean state..."
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql
sudo systemctl restart redis-server
sudo systemctl restart supervisor

print_success "=== Clean up completed! ==="
print_status "Home directory contents now:"
ls -la ~

print_status "Ready for fresh deployment!"
print_status "You can now run the deployment script."
