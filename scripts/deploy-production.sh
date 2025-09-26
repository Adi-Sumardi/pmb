#!/bin/bash

# PPDB Backend - Production Deployment Script
# Script untuk deploy aplikasi Laravel ke VPS production

set -e

# Colors for output
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

# Configuration
DOMAIN="your-domain.com"  # Ganti dengan domain Anda
APP_DIR="/var/www/ppdb-backend"
REPO_URL="https://github.com/Adi-Sumardi/pmb.git"
DB_NAME="ppdb_production"
DB_USER="ppdb_user"

print_status "=== PPDB Backend Production Deployment ==="

# Prompt for configuration
read -p "Enter your domain name: " DOMAIN
read -p "Enter database name [ppdb_production]: " input_db_name
DB_NAME=${input_db_name:-ppdb_production}

read -p "Enter database username [ppdb_user]: " input_db_user
DB_USER=${input_db_user:-ppdb_user}

read -s -p "Enter database password: " DB_PASSWORD
echo ""

read -s -p "Enter MySQL root password: " MYSQL_ROOT_PASSWORD
echo ""

# Create application directory
print_status "Creating application directory..."
sudo mkdir -p $APP_DIR
sudo chown -R $USER:www-data $APP_DIR

# Clone repository
print_status "Cloning repository..."
if [ -d "$APP_DIR/.git" ]; then
    print_status "Repository exists, pulling latest changes..."
    cd $APP_DIR
    git pull origin master
else
    git clone $REPO_URL $APP_DIR
    cd $APP_DIR
fi

# Set proper permissions
print_status "Setting permissions..."
sudo chown -R $USER:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

# Install PHP dependencies
print_status "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
npm ci --production=false

# Create .env file
print_status "Creating .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
    print_success ".env file created from example"
else
    print_warning ".env file already exists, keeping existing configuration"
fi

# Generate application key
print_status "Generating application key..."
php artisan key:generate --force

# Create database and user
print_status "Setting up database..."
mysql -u root -p$MYSQL_ROOT_PASSWORD <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

# Update .env with database configuration
print_status "Updating .env configuration..."
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s/APP_URL=.*/APP_URL=https:\/\/$DOMAIN/" .env

# Add production configurations
cat >> .env << EOF

# Cache Configuration
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Security Headers
SECURE_HEADERS_ENABLED=true
EOF

# Run database migrations and seeders
print_status "Running database migrations..."
php artisan migrate:fresh --seed --force

# Build frontend assets
print_status "Building production assets..."
npm run build

# Clear and cache configurations
print_status "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create Nginx configuration
print_status "Creating Nginx configuration..."
sudo tee /etc/nginx/sites-available/$DOMAIN > /dev/null << EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $APP_DIR/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self'";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Security headers for assets
    location ~* \.(js|css)$ {
        add_header Content-Security-Policy "default-src 'self'";
        expires 1y;
        access_log off;
    }
}
EOF

# Enable site
print_status "Enabling Nginx site..."
sudo ln -sf /etc/nginx/sites-available/$DOMAIN /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test nginx configuration
print_status "Testing Nginx configuration..."
sudo nginx -t

# Create supervisor configuration for queue worker
print_status "Setting up queue worker..."
sudo tee /etc/supervisor/conf.d/ppdb-worker.conf > /dev/null << EOF
[program:ppdb-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_DIR/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/worker.log
stopwaitsecs=3600
EOF

# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update

# Create backup script
print_status "Creating backup script..."
sudo tee /usr/local/bin/ppdb-backup > /dev/null << 'EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/ppdb"
DATE=$(date +%Y%m%d_%H%M%S)
APP_DIR="/var/www/ppdb-backend"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u root -p ppdb_production > $BACKUP_DIR/database_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C /var/www ppdb-backend

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
EOF

sudo chmod +x /usr/local/bin/ppdb-backup

# Add to crontab for daily backup
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/ppdb-backup") | crontab -

# Restart services
print_status "Restarting services..."
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart supervisor

# Install SSL certificate with Certbot
print_status "Installing SSL certificate..."
sudo apt install -y certbot python3-certbot-nginx
print_warning "Run this command to get SSL certificate:"
print_warning "sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN"

print_success "=== Deployment Complete! ==="
print_status "Your application is now available at: http://$DOMAIN"
print_status ""
print_status "Next steps:"
print_status "1. Run: sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN"
print_status "2. Test your application"
print_status "3. Setup monitoring (optional)"
print_status ""
print_status "Important files:"
print_status "- Application: $APP_DIR"
print_status "- Nginx config: /etc/nginx/sites-available/$DOMAIN"
print_status "- Logs: $APP_DIR/storage/logs/"
print_status "- Backup script: /usr/local/bin/ppdb-backup"