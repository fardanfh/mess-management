#!/bin/bash

###############################################################################
# Laravel Mess Management - Automated Deployment Script
# For Ubuntu 20.04 LTS / 22.04 LTS
# 
# Usage: bash deploy.sh
###############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/var/www/mess-management"
APP_USER="www-data"
APP_GROUP="www-data"
DB_NAME="mess_management"
DB_USER="mess_user"
DOMAIN="yourdomain.com"

echo -e "${BLUE}=====================================${NC}"
echo -e "${BLUE}Laravel Mess Management Deployment${NC}"
echo -e "${BLUE}=====================================${NC}\n"

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
  echo -e "${RED}ERROR: This script must be run as root${NC}"
  exit 1
fi

# Step 1: System Updates
echo -e "${YELLOW}[1/8] Updating system packages...${NC}"
apt update && apt upgrade -y > /dev/null 2>&1
echo -e "${GREEN}✓ System updated${NC}\n"

# Step 2: Install dependencies
echo -e "${YELLOW}[2/8] Installing dependencies...${NC}"
apt install -y php8.1-fpm php8.1-cli php8.1-mysql php8.1-mbstring \
  php8.1-xml php8.1-bcmath php8.1-curl php8.1-zip php8.1-json \
  php8.1-gd nginx mysql-server composer nodejs npm git curl wget \
  zip unzip supervisor > /dev/null 2>&1
echo -e "${GREEN}✓ Dependencies installed${NC}\n"

# Step 3: Create application directory
echo -e "${YELLOW}[3/8] Setting up application directory...${NC}"
if [ ! -d "$APP_DIR" ]; then
  mkdir -p "$APP_DIR"
  echo "Created $APP_DIR"
fi
echo -e "${GREEN}✓ Application directory ready${NC}\n"

# Step 4: Verify .env file
echo -e "${YELLOW}[4/8] Configuring environment...${NC}"
if [ ! -f "$APP_DIR/.env" ]; then
  echo -e "${RED}ERROR: .env file not found in $APP_DIR${NC}"
  echo "Please copy .env.example to .env and configure it:"
  echo "  cp $APP_DIR/.env.example $APP_DIR/.env"
  echo "  nano $APP_DIR/.env"
  exit 1
fi
echo -e "${GREEN}✓ .env file found${NC}\n"

# Step 5: Install PHP dependencies
echo -e "${YELLOW}[5/8] Installing PHP dependencies (this may take a moment)...${NC}"
cd "$APP_DIR"
composer install --no-dev --optimize-autoloader > /dev/null 2>&1
echo -e "${GREEN}✓ PHP dependencies installed${NC}\n"

# Step 6: Generate app key & setup database
echo -e "${YELLOW}[6/8] Setting up database...${NC}"
php artisan key:generate --force > /dev/null 2>&1
php artisan migrate --force > /dev/null 2>&1
echo -e "${GREEN}✓ Database migrations completed${NC}\n"

# Step 7: Build frontend assets
echo -e "${YELLOW}[7/8] Building frontend assets...${NC}"
npm install > /dev/null 2>&1
npm run production > /dev/null 2>&1
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Assets built and caches configured${NC}\n"

# Step 8: Set permissions
echo -e "${YELLOW}[8/8] Setting file permissions...${NC}"
chown -R $APP_USER:$APP_GROUP "$APP_DIR"
chmod -R 755 "$APP_DIR"
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"
chmod 600 "$APP_DIR/.env"
echo -e "${GREEN}✓ Permissions set correctly${NC}\n"

# Enable and restart services
echo -e "${YELLOW}Restarting services...${NC}"
systemctl restart php8.1-fpm
systemctl restart nginx
echo -e "${GREEN}✓ Services restarted${NC}\n"

echo -e "${GREEN}=====================================${NC}"
echo -e "${GREEN}Deployment completed successfully!${NC}"
echo -e "${GREEN}=====================================${NC}\n"

echo -e "${BLUE}Next steps:${NC}"
echo "1. Update Nginx configuration:"
echo "   - Edit /etc/nginx/sites-available/mess-management"
echo "   - Replace 'yourdomain.com' with your actual domain"
echo ""
echo "2. Setup SSL certificate:"
echo "   sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com"
echo ""
echo "3. Verify application is running:"
echo "   - Open https://yourdomain.com in browser"
echo ""
echo "4. Check logs if there are issues:"
echo "   tail -f $APP_DIR/storage/logs/laravel.log"
echo ""
echo -e "${YELLOW}For detailed instructions, see DEPLOYMENT_UBUNTU.md${NC}"
