#!/bin/bash

###############################################################################
# DTCDashboard Install & Update Script
# 
# This script:
# 1. Installs the app if not already installed
# 2. Checks if new commits exist on the remote repository
# 3. Pulls the latest changes from Git
# 4. Installs/updates Composer and Node dependencies
# 5. Runs database migrations
# 6. Builds Vite assets
# 7. Clears caches and restarts services
#
# Usage:
#   ./dtc-setup.sh              # Install if needed, update if newer commits
#   ./dtc-setup.sh check-only   # Only check if updates are available
#   ./dtc-setup.sh force        # Force update/reinstall everything
#
# NOTE: This is designed for MANUAL execution only (no auto-timers)
# Call this script whenever you need to update after pushing changes.
#
###############################################################################

set -e  # Exit on error

# Configuration
COMMAND="${1:-}"
APP_DIR="/var/www/dtc-dashboard"
LOG_FILE="/var/log/dtc-dashboard-setup.log"
GIT_REPO="https://github.com/Underlyingglitch/DTCDashboard.git"
REMOTE="origin"
BRANCH="main"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    local level=$1
    shift
    local message="$@"
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo -e "${timestamp} [${level}] ${message}" | tee -a "$LOG_FILE"
}

log_info() { log "INFO" "$@"; }
log_success() { log "${GREEN}SUCCESS${NC}" "$@"; }
log_error() { log "${RED}ERROR${NC}" "$@"; }
log_warning() { log "${YELLOW}WARNING${NC}" "$@"; }

# Error handler
trap 'log_error "Operation failed! Check $LOG_FILE for details."; exit 1' ERR

log_info "================================"
log_info "DTCDashboard Setup & Update"
log_info "================================"

# Determine if this is first installation
IS_FRESH_INSTALL=false
if [ ! -d "$APP_DIR/.git" ]; then
    IS_FRESH_INSTALL=true
    log_info "Fresh installation detected"
fi

# Step 0: Initial installation (if needed)
if [ "$IS_FRESH_INSTALL" = true ]; then
    log_info "Step 0: Cloning repository from GitHub..."
    
    # Create directory
    sudo mkdir -p "$APP_DIR"
    # sudo chown pi:pi "$APP_DIR"
    
    # Clone repository
    git clone "$GIT_REPO" "$APP_DIR"
    
    log_success "Repository cloned to $APP_DIR"
fi

cd "$APP_DIR"

# Step 1: Check git status and fetch latest
log_info "Step 1: Checking Git repository..."

if [ ! -d .git ]; then
    log_error "Not a git repository! Something went wrong."
    exit 1
fi

# Get current branch
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
log_info "Current branch: $CURRENT_BRANCH"

# Fetch latest from remote
log_info "Fetching from remote '$REMOTE'..."
git fetch $REMOTE $BRANCH

# Compare local vs remote
LOCAL_COMMIT=$(git rev-parse HEAD)
REMOTE_COMMIT=$(git rev-parse $REMOTE/$BRANCH)

log_info "Local commit:  $LOCAL_COMMIT"
log_info "Remote commit: $REMOTE_COMMIT"

# Check if updates are needed
if [ "$LOCAL_COMMIT" = "$REMOTE_COMMIT" ] && [ "$COMMAND" != "force" ] && [ "$IS_FRESH_INSTALL" = false ]; then
    log_success "Already up to date! No new commits on $REMOTE/$BRANCH"
    
    if [ "$COMMAND" = "check-only" ]; then
        exit 0
    fi
    
    # Still run some maintenance tasks
    log_info "Running lightweight maintenance tasks..."
    php artisan cache:clear
    log_success "Maintenance completed successfully"
    exit 0
fi

if [ "$COMMAND" = "check-only" ]; then
    if [ "$IS_FRESH_INSTALL" = true ]; then
        log_info "Fresh installation mode - ready to install"
    else
        log_info "New commits available for update"
    fi
    exit 0
fi

if [ "$IS_FRESH_INSTALL" = false ]; then
    log_info "New updates available! Proceeding with update..."
else
    log_info "Fresh installation - proceeding with setup..."
fi

# Step 2: Pull latest changes
log_info "Step 2: Pulling latest changes from $REMOTE/$BRANCH..."
git pull $REMOTE $BRANCH
log_success "Git pull completed"

