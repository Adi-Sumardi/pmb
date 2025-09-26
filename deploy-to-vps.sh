#!/bin/bash

# =============================================================================
# PPDB YAPI - VPS BiznetGio Deployment Script
# =============================================================================
# Quick deployment script untuk aplikasi PPDB YAPI
# Target: VPS BiznetGio (103.129.149.246) dengan domain yapi-alazhar.id
# SSH: ppdb26@103.129.149.246
#
# Usage: chmod +x deploy-to-vps.sh && ./deploy-to-vps.sh
# =============================================================================set -e  # Exit on any error

echo "🚀 PPDB YAPI - VPS Deployment Script"
echo "===================================="
echo ""

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="yapi-alazhar.id"
APP_PATH="/var/www/ppdb-yapi"
DB_NAME="ppdb_yapi_production"
DB_USER="ppdb_user"
DEPLOY_USER="deploy"

# Functions
print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
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

check_root() {
    if [[ $EUID -eq 0 ]]; then
        print_error "This script should not be run as root. Please run as regular user with sudo access."
        exit 1
    fi
}

check_os() {
    if [[ ! -f /etc/lsb-release ]] || ! grep -q "Ubuntu" /etc/lsb-release; then
        print_error "This script is designed for Ubuntu. Please check your OS."
        exit 1
    fi
    print_success "Ubuntu OS detected"
}

install_dependencies() {
    print_step "Installing system dependencies..."

    # Update system
    sudo apt update && sudo apt upgrade -y

    # Install essential packages
    sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release

    print_success "System dependencies installed"
}

install_php() {
    print_step "Installing PHP 8.1 and extensions..."

    # Add PHP repository
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt update

    # Install PHP and required extensions
    sudo apt install -y php8.1-fpm php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-json php8.1-pgsql php8.1-redis php8.1-intl

    # Verify PHP installation
    php -v
    print_success "PHP 8.1 installed successfully"
}

install_composer() {
    print_step "Installing Composer..."

    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer

    composer --version
    print_success "Composer installed successfully"
}

install_nodejs() {
    print_step "Installing Node.js and NPM..."

    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt install -y nodejs

    node -v
    npm -v
    print_success "Node.js installed successfully"
}

install_postgresql() {
    print_step "Installing and configuring PostgreSQL..."

    sudo apt install -y postgresql postgresql-contrib
    sudo systemctl start postgresql
    sudo systemctl enable postgresql

    # Generate random password
    DB_PASSWORD=$(openssl rand -base64 32)

    # Create database and user
    sudo -u postgres psql << EOF
CREATE DATABASE $DB_NAME;
CREATE USER $DB_USER WITH ENCRYPTED PASSWORD '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;
ALTER USER $DB_USER CREATEDB;
\q
EOF

    print_success "PostgreSQL installed and configured"
    print_warning "Database password: $DB_PASSWORD"
    print_warning "Please save this password securely!"

    # Save password to file for later use
    echo "DB_PASSWORD=$DB_PASSWORD" > ~/.ppdb_db_credentials
    chmod 600 ~/.ppdb_db_credentials
}

install_redis() {
    print_step "Installing and configuring Redis..."

    sudo apt install -y redis-server

    # Configure Redis
    sudo sed -i 's/# maxmemory <bytes>/maxmemory 256mb/' /etc/redis/redis.conf
    sudo sed -i 's/# maxmemory-policy noeviction/maxmemory-policy allkeys-lru/' /etc/redis/redis.conf

    sudo systemctl start redis-server
    sudo systemctl enable redis-server

    # Test Redis
    redis-cli ping
    print_success "Redis installed and configured"
}

