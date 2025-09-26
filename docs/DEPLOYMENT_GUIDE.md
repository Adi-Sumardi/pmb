# 🚀 Production Deployment Guide - PPDB YAPI

## 📋 Overview
Panduan lengkap untuk deploy aplikasi PPDB YAPI ke VPS BiznetGio dengan domain yapi-alazhar.id dari Hostinger.

**Target Environment:**
- **VPS Provider:** BiznetGio
- **Domain:** yapi-alazhar.id (Hostinger)
- **Server OS:** Ubuntu 20.04/22.04 LTS
- **Web Server:** Nginx
- **Database:** PostgreSQL 14+
- **Cache:** Redis 6+
- **PHP:** 8.1+

## 🏗️ Server Requirements

### Minimum Specifications
- **CPU:** 2 vCPU
- **RAM:** 4 GB
- **Storage:** 50 GB SSD
- **Bandwidth:** Unlimited

### Recommended Specifications
- **CPU:** 4 vCPU
- **RAM:** 8 GB
- **Storage:** 100 GB SSD
- **Bandwidth:** Unlimited

## 📦 Phase 1: Server Setup & Basic Configuration

### 1.1 Initial Server Setup
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install essential packages
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release

# Create deployment user
sudo adduser deploy
sudo usermod -aG sudo deploy
sudo su - deploy
```

### 1.2 Install PHP 8.1
```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP and extensions
sudo apt install -y php8.1-fpm php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-json php8.1-pgsql php8.1-redis php8.1-intl

# Verify PHP installation
php -v
```

### 1.3 Install Composer
```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verify Composer installation
composer --version
```

### 1.4 Install Node.js & NPM
```bash
# Install Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Verify installation
node -v
npm -v
```

## 🗄️ Phase 2: Database Setup

### 2.1 Install PostgreSQL
```bash
# Install PostgreSQL
sudo apt install -y postgresql postgresql-contrib

# Start and enable PostgreSQL
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Create database and user
sudo -u postgres psql << EOF
CREATE DATABASE ppdb_yapi_production;
CREATE USER ppdb_user WITH ENCRYPTED PASSWORD 'your_secure_password_here';
GRANT ALL PRIVILEGES ON DATABASE ppdb_yapi_production TO ppdb_user;
ALTER USER ppdb_user CREATEDB;
\q
EOF
```

### 2.2 Configure PostgreSQL
```bash
# Edit PostgreSQL configuration
sudo nano /etc/postgresql/14/main/postgresql.conf

# Add/modify these settings:
# listen_addresses = 'localhost'
# max_connections = 100
# shared_buffers = 256MB
# effective_cache_size = 1GB
# work_mem = 4MB
# maintenance_work_mem = 64MB

# Edit pg_hba.conf for authentication
sudo nano /etc/postgresql/14/main/pg_hba.conf

# Add this line for local connections:
# local   ppdb_yapi_production    ppdb_user                     md5

# Restart PostgreSQL
sudo systemctl restart postgresql
```

## 🔴 Phase 3: Redis Setup

### 3.1 Install Redis
```bash
# Install Redis
sudo apt install -y redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf

# Modify these settings:
# maxmemory 256mb
# maxmemory-policy allkeys-lru
# save 900 1
# save 300 10
# save 60 10000

# Start and enable Redis
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Test Redis
redis-cli ping
```

## 🌐 Phase 4: Nginx Setup

### 4.1 Install Nginx
```bash
# Install Nginx
sudo apt install -y nginx

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Test Nginx
sudo systemctl status nginx
```

### 4.2 Configure Nginx for PPDB YAPI
```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/yapi-alazhar.id
```

**Nginx Configuration:**
```nginx
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
        
        # Security
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
```

### 4.3 Enable Site Configuration
```bash
# Enable the site
sudo ln -s /etc/nginx/sites-available/yapi-alazhar.id /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

## 🚀 Phase 5: Application Deployment

### 5.1 Clone Repository
```bash
# Create application directory
sudo mkdir -p /var/www
sudo chown deploy:deploy /var/www

# Clone repository
cd /var/www
git clone https://github.com/Adi-Sumardi/pmb.git ppdb-yapi
cd ppdb-yapi

# Set proper ownership
sudo chown -R deploy:www-data /var/www/ppdb-yapi
sudo chmod -R 755 /var/www/ppdb-yapi
```

### 5.2 Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm ci --only=production

# Build assets
npm run build
```

### 5.3 Environment Configuration
```bash
# Copy production environment file
cp .env.production.example .env.production
cp .env.production .env

# Edit environment variables
nano .env
```

**Environment Configuration (.env):**
```bash
# Application
APP_NAME="PPDB YAPI Al-Azhar"
APP_ENV=production
APP_KEY=base64:your_generated_app_key_here
APP_DEBUG=false
APP_URL=https://yapi-alazhar.id

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ppdb_yapi_production
DB_USERNAME=ppdb_user
DB_PASSWORD=your_secure_password_here

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

# Mail Configuration (gunakan SMTP provider)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yapi-alazhar.id
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yapi-alazhar.id
MAIL_FROM_NAME="${APP_NAME}"

