# 🏨 Sistem Manajemen Kamar Mess Pengemudi

![Laravel](https://img.shields.io/badge/Laravel-8-red?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-8-blue?style=flat-square)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-7.4+-blue?style=flat-square)

Sistem terintegrasi untuk manajemen kamar mess pengemudi dengan fitur simulasi NFC ID Card, perhitungan biaya harian otomatis, dashboard analytics, dan reporting lengkap.

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Requirement](#-requirement)
- [Instalasi](#-instalasi-setup)
- [Penggunaan](#-penggunaan)
- [API Documentation](#-api-documentation)
- [Database Schema](#-database-schema)
- [Kontribusi](#-kontribusi)
- [License](#-license)

## ✨ Fitur Utama

### 1. **Role & Permission Management**
- 2 role utama: Petugas dan Management
- Permission-based access control
- Middleware untuk enforcing permissions
- Activity audit trail lengkap

### 2. **Driver Management**
- Daftar driver dengan ID Card unik
- Status aktif/nonaktif
- Riwayat check-in/out lengkap
- Statistik per driver

### 3. **Room Management**
- Manajemen kamar dengan nomor dan kapasitas
- Status: Available, Occupied, Maintenance
- Real-time occupancy tracking
- Soft delete untuk data protection

### 4. **Check-in/Check-out Process**
- Simulasi NFC ID Card scanning
- Validasi: driver tidak boleh check-in 2x
- Validasi: kamar tidak boleh overbooked
- Auto cost calculation (Rp 2.000/malam)
- Auto invoice generation
- Auto room status update

### 5. **Dashboard & Analytics**
- Real-time room statistics
- Monthly check-in/out charts
- Revenue tracking (paid/unpaid)
- Occupancy trend analysis
- Activity log viewer
- Export functionality (ready for PDF/Excel)

### 6. **API & Integration**
- NFC simulation API endpoints
- RESTful API design
- JSON responses
- Error handling

### 7. **Security**
- CSRF protection
- Input validation & sanitization
- Soft deletes
- Activity audit trail
- IP address logging

## 🛠️ Requirement

### System Requirements
- PHP >= 7.4
- MySQL 8.0+
- Node.js 14+
- Composer
- Git

### Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## 🚀 Instalasi & Setup

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/mess-management.git
cd mess-management
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mess_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Database Migrations
```bash
php artisan migrate --seed
```

Ini akan membuat semua tables dan seed dengan data default:
- 2 Roles (Petugas, Management)
- 2 Default Users
- 5 Sample Drivers
- 15 Sample Rooms

### 6. Build Assets
```bash
npm run dev
# atau untuk production:
npm run production
```

### 7. Start Application
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

### 8. Login Default
- **Petugas**: petugas@example.com / password
- **Management**: management@example.com / password

## 📖 Penggunaan

### Untuk Petugas

#### Tambah Driver
1. Klik menu "Drivers"
2. Klik tombol "Add Driver"
3. Isi form dengan data driver
4. Klik "Save Driver"

#### Manage Rooms
1. Klik menu "Rooms"
2. Tambah/edit/hapus kamar sesuai kebutuhan
3. Monitor status kamar (Available/Occupied/Maintenance)

#### Process Check-in
1. Klik menu "Check-in"
2. Klik "New Check-in"
3. Scan ID Card (atau input manual)
4. Pilih kamar yang tersedia
5. Klik "Process Check-in"

#### Process Check-out
1. Pilih driver yang sudah check-in
2. Klik tombol "Checkout" di check-in list
3. Verifikasi waktu check-out
4. Sistem auto-calculate cost
5. Klik "Confirm Checkout"
6. Invoice otomatis dibuat

### Untuk Management

#### View Dashboard
1. Login dengan akun Management
2. Dashboard akan menampilkan:
   - Statistik kamar real-time
   - Revenue summary
   - Driver statistics
   - Monthly charts
   - Activity logs

#### View Reports
1. Klik "View Report" di dashboard
2. Filter berdasarkan date range
3. Lihat detail check-ins dan check-outs
4. Export report (PDF/Excel)

## 📡 API Documentation

### NFC Simulation Endpoints

#### Read ID Card
```http
GET /api/nfc/read/{id_card}
```

Response (success):
```json
{
  "status": "success",
  "id_card": "DRV001",
  "driver_id": 1,
  "driver_name": "Budi Santoso",
  "driver_status": "active",
  "is_checked_in": false,
  "scan_time": "2025-12-07T10:30:00"
}
```

#### Check-in Ready
```http
POST /api/nfc/checkin
Content-Type: application/json

{
  "id_card": "DRV001",
  "room_id": 1
}
```

#### Check-out Ready
```http
POST /api/nfc/checkout
Content-Type: application/json

{
  "id_card": "DRV001"
}
```

### Web Routes

#### Drivers
```
GET    /drivers              - Daftar driver
POST   /drivers              - Tambah driver
GET    /drivers/{id}         - Detail driver
PUT    /drivers/{id}         - Edit driver
DELETE /drivers/{id}         - Hapus driver
GET    /drivers/search       - Cari driver
```

#### Rooms
```
GET    /rooms                - Daftar kamar
POST   /rooms                - Tambah kamar
GET    /rooms/{id}           - Detail kamar
PUT    /rooms/{id}           - Edit kamar
DELETE /rooms/{id}           - Hapus kamar
```

#### Check-in/Check-out
```
GET    /checkins             - Daftar check-in
POST   /checkins             - Proses check-in
GET    /checkouts            - Daftar check-out
POST   /checkouts            - Proses check-out
```

#### Dashboard
```
GET    /dashboard            - Dashboard utama
GET    /dashboard/report     - Laporan dashboard
```

## 💾 Database Schema

### Tables
- `users` - User authentication
- `roles` - Role management
- `permissions` - Permission management
- `role_permission` - Role-Permission junction
- `drivers` - Data pengemudi
- `rooms` - Data kamar
- `checkins` - Check-in history
- `checkouts` - Check-out history & cost
- `invoices` - Invoice & billing
- `activity_logs` - Audit trail

## 🎯 Perhitungan Biaya

```
Cost per Night = Rp 2.000
Days = ceiling((Check-out Time - Check-in Time) / 24 hours)
Total Cost = Days × 2.000
```

**Contoh:**
- Check-in: 07-12-2025 14:00
- Check-out: 09-12-2025 10:00
- Duration: 43.67 jam = 2 hari
- Biaya: 2 × Rp 2.000 = Rp 4.000

## 📁 Struktur Project

```
mess-management/
├── app/
│   ├── Models/
│   ├── Http/Controllers/
│   ├── Http/Middleware/
│   └── Traits/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── api.php
├── public/
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

## 🔧 Konfigurasi Lanjutan

### Mengubah Biaya per Malam
Edit `app/Models/Checkout.php`:
```php
const COST_PER_DAY = 2000; // Ubah nilai ini
```

### Mengubah Timezone
Edit `.env`:
```env
APP_TIMEZONE=Asia/Jakarta
```

### Email Configuration
Edit `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@mess-management.com
```

## 🐛 Troubleshooting

### Migration Error
```bash
php artisan migrate:refresh
php artisan migrate --seed
```

### Permission Error
```bash
chmod -R 775 storage bootstrap/cache
```

### Asset Not Loading
```bash
php artisan optimize
npm run dev
```

### Database Connection Error
- Pastikan MySQL service sudah berjalan
- Verifikasi konfigurasi .env
- Check username dan password database

## 📚 Dokumentasi Lengkap

- [IMPLEMENTATION_GUIDE.md](./IMPLEMENTATION_GUIDE.md) - Panduan implementasi
- [COMPLETION_SUMMARY.md](./COMPLETION_SUMMARY.md) - Ringkasan fitur
- [FINAL_CHECKLIST.md](./FINAL_CHECKLIST.md) - Checklist requirement

## 🤝 Contributing

Kontribusi sangat diterima! Silakan:

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see LICENSE file for details.

## 📞 Support

Untuk pertanyaan atau masalah, silakan buat issue di repository ini.

---

**Built with ❤️ for Driver Accommodation Management**  
**Version 1.0.0 - December 2025**

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
