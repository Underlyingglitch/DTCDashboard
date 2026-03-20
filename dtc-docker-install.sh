#!/bin/bash

###############################################################################
# DTCDashboard Docker Installer
# 
# Downloads and runs DTCDashboard using Docker Compose
#
# Usage:
#   curl -fsSL https://raw.githubusercontent.com/Underlyingglitch/DTCDashboard/main/dtc-docker-install.sh | bash
#
# This script:
# 1. Checks for Docker and Docker Compose
# 2. Downloads docker-compose.yml and .env.example
# 3. Prompts for configuration
# 4. Sets up .env and starts services
#
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

REPO_OWNER="Underlyingglitch"
REPO_NAME="DTCDashboard"
BRANCH="main"
APP_DIR="${1:-.}/dtc-dashboard"

echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}DTCDashboard Docker Installer${NC}"
echo -e "${BLUE}================================${NC}"
echo ""

# Check for Docker
echo -e "${YELLOW}Checking prerequisites...${NC}"

if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed${NC}"
    read -p "Would you like to install Docker and Docker Compose now? (y/n) " -n 1 -r
    echo ""
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${YELLOW}Installing Docker...${NC}"
        curl -fsSL https://get.docker.com | bash
        # Add current user to docker group
        sudo usermod -aG docker "$USER"
        echo -e "${GREEN}✓ Docker installed${NC}"
        echo "Note: You may need to run 'newgrp docker' or log out/in for group changes to take effect"
    else
        echo "To install Docker manually, visit https://docs.docker.com/install/"
        exit 1
    fi
fi

# Check for Docker Compose plugin (docker compose) or standalone (docker-compose)
if ! docker compose version &> /dev/null && ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose is not installed${NC}"
    echo "Install Docker Desktop or Docker Compose plugin: https://docs.docker.com/compose/install/"
    exit 1
fi

echo -e "${GREEN}✓ Docker installed${NC}"
echo -e "${GREEN}✓ Docker Compose installed${NC}"
echo ""

NEEDS_AUTH="Y"
if [[ $NEEDS_AUTH =~ ^[Yy]$ ]]; then
    REGISTRY_URL="registry.rickokkersen.nl"
    read -p "Enter username: " REGISTRY_USER
    read -sp "Enter password: " REGISTRY_PASS
    echo ""
    
    echo -e "${YELLOW}Logging in to Docker registry...${NC}"
    
    # Attempt docker login
    if [ -z "$REGISTRY_URL" ]; then
        # Docker Hub login
        echo "$REGISTRY_PASS" | docker login -u "$REGISTRY_USER" --password-stdin
    else
        # Private registry login
        echo "$REGISTRY_PASS" | docker login -u "$REGISTRY_USER" --password-stdin "$REGISTRY_URL"
    fi
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Successfully authenticated with Docker registry${NC}"
    else
        echo -e "${RED}❌ Docker registry authentication failed${NC}"
        read -p "Continue anyway? (y/n): " CONTINUE
        if [[ ! $CONTINUE =~ ^[Yy]$ ]]; then
            exit 1
        fi
    fi
    echo ""
fi

# Create app directory
echo -e "${YELLOW}Setting up application directory...${NC}"
mkdir -p "$APP_DIR"
cd "$APP_DIR"

# Determine docker compose command (plugin or standalone)
if docker compose version &> /dev/null; then
    DOCKER_COMPOSE="docker compose"
else
    DOCKER_COMPOSE="docker-compose"
fi

# Helper function to safely update .env values
update_env() {
    local key=$1
    local value=$2
    local file=.env
    
    # Escape special characters in value for sed
    value=$(printf '%s\n' "$value" | sed -e 's/[\/&]/\\&/g')
    
    # Update the key with the escaped value
    if grep -q "^${key}=" "$file"; then
        sed -i "s|^${key}=.*|${key}=${value}|" "$file"
    else
        echo "${key}=${value}" >> "$file"
    fi
}

# Download docker-compose.yml
echo -e "${YELLOW}Downloading docker-compose.yml...${NC}"
curl -fsSL "https://raw.githubusercontent.com/$REPO_OWNER/$REPO_NAME/$BRANCH/docker-compose.yml" -o docker-compose.yml
echo -e "${GREEN}✓ docker-compose.yml downloaded${NC}"

# Download .env template
if [ ! -f .env.example ]; then
    echo -e "${YELLOW}Downloading .env.example...${NC}"
    curl -fsSL "https://raw.githubusercontent.com/$REPO_OWNER/$REPO_NAME/$BRANCH/.env.example" -o .env.example
    echo -e "${GREEN}✓ .env.example downloaded${NC}"
fi