# Xendit Payment Gateway
XENDIT_SECRET_KEY=your_xendit_live_secret_key
XENDIT_WEBHOOK_TOKEN=your_webhook_verification_token

# WhatsApp API
WHATSAPP_API_KEY=your_watzap_api_key
WHATSAPP_NUMBER_KEY=your_watzap_number_key

# File Upload
FILESYSTEM_DISK=public
MAX_UPLOAD_SIZE=10485760

# Security
BCRYPT_ROUNDS=12
```

### 5.4 Generate Application Key & Run Migrations
```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Create symbolic link for storage
php artisan storage:link

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoload files
composer dump-autoload --optimize
```

### 5.5 Set File Permissions
```bash
# Set proper permissions
sudo chown -R deploy:www-data /var/www/ppdb-yapi
sudo chmod -R 755 /var/www/ppdb-yapi
sudo chmod -R 775 /var/www/ppdb-yapi/storage
sudo chmod -R 775 /var/www/ppdb-yapi/bootstrap/cache
```

## 🔒 Phase 6: SSL Certificate (Let's Encrypt)

### 6.1 Install Certbot
```bash
# Install snapd
sudo apt install snapd

# Install certbot
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot
```

### 6.2 Obtain SSL Certificate
```bash
# Get SSL certificate
sudo certbot --nginx -d yapi-alazhar.id -d www.yapi-alazhar.id

# Test auto-renewal
sudo certbot renew --dry-run
```

## 🌍 Phase 7: Domain Configuration (Hostinger)

### 7.1 DNS Configuration di Hostinger
Login ke **Hostinger Control Panel** dan set DNS records:

```
Type    Name    Value                   TTL
A       @       [YOUR_VPS_IP_ADDRESS]   3600
A       www     [YOUR_VPS_IP_ADDRESS]   3600
CNAME   mail    yapi-alazhar.id         3600
```

### 7.2 Verifikasi DNS
```bash
# Test DNS resolution
nslookup yapi-alazhar.id
dig yapi-alazhar.id
```

## ⚙️ Phase 8: Process Management

### 8.1 Setup Supervisor for Queue Workers
```bash
# Install Supervisor
sudo apt install -y supervisor

# Create configuration for Laravel queues
sudo nano /etc/supervisor/conf.d/ppdb-yapi-worker.conf
```

**Supervisor Configuration:**
```ini
[program:ppdb-yapi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ppdb-yapi/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=deploy
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/ppdb-yapi/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Update supervisor configuration
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ppdb-yapi-worker:*
```

### 8.2 Setup Cron Jobs
```bash
# Edit crontab for deploy user
crontab -e

# Add Laravel scheduler
* * * * * cd /var/www/ppdb-yapi && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Phase 9: Monitoring & Maintenance

### 9.1 Setup Log Rotation
```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/ppdb-yapi
```

```
/var/www/ppdb-yapi/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0644 deploy www-data
    postrotate
        php /var/www/ppdb-yapi/artisan config:cache
    endscript
}
```

### 9.2 Performance Monitoring
```bash
# Install htop for system monitoring
sudo apt install -y htop

# Monitor system resources
htop

# Monitor Nginx logs
sudo tail -f /var/log/nginx/yapi-alazhar.id.access.log
sudo tail -f /var/log/nginx/yapi-alazhar.id.error.log

# Monitor application logs
tail -f /var/www/ppdb-yapi/storage/logs/laravel.log
```

## 🔧 Phase 10: Optimization & Security

### 10.1 PHP-FPM Optimization
```bash
# Edit PHP-FPM pool configuration
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

**Key Settings:**
```ini
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 10
pm.max_requests = 500

; Memory limit
php_value[memory_limit] = 256M
php_value[upload_max_filesize] = 10M
php_value[post_max_size] = 10M
```

### 10.2 Firewall Configuration
```bash
# Configure UFW firewall
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

# Check status
sudo ufw status
```

### 10.3 Fail2ban for Security
```bash
# Install fail2ban
sudo apt install -y fail2ban

# Create configuration
sudo nano /etc/fail2ban/jail.local
```

```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true

[nginx-http-auth]
enabled = true

[nginx-botsearch]
enabled = true
```

```bash
# Start fail2ban
sudo systemctl start fail2ban
sudo systemctl enable fail2ban
```

## 🚀 Phase 11: Deployment Automation

### 11.1 Create Deployment Script
```bash
# Create deployment script
nano /var/www/ppdb-yapi/deploy.sh
```

```bash
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

# Clear and cache config
php artisan down --render="maintenance" --secret="deployment-secret"
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
```

```bash
# Make script executable
chmod +x /var/www/ppdb-yapi/deploy.sh
```

## 🧪 Phase 12: Testing & Verification

### 12.1 Health Check
```bash
# Test application health
curl -I https://yapi-alazhar.id/health/check

# Test database connection
php artisan migrate:status

# Test cache
php artisan cache:clear
redis-cli ping

# Test queue workers
php artisan queue:work --once
```

### 12.2 Performance Testing
```bash
# Install Apache Bench for testing
sudo apt install -y apache2-utils

# Test website performance
ab -n 100 -c 10 https://yapi-alazhar.id/

# Test specific endpoints
ab -n 50 -c 5 https://yapi-alazhar.id/health/check
```

## 🔄 Phase 13: Backup Strategy

### 13.1 Database Backup Script
```bash
# Create backup script
nano /home/deploy/backup-db.sh
```

```bash
#!/bin/bash

# Configuration
DB_NAME="ppdb_yapi_production"
DB_USER="ppdb_user"
BACKUP_DIR="/home/deploy/backups"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Create database backup
pg_dump -h localhost -U $DB_USER -d $DB_NAME > $BACKUP_DIR/ppdb_backup_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/ppdb_backup_$DATE.sql

# Keep only last 7 days of backups
find $BACKUP_DIR -name "ppdb_backup_*.sql.gz" -mtime +7 -delete

echo "Database backup completed: ppdb_backup_$DATE.sql.gz"
```

### 13.2 Setup Automated Backups
```bash
# Make backup script executable
chmod +x /home/deploy/backup-db.sh

# Add to crontab (daily backup at 2 AM)
crontab -e

# Add this line:
0 2 * * * /home/deploy/backup-db.sh >> /home/deploy/backup.log 2>&1
```

## 📱 Phase 14: Monitoring & Alerts

### 14.1 Setup Application Monitoring
```bash
# Create monitoring script
nano /home/deploy/monitor-app.sh
```

```bash
#!/bin/bash

# Check if application is running
if ! curl -f -s https://yapi-alazhar.id/health/check > /dev/null; then
    echo "❌ Application is down!" | mail -s "PPDB YAPI - Application Down" admin@yapi-alazhar.id
fi

# Check disk space
DISK_USAGE=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 85 ]; then
    echo "⚠️ Disk usage is ${DISK_USAGE}%" | mail -s "PPDB YAPI - High Disk Usage" admin@yapi-alazhar.id
