# 🚀 Deploy Laravel ke Apache + PHP 8.0 Server
## Panduan untuk Server Anda

**Server Anda:**
- PHP: 8.0.30 ✅ (Sudah ada)
- Apache: 2.4.52 ✅ (Sudah ada)
- OS: Ubuntu ✅

---

## STEP 1: Cek Requirement (2 menit)

```bash
# Cek PHP version
php -v

# Cek Apache version
apache2 -v

# Cek MySQL
mysql --version

# Cek Composer
composer --version
```

Jika ada yang tidak terinstall, install dulu:
```bash
apt install -y php8.0-mysql php8.0-mbstring php8.0-xml php8.0-curl php8.0-zip php8.0-gd php8.0-json composer mysql-server
```

---

## STEP 2: Upload Project (5-10 menit)

### Pilihan A: Pakai Git

```bash
cd /var/www/html
git clone https://github.com/username/mess-management.git
cd mess-management
```

### Pilihan B: Upload via SFTP

Upload folder project ke `/var/www/html/mess-management`

**✓ Project sudah ada di `/var/www/html/mess-management`**

---

## STEP 3: Setup Database (3 menit)

```bash
mysql -u root -p
```

Masukkan password MySQL Anda.

Kemudian ketik:

```sql
CREATE DATABASE mess_management;
CREATE USER 'mess_user'@'localhost' IDENTIFIED BY 'backadmin234';
GRANT ALL PRIVILEGES ON mess_management.* TO 'mess_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Ganti `password123` dengan password pilihan Anda!**

**✓ Database sudah dibuat**

---

## STEP 4: Configure .env (3 menit)

```bash
cd /var/www/html/mess-management
cp .env.example .env
nano .env
```

Ubah baris ini:

```env
APP_NAME=MessManagement
APP_ENV=production
APP_DEBUG=false
APP_URL=http://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mess_management
DB_USERNAME=mess_user
DB_PASSWORD=password123
```

**Ganti:**
- `APP_URL=` → `http://yourdomain.com` (atau IP server)
- `password123` → password yang Anda buat

**Save:** `Ctrl+X` → `Y` → `Enter`

**✓ .env sudah dikonfigurasi**

---

## STEP 5: Install Dependencies (10 menit)

```bash
cd /var/www/html/mess-management

# Install PHP dependencies
composer install --no-dev --optimize-autoloader
```

Tunggu sampai selesai.

```bash
# Generate APP_KEY
php artisan key:generate

# Jika NPM tersedia (untuk compile assets)
npm install
npm run production
```

Jika `npm` error, skip saja (tidak critical).

**✓ Dependencies installed**

---

## STEP 6: Setup Database & Cache (3 menit)

```bash
cd /var/www/html/mess-management

php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

**✓ Database ready**

---

## STEP 7: Set File Permissions (2 menit)

```bash
cd /var/www/html/mess-management

# Set permissions
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
chmod 644 .env
```

**✓ Permissions set**

---

## STEP 8: Enable Apache Modules (1 menit)

```bash
# Enable mod_rewrite untuk Laravel routing
a2enmod rewrite

# Restart Apache
systemctl restart apache2
```

**✓ Apache modules enabled**

---

## STEP 9: Configure Apache Virtual Host (3 menit)

### A. Buat file virtual host

```bash
nano /etc/apache2/sites-available/mess-management.conf
```

### B. Copy-paste ini:

```apache
<VirtualHost *:80>
    ServerName mess-driver.cititrans.co.id
    ServerAlias mess-driver.cititrans.co.id
    ServerAdmin admin@mess-driver.cititrans.co.id

    DocumentRoot /var/www/html/mess-management/public

    <Directory /var/www/html/mess-management/public>
        AllowOverride All
        Require all granted
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>

    <Directory /var/www/html/mess-management>
        <Files .env>
            Require all denied
        </Files>
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/mess-management-error.log
    CustomLog ${APACHE_LOG_DIR}/mess-management-access.log combined

    <IfModule mod_dir.c>
        DirectoryIndex index.php index.html
    </IfModule>
