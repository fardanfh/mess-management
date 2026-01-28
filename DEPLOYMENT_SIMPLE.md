# 🚀 Deploy Laravel Mess Management ke Ubuntu Server
## Panduan Singkat & Mudah

---

## PERSIAPAN (5 menit)

Sebelum mulai, siapkan informasi ini:
- **Domain**: contoh.com (domain yang akan diakses)
- **IP Server**: 123.45.67.89 (dari hosting provider)
- **Password Database**: pilih password kuat

---

## STEP 1: Login ke Server (2 menit)

Buka Terminal/Command Prompt dan ketik:

```bash
ssh root@123.45.67.89
```

Ganti `123.45.67.89` dengan IP server Anda. Tekan Enter, lalu masukkan password.

**✓ Selesai jika Anda sudah di dalam server (prompt berubah)**

---

## STEP 2: Update System (3 menit)

Copy-paste perintah ini satu per satu:

```bash
apt update && apt upgrade -y
```

Tunggu hingga selesai. Ini hanya perlu dilakukan sekali.

**✓ Selesai jika pesan selesai muncul**

---

## STEP 3: Install Semua Program yang Diperlukan (5 menit)

Copy-paste semuanya sekaligus:

```bash
apt install -y php8.1-fpm php8.1-cli php8.1-mysql php8.1-mbstring php8.1-xml php8.1-bcmath php8.1-curl php8.1-zip php8.1-json php8.1-gd nginx mysql-server composer nodejs npm git
```

Tunggu sampai selesai (bisa agak lama).

**✓ Selesai jika tidak ada error message**

---

## STEP 4: Setup Database (3 menit)

```bash
sudo mysql
```

Kemudian copy-paste ini satu per satu (di dalam MySQL):

```sql
CREATE DATABASE mess_management;
CREATE USER 'mess_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON mess_management.* TO 'mess_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Ganti `password123` dengan password yang Anda inginkan!**

**✓ Keluar dari MySQL jika sudah**

---

## STEP 5: Upload Project ke Server (5-10 menit)

### Pilihan A: Pakai Git (Jika ada repository)

```bash
cd /var/www
git clone https://github.com/username/mess-management.git
cd mess-management
```

Ganti URL dengan URL repository Anda.

### Pilihan B: Upload via SFTP (Jika tidak ada Git)

Gunakan WinSCP atau Cyberduck untuk upload folder project ke `/var/www/mess-management`

**✓ Project sudah ada di `/var/www/mess-management`**

---

## STEP 6: Setup Project (5 menit)

```bash
cd /var/www/mess-management
```

### A. Copy file .env

```bash
cp .env.example .env
```

### B. Edit konfigurasi database

```bash
nano .env
```

Cari dan ubah baris ini:

```env
APP_URL=http://localhost
APP_ENV=production
APP_DEBUG=false

DB_HOST=127.0.0.1
DB_USERNAME=mess_user
DB_PASSWORD=password123
DB_DATABASE=mess_management
```

**Ganti:**
- `APP_URL=` menjadi `APP_URL=https://contoh.com`
- `password123` dengan password database yang Anda buat di STEP 4
- `contoh.com` dengan domain Anda

**Untuk save:** Tekan `Ctrl+X`, lalu `Y`, lalu `Enter`

**✓ File .env sudah disimpan**

---

## STEP 7: Install Dependencies (10 menit)

```bash
composer install --no-dev --optimize-autoloader
```

Tunggu sampai selesai.

```bash
php artisan key:generate
```

```bash
npm install
npm run production
```

**✓ Semua dependencies terinstall**

---

## STEP 8: Setup Database & Cache (3 menit)

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**✓ Database siap**

---

## STEP 9: Set Permissions (2 menit)

```bash
chown -R www-data:www-data /var/www/mess-management
chmod -R 755 /var/www/mess-management
chmod -R 775 /var/www/mess-management/storage
chmod -R 775 /var/www/mess-management/bootstrap/cache
chmod 600 /var/www/mess-management/.env
```

**✓ Permission sudah benar**

---

## STEP 10: Setup Nginx (3 menit)

### A. Buat file Nginx config

```bash
nano /etc/nginx/sites-available/contoh.com
```

Ganti `contoh.com` dengan domain Anda.

### B. Copy-paste ini ke file:

```nginx
server {
    listen 80;
    server_name contoh.com www.contoh.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name contoh.com www.contoh.com;
    root /var/www/mess-management/public;
    index index.php;

    ssl_certificate /etc/letsencrypt/live/contoh.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/contoh.com/privkey.pem;

    access_log /var/log/nginx/mess-access.log;
    error_log /var/log/nginx/mess-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }
}
```

**Ganti semua `contoh.com` dengan domain Anda!**

**Untuk save:** Tekan `Ctrl+X`, lalu `Y`, lalu `Enter`

### C. Aktifkan Nginx config

```bash
ln -s /etc/nginx/sites-available/contoh.com /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

**✓ Nginx configured**

---

## STEP 11: Setup SSL Certificate (5 menit)

```bash
apt install -y certbot python3-certbot-nginx
certbot certonly --nginx -d contoh.com -d www.contoh.com
```

Ganti `contoh.com` dengan domain Anda.

Ikuti pertanyaan yang muncul:
- Email: masukkan email Anda
- Setuju Terms: tekan `Y`

```bash
systemctl restart nginx
```

**✓ SSL certificate installed**

---

## STEP 12: Test Application (2 menit)

Buka browser dan akses:
```
https://contoh.com
```

Ganti `contoh.com` dengan domain Anda.

**Jika berhasil:** Aplikasi akan tampil

**Jika error:** Lihat bagian Troubleshooting di bawah

---

## ✅ CHECKLIST SELESAI!

- [x] Login ke server
- [x] Update system
- [x] Install program
- [x] Setup database
- [x] Upload project
- [x] Setup .env
- [x] Install dependencies
- [x] Database migration
- [x] Set permissions
- [x] Setup Nginx
- [x] Setup SSL
- [x] Test aplikasi

---

## 🆘 TROUBLESHOOTING

### Error: Blank page

```bash
tail -f /var/www/mess-management/storage/logs/laravel.log
```

Lihat error message dan cari solusinya.

### Error: Database connection

Pastikan database sudah dibuat dan password di `.env` sesuai:

```bash
mysql -u mess_user -p mess_management
```

Masukkan password yang Anda buat di STEP 4.

### Error: Permission denied

```bash
chown -R www-data:www-data /var/www/mess-management
chmod -R 755 /var/www/mess-management
```

### Error: Nginx not found

```bash
systemctl status nginx
systemctl start nginx
```

### Error: PHP-FPM error

```bash
systemctl status php8.1-fpm
systemctl restart php8.1-fpm
```

---

## 📝 CATATAN PENTING

1. **Backup data secara berkala**
   ```bash
   mysqldump -u mess_user -p mess_management > backup.sql
   ```

2. **Lihat log jika ada masalah**
   ```bash
   tail -f /var/log/nginx/mess-error.log
   tail -f /var/www/mess-management/storage/logs/laravel.log
   ```

3. **Update dependencies setiap bulan**
   ```bash
   cd /var/www/mess-management
   composer update
   npm update
   ```

---

## 📞 SUPPORT

Jika ada error, cek:
- Log files
- Pastikan password database benar
- Pastikan domain di DNS sudah pointing ke server IP
- Pastikan SSH key sudah benar

**Selamat! Aplikasi sudah live di server Anda! 🎉**
