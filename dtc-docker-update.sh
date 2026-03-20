#!/bin/bash

###############################################################################
# DTCDashboard Docker Update Script
# 
# Updates the running Docker containers
#
# Usage:
#   ./dtc-docker-update.sh
#
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Check for sudo/root access
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}❌ This script requires sudo/root privileges${NC}"
    echo "Please run: sudo bash $0"
    exit 1
fi

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$APP_DIR"

# Determine docker compose command (plugin or standalone)
if docker compose version &> /dev/null; then
    DOCKER_COMPOSE="docker compose"
else
    DOCKER_COMPOSE="docker-compose"
fi

echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}DTCDashboard Docker Update${NC}"
echo -e "${BLUE}================================${NC}"
echo ""

# Check if docker-compose.yml exists
if [ ! -f docker-compose.yml ]; then
    echo -e "${RED}Error: docker-compose.yml not found in $APP_DIR${NC}"
    exit 1
fi

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${RED}Error: .env file not found${NC}"
    echo "Please run the installer first: curl -fsSL https://raw.githubusercontent.com/Underlyingglitch/DTCDashboard/main/dtc-docker-install.sh | bash"
    exit 1
fi

# Pull latest changes
if git rev-parse --git-dir > /dev/null 2>&1; then
    echo -e "${YELLOW}Updating repository...${NC}"
    git pull origin main
    echo -e "${GREEN}✓ Repository updated${NC}"
fi

# Pull latest Docker images
echo -e "${YELLOW}Pulling latest Docker images...${NC}"
$DOCKER_COMPOSE pull

# Run migrations
echo -e "${YELLOW}Running database migrations...${NC}"
$DOCKER_COMPOSE exec -T php php artisan migrate --force

# Clear caches
echo -e "${YELLOW}Clearing caches...${NC}"
$DOCKER_COMPOSE exec -T php php artisan cache:clear
$DOCKER_COMPOSE exec -T php php artisan config:cache
$DOCKER_COMPOSE exec -T php php artisan route:cache
$DOCKER_COMPOSE exec -T php php artisan view:cache

# Restart containers
echo -e "${YELLOW}Restarting services...${NC}"
$DOCKER_COMPOSE up -d

echo ""
echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Update completed successfully!${NC}"
echo -e "${GREEN}================================${NC}"
echo -e "${BLUE}Latest commit: $(git rev-parse HEAD --short 2>/dev/null || echo 'N/A')${NC}"
