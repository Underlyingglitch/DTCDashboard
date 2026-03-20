#!/bin/bash

###############################################################################
# DTCDashboard Quick Installer
# 
# Downloads and runs the setup script from GitHub
#
# Usage:
#   curl -fsSL https://raw.githubusercontent.com/Underlyingglitch/DTCDashboard/main/dtc-install.sh | bash
#   OR
#   wget -qO - https://raw.githubusercontent.com/Underlyingglitch/DTCDashboard/main/dtc-install.sh | bash
#
# This script:
# 1. Checks prerequisites
# 2. Downloads dtc-setup.sh from GitHub
# 3. Makes it executable
# 4. Runs the setup
#
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}DTCDashboard Quick Installer${NC}"
echo -e "${BLUE}================================${NC}"
echo ""

# Check prerequisites
echo -e "${YELLOW}Checking prerequisites...${NC}"

MISSING_PACKAGES=()

if ! command -v git &> /dev/null; then
    MISSING_PACKAGES+=("git")
fi

# Just check if PHP exists (any version) - dtc-setup.sh will ensure php8.3
if ! command -v php &> /dev/null; then
    MISSING_PACKAGES+=("php-cli")
fi

if ! command -v wget &> /dev/null && ! command -v curl &> /dev/null; then
    MISSING_PACKAGES+=("curl")
fi

# Install missing packages if needed
if [ ${#MISSING_PACKAGES[@]} -gt 0 ]; then
    echo -e "${YELLOW}Missing packages: ${MISSING_PACKAGES[*]}${NC}"
    echo ""
    
    # Check if stdin is available (interactive mode)
    if [ -t 0 ]; then
        read -p "Would you like to install them now? (y/n) " -n 1 -r
        echo ""
    else
        # Non-interactive mode (piped via curl) - auto-proceed
        echo -e "${YELLOW}Running in non-interactive mode - proceeding with installation...${NC}"
        REPLY="y"
    fi
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${YELLOW}Installing missing packages and updating repository...${NC}"
        sudo apt-get update -qq
        
        # For PHP, try to add Sury PPA for PHP 8.5 if php-cli not found
        if echo "${MISSING_PACKAGES[@]}" | grep -q "php-cli"; then
            echo -e "${YELLOW}Adding PHP repository for PHP 8.5...${NC}"
            if ! grep -q "deb.*sury.*php" /etc/apt/sources.list.d/* 2>/dev/null; then
                echo -e "${BLUE}Setting up Sury PHP PPA...${NC}"
                sudo apt-get install -y lsb-release ca-certificates curl 2>&1 | grep -v "^Get:" || true
                curl -sSL https://packages.sury.org/php/README.txt | sudo bash -E 2>&1 | grep -E "(Adding|Updated|Setting)" || true
                sudo apt-get update -qq
            fi
            # Update package list to use php8.5-cli
            MISSING_PACKAGES=("${MISSING_PACKAGES[@]/php-cli/php8.5-cli}")
            echo -e "${BLUE}Installing PHP 8.5...${NC}"
        fi
        
        sudo apt-get install -y "${MISSING_PACKAGES[@]}"
        
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✓ Packages installed successfully${NC}"
        else
            echo -e "${RED}❌ Failed to install packages${NC}"
            exit 1
        fi
    else
        echo -e "${RED}Cannot proceed without required packages${NC}"
        echo "To install manually, run:"
        echo "  sudo apt-get update"
        echo "  curl -sSL https://packages.sury.org/php/README.txt | sudo bash -E"
        echo "  sudo apt-get install git php8.5-cli curl"
        exit 1
    fi
fi

echo -e "${GREEN}✓ Git installed${NC}"
echo -e "${GREEN}✓ PHP installed${NC}"
echo -e "${GREEN}✓ Downloader available${NC}"
echo ""

# Download setup script
SETUP_URL="https://raw.githubusercontent.com/Underlyingglitch/DTCDashboard/main/dtc-setup.sh"
SETUP_FILE="/tmp/dtc-setup.sh"

echo -e "${YELLOW}Downloading setup script...${NC}"

if command -v curl &> /dev/null; then
    curl -fsSL "$SETUP_URL" -o "$SETUP_FILE"
else
    wget -qO "$SETUP_FILE" "$SETUP_URL"
fi

if [ ! -f "$SETUP_FILE" ]; then
    echo -e "${RED}❌ Failed to download setup script${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Setup script downloaded${NC}"

# Make executable
chmod +x "$SETUP_FILE"

# Run setup script
echo ""
echo -e "${YELLOW}Starting installation...${NC}"
echo ""

bash "$SETUP_FILE" "$@"