install_nginx() {
    print_step "Installing and configuring Nginx..."

    sudo apt install -y nginx
    sudo systemctl start nginx
    sudo systemctl enable nginx

    # Create Nginx configuration
    sudo tee /etc/nginx/sites-available/$DOMAIN > /dev/null << 'EOF'
server {
    listen 80;
    server_name yapi-alazhar.id www.yapi-alazhar.id;
    root /var/www/ppdb-yapi/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }

    # Prevent access to storage and bootstrap/cache
    location ~* /(storage|bootstrap\/cache) {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # File upload size
    client_max_body_size 10M;

    # Logs
    access_log /var/log/nginx/yapi-alazhar.id.access.log;
    error_log /var/log/nginx/yapi-alazhar.id.error.log;
}
EOF

    # Enable site
    sudo ln -sf /etc/nginx/sites-available/$DOMAIN /etc/nginx/sites-enabled/
    sudo rm -f /etc/nginx/sites-enabled/default

    # Test and reload Nginx
    sudo nginx -t
    sudo systemctl reload nginx

    print_success "Nginx installed and configured"
}

deploy_application() {
    print_step "Deploying PPDB YAPI application..."

    # Create application directory
    sudo mkdir -p /var/www
    sudo chown $USER:$USER /var/www

    # Clone repository
    cd /var/www
    if [[ -d "ppdb-yapi" ]]; then
        print_warning "Application directory exists, pulling latest changes..."
        cd ppdb-yapi
        git pull origin master
    else
        print_step "Cloning repository..."
        git clone https://github.com/Adi-Sumardi/pmb.git ppdb-yapi
        cd ppdb-yapi
    fi

    # Install dependencies
    print_step "Installing PHP dependencies..."
    composer install --optimize-autoloader --no-dev

    print_step "Installing Node.js dependencies..."
    npm ci --only=production

    print_step "Building assets..."
    npm run build

    print_success "Application deployed successfully"
}

configure_environment() {
    print_step "Configuring application environment..."

    cd $APP_PATH

    # Load database credentials
    if [[ -f ~/.ppdb_db_credentials ]]; then
        source ~/.ppdb_db_credentials
    else
        print_error "Database credentials not found!"
        exit 1
    fi

    # Copy environment file
    if [[ -f .env.production.example ]]; then
        cp .env.production.example .env
    else
        cp .env.example .env
    fi

    # Generate application key
    php artisan key:generate --force

    # Get the generated key for replacement
    APP_KEY=$(grep "APP_KEY=" .env | cut -d '=' -f2)

    # Update environment configuration
    cat > .env << EOF
# Application
APP_NAME="PPDB YAPI Al-Azhar"
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=false
APP_URL=https://$DOMAIN

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASSWORD

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2
REDIS_QUEUE_DB=3

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@$DOMAIN
MAIL_PASSWORD=your_email_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@$DOMAIN
MAIL_FROM_NAME="\${APP_NAME}"

# Payment Gateway (Xendit)
XENDIT_SECRET_KEY=xnd_production_izBEiajnupjv22yr8Kf0lZlXMcO0XJvM5LxLadP5qTew7whLqYVBFGph1lsSZn
XENDIT_WEBHOOK_TOKEN=Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te

# WhatsApp API
WHATSAPP_API_KEY=SGIKDRJTT0MDQRVX
WHATSAPP_NUMBER_KEY=f49e10YYE2Gee1hb

# File Upload
FILESYSTEM_DISK=public
MAX_UPLOAD_SIZE=10485760

# Security
BCRYPT_ROUNDS=12
EOF

    print_success "Environment configured"
    print_success "WhatsApp API credentials: CONFIGURED ✅"
    print_warning "Please update the following in .env file:"
    print_warning "- MAIL_PASSWORD (email password)"
    print_warning "- XENDIT_SECRET_KEY (Xendit live key - akan diberikan kemudian)"
    print_warning "- XENDIT_WEBHOOK_TOKEN (webhook token)"
}

setup_application() {
    print_step "Setting up application..."

    cd $APP_PATH

    # Run migrations
    php artisan migrate --force

    # Create storage link
    php artisan storage:link

    # Cache configurations
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Optimize autoloader
    composer dump-autoload --optimize

    # Set permissions
    sudo chown -R $USER:www-data $APP_PATH
    sudo chmod -R 755 $APP_PATH
    sudo chmod -R 775 $APP_PATH/storage
    sudo chmod -R 775 $APP_PATH/bootstrap/cache

    print_success "Application setup completed"
}

