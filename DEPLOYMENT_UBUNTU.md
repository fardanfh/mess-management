# Laravel Mess Management - Ubuntu Deployment Guide

## Prerequisites

- Ubuntu 20.04 LTS atau 22.04 LTS
- SSH access ke server
- Domain yang sudah pointing ke server IP
- Root atau sudo access

---

## Step 1: Install System Dependencies

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1 dan extensions yang diperlukan
sudo apt install -y php8.1-fpm php8.1-cli php8.1-mysql php8.1-mbstring \
  php8.1-xml php8.1-bcmath php8.1-curl php8.1-zip php8.1-json php8.1-gd

# Install Nginx
sudo apt install -y nginx

# Install MySQL Server
sudo apt install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Node.js (untuk build assets)
sudo apt install -y nodejs npm

# Install Git
sudo apt install -y git

# Install additional tools
sudo apt install -y curl wget zip unzip supervisor
```

---

## Step 2: Setup Database

```bash
# Login ke MySQL
sudo mysql

# Create database dan user
CREATE DATABASE mess_management;
CREATE USER 'mess_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON mess_management.* TO 'mess_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Step 3: Setup Application Directory

```bash
# Create application directory
sudo mkdir -p /var/www/mess-management
cd /var/www/mess-management

# Clone repository (atau upload via SFTP/Git)
# Jika menggunakan git:
sudo git clone <repository_url> .

# Set proper permissions
sudo chown -R www-data:www-data /var/www/mess-management
sudo chmod -R 755 /var/www/mess-management
sudo chmod -R 775 /var/www/mess-management/storage
sudo chmod -R 775 /var/www/mess-management/bootstrap/cache

# Create .env file
sudo cp .env.example .env
sudo nano .env  # Edit sesuai konfigurasi server
```

---

## Step 4: Configure .env for Production

**Edit `/var/www/mess-management/.env`:**

```env
APP_NAME=MessManagement
APP_ENV=production
APP_KEY=  # Akan di-generate di step berikutnya
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mess_management
DB_USERNAME=mess_user
DB_PASSWORD=strong_password_here

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=log
# Atau setup SMTP jika perlu email
```

---

## Step 5: Install Dependencies & Generate Key

```bash
cd /var/www/mess-management

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Generate APP_KEY
php artisan key:generate

# Run database migrations
php artisan migrate --force

# (Optional) Seed database jika ada seeder
php artisan db:seed --force

# Build frontend assets
npm install
npm run production

# Cache config & routes
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 6: Configure Nginx

**Create file `/etc/nginx/sites-available/mess-management`:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;

    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    # SSL Certificate paths (jika sudah ada)
    # ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    # ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    root /var/www/mess-management/public;
    index index.php;

    # Logging
    access_log /var/log/nginx/mess-management-access.log;
    error_log /var/log/nginx/mess-management-error.log;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript 
               application/x-javascript application/xml+rss 
               application/atom+xml image/svg+xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }
}
```

**Enable Nginx site:**

```bash
sudo ln -s /etc/nginx/sites-available/mess-management /etc/nginx/sites-enabled/
sudo nginx -t  # Test configuration
sudo systemctl restart nginx
```

---

## Step 7: Setup SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Generate certificate
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renew (check if cron job exists)
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

**Update Nginx config dengan SSL paths:**

```bash
sudo nano /etc/nginx/sites-available/mess-management

# Uncomment SSL certificate lines dan update dengan path Certbot
```

---

## Step 8: Setup PHP-FPM

```bash
# Edit PHP-FPM pool configuration
sudo nano /etc/php/8.1/fpm/pool.d/www.conf

# Pastikan settings:
# user = www-data
# group = www-data
# listen = /run/php/php8.1-fpm.sock
# listen.owner = www-data
# listen.group = www-data

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
sudo systemctl enable php8.1-fpm
```

---

## Step 9: Setup Supervisor untuk Queue Jobs (Optional)