# Create .env if it doesn't exist
if [ ! -f .env ]; then
    echo ""
    echo -e "${YELLOW}════ Application Configuration ════${NC}"
    echo ""
    
    cp .env.example .env
    
    # Get hostname
    HOSTNAME=$(hostname)
    
    # APP_NAME
    read -p "Enter application name (default: DTCDashboard): " APP_NAME
    APP_NAME="${APP_NAME:-DTCDashboard}"
    update_env "APP_NAME" "$APP_NAME"
    
    # APP_URL
    read -p "Enter APP_URL (default: http://$HOSTNAME.local): " APP_URL
    APP_URL="${APP_URL:-http://$HOSTNAME.local}"
    update_env "APP_URL" "$APP_URL"
    
    # Database name
    read -p "Enter database name (default: dtc_dashboard): " DB_DATABASE
    DB_DATABASE="${DB_DATABASE:-dtc_dashboard}"
    update_env "DB_DATABASE" "$DB_DATABASE"
    
    # Database user
    read -p "Enter database username (default: dtc_user): " DB_USERNAME
    DB_USERNAME="${DB_USERNAME:-dtc_user}"
    update_env "DB_USERNAME" "$DB_USERNAME"
    
    # Database password
    read -sp "Enter database password (default: random): " DB_PASSWORD
    echo ""
    if [ -z "$DB_PASSWORD" ]; then
        DB_PASSWORD=$(openssl rand -base64 16)
        echo -e "${BLUE}Generated random password${NC}"
    fi
    update_env "DB_PASSWORD" "$DB_PASSWORD"
    
    # Debug mode
    read -p "Enable debug mode? (y/n, default: n): " DEBUG_MODE
    if [[ $DEBUG_MODE =~ ^[Yy]$ ]]; then
        update_env "APP_DEBUG" "true"
        update_env "LOG_LEVEL" "debug"
    else
        update_env "APP_DEBUG" "false"
        update_env "LOG_LEVEL" "warning"
    fi
    
    # Generate REVERB settings
    REVERB_APP_ID=$(openssl rand -hex 8)
    REVERB_APP_KEY=$(openssl rand -hex 16)
    REVERB_APP_SECRET=$(openssl rand -hex 16)
    REVERB_HOST="$HOSTNAME.local"
    
    update_env "REVERB_APP_ID" "$REVERB_APP_ID"
    update_env "REVERB_APP_KEY" "$REVERB_APP_KEY"
    update_env "REVERB_APP_SECRET" "$REVERB_APP_SECRET"
    update_env "REVERB_HOST" "$REVERB_HOST"
    
    # Configure MQTT settings
    MQTT_USERNAME="${DB_USERNAME:-mosquitto}"
    MQTT_PASSWORD=$(openssl rand -base64 12)
    
    update_env "MQTT_HOST" "mqtt"
    update_env "MQTT_PORT" "1883"
    update_env "MQTT_USERNAME" "$MQTT_USERNAME"
    update_env "MQTT_PASSWORD" "$MQTT_PASSWORD"
    update_env "MQTT_PROTOCOL" "mqtt"
    
    # Configure Docker-specific settings
    update_env "DB_HOST" "mariadb"
    update_env "REDIS_HOST" "redis"
    update_env "CACHE_DRIVER" "redis"
    update_env "QUEUE_CONNECTION" "redis"
    update_env "SESSION_DRIVER" "redis"
    update_env "BROADCAST_DRIVER" "reverb"
    update_env "FPM_HOST" "php:9000"
    
    echo ""
    echo -e "${GREEN}✓ Configuration saved to .env${NC}"
else
    echo -e "${BLUE}ℹ .env file already exists${NC}"
fi

# Start Docker containers
echo ""
echo -e "${YELLOW}Starting Docker containers...${NC}"
$DOCKER_COMPOSE up -d

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}================================${NC}"
    echo -e "${GREEN}Setup completed successfully!${NC}"
    echo -e "${GREEN}================================${NC}"
    echo ""
    echo -e "${BLUE}Application Details:${NC}"
    echo "  Directory: $APP_DIR"
    echo "  URL: $APP_URL"
    echo "  Database: $DB_DATABASE"
    echo ""
    echo -e "${BLUE}Useful Commands:${NC}"
    echo "  View logs:     $DOCKER_COMPOSE logs -f"
    echo "  Stop services: $DOCKER_COMPOSE down"
    echo "  Restart:       $DOCKER_COMPOSE restart"
    echo "  Update app:    $DOCKER_COMPOSE pull && $DOCKER_COMPOSE up -d"
    echo ""
    echo -e "${GREEN}🚀 Your application is running!${NC}"
else
    echo -e "${RED}❌ Failed to start Docker containers${NC}"
    exit 1
fi
