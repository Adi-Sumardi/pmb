#!/bin/bash

# PostgreSQL Setup Script for PPDB YAPI
# This script sets up PostgreSQL database for production use

set -e

echo "🗃️  PPDB YAPI - PostgreSQL Setup Script"
echo "======================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
DB_NAME="ppdb_yapi"
DB_USER="ppdb_user"
DB_PASSWORD=""
POSTGRES_VERSION="15"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to generate secure password
generate_password() {
    openssl rand -base64 32 | tr -d "=+/" | cut -c1-25
}

# Check if PostgreSQL is installed
check_postgresql() {
    print_status "Checking PostgreSQL installation..."

    if ! command -v psql &> /dev/null; then
        print_error "PostgreSQL is not installed. Please install PostgreSQL first."
        echo "For Ubuntu/Debian: sudo apt-get install postgresql postgresql-contrib"
        echo "For CentOS/RHEL: sudo yum install postgresql postgresql-server"
        echo "For macOS: brew install postgresql"
        exit 1
    fi

    print_status "PostgreSQL is installed ✓"
}

# Check if PostgreSQL service is running
check_postgresql_service() {
    print_status "Checking PostgreSQL service status..."

    if ! sudo systemctl is-active --quiet postgresql; then
        print_warning "PostgreSQL service is not running. Starting..."
        sudo systemctl start postgresql
        sudo systemctl enable postgresql
    fi

    print_status "PostgreSQL service is running ✓"
}

# Create database and user
setup_database() {
    print_status "Setting up database and user..."

    # Generate secure password if not provided
    if [ -z "$DB_PASSWORD" ]; then
        DB_PASSWORD=$(generate_password)
        print_status "Generated secure password for database user"
    fi

    # Connect as postgres user and create database/user
    sudo -u postgres psql << EOF
-- Create user if not exists
DO \$\$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_user WHERE usename = '$DB_USER') THEN
        CREATE USER $DB_USER WITH PASSWORD '$DB_PASSWORD';
    END IF;
END
\$\$;

-- Create database if not exists
SELECT 'CREATE DATABASE $DB_NAME OWNER $DB_USER'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '$DB_NAME')\gexec

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;
GRANT ALL ON SCHEMA public TO $DB_USER;

-- Enable required extensions
\c $DB_NAME;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";

EOF

    print_status "Database '$DB_NAME' and user '$DB_USER' created successfully ✓"
}

# Configure PostgreSQL for production
configure_postgresql() {
    print_status "Configuring PostgreSQL for production..."

    # Find PostgreSQL config directory
    PG_VERSION=$(psql --version | awk '{print $3}' | sed 's/\..*//')
    PG_CONFIG_DIR="/etc/postgresql/$PG_VERSION/main"

    if [ ! -d "$PG_CONFIG_DIR" ]; then
        print_warning "PostgreSQL config directory not found at $PG_CONFIG_DIR"
        print_warning "Please manually configure PostgreSQL for production"
        return
    fi

    # Backup original config
    sudo cp "$PG_CONFIG_DIR/postgresql.conf" "$PG_CONFIG_DIR/postgresql.conf.backup"
    sudo cp "$PG_CONFIG_DIR/pg_hba.conf" "$PG_CONFIG_DIR/pg_hba.conf.backup"

    # Configure postgresql.conf for production
    sudo tee -a "$PG_CONFIG_DIR/postgresql.conf" > /dev/null << EOF

# PPDB YAPI Production Configuration
listen_addresses = 'localhost'
max_connections = 100
shared_buffers = 256MB
effective_cache_size = 1GB
work_mem = 4MB
maintenance_work_mem = 64MB
checkpoint_completion_target = 0.9
wal_buffers = 16MB
default_statistics_target = 100
random_page_cost = 1.1
effective_io_concurrency = 200

# Logging
log_destination = 'stderr'
logging_collector = on
log_directory = 'log'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = 1d
log_rotation_size = 100MB
log_min_duration_statement = 1000
log_connections = on
log_disconnections = on
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '

# Security
ssl = on
password_encryption = scram-sha-256

EOF

    # Configure pg_hba.conf for security
    print_status "Configuring authentication..."

    # Restart PostgreSQL to apply changes
    sudo systemctl restart postgresql

    print_status "PostgreSQL configuration updated ✓"
}

