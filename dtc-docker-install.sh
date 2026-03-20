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

if ! command -v docker-compose &> /dev/null; then
    echo -e "${YELLOW}Installing Docker Compose...${NC}"
    sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    echo -e "${GREEN}✓ Docker Compose installed${NC}"
fi

echo -e "${GREEN}✓ Docker installed${NC}"
echo -e "${GREEN}✓ Docker Compose installed${NC}"
echo ""

# Create app directory
echo -e "${YELLOW}Setting up application directory...${NC}"
mkdir -p "$APP_DIR"
cd "$APP_DIR"

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
    sed -i "s|APP_NAME=.*|APP_NAME=$APP_NAME|g" .env
    
    # APP_URL
    read -p "Enter APP_URL (default: http://$HOSTNAME.local): " APP_URL
    APP_URL="${APP_URL:-http://$HOSTNAME.local}"
    sed -i "s|APP_URL=.*|APP_URL=$APP_URL|g" .env
    
    # Database name
    read -p "Enter database name (default: dtc_dashboard): " DB_DATABASE
    DB_DATABASE="${DB_DATABASE:-dtc_dashboard}"
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|g" .env
    
    # Database user
    read -p "Enter database username (default: dtc_user): " DB_USERNAME
    DB_USERNAME="${DB_USERNAME:-dtc_user}"
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|g" .env
    
    # Database password
    read -sp "Enter database password (default: random): " DB_PASSWORD
    echo ""
    if [ -z "$DB_PASSWORD" ]; then
        DB_PASSWORD=$(openssl rand -base64 16)
        echo -e "${BLUE}Generated random password${NC}"
    fi
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|g" .env
    
    # Debug mode
    read -p "Enable debug mode? (y/n, default: n): " DEBUG_MODE
    if [[ $DEBUG_MODE =~ ^[Yy]$ ]]; then
        sed -i "s|APP_DEBUG=.*|APP_DEBUG=true|g" .env
        sed -i "s|LOG_LEVEL=.*|LOG_LEVEL=debug|g" .env
    else
        sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|g" .env
        sed -i "s|LOG_LEVEL=.*|LOG_LEVEL=warning|g" .env
    fi
    
    # Generate REVERB settings
    REVERB_APP_ID=$(openssl rand -hex 8)
    REVERB_APP_KEY=$(openssl rand -hex 16)
    REVERB_APP_SECRET=$(openssl rand -hex 16)
    REVERB_HOST="$HOSTNAME.local"
    
    sed -i "s|REVERB_APP_ID=.*|REVERB_APP_ID=$REVERB_APP_ID|g" .env
    sed -i "s|REVERB_APP_KEY=.*|REVERB_APP_KEY=$REVERB_APP_KEY|g" .env
    sed -i "s|REVERB_APP_SECRET=.*|REVERB_APP_SECRET=$REVERB_APP_SECRET|g" .env
    sed -i "s|REVERB_HOST=.*|REVERB_HOST=\"$REVERB_HOST\"|g" .env
    
    # Configure Docker-specific settings
    sed -i "s|DB_HOST=.*|DB_HOST=mariadb|g" .env
    sed -i "s|REDIS_HOST=.*|REDIS_HOST=redis|g" .env
    sed -i "s|CACHE_DRIVER=.*|CACHE_DRIVER=redis|g" .env
    sed -i "s|QUEUE_CONNECTION=.*|QUEUE_CONNECTION=redis|g" .env
    sed -i "s|SESSION_DRIVER=.*|SESSION_DRIVER=redis|g" .env
    sed -i "s|BROADCAST_DRIVER=.*|BROADCAST_DRIVER=reverb|g" .env
    
    echo ""
    echo -e "${GREEN}✓ Configuration saved to .env${NC}"
else
    echo -e "${BLUE}ℹ .env file already exists${NC}"
fi

# Start Docker containers
echo ""
echo -e "${YELLOW}Starting Docker containers...${NC}"
docker-compose up -d

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
    echo "  View logs:     docker-compose -f $APP_DIR/docker-compose.yml logs -f"
    echo "  Stop services: docker-compose -f $APP_DIR/docker-compose.yml down"
    echo "  Restart:       docker-compose -f $APP_DIR/docker-compose.yml restart"
    echo "  Update app:    cd $APP_DIR && git pull && docker-compose up -d"
    echo ""
    echo -e "${GREEN}🚀 Your application is running!${NC}"
else
    echo -e "${RED}❌ Failed to start Docker containers${NC}"
    exit 1
fi