**Create `/etc/supervisor/conf.d/mess-management.conf`:**

```ini
[program:mess-management-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/mess-management/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/mess-management-worker.log
user=www-data
```

**Activate:**

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start mess-management-worker:*
```

---

## Step 10: Setup Automated Backups

**Create backup script `/home/ubuntu/backup-mess.sh`:**

```bash
#!/bin/bash

BACKUP_DIR="/backups/mess-management"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="mess_management"
DB_USER="mess_user"

mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u$DB_USER -p"password" $DB_NAME > $BACKUP_DIR/db_$DATE.sql
gzip $BACKUP_DIR/db_$DATE.sql

# Backup application files
tar -czf $BACKUP_DIR/app_$DATE.tar.gz /var/www/mess-management

# Delete old backups (keep last 7 days)
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed at $DATE"
```

**Make executable & add to cron:**

```bash
chmod +x /home/ubuntu/backup-mess.sh

# Add to crontab (daily at 2 AM)
sudo crontab -e

# Add line:
0 2 * * * /home/ubuntu/backup-mess.sh
```

---

## Step 11: Security Hardening

```bash
# Configure UFW Firewall
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable

# Set strong permissions on .env
sudo chmod 600 /var/www/mess-management/.env

# Setup fail2ban untuk brute-force protection
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

---

## Step 12: Monitor & Maintenance

```bash
# View application logs
tail -f /var/www/mess-management/storage/logs/laravel.log

# View Nginx errors
tail -f /var/log/nginx/mess-management-error.log

# Monitor system resources
htop

# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# Check Nginx status
sudo systemctl status nginx
```

---

## Deployment Checklist

- [ ] SSH access ke server
- [ ] System dependencies terinstall
- [ ] Database created dan configured
- [ ] Application files uploaded
- [ ] .env file configured untuk production
- [ ] Composer dependencies installed
- [ ] Database migrations ran
- [ ] Assets compiled (npm run production)
- [ ] Nginx configured dan enabled
- [ ] SSL certificate installed
- [ ] Permissions set correctly
- [ ] Caching enabled (config, routes, views)
- [ ] Logs accessible
- [ ] Backups configured
- [ ] Firewall configured
- [ ] Domain SSL configured

---

## Troubleshooting

### Laravel shows blank page
```bash
# Check Laravel logs
tail -f /var/www/mess-management/storage/logs/laravel.log

# Check Nginx error logs
tail -f /var/log/nginx/mess-management-error.log

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Database connection error
```bash
# Test MySQL connection
mysql -u mess_user -p -h 127.0.0.1 mess_management

# Check .env credentials
cat /var/www/mess-management/.env | grep DB_
```

### Permission denied errors
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/mess-management
sudo chmod -R 755 /var/www/mess-management
sudo chmod -R 775 /var/www/mess-management/storage
sudo chmod -R 775 /var/www/mess-management/bootstrap/cache
```

### PHP-FPM connection refused
```bash
# Restart PHP-FPM
sudo systemctl restart php8.1-fpm

# Check socket exists
ls -la /run/php/php8.1-fpm.sock
```

---

## Post-Deployment

1. **Setup automated updates:**
   ```bash
   sudo apt install -y unattended-upgrades
   sudo dpkg-reconfigure -plow unattended-upgrades
   ```

2. **Monitor application:**
   - Setup uptime monitoring (Uptime Robot, Pingdom)
   - Setup error tracking (Sentry, Rollbar)
   - Monitor server resources

3. **Regular maintenance:**
   - Review logs weekly
   - Update dependencies monthly
   - Test backups regularly

---

## Support & Reference

- Laravel Documentation: https://laravel.com/docs/8.x
- Nginx Documentation: https://nginx.org/en/docs/
- MySQL Documentation: https://dev.mysql.com/doc/
- Ubuntu Server Documentation: https://ubuntu.com/server/docs

---

**Last Updated:** January 7, 2026
