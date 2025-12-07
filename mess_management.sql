-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 07, 2025 at 03:58 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mess_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `changes` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `changes`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'create', 'Driver', 6, 'Created driver: Fardan Faturrahman', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 01:36:38', '2025-12-07 01:36:38'),
(2, 1, 'create', 'Room', 16, 'Created room: 347', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 01:37:01', '2025-12-07 01:37:01'),
(3, 1, 'checkin', 'Checkin', 1, 'Driver Fardan Faturrahman checked in to room 347', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 01:40:44', '2025-12-07 01:40:44'),
(4, 1, 'checkout', 'Checkout', 1, 'Driver Fardan Faturrahman checked out from room 347. Nights: 1, Cost: Rp 2,000', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 01:42:27', '2025-12-07 01:42:27'),
(5, 1, 'payment', 'Checkout', 1, 'Payment marked as paid for checkout #1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 01:42:34', '2025-12-07 01:42:34'),
(6, 1, 'checkin', 'Checkin', 2, 'Driver Budi Santoso checked in to room 301', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 02:09:27', '2025-12-07 02:09:27'),
(7, 1, 'checkout', 'Checkout', 2, 'Driver Budi Santoso checked out from room 301. Nights: 1, Cost: Rp 2,000', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 05:03:53', '2025-12-07 05:03:53'),
(8, 1, 'payment', 'Checkout', 2, 'Payment marked as paid for checkout #2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 05:04:07', '2025-12-07 05:04:07'),
(9, 1, 'checkin', 'Checkin', 3, 'Driver Siti Nurhaliza checked in to room 305', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 05:21:51', '2025-12-07 05:21:51'),
(10, 1, 'checkout', 'Checkout', 3, 'Driver Siti Nurhaliza checked out from room 305. Nights: 1, Cost: Rp 2,000', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 05:36:20', '2025-12-07 05:36:20'),
(11, 1, 'payment', 'Checkout', 3, 'Payment marked as paid for checkout #3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 05:36:26', '2025-12-07 05:36:26'),
(12, 1, 'create', 'Driver', 7, 'Created driver: test', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 07:12:46', '2025-12-07 07:12:46'),
(13, 1, 'delete', 'Driver', 7, 'Deleted driver: test', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 07:12:50', '2025-12-07 07:12:50'),
(14, 1, 'checkin', 'Checkin', 4, 'Driver Fardan Faturrahman checked in to room 101', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 07:27:50', '2025-12-07 07:27:50'),
(15, 1, 'checkout', 'Checkout', 4, 'Driver Fardan Faturrahman checked out from room 101. Nights: 1, Cost: Rp 2,000', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 07:29:51', '2025-12-07 07:29:51'),
(16, 1, 'payment', 'Checkout', 4, 'Payment marked as paid for checkout #4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 07:29:57', '2025-12-07 07:29:57'),
(17, 1, 'checkin', 'Checkin', 5, 'Driver Fardan Faturrahman checked in to room 101', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 07:52:53', '2025-12-07 07:52:53'),
(18, 1, 'checkin', 'Checkin', 6, 'Driver Eka Putri checked in to room 347', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 08:07:12', '2025-12-07 08:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `check_in_time` datetime NOT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `status` enum('checked_in','checked_out') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'checked_in',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkins`
--

INSERT INTO `checkins` (`id`, `driver_id`, `room_id`, `user_id`, `check_in_time`, `check_out_time`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 6, 16, 1, '2025-12-07 08:40:00', '2025-12-07 08:42:00', 'checked_out', NULL, '2025-12-07 01:40:44', '2025-12-07 01:42:27'),
(2, 1, 11, 1, '2025-12-07 09:09:00', '2025-12-07 12:03:00', 'checked_out', NULL, '2025-12-07 02:09:27', '2025-12-07 05:03:53'),
(3, 3, 15, 1, '2025-12-07 12:21:00', '2025-12-07 15:39:00', 'checked_out', NULL, '2025-12-07 05:21:51', '2025-12-07 05:36:20'),
(4, 6, 1, 1, '2025-12-07 14:27:00', '2025-12-07 14:29:00', 'checked_out', NULL, '2025-12-07 07:27:50', '2025-12-07 07:29:51'),
(5, 6, 1, 1, '2025-12-07 14:52:00', NULL, 'checked_in', NULL, '2025-12-07 07:52:53', '2025-12-07 07:52:53'),
(6, 5, 16, 1, '2025-12-07 15:06:00', NULL, 'checked_in', NULL, '2025-12-07 08:07:12', '2025-12-07 08:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE `checkouts` (
  `id` bigint UNSIGNED NOT NULL,
  `checkin_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `checkout_time` datetime NOT NULL,
  `nights_stayed` int NOT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `payment_status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_date` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkouts`
--

INSERT INTO `checkouts` (`id`, `checkin_id`, `driver_id`, `room_id`, `checkout_time`, `nights_stayed`, `total_cost`, `payment_status`, `payment_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 16, '2025-12-07 08:42:00', 1, 2000.00, 'paid', '2025-12-07 08:42:34', NULL, '2025-12-07 01:42:27', '2025-12-07 01:42:34'),
(2, 2, 1, 11, '2025-12-07 12:03:00', 1, 2000.00, 'paid', '2025-12-07 12:04:07', NULL, '2025-12-07 05:03:53', '2025-12-07 05:04:07'),
(3, 3, 3, 15, '2025-12-07 15:39:00', 1, 2000.00, 'paid', '2025-12-07 12:36:26', NULL, '2025-12-07 05:36:20', '2025-12-07 05:36:26'),
(4, 4, 6, 1, '2025-12-07 14:29:00', 1, 2000.00, 'paid', '2025-12-07 14:29:57', NULL, '2025-12-07 07:29:51', '2025-12-07 07:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL,
  `id_card` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `id_card`, `name`, `phone`, `email`, `address`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'DRV001', 'Budi Santoso', '08123456789', 'budi@example.com', 'Jl. Merdeka No. 123, Jakarta', 'active', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(2, 'DRV002', 'Ahmad Wijaya', '08234567890', 'ahmad@example.com', 'Jl. Sudirman No. 456, Jakarta', 'active', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(3, 'DRV003', 'Siti Nurhaliza', '08345678901', 'siti@example.com', 'Jl. Gatot Subroto No. 789, Jakarta', 'active', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(4, 'DRV004', 'Rudi Hermawan', '08456789012', 'rudi@example.com', 'Jl. Terogong No. 321, Depok', 'active', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(5, 'DRV005', 'Eka Putri', '08567890123', 'eka@example.com', 'Jl. Ahmad Yani No. 654, Bekasi', 'active', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(6, 'DRV-33598', 'Fardan Faturrahman', '089654524264', 'fardanfhj@gmail.com', 'KP CEMPAKA RT 03 / RW 03 DESA CIMAREME KECAMATAN NGAMPRAH', 'active', NULL, '2025-12-07 01:36:38', '2025-12-07 01:36:38'),
(7, 'DRV-33591', 'test', '089676941838', 'test@gmail.com', 'KP CEMPAKA RT 03 / RW 03 DESA CIMAREME KECAMATAN NGAMPRAH', 'active', '2025-12-07 07:12:50', '2025-12-07 07:12:46', '2025-12-07 07:12:50');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `checkout_id` bigint UNSIGNED DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('draft','issued','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `due_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `driver_id`, `checkout_id`, `invoice_date`, `total_amount`, `status`, `due_date`, `paid_date`, `notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'INV-202512-00001', 6, 1, '2025-12-07', 2000.00, 'paid', '2025-12-14 08:42:27', '2025-12-07 08:42:34', NULL, NULL, '2025-12-07 01:42:27', '2025-12-07 01:42:34'),
(2, 'INV-202512-00002', 1, 2, '2025-12-07', 2000.00, 'paid', '2025-12-14 12:03:53', '2025-12-07 12:04:07', NULL, NULL, '2025-12-07 05:03:53', '2025-12-07 05:04:07'),
(3, 'INV-202512-00003', 3, 3, '2025-12-07', 2000.00, 'paid', '2025-12-14 12:36:20', '2025-12-07 12:36:26', NULL, NULL, '2025-12-07 05:36:20', '2025-12-07 05:36:26'),
(4, 'INV-202512-00004', 6, 4, '2025-12-07', 2000.00, 'paid', '2025-12-14 14:29:51', '2025-12-07 14:29:57', NULL, NULL, '2025-12-07 07:29:51', '2025-12-07 07:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_12_07_000001_create_roles_table', 1),
(6, '2025_12_07_000002_create_permissions_table', 1),
(7, '2025_12_07_000003_create_role_permission_table', 1),
(8, '2025_12_07_000004_update_users_table_add_role', 1),
(9, '2025_12_07_000005_create_drivers_table', 1),
(10, '2025_12_07_000006_create_rooms_table', 1),
(11, '2025_12_07_000007_create_checkins_table', 1),
(12, '2025_12_07_000008_create_checkouts_table', 1),
(13, '2025_12_07_000009_create_invoices_table', 1),
(14, '2025_12_07_000010_create_activity_logs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'manage_drivers', 'Kelola data pengemudi', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(2, 'manage_rooms', 'Kelola data kamar', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(3, 'process_checkin', 'Proses check-in', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(4, 'process_checkout', 'Proses check-out', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(5, 'view_dashboard', 'Lihat dashboard', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(6, 'view_reports', 'Lihat laporan', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(7, 'manage_payments', 'Kelola pembayaran', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(8, 'view_activity_logs', 'Lihat activity logs', '2025-12-07 01:23:05', '2025-12-07 01:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Petugas', 'Petugas yang mengelola check-in/out dan data operasional', '2025-12-07 01:23:05', '2025-12-07 01:23:05'),
(2, 'Management', 'Management yang melihat laporan dan dashboard', '2025-12-07 01:23:05', '2025-12-07 01:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 7, NULL, NULL),
(6, 2, 5, NULL, NULL),
(7, 2, 6, NULL, NULL),
(8, 2, 8, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '1',
  `status` enum('tersedia','terisi','perbaikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `capacity`, `status`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '101', 1, 'terisi', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 07:52:53'),
(2, '102', 1, 'tersedia', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(3, '103', 2, 'tersedia', 'Double room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(4, '104', 2, 'terisi', 'Double room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(5, '105', 1, 'perbaikan', 'Single room - maintenance', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(6, '201', 1, 'tersedia', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(7, '202', 1, 'tersedia', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(8, '203', 2, 'tersedia', 'Double room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(9, '204', 2, 'tersedia', 'Double room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(10, '205', 1, 'terisi', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(11, '301', 1, 'tersedia', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 05:03:53'),
(12, '302', 1, 'tersedia', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(13, '303', 2, 'terisi', 'Double room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(14, '304', 2, 'tersedia', 'Double room', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06'),
(15, '305', 1, 'tersedia', 'Single room', NULL, '2025-12-07 01:23:06', '2025-12-07 05:36:20'),
(16, '347', 1, 'terisi', 'Test', NULL, '2025-12-07 01:37:01', '2025-12-07 08:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'Petugas Demo', 'petugas@example.com', NULL, '$2y$10$5YYseS5P5TWHn8X79FlQeuFKhgyAXoYj7u/JNabbwQqvbyNO3Jtw2', NULL, '2025-12-07 01:23:05', '2025-12-07 01:23:05', 1),
(2, 'Management Demo', 'management@example.com', NULL, '$2y$10$yQD9HOAtRsTAZ8i4d0sXNuz8mg7yD14RR.8b/.qw85IaFq5yt8j7W', NULL, '2025-12-07 01:23:06', '2025-12-07 01:23:06', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checkins_driver_id_foreign` (`driver_id`),
  ADD KEY `checkins_room_id_foreign` (`room_id`),
  ADD KEY `checkins_user_id_foreign` (`user_id`);

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checkouts_checkin_id_foreign` (`checkin_id`),
  ADD KEY `checkouts_driver_id_foreign` (`driver_id`),
  ADD KEY `checkouts_room_id_foreign` (`room_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drivers_id_card_unique` (`id_card`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_driver_id_foreign` (`driver_id`),
  ADD KEY `invoices_checkout_id_foreign` (`checkout_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permission_role_id_foreign` (`role_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rooms_room_number_unique` (`room_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkins_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD CONSTRAINT `checkouts_checkin_id_foreign` FOREIGN KEY (`checkin_id`) REFERENCES `checkins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkouts_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkouts_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_checkout_id_foreign` FOREIGN KEY (`checkout_id`) REFERENCES `checkouts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
