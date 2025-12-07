Saya ingin Anda membuatkan sistem lengkap Manajemen Kamar Mess Pengemudi menggunakan Laravel 8 dan MySQL, dengan fitur simulasi NFC ID Card, biaya menginap harian, dan dashboard modern penuh laporan.

KONTEKS & ARSITEKTUR SISTEM

Digunakan oleh 2 role utama: Petugas dan Management.

Pengemudi check-in & check-out menggunakan ID Card NFC, namun karena belum memiliki alatnya, gunakan API simulasi NFC (misal: /api/nfc/read/{id_card}).

Terdapat ± 200 kamar mess.

Gunakan template dashboard yang modern dan responsif, seperti Bootstrap 5, AdminLTE, atau Tailwind.

Gunakan Chart.js untuk visualisasi laporan.

Setiap pengemudi dikenakan biaya Rp 2.000 per hari.

Saat checkout, status kamar otomatis berubah menjadi “tersedia / kosong”.

Sistem harus menggunakan Role & Permission, log aktivitas, validasi occupancy, dan soft delete.

FITUR SISTEM
1. Role & Permission

Role: Petugas, Management

Petugas: CRUD data, proses check-in/out

Management: melihat laporan & dashboard

Gunakan middleware untuk batasan akses

2. Fitur untuk Pengemudi (melalui operasi petugas)
Check-in

Petugas scan ID Card (via API simulasi)

Sistem menampilkan data pengemudi

Petugas pilih kamar → kamar berubah status menjadi terisi

Sistem memvalidasi:

tidak boleh check-in dua kali

kamar tidak boleh terisi dua kali

Check-out

Petugas scan ID Card

Sistem menghitung lama menginap otomatis (per hari, berdasarkan jam)

Biaya = lama menginap × 2.000

Kamar otomatis berubah menjadi tersedia

Data masuk ke riwayat

3. Fitur untuk Petugas

Login & Logout

Entri data pengemudi

Entri data kamar mess

Nomor kamar

Kapasitas

Status (tersedia / terisi / perbaikan)

Pemesanan kamar

Proses check-in / check-out

Rekap data operasional

Pencarian & filter

Log aktivitas (audit trail) setiap tindakan

4. Fitur untuk Management

Dashboard penuh insight:

Total kamar terisi

Total kamar kosong

Check-in harian

Check-out harian

Grafik check-in / check-out bulanan (Chart.js)

Total biaya yang sudah dibayarkan / dipungut

Rekap laporan harian, mingguan, dan bulanan

Export PDF / Excel

5. API & Simulasi NFC

Buat endpoint simulasi NFC:

Input: ID Card

Output JSON:

id_card

waktu_scan

status

Endpoint dipakai untuk proses check-in dan check-out.

6. Validasi Sistem

Driver tidak bisa check-in 2 kali

Driver tidak bisa check-out jika belum check-in

Kamar tidak bisa ditempati dua driver (kecuali kapasitas > 1)

Soft delete untuk pengemudi, kamar, riwayat

7. Perhitungan Lama Menginap

Hitung selisih jam

Konversi ke hari (24 jam = 1 hari)

Jika lewat 24 jam → hari bertambah

Format:
total_biaya = jumlah_hari × 2000

8. Dashboard & UI

Gunakan template admin modern (AdminLTE / SB Admin / Bootstrap 5)

Fitur mobile-friendly

Mode pencarian, filter, dan pagination

Chart.js untuk data laporan

Dark mode opsional

9. Keluaran yang Harus Dihasilkan

Struktur project Laravel

Migration tabel:

users

roles & permissions

drivers

rooms

bookings / checkin

checkout

biaya / invoice

activity_logs

Model + relasi Eloquent lengkap

Controller:

DriverController

RoomController

CheckinController

CheckoutController

DashboardController

NFCController (simulasi)

Service logic:

check-in

check-out + hitung biaya

update status kamar otomatis

audit trail

Routes: web.php dan api.php

Blade template CRUD & dashboard

Integrasi Chart.js

Flow proses check-in / check-out

Contoh UI dashboard dan form