</VirtualHost>
```

**Ganti `yourdomain.com` dengan domain Anda!**

**Save:** `Ctrl+X` → `Y` → `Enter`

### C. Aktifkan virtual host

```bash
a2ensite mess-management.conf
apache2ctl configtest
```

Seharusnya output: `Syntax OK`

### D. Restart Apache

```bash
systemctl restart apache2
```

**✓ Apache configured**

---

## STEP 10: Test Application (2 menit)

### Opsi A: Jika punya domain

Buka browser:
```
http://yourdomain.com
```

### Opsi B: Jika pakai IP server

Buka browser:
```
http://YOUR_SERVER_IP/mess-management/public
```

**Jika muncul login page:** ✅ Sukses!

**Jika blank page:** Lihat troubleshooting

---

## ✅ DEPLOYMENT SELESAI!

Aplikasi sudah live di server Anda!

**Login dengan:**
- Email: `petugas@example.com`
- Password: `password` (atau sesuai seeder)

---

## 🆘 TROUBLESHOOTING

### Error: Blank page / 500 error

```bash
# Cek Laravel log
tail -f /var/www/html/mess-management/storage/logs/laravel.log

# Cek Apache error log
tail -f /var/log/apache2/mess-management-error.log
```

### Error: Database connection

Pastikan `.env` database config benar:

```bash
# Test database connection
mysql -u mess_user -p mess_management
```

Ganti password sesuai yang Anda setting.

### Error: Permission denied

```bash
cd /var/www/html/mess-management
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
```

### Error: Page not found (404)

Pastikan `mod_rewrite` enabled:

```bash
apache2ctl -M | grep rewrite
```

Jika tidak ada, enable:
```bash
a2enmod rewrite
systemctl restart apache2
```

### Error: Composer not found

```bash
apt install -y composer
```

---

## 📋 STEP CHECKLIST

- [ ] STEP 1: Cek requirement
- [ ] STEP 2: Upload project ke `/var/www/html/mess-management`
- [ ] STEP 3: Create database `mess_management`
- [ ] STEP 4: Edit `.env` dengan database config
- [ ] STEP 5: Install dependencies dengan Composer
- [ ] STEP 6: Run migrations & cache
- [ ] STEP 7: Set permissions
- [ ] STEP 8: Enable Apache modules
- [ ] STEP 9: Configure virtual host
- [ ] STEP 10: Test di browser
- [ ] ✅ Done!

---

## 📝 CATATAN PENTING

### 1. Backup Database Secara Berkala

```bash
mysqldump -u mess_user -p mess_management > backup-$(date +%Y%m%d).sql
```

### 2. Monitor Error Log

```bash
# Laravel errors
tail -f /var/www/html/mess-management/storage/logs/laravel.log

# Apache errors
tail -f /var/log/apache2/mess-management-error.log
```

### 3. Update Dependencies

```bash
cd /var/www/html/mess-management
composer update
```

### 4. Jika Ada Database Changes

```bash
cd /var/www/html/mess-management
php artisan migrate
```

---

## 🔐 SECURITY TIPS

1. **Proteksi .env file** (sudah ada di Apache config)

2. **Setup firewall**
   ```bash
   ufw allow 22/tcp   # SSH
   ufw allow 80/tcp   # HTTP
   ufw allow 443/tcp  # HTTPS
   ufw enable
   ```

3. **Jika punya domain:**
   - Install SSL certificate dengan Let's Encrypt
   - Update Apache config untuk HTTPS

4. **Disable directory listing**
   ```bash
   a2enmod dir
   ```

---

## 📞 NEXT STEPS

1. **Akses aplikasi** → `http://yourdomain.com`
2. **Login** → gunakan user dari seeder
3. **Test features** → checkin/checkout, reports, etc
4. **Backup data** → setup automated backup

---

**Selamat! Server Anda sudah siap! 🎉**

Perlu bantuan setup SSL atau konfigurasi lebih lanjut?
