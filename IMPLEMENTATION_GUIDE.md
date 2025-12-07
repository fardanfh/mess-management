# Sistem Manajemen Kamar Mess Pengemudi

Sistem lengkap untuk mengelola kamar mess pengemudi dengan fitur check-in/check-out, biaya harian, dashboard, laporan, dan simulasi NFC ID Card.

## 📋 Fitur Sistem

### 1. **Role & Permission Management**
- **Petugas**: CRUD data, proses check-in/out, entri driver dan kamar
- **Management**: Akses dashboard, laporan, dan analitik
- Middleware untuk batasan akses berdasarkan role
- Activity logging untuk audit trail

### 2. **Manajemen Pengemudi (Drivers)**
- Daftar pengemudi dengan ID Card unik
- Status aktif/nonaktif
- Riwayat check-in/out lengkap
- Statistik per pengemudi

### 3. **Manajemen Kamar (Rooms)**
- Daftar kamar dengan nomor dan kapasitas
- Status: Available (Tersedia) / Occupied (Terisi) / Maintenance (Perbaikan)
- Validasi occupancy
- Tracking penghuni saat ini

### 4. **Check-in Process**
- Simulasi NFC scan card
- Validasi: driver tidak boleh check-in 2 kali
- Validasi: kamar tidak boleh overbooked
- Auto update status kamar
- Pencatatan user dan waktu

### 5. **Check-out Process**
- Perhitungan lama menginap otomatis (per hari)
- Biaya = lama menginap × Rp 2.000
- Auto generate invoice
- Auto update status kamar menjadi tersedia
- Pencatatan payment status

### 6. **Dashboard & Reporting**
- Statistik kamar (tersedia, terisi, perbaikan)
- Grafik check-in/out bulanan
- Summary revenue (total, dibayar, belum dibayar)
- Activity log real-time
- Trend occupancy
- Export report (siap untuk PDF/Excel)

### 7. **API Simulasi NFC**
- `GET /api/nfc/read/{id_card}` - Simulasi scan kartu
- `POST /api/nfc/checkin` - Proses check-in via API
- `POST /api/nfc/checkout` - Proses check-out via API

### 8. **Soft Delete & Audit Trail**
- Penghapusan data dengan soft delete
- Activity logging setiap aksi
- Tracking user, IP address, dan user agent

## 🛠️ Stack Teknologi

- **Backend**: Laravel 8
- **Database**: MySQL
- **Frontend**: Bootstrap 5, Chart.js
- **Authentication**: Laravel Sanctum
- **ORM**: Eloquent

## 📦 Database Structure

### Tables
- `users` - User login
- `roles` - Daftar role
- `permissions` - Daftar permission
- `role_permission` - Relasi role-permission
- `drivers` - Data pengemudi
- `rooms` - Data kamar
- `checkins` - Riwayat check-in
- `checkouts` - Riwayat check-out & biaya
- `invoices` - Invoice/tagihan
- `activity_logs` - Audit trail

## 🚀 Instalasi & Setup

### 1. Clone & Install Dependencies
```bash
cd mess-management
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Configuration
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mess_management
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate --seed
```

### 5. Build Frontend Assets
```bash
npm run dev
# atau
npm run production
```

### 6. Serve Application
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👤 User Default

Setelah `migrate --seed`, silakan create user secara manual atau gunakan:
- Email: `petugas@example.com` (Password: password)
- Email: `management@example.com` (Password: password)

## 📊 API Endpoints

### NFC Simulation
```
GET    /api/nfc/read/{id_card}              - Scan kartu & ambil data driver
POST   /api/nfc/checkin                     - Cek ready untuk check-in
POST   /api/nfc/checkout                    - Cek ready untuk check-out
```

### Drivers
```
GET    /drivers                             - Daftar driver
GET    /drivers/{id}                        - Detail driver
POST   /drivers                             - Tambah driver
PUT    /drivers/{id}                        - Edit driver
DELETE /drivers/{id}                        - Hapus driver
GET    /drivers/search                      - Cari driver
```

### Rooms
```
GET    /rooms                               - Daftar kamar
GET    /rooms/{id}                          - Detail kamar
POST   /rooms                               - Tambah kamar
PUT    /rooms/{id}                          - Edit kamar
DELETE /rooms/{id}                          - Hapus kamar
GET    /api/rooms/available                 - API kamar tersedia
```

### Check-in
```
GET    /checkins                            - Daftar check-in
GET    /checkins/{id}                       - Detail check-in
POST   /checkins                            - Proses check-in
POST   /checkins/scan-card                  - Scan kartu NFC
GET    /checkins/{id}/checkout-form         - Form check-out
```

### Check-out
```
GET    /checkouts                           - Daftar check-out
GET    /checkouts/{id}                      - Detail check-out
POST   /checkouts                           - Proses check-out
POST   /checkouts/{id}/mark-paid            - Tandai sebagai dibayar
GET    /checkouts/report                    - Laporan check-out
```

### Dashboard
```
GET    /dashboard                           - Dashboard utama
GET    /dashboard/report                    - Laporan dashboard
```

## 💡 Fitur Khusus

### Perhitungan Lama Menginap
```
Total Jam = Check-out Time - Check-in Time
Malam = Ceiling(Total Jam / 24)
Biaya = Malam × Rp 2.000
```

### Validasi Check-in
- ❌ Driver tidak boleh check-in jika sudah checked-in sebelumnya
- ❌ Kamar tidak boleh terisi jika sudah penuh (sesuai kapasitas)
- ✅ Auto update status kamar ke "terisi"

### Validasi Check-out
- ✅ Validasi driver harus dalam status checked-in
- ✅ Auto calculate cost
- ✅ Auto generate invoice
- ✅ Auto update kamar menjadi tersedia

### Activity Logging
Setiap aksi dicatat dengan:
- User ID
- Action (create, update, delete, checkin, checkout, payment)
- Model type & ID
- IP address & User agent
- Changes (untuk update)

## 📱 Mobile Responsive
- Sidebar responsive (collapse on mobile)
- Tabel scrollable
- Form mobile-friendly
- Bootstrap 5 grid system

## 🔐 Security Features
- CSRF Protection
- Input validation
- Soft delete (data tidak benar-benar terhapus)
- Activity audit trail
- Role-based access control

## 📈 Future Enhancements
- Export PDF & Excel reports
- Email notification
- SMS notification untuk reminder checkout
- Mobile app
- Real NFC card reader integration
- Multi-shift management
- Advanced analytics

## 🐛 Troubleshooting

### Database migration error
```bash
php artisan migrate:refresh
php artisan migrate:fresh --seed
```

### Asset not loading
```bash
php artisan optimize
npm run dev
```

### Permission denied
Pastikan `storage/` dan `bootstrap/cache/` writable:
```bash
chmod -R 775 storage bootstrap/cache
```

## 📄 Lisensi
MIT License

## 👥 Support
Untuk pertanyaan atau issue, silakan buat issue di repository ini.

---

**Developed with ❤️ for Mess Management System**