fi

# Check memory usage
MEMORY_USAGE=$(free | awk 'NR==2{printf "%.2f", $3*100/$2}')
if (( $(echo "$MEMORY_USAGE > 90" | bc -l) )); then
    echo "⚠️ Memory usage is ${MEMORY_USAGE}%" | mail -s "PPDB YAPI - High Memory Usage" admin@yapi-alazhar.id
fi
```

## 🎯 Final Checklist

### ✅ Pre-Launch Verification
- [ ] Domain DNS properly configured
- [ ] SSL certificate installed and auto-renewal setup
- [ ] Database migrations completed
- [ ] Application cache cleared and optimized
- [ ] File permissions set correctly
- [ ] Queue workers running
- [ ] Cron jobs configured
- [ ] Backup system operational
- [ ] Monitoring scripts active
- [ ] Firewall rules configured
- [ ] Fail2ban active

### ✅ Post-Launch Monitoring
- [ ] Application health checks passing
- [ ] Payment gateway working (test with small amount)
- [ ] WhatsApp notifications working
- [ ] Email notifications working
- [ ] File uploads working
- [ ] User registration flow complete
- [ ] Admin panel accessible
- [ ] Performance benchmarks acceptable

## 🆘 Troubleshooting Common Issues

### Issue 1: Application 500 Error
```bash
# Check application logs
tail -f /var/www/ppdb-yapi/storage/logs/laravel.log

# Check Nginx error logs
sudo tail -f /var/log/nginx/yapi-alazhar.id.error.log

# Check PHP-FPM logs
sudo tail -f /var/log/php8.1-fpm.log

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Issue 2: Database Connection Error
```bash
# Test database connection
sudo -u postgres psql -d ppdb_yapi_production -U ppdb_user

# Check PostgreSQL service
sudo systemctl status postgresql

# Check database configuration
php artisan tinker
>>> DB::connection()->getPdo();
```

### Issue 3: Redis Connection Error
```bash
# Test Redis connection
redis-cli ping

# Check Redis service
sudo systemctl status redis-server

# Check Redis logs
sudo tail -f /var/log/redis/redis-server.log
```

### Issue 4: SSL Certificate Issues
```bash
# Check SSL certificate status
sudo certbot certificates

# Renew SSL certificate
sudo certbot renew

# Test SSL configuration
openssl s_client -connect yapi-alazhar.id:443
```

## 📞 Support & Maintenance

### Daily Tasks
- Monitor application logs
- Check system resources (CPU, memory, disk)
- Verify backup completion
- Review security logs

### Weekly Tasks
- Update system packages
- Review application performance
- Check SSL certificate expiry
- Analyze user feedback

### Monthly Tasks
- Database optimization (VACUUM, ANALYZE)
- Security audit
- Performance optimization review
- Backup restoration test

---

## 🎉 Congratulations!

Aplikasi PPDB YAPI Anda sekarang sudah berhasil di-deploy ke production dengan konfigurasi yang optimal dan secure! 

**Access URL:** https://yapi-alazhar.id

Untuk dukungan teknis lebih lanjut, pastikan untuk memantau logs secara rutin dan mengikuti best practices yang telah ditetapkan dalam panduan ini.

**Good luck dengan deployment Anda!** 🚀