install_ssl() {
    print_step "Installing SSL certificate with Let's Encrypt..."

    # Install snapd and certbot
    sudo apt install -y snapd
    sudo snap install --classic certbot
    sudo ln -sf /snap/bin/certbot /usr/bin/certbot

    print_warning "About to request SSL certificate for $DOMAIN"
    print_warning "Make sure your domain DNS is properly configured and pointing to this server!"

    read -p "Continue with SSL installation? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email admin@$DOMAIN

        # Test auto-renewal
        sudo certbot renew --dry-run

        print_success "SSL certificate installed successfully"
    else
        print_warning "SSL installation skipped. You can run 'sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN' later."
    fi
}

setup_process_management() {
    print_step "Setting up process management (Supervisor)..."

    sudo apt install -y supervisor

    # Create supervisor configuration for queue workers
    sudo tee /etc/supervisor/conf.d/ppdb-yapi-worker.conf > /dev/null << EOF
[program:ppdb-yapi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_PATH/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$USER
numprocs=2
redirect_stderr=true
stdout_logfile=$APP_PATH/storage/logs/worker.log
stopwaitsecs=3600
EOF

    # Update supervisor
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start ppdb-yapi-worker:*

    print_success "Process management configured"
}

setup_cron() {
    print_step "Setting up Laravel scheduler..."

    # Add cron job for Laravel scheduler
    (crontab -l 2>/dev/null || true; echo "* * * * * cd $APP_PATH && php artisan schedule:run >> /dev/null 2>&1") | crontab -

    print_success "Laravel scheduler configured"
}

setup_firewall() {
    print_step "Configuring firewall..."

    # Reset firewall
    sudo ufw --force reset
    sudo ufw default deny incoming
    sudo ufw default allow outgoing

    # Allow SSH
    sudo ufw allow ssh

    # Allow HTTP and HTTPS
    sudo ufw allow 80/tcp
    sudo ufw allow 443/tcp

    # Enable firewall
    sudo ufw --force enable

    print_success "Firewall configured"
}

create_deployment_script() {
    print_step "Creating deployment script..."

    cat > $APP_PATH/deploy.sh << 'EOF'
#!/bin/bash

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /var/www/ppdb-yapi

# Pull latest changes
git pull origin master

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm ci --only=production

# Build assets
npm run build

# Put application in maintenance mode
php artisan down --render="maintenance" --secret="deployment-secret"

# Clear and cache config
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Restart services
sudo supervisorctl restart ppdb-yapi-worker:*
sudo systemctl reload php8.1-fpm
sudo systemctl reload nginx

# Bring application back up
php artisan up

echo "✅ Deployment completed successfully!"
EOF

    chmod +x $APP_PATH/deploy.sh

    print_success "Deployment script created at $APP_PATH/deploy.sh"
}

create_backup_script() {
    print_step "Creating backup script..."

    mkdir -p /home/$USER/backups

    cat > /home/$USER/backup-db.sh << EOF
#!/bin/bash

# Configuration
DB_NAME="$DB_NAME"
DB_USER="$DB_USER"
BACKUP_DIR="/home/$USER/backups"
DATE=\$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p \$BACKUP_DIR

# Create database backup
pg_dump -h localhost -U \$DB_USER -d \$DB_NAME > \$BACKUP_DIR/ppdb_backup_\$DATE.sql

# Compress backup
gzip \$BACKUP_DIR/ppdb_backup_\$DATE.sql

# Keep only last 7 days of backups
find \$BACKUP_DIR -name "ppdb_backup_*.sql.gz" -mtime +7 -delete

echo "Database backup completed: ppdb_backup_\$DATE.sql.gz"
EOF

    chmod +x /home/$USER/backup-db.sh

    # Add to crontab (daily backup at 2 AM)
    (crontab -l 2>/dev/null || true; echo "0 2 * * * /home/$USER/backup-db.sh >> /home/$USER/backup.log 2>&1") | crontab -

    print_success "Backup script created and scheduled"
}

final_checks() {
    print_step "Running final checks..."

    # Check services
    if systemctl is-active --quiet nginx; then
        print_success "Nginx is running"
    else
        print_error "Nginx is not running"
    fi

    if systemctl is-active --quiet postgresql; then
        print_success "PostgreSQL is running"
    else
        print_error "PostgreSQL is not running"
    fi

    if systemctl is-active --quiet redis-server; then
        print_success "Redis is running"
    else
        print_error "Redis is not running"
    fi

    if systemctl is-active --quiet php8.1-fpm; then
        print_success "PHP-FPM is running"
    else
        print_error "PHP-FPM is not running"
    fi

    # Check application
    cd $APP_PATH
    if php artisan --version > /dev/null 2>&1; then
        print_success "Laravel application is accessible"
    else
        print_error "Laravel application has issues"
    fi

    print_success "Final checks completed"
}

display_summary() {
    echo ""
    echo "🎉 ==============================================="
    echo "   PPDB YAPI DEPLOYMENT COMPLETED SUCCESSFULLY!"
    echo "==============================================="
    echo ""
    echo "📋 Deployment Summary:"
    echo "   • Domain: https://$DOMAIN"
    echo "   • VPS IP: 103.129.149.246"
    echo "   • SSH Access: ssh ppdb26@103.129.149.246"
    echo "   • Application Path: $APP_PATH"
    echo "   • Database: $DB_NAME"
    echo "   • SSL: $(if [[ -f /etc/letsencrypt/live/$DOMAIN/fullchain.pem ]]; then echo "Enabled"; else echo "Not installed"; fi)"
    echo ""
    echo "✅ API Credentials (CONFIGURED):"
    echo "   • WhatsApp API: SGIKDRJTT0MDQRVX"
    echo "   • WhatsApp Number: f49e10YYE2Gee1hb"
    echo ""
    echo "🔧 Next Steps:"
    echo "   1. Update environment variables in: $APP_PATH/.env"
    echo "      - MAIL_PASSWORD (email password)"
    echo "      - XENDIT_SECRET_KEY (akan diberikan kemudian)"
    echo "      - XENDIT_WEBHOOK_TOKEN (webhook token)"
    echo ""
    echo "   2. Test your application: https://$DOMAIN"
    echo ""
    echo "   3. Configure DNS at Hostinger:"
    echo "      A     @     103.129.149.246"
    echo "      A     www   103.129.149.246"
    echo ""
    echo "🛠️  Useful Commands:"
    echo "   • Deploy updates: $APP_PATH/deploy.sh"
    echo "   • Backup database: /home/$USER/backup-db.sh"
    echo "   • Check logs: tail -f $APP_PATH/storage/logs/laravel.log"
    echo "   • Check workers: sudo supervisorctl status"
    echo ""
    echo "📞 Need help? Check the full documentation at:"
    echo "   $APP_PATH/docs/DEPLOYMENT_GUIDE.md"
    echo ""
    print_success "Happy deploying! 🚀"
}

# Main execution
main() {
    echo "Starting automated deployment for PPDB YAPI..."
    echo "Target Domain: $DOMAIN"
    echo ""

    check_root
    check_os

    # Ask for confirmation
    read -p "Continue with installation? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Installation cancelled."
        exit 1
    fi

    # Run installation steps
    install_dependencies
    install_php
    install_composer
    install_nodejs
    install_postgresql
    install_redis
    install_nginx
    deploy_application
    configure_environment
    setup_application
    install_ssl
    setup_process_management
    setup_cron
    setup_firewall
    create_deployment_script
    create_backup_script
    final_checks
    display_summary
}

# Run main function
main "$@"