# Create database backup script
create_backup_script() {
    print_status "Creating database backup script..."

    cat > /tmp/ppdb_backup.sh << EOF
#!/bin/bash

# PPDB Database Backup Script
DATE=\$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/var/backups/ppdb"
DB_NAME="$DB_NAME"
DB_USER="$DB_USER"

# Create backup directory if it doesn't exist
mkdir -p \$BACKUP_DIR

# Create backup
pg_dump -h localhost -U \$DB_USER -d \$DB_NAME > "\$BACKUP_DIR/ppdb_backup_\$DATE.sql"

# Compress backup
gzip "\$BACKUP_DIR/ppdb_backup_\$DATE.sql"

# Remove backups older than 30 days
find \$BACKUP_DIR -name "ppdb_backup_*.sql.gz" -mtime +30 -delete

echo "Backup completed: ppdb_backup_\$DATE.sql.gz"
EOF

    sudo mv /tmp/ppdb_backup.sh /usr/local/bin/ppdb_backup.sh
    sudo chmod +x /usr/local/bin/ppdb_backup.sh

    print_status "Backup script created at /usr/local/bin/ppdb_backup.sh ✓"
}

# Setup monitoring
setup_monitoring() {
    print_status "Setting up basic monitoring..."

    # Create monitoring script
    cat > /tmp/ppdb_monitor.sh << EOF
#!/bin/bash

# PPDB Database Monitoring Script
DB_NAME="$DB_NAME"
DB_USER="$DB_USER"

echo "PPDB Database Status - \$(date)"
echo "================================"

# Check database connectivity
if psql -h localhost -U \$DB_USER -d \$DB_NAME -c "SELECT 1;" >/dev/null 2>&1; then
    echo "✓ Database connection: OK"
else
    echo "✗ Database connection: FAILED"
fi

# Check database size
DB_SIZE=\$(psql -h localhost -U \$DB_USER -d \$DB_NAME -t -c "SELECT pg_size_pretty(pg_database_size('\$DB_NAME'));" | tr -d ' ')
echo "📊 Database size: \$DB_SIZE"

# Check active connections
CONNECTIONS=\$(psql -h localhost -U \$DB_USER -d \$DB_NAME -t -c "SELECT count(*) FROM pg_stat_activity WHERE datname='\$DB_NAME';" | tr -d ' ')
echo "🔗 Active connections: \$CONNECTIONS"

# Check long running queries
LONG_QUERIES=\$(psql -h localhost -U \$DB_USER -d \$DB_NAME -t -c "SELECT count(*) FROM pg_stat_activity WHERE state = 'active' AND query_start < now() - interval '5 minutes';" | tr -d ' ')
echo "⏰ Long running queries (>5min): \$LONG_QUERIES"

EOF

    sudo mv /tmp/ppdb_monitor.sh /usr/local/bin/ppdb_monitor.sh
    sudo chmod +x /usr/local/bin/ppdb_monitor.sh

    print_status "Monitoring script created at /usr/local/bin/ppdb_monitor.sh ✓"
}

# Main execution
main() {
    echo
    print_status "Starting PostgreSQL setup for PPDB YAPI..."
    echo

    check_postgresql
    check_postgresql_service
    setup_database
    configure_postgresql
    create_backup_script
    setup_monitoring

    echo
    print_status "✅ PostgreSQL setup completed successfully!"
    echo
    echo "📋 Database Information:"
    echo "   Database Name: $DB_NAME"
    echo "   Database User: $DB_USER"
    echo "   Database Password: $DB_PASSWORD"
    echo
    echo "🔧 Next Steps:"
    echo "   1. Update your .env file with the database credentials"
    echo "   2. Run: php artisan migrate"
    echo "   3. Run: php artisan db:seed (if needed)"
    echo "   4. Test database connection: /usr/local/bin/ppdb_monitor.sh"
    echo "   5. Setup cron job for backups: 0 2 * * * /usr/local/bin/ppdb_backup.sh"
    echo
    print_warning "⚠️  Please save the database password securely!"
    echo
}

# Run main function
main "$@"