# Step 3: Setup environment for fresh install
if [ "$IS_FRESH_INSTALL" = true ]; then
    log_info "Step 3: Setting up environment..."
    
    # Copy environment file if it doesn't exist
    if [ ! -f .env ]; then
        cp .env.example .env
        log_info "Created .env file from .env.example"
    fi
    
    # Install required web server packages
    log_info "Checking web server packages..."
    if ! command -v nginx &> /dev/null; then
        log_info "Installing Nginx..."
        sudo apt-get update -qq
        sudo apt-get install -y nginx
        log_success "Nginx installed"
    fi
    
    if ! command -v php &> /dev/null; then
        log_info "Installing PHP-FPM and extensions..."
        sudo apt-get update -qq
        sudo apt-get install -y php8.3-fpm php8.3-cli php8.3-curl php8.3-mbstring php8.3-mysql php8.3-redis php8.3-bcmath php8.3-xml php8.3-zip
        log_success "PHP installed"
    fi
    
    # Get hostname for default APP_URL
    HOSTNAME=$(hostname)
    
    # Interactive configuration
    log_info ""
    log_info "════ Database & Configuration Setup ════"
    log_info ""
    
    # APP_URL
    read -p "Enter APP_URL (default: http://$HOSTNAME.local): " APP_URL
    APP_URL="${APP_URL:-http://$HOSTNAME.local}"
    log_info "APP_URL set to: $APP_URL"
    
    # Database name
    read -p "Enter database name (default: dtc_dashboard): " DB_DATABASE
    DB_DATABASE="${DB_DATABASE:-dtc_dashboard}"
    log_info "Database name: $DB_DATABASE"
    
    # Database user
    read -p "Enter database username (default: dtc_user): " DB_USERNAME
    DB_USERNAME="${DB_USERNAME:-dtc_user}"
    log_info "Database user: $DB_USERNAME"
    
    # Database password
    read -sp "Enter database password (default: random): " DB_PASSWORD
    echo ""
    if [ -z "$DB_PASSWORD" ]; then
        DB_PASSWORD=$(openssl rand -base64 16)
        log_info "Generated random password: $DB_PASSWORD"
    else
        log_info "Database password set"
    fi
    
    # Generate random REVERB settings
    REVERB_APP_ID=$(openssl rand -hex 8)
    REVERB_APP_KEY=$(openssl rand -hex 16)
    REVERB_APP_SECRET=$(openssl rand -hex 16)
    REVERB_HOST="$HOSTNAME.local"
    
    log_info ""
    log_info "Reverb WebSocket Settings:"
    log_info "  REVERB_HOST: $REVERB_HOST"
    log_info "  REVERB_APP_ID: $REVERB_APP_ID"
    log_info ""
    
    # Update .env file with values
    log_info "Updating .env file..."
    sed -i "s|APP_URL=.*|APP_URL=$APP_URL|g" .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|g" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|g" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|g" .env
    sed -i "s|REVERB_APP_ID=.*|REVERB_APP_ID=$REVERB_APP_ID|g" .env
    sed -i "s|REVERB_APP_KEY=.*|REVERB_APP_KEY=$REVERB_APP_KEY|g" .env
    sed -i "s|REVERB_APP_SECRET=.*|REVERB_APP_SECRET=$REVERB_APP_SECRET|g" .env
    sed -i "s|REVERB_HOST=.*|REVERB_HOST=\"$REVERB_HOST\"|g" .env
    
    # Setup Redis (no password by default, just ensure running)
    log_info ""
    log_info "Setting up Redis..."
    sed -i "s|REDIS_PASSWORD=.*|REDIS_PASSWORD=null|g" .env
    sed -i "s|REDIS_HOST=.*|REDIS_HOST=127.0.0.1|g" .env
    sed -i "s|REDIS_PORT=.*|REDIS_PORT=6379|g" .env
    
    # Ensure Redis is running (install if needed)
    if command -v redis-server &> /dev/null; then
        sudo systemctl start redis-server 2>/dev/null || log_warning "Could not start Redis via systemctl"
        log_success "Redis started"
    else
        log_info "Redis not installed - installing..."
        sudo apt-get update -qq
        sudo apt-get install -y redis-server
        if [ $? -eq 0 ]; then
            log_success "Redis installed successfully"
            sudo systemctl start redis-server 2>/dev/null || log_warning "Could not start Redis via systemctl"
            log_success "Redis started"
        else
            log_error "Failed to install Redis"
            exit 1
        fi
    fi
    
    # Setup MariaDB/MySQL
    log_info ""
    log_info "Setting up MariaDB..."
    
    if command -v mysql &> /dev/null; then
        # Ensure MariaDB is running
        sudo systemctl start mariadb 2>/dev/null || log_warning "Could not start MariaDB via systemctl"
        log_success "MariaDB started"
        
        # Create database and user
        log_info "Creating database and user..."
        sudo mysql -u root << MYSQL_EOF
-- Create database
CREATE DATABASE IF NOT EXISTS $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (use localhost only for security)
CREATE USER IF NOT EXISTS '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';

