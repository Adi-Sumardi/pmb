#!/bin/bash

# PPDB Backend - VPS Setup Script
# Script untuk setup environment dari awal di VPS Ubuntu 22.04/24.04

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
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

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   print_error "Jangan jalankan script ini sebagai root! Gunakan user biasa dengan sudo access."
   exit 1
fi

print_status "=== PPDB Backend VPS Setup ==="
print_status "Setup akan dimulai untuk environment production..."

# Update system
print_status "Updating sistem..."
sudo apt update && sudo apt upgrade -y

# Install basic packages
print_status "Installing basic packages..."
sudo apt install -y software-properties-common apt-transport-https ca-certificates \
    gnupg lsb-release curl wget git unzip zip htop nano vim \
    build-essential supervisor redis-server

# Add PHP repository
print_status "Adding PHP 8.2 repository..."
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 and extensions
print_status "Installing PHP 8.2 dan extensions..."
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-pgsql \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
    php8.2-bcmath php8.2-json php8.2-intl php8.2-soap php8.2-redis \
    php8.2-sqlite3 php8.2-xdebug

# Install Composer
print_status "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js dan npm
print_status "Installing Node.js 20.x..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install nginx
print_status "Installing Nginx..."
sudo apt install -y nginx

# Install PostgreSQL
print_status "Installing PostgreSQL Server..."
sudo apt install -y postgresql postgresql-contrib
print_warning "PostgreSQL akan dikonfigurasi secara otomatis!"

# Configure PHP-FPM
print_status "Configuring PHP-FPM..."
sudo sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 64M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/post_max_size = 8M/post_max_size = 64M/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/max_execution_time = 30/max_execution_time = 300/' /etc/php/8.2/fpm/php.ini
sudo sed -i 's/memory_limit = 128M/memory_limit = 512M/' /etc/php/8.2/fpm/php.ini

# Start services
print_status "Starting services..."
sudo systemctl enable nginx
sudo systemctl enable php8.2-fpm
sudo systemctl enable postgresql
sudo systemctl enable redis-server
sudo systemctl enable supervisor

sudo systemctl start nginx
sudo systemctl start php8.2-fpm
sudo systemctl start postgresql
sudo systemctl start redis-server
sudo systemctl start supervisor

# Configure firewall
print_status "Configuring UFW firewall..."
sudo ufw --force enable
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw allow 5432  # PostgreSQL
sudo ufw allow 6379  # Redis

print_success "=== VPS Environment Setup Complete! ==="
print_status "Next steps:"
print_status "1. PostgreSQL is ready to use"
print_status "2. Create database and user for the application"
print_status "3. Clone your repository"
print_status "4. Run the deployment script"

print_status "Installed versions:"
php --version | head -n 1
composer --version
node --version
npm --version
nginx -v
sudo -u postgres psql --version
