-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2026 at 09:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.0.0

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `locker_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `locker_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'DRV001', 'Budi Santoso', '08123456789', 'budi@example.com', 'Jl. Merdeka No. 123, Jakarta', 'active', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(2, 'DRV002', 'Ahmad Wijaya', '08234567890', 'ahmad@example.com', 'Jl. Sudirman No. 456, Jakarta', 'active', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(3, 'DRV003', 'Siti Nurhaliza', '08345678901', 'siti@example.com', 'Jl. Gatot Subroto No. 789, Jakarta', 'active', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(4, 'DRV004', 'Rudi Hermawan', '08456789012', 'rudi@example.com', 'Jl. Terogong No. 321, Depok', 'active', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(5, 'DRV005', 'Eka Putri', '08567890123', 'eka@example.com', 'Jl. Ahmad Yani No. 654, Bekasi', 'active', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28');

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
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` bigint UNSIGNED NOT NULL,
  `checkin_id` bigint UNSIGNED NOT NULL,
  `fine_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `added_by` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `lockers`
--

CREATE TABLE `lockers` (
  `id` bigint UNSIGNED NOT NULL,
  `locker_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_id` bigint UNSIGNED DEFAULT NULL,
  `capacity` int NOT NULL DEFAULT '2',
  `status` enum('tersedia','penuh','perbaikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lockers`
--

INSERT INTO `lockers` (`id`, `locker_number`, `room_id`, `capacity`, `status`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', NULL, 2, 'tersedia', 'Locker 1 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(2, '2', NULL, 2, 'tersedia', 'Locker 2 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(3, '3', NULL, 2, 'tersedia', 'Locker 3 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(4, '4', NULL, 2, 'tersedia', 'Locker 4 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(5, '5', NULL, 2, 'tersedia', 'Locker 5 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(6, '6', NULL, 2, 'tersedia', 'Locker 6 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(7, '7', NULL, 2, 'tersedia', 'Locker 7 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(8, '8', NULL, 2, 'tersedia', 'Locker 8 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(9, '9', NULL, 2, 'tersedia', 'Locker 9 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(10, '10', NULL, 2, 'tersedia', 'Locker 10 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(11, '11', NULL, 2, 'tersedia', 'Locker 11 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(12, '12', NULL, 2, 'tersedia', 'Locker 12 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(13, '13', NULL, 2, 'tersedia', 'Locker 13 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(14, '14', NULL, 2, 'tersedia', 'Locker 14 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(15, '15', NULL, 2, 'tersedia', 'Locker 15 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(16, '16', NULL, 2, 'tersedia', 'Locker 16 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(17, '17', NULL, 2, 'tersedia', 'Locker 17 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(18, '18', NULL, 2, 'tersedia', 'Locker 18 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(19, '19', NULL, 2, 'tersedia', 'Locker 19 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(20, '20', NULL, 2, 'tersedia', 'Locker 20 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(21, '21', NULL, 2, 'tersedia', 'Locker 21 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(22, '22', NULL, 2, 'tersedia', 'Locker 22 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(23, '23', NULL, 2, 'tersedia', 'Locker 23 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(24, '24', NULL, 2, 'tersedia', 'Locker 24 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(25, '25', NULL, 2, 'tersedia', 'Locker 25 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(26, '26', NULL, 2, 'tersedia', 'Locker 26 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(27, '27', NULL, 2, 'tersedia', 'Locker 27 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(28, '28', NULL, 2, 'tersedia', 'Locker 28 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(29, '29', NULL, 2, 'tersedia', 'Locker 29 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(30, '30', NULL, 2, 'tersedia', 'Locker 30 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(31, '31', NULL, 2, 'tersedia', 'Locker 31 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(32, '32', NULL, 2, 'tersedia', 'Locker 32 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(33, '33', NULL, 2, 'tersedia', 'Locker 33 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(34, '34', NULL, 2, 'tersedia', 'Locker 34 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(35, '35', NULL, 2, 'tersedia', 'Locker 35 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(36, '36', NULL, 2, 'tersedia', 'Locker 36 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(37, '37', NULL, 2, 'tersedia', 'Locker 37 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(38, '38', NULL, 2, 'tersedia', 'Locker 38 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(39, '39', NULL, 2, 'tersedia', 'Locker 39 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(40, '40', NULL, 2, 'tersedia', 'Locker 40 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(41, '41', NULL, 2, 'tersedia', 'Locker 41 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(42, '42', NULL, 2, 'tersedia', 'Locker 42 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(43, '43', NULL, 2, 'tersedia', 'Locker 43 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(44, '44', NULL, 2, 'tersedia', 'Locker 44 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(45, '45', NULL, 2, 'tersedia', 'Locker 45 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(46, '46', NULL, 2, 'tersedia', 'Locker 46 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(47, '47', NULL, 2, 'tersedia', 'Locker 47 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(48, '48', NULL, 2, 'tersedia', 'Locker 48 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(49, '49', NULL, 2, 'tersedia', 'Locker 49 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(50, '50', NULL, 2, 'tersedia', 'Locker 50 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(51, '51', NULL, 2, 'tersedia', 'Locker 51 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(52, '52', NULL, 2, 'tersedia', 'Locker 52 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(53, '53', NULL, 2, 'tersedia', 'Locker 53 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(54, '54', NULL, 2, 'tersedia', 'Locker 54 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(55, '55', NULL, 2, 'tersedia', 'Locker 55 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(56, '56', NULL, 2, 'tersedia', 'Locker 56 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(57, '57', NULL, 2, 'tersedia', 'Locker 57 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(58, '58', NULL, 2, 'tersedia', 'Locker 58 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(59, '59', NULL, 2, 'tersedia', 'Locker 59 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(60, '60', NULL, 2, 'tersedia', 'Locker 60 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(61, '61', NULL, 2, 'tersedia', 'Locker 61 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(62, '62', NULL, 2, 'tersedia', 'Locker 62 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(63, '63', NULL, 2, 'tersedia', 'Locker 63 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(64, '64', NULL, 2, 'tersedia', 'Locker 64 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(65, '65', NULL, 2, 'tersedia', 'Locker 65 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(66, '66', NULL, 2, 'tersedia', 'Locker 66 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(67, '67', NULL, 2, 'tersedia', 'Locker 67 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(68, '68', NULL, 2, 'tersedia', 'Locker 68 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(69, '69', NULL, 2, 'tersedia', 'Locker 69 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(70, '70', NULL, 2, 'tersedia', 'Locker 70 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(71, '71', NULL, 2, 'tersedia', 'Locker 71 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(72, '72', NULL, 2, 'tersedia', 'Locker 72 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(73, '73', NULL, 2, 'tersedia', 'Locker 73 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(74, '74', NULL, 2, 'tersedia', 'Locker 74 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(75, '75', NULL, 2, 'tersedia', 'Locker 75 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(76, '76', NULL, 2, 'tersedia', 'Locker 76 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(77, '77', NULL, 2, 'tersedia', 'Locker 77 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(78, '78', NULL, 2, 'tersedia', 'Locker 78 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(79, '79', NULL, 2, 'tersedia', 'Locker 79 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(80, '80', NULL, 2, 'tersedia', 'Locker 80 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(81, '81', NULL, 2, 'tersedia', 'Locker 81 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(82, '82', NULL, 2, 'tersedia', 'Locker 82 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(83, '83', NULL, 2, 'tersedia', 'Locker 83 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(84, '84', NULL, 2, 'tersedia', 'Locker 84 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(85, '85', NULL, 2, 'tersedia', 'Locker 85 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(86, '86', NULL, 2, 'tersedia', 'Locker 86 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(87, '87', NULL, 2, 'tersedia', 'Locker 87 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(88, '88', NULL, 2, 'tersedia', 'Locker 88 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(89, '89', NULL, 2, 'tersedia', 'Locker 89 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(90, '90', NULL, 2, 'tersedia', 'Locker 90 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(91, '91', NULL, 2, 'tersedia', 'Locker 91 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(92, '92', NULL, 2, 'tersedia', 'Locker 92 - 2 capacity', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29');

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
(14, '2025_12_07_000010_create_activity_logs_table', 1),
(15, '2025_12_07_000011_create_lockers_table', 1),
(16, '2025_12_07_000012_add_locker_id_to_checkins_table', 1),
(17, '2025_12_07_000013_add_locker_id_to_checkouts_table', 1),
(18, '2025_12_07_000014_cleanup_null_locker_id_in_checkins_table', 1),
(19, '2026_01_05_165500_create_fines_table', 1),
(20, '2026_01_07_000000_add_username_to_users_table', 1),
(21, '2026_01_07_000001_update_username_in_users_table', 1),
(22, '2026_01_07_000015_make_room_id_nullable_in_lockers_table', 1);

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
(1, 'manage_drivers', 'Kelola data pengemudi', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(2, 'manage_rooms', 'Kelola data kamar', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(3, 'process_checkin', 'Proses check-in', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(4, 'process_checkout', 'Proses check-out', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(5, 'view_dashboard', 'Lihat dashboard', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(6, 'view_reports', 'Lihat laporan', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(7, 'manage_payments', 'Kelola pembayaran', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(8, 'view_activity_logs', 'Lihat activity logs', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(9, 'manage_roles', 'Kelola roles dan permissions', '2026-01-07 09:38:28', '2026-01-07 09:38:28');

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
(1, 'Petugas', 'Petugas yang mengelola check-in/out dan data operasional', '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(2, 'Management', 'Management yang melihat laporan dan dashboard', '2026-01-07 09:38:28', '2026-01-07 09:38:28');

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
(8, 2, 8, NULL, NULL),
(9, 2, 9, NULL, NULL);

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
(1, '1', 1, 'tersedia', 'Room 1 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(2, '2', 1, 'tersedia', 'Room 2 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(3, '3', 1, 'tersedia', 'Room 3 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(4, '4', 1, 'tersedia', 'Room 4 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(5, '5', 1, 'tersedia', 'Room 5 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(6, '6', 1, 'tersedia', 'Room 6 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(7, '7', 1, 'tersedia', 'Room 7 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(8, '8', 1, 'tersedia', 'Room 8 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(9, '9', 1, 'tersedia', 'Room 9 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(10, '10', 1, 'tersedia', 'Room 10 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(11, '11', 1, 'tersedia', 'Room 11 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(12, '12', 1, 'tersedia', 'Room 12 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(13, '13', 1, 'tersedia', 'Room 13 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(14, '14', 1, 'tersedia', 'Room 14 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(15, '15', 1, 'tersedia', 'Room 15 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(16, '16', 1, 'tersedia', 'Room 16 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(17, '17', 1, 'tersedia', 'Room 17 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(18, '18', 1, 'tersedia', 'Room 18 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(19, '19', 1, 'tersedia', 'Room 19 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(20, '20', 1, 'tersedia', 'Room 20 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(21, '21', 1, 'tersedia', 'Room 21 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(22, '22', 1, 'tersedia', 'Room 22 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(23, '23', 1, 'tersedia', 'Room 23 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(24, '24', 1, 'tersedia', 'Room 24 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(25, '25', 1, 'tersedia', 'Room 25 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(26, '26', 1, 'tersedia', 'Room 26 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(27, '27', 1, 'tersedia', 'Room 27 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(28, '28', 1, 'tersedia', 'Room 28 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(29, '29', 1, 'tersedia', 'Room 29 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(30, '30', 1, 'tersedia', 'Room 30 - Single bed', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28'),
(31, '31', 1, 'tersedia', 'Room 31 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(32, '32', 1, 'tersedia', 'Room 32 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(33, '33', 1, 'tersedia', 'Room 33 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(34, '34', 1, 'tersedia', 'Room 34 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(35, '35', 1, 'tersedia', 'Room 35 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(36, '36', 1, 'tersedia', 'Room 36 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(37, '37', 1, 'tersedia', 'Room 37 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(38, '38', 1, 'tersedia', 'Room 38 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(39, '39', 1, 'tersedia', 'Room 39 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(40, '40', 1, 'tersedia', 'Room 40 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(41, '41', 1, 'tersedia', 'Room 41 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(42, '42', 1, 'tersedia', 'Room 42 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(43, '43', 1, 'tersedia', 'Room 43 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(44, '44', 1, 'tersedia', 'Room 44 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(45, '45', 1, 'tersedia', 'Room 45 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(46, '46', 1, 'tersedia', 'Room 46 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(47, '47', 1, 'tersedia', 'Room 47 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(48, '48', 1, 'tersedia', 'Room 48 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(49, '49', 1, 'tersedia', 'Room 49 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(50, '50', 1, 'tersedia', 'Room 50 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(51, '51', 1, 'tersedia', 'Room 51 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(52, '52', 1, 'tersedia', 'Room 52 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(53, '53', 1, 'tersedia', 'Room 53 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(54, '54', 1, 'tersedia', 'Room 54 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(55, '55', 1, 'tersedia', 'Room 55 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(56, '56', 1, 'tersedia', 'Room 56 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(57, '57', 1, 'tersedia', 'Room 57 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(58, '58', 1, 'tersedia', 'Room 58 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(59, '59', 1, 'tersedia', 'Room 59 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(60, '60', 1, 'tersedia', 'Room 60 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(61, '61', 1, 'tersedia', 'Room 61 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(62, '62', 1, 'tersedia', 'Room 62 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(63, '63', 1, 'tersedia', 'Room 63 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(64, '64', 1, 'tersedia', 'Room 64 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(65, '65', 1, 'tersedia', 'Room 65 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(66, '66', 1, 'tersedia', 'Room 66 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(67, '67', 1, 'tersedia', 'Room 67 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(68, '68', 1, 'tersedia', 'Room 68 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(69, '69', 1, 'tersedia', 'Room 69 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(70, '70', 1, 'tersedia', 'Room 70 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(71, '71', 1, 'tersedia', 'Room 71 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(72, '72', 1, 'tersedia', 'Room 72 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(73, '73', 1, 'tersedia', 'Room 73 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(74, '74', 1, 'tersedia', 'Room 74 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(75, '75', 1, 'tersedia', 'Room 75 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(76, '76', 1, 'tersedia', 'Room 76 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(77, '77', 1, 'tersedia', 'Room 77 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(78, '78', 1, 'tersedia', 'Room 78 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(79, '79', 1, 'tersedia', 'Room 79 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(80, '80', 1, 'tersedia', 'Room 80 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(81, '81', 1, 'tersedia', 'Room 81 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(82, '82', 1, 'tersedia', 'Room 82 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(83, '83', 1, 'tersedia', 'Room 83 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(84, '84', 1, 'tersedia', 'Room 84 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(85, '85', 1, 'tersedia', 'Room 85 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(86, '86', 1, 'tersedia', 'Room 86 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(87, '87', 1, 'tersedia', 'Room 87 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(88, '88', 1, 'tersedia', 'Room 88 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(89, '89', 1, 'tersedia', 'Room 89 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(90, '90', 1, 'tersedia', 'Room 90 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(91, '91', 1, 'tersedia', 'Room 91 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(92, '92', 1, 'tersedia', 'Room 92 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(93, '93', 1, 'tersedia', 'Room 93 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(94, '94', 1, 'tersedia', 'Room 94 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(95, '95', 1, 'tersedia', 'Room 95 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(96, '96', 1, 'tersedia', 'Room 96 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(97, '97', 1, 'tersedia', 'Room 97 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(98, '98', 1, 'tersedia', 'Room 98 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(99, '99', 1, 'tersedia', 'Room 99 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(100, '100', 1, 'tersedia', 'Room 100 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(101, '101', 1, 'tersedia', 'Room 101 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(102, '102', 1, 'tersedia', 'Room 102 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(103, '103', 1, 'tersedia', 'Room 103 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(104, '104', 1, 'tersedia', 'Room 104 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(105, '105', 1, 'tersedia', 'Room 105 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(106, '106', 1, 'tersedia', 'Room 106 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(107, '107', 1, 'tersedia', 'Room 107 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(108, '108', 1, 'tersedia', 'Room 108 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(109, '109', 1, 'tersedia', 'Room 109 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(110, '110', 1, 'tersedia', 'Room 110 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(111, '111', 1, 'tersedia', 'Room 111 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(112, '112', 1, 'tersedia', 'Room 112 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(113, '113', 1, 'tersedia', 'Room 113 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(114, '114', 1, 'tersedia', 'Room 114 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(115, '115', 1, 'tersedia', 'Room 115 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(116, '116', 1, 'tersedia', 'Room 116 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(117, '117', 1, 'tersedia', 'Room 117 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(118, '118', 1, 'tersedia', 'Room 118 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(119, '119', 1, 'tersedia', 'Room 119 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(120, '120', 1, 'tersedia', 'Room 120 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(121, '121', 1, 'tersedia', 'Room 121 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(122, '122', 1, 'tersedia', 'Room 122 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(123, '123', 1, 'tersedia', 'Room 123 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(124, '124', 1, 'tersedia', 'Room 124 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(125, '125', 1, 'tersedia', 'Room 125 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(126, '126', 1, 'tersedia', 'Room 126 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(127, '127', 1, 'tersedia', 'Room 127 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(128, '128', 1, 'tersedia', 'Room 128 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(129, '129', 1, 'tersedia', 'Room 129 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(130, '130', 1, 'tersedia', 'Room 130 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(131, '131', 1, 'tersedia', 'Room 131 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(132, '132', 1, 'tersedia', 'Room 132 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(133, '133', 1, 'tersedia', 'Room 133 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(134, '134', 1, 'tersedia', 'Room 134 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(135, '135', 1, 'tersedia', 'Room 135 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(136, '136', 1, 'tersedia', 'Room 136 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(137, '137', 1, 'tersedia', 'Room 137 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(138, '138', 1, 'tersedia', 'Room 138 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(139, '139', 1, 'tersedia', 'Room 139 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(140, '140', 1, 'tersedia', 'Room 140 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(141, '141', 1, 'tersedia', 'Room 141 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(142, '142', 1, 'tersedia', 'Room 142 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(143, '143', 1, 'tersedia', 'Room 143 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(144, '144', 1, 'tersedia', 'Room 144 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(145, '145', 1, 'tersedia', 'Room 145 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(146, '146', 1, 'tersedia', 'Room 146 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(147, '147', 1, 'tersedia', 'Room 147 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(148, '148', 1, 'tersedia', 'Room 148 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(149, '149', 1, 'tersedia', 'Room 149 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29'),
(150, '150', 1, 'tersedia', 'Room 150 - Single bed', NULL, '2026-01-07 09:38:29', '2026-01-07 09:38:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'Petugas Demo', 'petugas', 'petugas@example.com', NULL, '$2y$10$MlwIRpt76mgiSsUgMY9T4ewfmog6RlW1FGw29zxSnlcE1EhHe75yu', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28', 1),
(2, 'Management Demo', 'management', 'management@example.com', NULL, '$2y$10$/wNRhoD4F3hbaMbUb3g19uflRzO33kToXCqVz8NKx7Zq12WOptZTy', NULL, '2026-01-07 09:38:28', '2026-01-07 09:38:28', 2);

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
  ADD KEY `checkins_user_id_foreign` (`user_id`),
  ADD KEY `checkins_locker_id_foreign` (`locker_id`);

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checkouts_checkin_id_foreign` (`checkin_id`),
  ADD KEY `checkouts_driver_id_foreign` (`driver_id`),
  ADD KEY `checkouts_room_id_foreign` (`room_id`),
  ADD KEY `checkouts_locker_id_foreign` (`locker_id`);

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
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fines_checkin_id_foreign` (`checkin_id`),
  ADD KEY `fines_added_by_foreign` (`added_by`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_driver_id_foreign` (`driver_id`),
  ADD KEY `invoices_checkout_id_foreign` (`checkout_id`);

--
-- Indexes for table `lockers`
--
ALTER TABLE `lockers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lockers_locker_number_unique` (`locker_number`),
  ADD KEY `lockers_room_id_foreign` (`room_id`);

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
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lockers`
--
ALTER TABLE `lockers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

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
  ADD CONSTRAINT `checkins_locker_id_foreign` FOREIGN KEY (`locker_id`) REFERENCES `lockers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkins_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD CONSTRAINT `checkouts_checkin_id_foreign` FOREIGN KEY (`checkin_id`) REFERENCES `checkins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkouts_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkouts_locker_id_foreign` FOREIGN KEY (`locker_id`) REFERENCES `lockers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkouts_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fines_checkin_id_foreign` FOREIGN KEY (`checkin_id`) REFERENCES `checkins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_checkout_id_foreign` FOREIGN KEY (`checkout_id`) REFERENCES `checkouts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lockers`
--
ALTER TABLE `lockers`
  ADD CONSTRAINT `lockers_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

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