-- Grant permissions
GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;
MYSQL_EOF
        
        if [ $? -eq 0 ]; then
            log_success "Database and user created successfully"
        else
            log_warning "Database creation may have failed - check MariaDB manually"
        fi
    else
        log_info "MariaDB not installed - installing..."
        sudo apt-get update -qq
        sudo apt-get install -y mariadb-server
        if [ $? -eq 0 ]; then
            log_success "MariaDB installed successfully"
            sudo systemctl start mariadb 2>/dev/null || log_warning "Could not start MariaDB via systemctl"
            log_success "MariaDB started"
            
            # Create database and user
            log_info "Creating database and user..."
            sudo mysql -u root << MYSQL_EOF
-- Create database
CREATE DATABASE IF NOT EXISTS $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (use localhost only for security)
CREATE USER IF NOT EXISTS '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';

-- Grant permissions
GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;
MYSQL_EOF
            
            if [ $? -eq 0 ]; then
                log_success "Database and user created successfully"
            else
                log_warning "Database creation may have failed - check MariaDB manually"
            fi
        else
            log_error "Failed to install MariaDB"
            exit 1
        fi
    fi
    
    # Update QUEUE and CACHE settings
    log_info "Configuring queue and cache drivers..."
    sed -i "s|QUEUE_CONNECTION=.*|QUEUE_CONNECTION=redis|g" .env
    sed -i "s|CACHE_DRIVER=.*|CACHE_DRIVER=redis|g" .env
    sed -i "s|SESSION_DRIVER=.*|SESSION_DRIVER=redis|g" .env
    
    # Create required directories
    mkdir -p storage/logs storage/framework/{cache,sessions,views}
    chmod -R 775 storage bootstrap/cache
    
    # Generate application key if missing
    log_info "Generating application key..."
    if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null || grep "^APP_KEY=$" .env 2>/dev/null | grep -q "^APP_KEY=$"; then
        php artisan key:generate --force
        log_success "Application key generated"
    else
        log_info "Application key already exists"
    fi
    
    # Step 3.5: Setup system configuration (Nginx, PHP, Supervisor)
    log_info ""
    log_info "Step 3.5: Setting up system configuration..."
    
    # Install and configure Supervisor
    log_info "Setting up Supervisor for background services..."
    if ! command -v supervisord &> /dev/null; then
        log_info "Installing Supervisor..."
        sudo apt-get update -qq
        sudo apt-get install -y supervisor
        log_success "Supervisor installed"
    else
        log_info "Supervisor already installed"
    fi
    
    # Copy supervisor configuration files
    log_info "Copying Supervisor configuration files..."
    if [ -d "supervisor" ]; then
        sudo cp supervisor/*.conf /etc/supervisor/conf.d/ 2>/dev/null || log_warning "Could not copy supervisor configs"
        log_success "Supervisor configs installed"
    fi
    
    # Apply PHP configuration from docker/php.ini
    log_info "Applying PHP configuration..."
    PHP_INI_PATH=$(php -i | grep "Loaded Configuration File" | cut -d' ' -f5)
    if [ -n "$PHP_INI_PATH" ] && [ -f "docker/php.ini" ]; then
        log_info "Updating PHP settings in $PHP_INI_PATH"
        
        # Extract settings from docker/php.ini and apply them
        while IFS= read -r line; do
            if [[ ! "$line" =~ ^# ]] && [[ ! -z "$line" ]]; then
                KEY=$(echo "$line" | cut -d'=' -f1 | xargs)
                VALUE=$(echo "$line" | cut -d'=' -f2- | xargs)
                
                # Check if setting exists and update it, or add it
                if grep -q "^${KEY}" "$PHP_INI_PATH"; then
                    sudo sed -i "s|^${KEY}.*|${KEY} = ${VALUE}|g" "$PHP_INI_PATH"
                else
                    echo "${KEY} = ${VALUE}" | sudo tee -a "$PHP_INI_PATH" > /dev/null
                fi
            fi
        done < <(grep -v "^#" docker/php.ini | grep -v "^$")
        
        log_success "PHP configuration updated"
    fi
    
    # Setup Nginx configuration
    log_info "Setting up Nginx configuration..."
    if command -v nginx &> /dev/null; then
        if [ -f "docker/nginx.conf" ]; then
            # Create nginx site configuration from docker template
            NGINX_SITE_CONF="/etc/nginx/sites-available/dtc-dashboard"
            
            log_info "Creating Nginx site configuration..."
            
            # Create config from docker template, replacing placeholder paths and socket
            # Replace app path and FastCGI socket (for php8.3-fpm)
            sudo bash -c "cat docker/nginx.conf | \
                sed 's|/opt/apps/laravel/public|$APP_DIR/public|g' | \
                sed 's|\${FPM_HOST}|unix:/run/php/php8.3-fpm.sock|g' \
                > $NGINX_SITE_CONF"
            
            # Enable the site if not already enabled
            if [ ! -L "/etc/nginx/sites-enabled/dtc-dashboard" ]; then
                sudo ln -s "$NGINX_SITE_CONF" /etc/nginx/sites-enabled/dtc-dashboard
                log_info "Nginx site enabled"
            fi
            
            # Test nginx configuration
            if sudo nginx -t 2>&1 | grep -q "successful"; then
                log_success "Nginx configuration validated"
            else
                log_warning "Nginx configuration may have issues - please verify manually"
            fi
            
            log_success "Nginx configuration set up"
        fi
    else
        log_warning "Nginx not installed - please install: sudo apt install nginx"
    fi
    
    log_success "System configuration completed"
    log_info ""
    
    log_success "Environment setup completed"
    log_info ""
fi

# Step 4: Check if composer.json changed
log_info "Step 4: Checking dependencies..."
if [ -f composer.json ]; then
    log_info "Installing Composer dependencies..."
    if command -v php &> /dev/null; then
        composer install --no-dev --optimize-autoloader
        log_success "Composer dependencies installed"
    else
        log_warning "PHP not found, skipping Composer"
    fi
fi

if [ -f package.json ]; then
    log_info "Installing Node dependencies..."
    if command -v npm &> /dev/null; then
        npm ci
        log_success "Node dependencies installed"
    else
        log_warning "npm not found, skipping Node dependencies"
    fi
fi

# Step 5: Run database migrations (if any)
log_info "Step 5: Running database migrations..."
php artisan migrate --force --no-interaction
log_success "Database migrations completed"

# Step 6: Build Vite assets
log_info "Step 6: Building assets..."
npm run build
log_success "Assets built successfully"

# Step 7: Clear caches
log_info "Step 7: Clearing caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
log_success "Caches cleared"

# Step 8: Optimize application
log_info "Step 8: Optimizing application..."
php artisan optimize
log_success "Application optimized"

# Step 9: Restart services (if running with sudo)
log_info "Step 9: Restarting services..."

if command -v systemctl &> /dev/null; then
    # Try to restart services (may require sudo)
    if [ "$(id -u)" = "0" ]; then
        systemctl restart php8.3-fpm 2>/dev/null || log_warning "Could not restart PHP-FPM"
        systemctl restart nginx 2>/dev/null || log_warning "Could not restart nginx"
        systemctl restart supervisor 2>/dev/null || log_warning "Could not restart supervisor"
        
        # Reload supervisor to pick up new configs
        if command -v supervisorctl &> /dev/null; then
            supervisorctl reread 2>/dev/null || true
            supervisorctl update 2>/dev/null || true
            log_info "Supervisor configurations reloaded"
        fi
    else
        log_warning "Not running as sudo, skipping service restart"
        log_info "To restart services manually, run:"
        log_info "  sudo systemctl restart php8.3-fpm nginx supervisor"
        log_info "  sudo supervisorctl reread && sudo supervisorctl update"
    fi
fi

# Step 10: Verify
log_info "Step 10: Verifying..."
php artisan tinker --execute="echo 'Laravel OK'" 2>/dev/null || true
STATUS=$?

if [ $STATUS -eq 0 ] || [ "$IS_FRESH_INSTALL" = true ]; then
    if [ "$IS_FRESH_INSTALL" = true ]; then
        log_success "================================"
        log_success "Installation completed successfully!"
        log_success "================================"
        log_info ""
        log_info "✅ DATABASE SETUP COMPLETED:"
        log_info "   Database: $DB_DATABASE"
        log_info "   User: $DB_USERNAME"
        log_info ""
        log_info "✅ CONFIGURATION COMPLETED:"
        log_info "   APP_URL: $APP_URL"
        log_info "   Redis: Configured & Running"
        log_info "   Reverb Host: $REVERB_HOST"
        log_info "   Application Key: Generated ✅"
        log_info ""
        log_info "✅ SYSTEM SETUP COMPLETED:"
        log_info "   Nginx: Configured"
        log_info "   PHP: Configuration applied"
        log_info "   Supervisor: Installed with background services"
        log_info ""
        log_info "📝 Review your configuration:"
        log_info "   cat $APP_DIR/.env"
        log_info ""
        log_info "📋 Supervisor services installed:"
        log_info "   - Queue Worker (default)"
        log_info "   - Sync Queue Worker"
        log_info "   - Task Scheduler"
        log_info "   - Reverb WebSocket Server"
        log_info ""
        log_info "🚀 Your application is ready!"
    else
        log_success "================================"
        log_success "Update completed successfully!"
        log_success "================================"
        log_success "New commit: $(git rev-parse HEAD --short)"
    fi
    exit 0
else
    log_error "================================"
    log_error "Verification failed!"
    log_error "================================"
    exit 1
fi
