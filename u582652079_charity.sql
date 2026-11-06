-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 06, 2026 at 07:30 PM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u582652079_charity`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `cover_image`, `status`, `is_featured`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'الجمعية تطلق حملة كسوة الشتاء لهذا العام', 'winter-campaign-launch', 'انطلقت الحملة لتوفير الدفء للأسر المحتاجة في المناطق الباردة.', '<p>بفضل الله، أطلقت جمعيتنا حملة كسوة الشتاء التي تهدف لتوفير 5000 بطانية وملابس شتوية للأسر المتعففة والمخيمات. شاركنا الأجر.</p>', '/uploads/articles/2026/11/20261106221753_e13db501.jpg', 'published', 1, '2026-10-31 21:09:55', NULL, NULL, '2026-10-31 21:09:55', '2026-11-06 19:17:53', NULL),
(2, 2, 'تقرير ميداني: زيارة قرية \"أسل\" بعد حفر البئر', 'field-report-well', 'شاهد كيف تغيرت حياة الأهالي بعد توفر المياه النظيفة.', '<p>قمنا بزيارة القرية لمتابعة سير العمل. الأهالي يعبرون عن سعادتهم الغامرة، والأطفال أصبحوا يذهبون للمدرسة بدلاً من قطع الكيلومترات لجلب الماء.</p>', '/uploads/articles/2026/11/20261106221820_7b36518b.png', 'published', 0, '2026-10-31 21:09:55', NULL, NULL, '2026-10-31 21:09:55', '2026-11-06 19:18:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `article_categories`
--

CREATE TABLE `article_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_categories`
--

INSERT INTO `article_categories` (`id`, `name`, `slug`) VALUES
(1, 'أخبار الجمعية', 'news'),
(2, 'تقارير ميدانية', 'reports');

-- --------------------------------------------------------

--
-- Table structure for table `assistance_documents`
--

CREATE TABLE `assistance_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assistance_requests`
--

CREATE TABLE `assistance_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_number` varchar(50) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('new','reviewing','approved','rejected','completed') NOT NULL DEFAULT 'new',
  `internal_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` bigint(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.113.212', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-25 22:19:28'),
(2, 1, 'settings.update', 'settings', NULL, 'Group: brand', '197.54.113.212', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-25 22:31:39'),
(3, 1, 'settings.update', 'settings', NULL, 'Group: brand', '197.54.113.212', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-25 22:31:40'),
(4, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.113.212', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-27 22:15:53'),
(5, 2, 'auth.login', 'user', 2, 'User logged in', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 19:28:18'),
(6, 2, 'auth.logout', 'user', 2, 'User logged out', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 20:50:55'),
(7, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:03:11'),
(8, 1, 'auth.logout', 'user', 1, 'User logged out', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:11:50'),
(9, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:12:07'),
(10, 1, 'settings.update', 'settings', NULL, 'Group: brand', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:19:22'),
(11, 1, 'auth.logout', 'user', 1, 'User logged out', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:27:05'),
(12, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:27:59'),
(13, 1, 'auth.logout', 'user', 1, 'User logged out', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:28:51'),
(14, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:29:13'),
(15, 1, 'auth.logout', 'user', 1, 'User logged out', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:37:02'),
(16, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:51:30'),
(17, 1, 'project.update', 'project', 3, NULL, '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 21:52:18'),
(18, 1, 'settings.update', 'settings', NULL, 'Group: general', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 22:14:15'),
(19, 1, 'settings.update', 'settings', NULL, 'Group: general', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 22:15:03'),
(20, 1, 'settings.update', 'settings', NULL, 'Group: general', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 22:15:05'),
(21, 1, 'settings.update', 'settings', NULL, 'Group: general', '197.54.14.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-10-31 22:15:05'),
(22, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.14.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Mobile Safari/537.36', '2026-11-01 16:06:32'),
(23, 1, 'settings.update', 'settings', NULL, 'Group: general', '197.54.14.218', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 16:10:07'),
(24, 1, 'auth.login', 'user', 1, 'User logged in', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:01:44'),
(25, 1, 'settings.update', 'settings', NULL, 'Group: general', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:07:17'),
(26, 1, 'settings.update', 'settings', NULL, 'Group: contact', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:08:27'),
(27, 1, 'settings.update', 'settings', NULL, 'Group: contact', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:08:59'),
(28, 1, 'settings.update', 'settings', NULL, 'Group: seo', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:10:05'),
(29, 1, 'project.update', 'project', 4, NULL, '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:16:31'),
(30, 1, 'project.update', 'project', 4, NULL, '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:21:44'),
(31, 1, 'project.update', 'project', 4, NULL, '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:22:32'),
(32, 1, 'project.create', 'project', 7, 'Created: ســقيا الحجاج والمعــتمرين والزوار للحرمين الشريفين ( مكة والمدينة المنورة)', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:30:50'),
(33, 1, 'project.update', 'project', 7, NULL, '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:31:46'),
(34, 1, 'article.update', 'article', 2, NULL, '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:41:07'),
(35, 1, 'auth.logout', 'user', 1, 'User logged out', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:41:41'),
(36, 1, 'auth.login', 'user', 1, 'User logged in', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:42:14'),
(37, 1, 'user.create', 'user', 3, 'ahmed.oman.aa@gmail.com', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:43:32'),
(38, 1, 'auth.logout', 'user', 1, 'User logged out', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:43:49'),
(39, 1, 'auth.login', 'user', 1, 'User logged in', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:45:06'),
(40, 1, 'auth.logout', 'user', 1, 'User logged out', '128.234.24.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:46:26'),
(41, 1, 'auth.login', 'user', 1, 'User logged in', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-02 21:54:27'),
(42, 1, 'auth.login', 'user', 1, 'User logged in', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 13:46:59'),
(43, 1, 'user.create', 'user', 4, 'workahmed372@gmail.com', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 13:48:09'),
(44, 1, 'auth.logout', 'user', 1, 'User logged out', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 13:48:20'),
(45, 4, 'auth.login', 'user', 4, 'User logged in', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 13:48:30'),
(46, 4, 'project.create', 'project', 8, 'Created: أهــداء أوقف مصحفا', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:14:47'),
(47, 4, 'campaign.create', 'campaign', 3, 'أهداء أوقف مصحفا', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:16:34'),
(48, 4, 'campaign.update', 'campaign', 3, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:21:54'),
(49, 4, 'campaign.update', 'campaign', 3, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:25:54'),
(50, 4, 'campaign.update', 'campaign', 3, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:29:10'),
(51, 4, 'project.update', 'project', 2, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:40:25'),
(52, 4, 'project.update', 'project', 1, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:45:36'),
(53, 4, 'project.update', 'project', 1, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:46:51'),
(54, 4, 'project.update', 'project', 3, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:50:58'),
(55, 4, 'project.update', 'project', 3, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:51:16'),
(56, 4, 'project.update', 'project', 6, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:54:07'),
(57, 4, 'donation.status_change', 'donation', 10, 'Status: pending → awaiting_verification', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:56:32'),
(58, 4, 'project.update', 'project', 5, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 14:59:10'),
(59, 4, 'project.update', 'project', 1, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 15:18:43'),
(60, 4, 'project.update', 'project', 1, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 15:19:55'),
(61, 4, 'project.update', 'project', 5, NULL, '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 15:21:33'),
(62, 4, 'auth.logout', 'user', 4, 'User logged out', '2.90.0.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-03 15:23:30'),
(63, 1, 'auth.login', 'user', 1, 'User logged in', '2.90.37.166', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-05 13:08:39'),
(64, 1, 'project.update', 'project', 1, NULL, '2.90.37.166', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-05 13:10:17'),
(65, 1, 'auth.logout', 'user', 1, 'User logged out', '2.90.37.166', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-05 13:17:19'),
(66, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 16:23:21'),
(67, 1, 'settings.update', 'settings', NULL, 'Group: brand', '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 17:05:52'),
(68, 1, 'donation.status_change', 'donation', 12, 'Status: pending → awaiting_verification', '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 17:38:09'),
(69, 1, 'settings.update', 'settings', NULL, 'Group: brand', '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 18:01:41'),
(70, 1, 'settings.update', 'settings', NULL, 'Group: brand', '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 18:01:47'),
(71, 1, 'auth.login', 'user', 1, 'User logged in', '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 19:03:02'),
(72, 1, 'article.update', 'article', 1, NULL, '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 19:03:16'),
(73, 1, 'article.update', 'article', 2, NULL, '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 19:03:28'),
(74, 1, 'article.update', 'article', 1, NULL, '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 19:17:53'),
(75, 1, 'article.update', 'article', 2, NULL, '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 19:18:20'),
(76, 1, 'project.update', 'project', 1, NULL, '197.54.6.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-06 19:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `account_holder` varchar(150) NOT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `iban` varchar(60) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `bank_name`, `account_holder`, `account_number`, `iban`, `logo`, `instructions`, `is_active`, `sort_order`, `created_at`) VALUES
(2, 'مصرف الإمارات الإسلامي', 'جمعية نور مخة العالمية', '3578521802301', 'AE520340003578521802301', NULL, 'يرجى إرفاق صورة الإيصال بعد التحويل لتأكيد التبرع.', 1, 1, '2026-10-31 20:54:18'),
(3, 'بنك صحار الدولي', 'جمعية نور مخة العالمية', '030020014988', 'OM220300000030020014988', NULL, 'يرجى إرفاق صورة الإيصال بعد التحويل لتأكيد التبرع.', 1, 2, '2026-10-31 20:54:18'),
(4, 'بنك الرياض', 'جمعية نور مخة العالمية', '1575973509940', 'SA3520000001575973509940', NULL, 'يرجى إرفاق صورة الإيصال بعد التحويل لتأكيد التبرع.', 1, 3, '2026-10-31 20:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `goal_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `raised_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `currency` char(3) NOT NULL DEFAULT 'SAR',
  `image` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('draft','active','ended','paused') NOT NULL DEFAULT 'draft',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `project_id`, `title`, `slug`, `description`, `goal_amount`, `raised_amount`, `currency`, `image`, `start_date`, `end_date`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'حملة شتاء دافئ 2024', 'warm-winter-2024', '<p>توفير بطانيات وملابس شتوية ثقيلة للمخيمات والمناطق الباردة.</p>', 200000.00, 85000.00, 'SAR', 'https://images.unsplash.com/photo-1482517967863-00e15c9b44be?q=80&w=800', NULL, NULL, 'active', 1, '2026-10-31 21:09:55', '2026-10-31 21:09:55'),
(2, NULL, 'إفطار صائم - رمضان المبارك', 'iftar-ramadan', '<p>توزيع وجبات إفطار ساخنة يومياً في المساجد ومناطق العمال.</p>', 150000.00, 140000.00, 'SAR', 'https://images.unsplash.com/photo-1564769625905-50e93615e769?q=80&w=800', NULL, NULL, 'active', 1, '2026-10-31 21:09:55', '2026-10-31 21:09:55'),
(3, 8, 'أهداء أوقف مصحفا', 'أهداء-أوقف-مصحفا', '<p>يهدف المشروع إلى نشر كتاب الله تعالى وتيسير وصول المصاحف إلى المسلمين، وتعزيز ارتباطهم بالقرآن الكريم تلاوةً وتدبراً وحفظاً، من خلال إتاحة الفرصة للمحسنين لإهداء المصاحف وطباعتها وتوزيعها على المستفيدين.</p>\r\n<p>الأهداف التفصيلية:</p>\r\n<ol>\r\n<li>\r\n<div>\r\n<div>\r\n<p>توفير المصاحف للأشخاص الذين لا يملكون نسخاً من القرآن الكريم.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>نشر ثقافة قراءة القرآن الكريم وتدبر آياته.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>دعم حلقات تحفيظ القرآن الكريم بالمساجد ودور التعليم.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>إيصال المصاحف إلى المحتاجين والطلاب والطالبات.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تعزيز ارتباط الأطفال والناشئة بكتاب الله منذ الصغر.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>إحياء سنة إهداء المصحف وجعلها هدية نافعة ومباركة.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تشجيع المحسنين على المساهمة في نشر القرآن الكريم وطباعته وتوزيعه</p>\r\n</div>\r\n</div>\r\n</li>\r\n</ol>', 10000.00, 0.00, '', '/uploads/campaigns/2026/11/20261103172910_8e0eba6d.jpg', '2026-11-01', '2027-05-03', 'active', 1, '2026-11-03 14:16:34', '2026-11-03 14:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `icon`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'كفالة الأيتام', 'orphans', NULL, NULL, 'baby', 1, 1, '2026-10-31 21:09:55'),
(2, 'سقيا الماء', 'water', NULL, NULL, 'droplets', 1, 2, '2026-10-31 21:09:55'),
(3, 'المساعدات الغذائية', 'food', NULL, NULL, 'utensils', 1, 3, '2026-10-31 21:09:55'),
(4, 'الرعاية الصحية', 'health', NULL, NULL, 'heart-pulse', 1, 4, '2026-10-31 21:09:55'),
(5, 'بناء المساجد', 'mosques', NULL, NULL, 'landmark', 1, 5, '2026-10-31 21:09:55'),
(6, 'التعليم', 'education', NULL, NULL, 'graduation-cap', 1, 6, '2026-10-31 21:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','archived') NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(10) UNSIGNED NOT NULL,
  `donation_number` varchar(50) NOT NULL,
  `donor_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `campaign_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` decimal(14,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'SAR',
  `donation_type` enum('one_time','gift','general') NOT NULL DEFAULT 'one_time',
  `message` text DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','awaiting_verification','paid','failed','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` enum('bank_transfer','gateway','offline','cash') NOT NULL DEFAULT 'bank_transfer',
  `reference` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donation_number`, `donor_id`, `project_id`, `campaign_id`, `amount`, `currency`, `donation_type`, `message`, `is_anonymous`, `status`, `payment_method`, `reference`, `created_at`, `updated_at`, `verified_at`, `verified_by`) VALUES
(1, 'DON-20240101-A1', 1, 1, NULL, 500.00, 'SAR', 'one_time', NULL, 0, 'paid', 'bank_transfer', NULL, '2024-01-05 10:00:00', '2026-10-31 21:09:55', NULL, NULL),
(2, 'DON-20240102-B2', 2, 2, NULL, 1000.00, 'SAR', 'one_time', NULL, 0, 'paid', 'bank_transfer', NULL, '2024-01-10 14:30:00', '2026-10-31 21:09:55', NULL, NULL),
(3, 'DON-20240103-C3', 3, 3, NULL, 5000.00, 'SAR', 'one_time', NULL, 0, 'paid', 'bank_transfer', NULL, '2024-02-15 09:15:00', '2026-10-31 21:09:55', NULL, NULL),
(4, 'DON-20240104-D4', 4, 1, NULL, 250.00, 'SAR', 'one_time', NULL, 0, 'paid', 'bank_transfer', NULL, '2024-02-20 18:00:00', '2026-10-31 21:09:55', NULL, NULL),
(5, 'DON-20240105-E5', 1, 4, NULL, 100.00, 'SAR', 'one_time', NULL, 0, 'paid', 'bank_transfer', NULL, '2024-03-01 11:00:00', '2026-10-31 21:09:55', NULL, NULL),
(6, 'DON-20240106-F6', 2, 6, NULL, 300.00, 'SAR', 'one_time', NULL, 0, 'pending', 'bank_transfer', NULL, '2024-03-05 08:00:00', '2026-10-31 21:09:55', NULL, NULL),
(7, 'DON-20240107-G7', 3, 5, NULL, 10000.00, 'SAR', 'one_time', NULL, 0, 'paid', 'bank_transfer', NULL, '2024-03-10 15:45:00', '2026-10-31 21:09:55', NULL, NULL),
(8, 'DON-20261101-019AA3A4', 5, NULL, NULL, 200.00, 'SAR', 'general', NULL, 0, 'pending', 'bank_transfer', NULL, '2026-10-31 22:00:05', '2026-10-31 22:00:05', NULL, NULL),
(9, 'DON-20261102-A4119D8A', 6, 4, NULL, 100.00, '', 'gift', NULL, 0, 'pending', 'bank_transfer', NULL, '2026-11-01 22:24:08', '2026-11-01 22:24:08', NULL, NULL),
(10, 'DON-20261103-EABBC746', 7, 8, NULL, 100.00, '', 'one_time', NULL, 1, 'awaiting_verification', 'bank_transfer', NULL, '2026-11-03 14:55:41', '2026-11-03 14:56:32', NULL, NULL),
(11, 'DON-20261106-927D94AE', 5, NULL, NULL, 300.00, '', 'general', NULL, 1, 'pending', 'bank_transfer', NULL, '2026-11-06 17:37:28', '2026-11-06 17:37:28', NULL, NULL),
(12, 'DON-20261106-13704098', 5, NULL, NULL, 200.00, 'SAR', 'general', NULL, 0, 'awaiting_verification', 'bank_transfer', NULL, '2026-11-06 17:37:52', '2026-11-06 17:38:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `donation_items`
--

CREATE TABLE `donation_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `donation_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` decimal(14,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donation_items`
--

INSERT INTO `donation_items` (`id`, `donation_id`, `project_id`, `amount`, `quantity`) VALUES
(1, 8, NULL, 200.00, 1),
(2, 9, 4, 100.00, 1),
(3, 10, 8, 100.00, 1),
(4, 12, NULL, 200.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `donation_packages`
--

CREATE TABLE `donation_packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `email`, `phone`, `is_anonymous`, `created_at`) VALUES
(1, 'أحمد عبدالله', 'ahmed@example.com', '0501111111', 0, '2026-10-31 21:09:55'),
(2, 'فاعل خير', 'anon@example.com', '0502222222', 1, '2026-10-31 21:09:55'),
(3, 'شركة الأمل التجارية', 'amal@example.com', '0114444444', 0, '2026-10-31 21:09:55'),
(4, 'سارة محمد', 'sara@example.com', '0533333333', 0, '2026-10-31 21:09:55'),
(5, 'Mohamed Abo-Elmaaty', 'mo.m3aty@gmail.com', '01275844735', 0, '2026-10-31 22:00:05'),
(6, 'work ahmed', 'ahmed.oman.aa@gmail.com', '0535488493', 0, '2026-11-01 22:24:08'),
(7, 'احمد', 'workahmed372@gmail.com', '0535488493', 1, '2026-11-03 14:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `gift_donations`
--

CREATE TABLE `gift_donations` (
  `id` int(10) UNSIGNED NOT NULL,
  `donation_id` int(10) UNSIGNED NOT NULL,
  `recipient_name` varchar(150) DEFAULT NULL,
  `recipient_email` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `card_design` varchar(50) DEFAULT NULL,
  `share_token` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL,
  `secondary_text` varchar(100) DEFAULT NULL,
  `secondary_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `description`, `image`, `cta_text`, `cta_link`, `secondary_text`, `secondary_link`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'كن سبباً في رسم البسمة', 'جمعية نور مخة العالمية', 'تبرعك مهما كان صغيراً يصنع فرقاً كبيراً في حياة إنسان محتاج.', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1600', 'تبرع الآن', '/donate', NULL, NULL, 0, 1, '2026-10-31 21:09:55'),
(2, 'العنوان الرئيسي *', 'النص الفرعي (أعلى العنوان)', 'نص الوصف', '/uploads/hero/2026/11/20261106203414_f899cfcb.png', 'الزر الأول (رئيسي)', '/donate', 'الزر الثاني (شفاف اختياري)', '/projects', 0, 1, '2026-11-06 17:34:14');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `email`, `ip_address`, `success`, `created_at`) VALUES
(1, 'mo.m3aty@gmail.com', '197.54.113.212', 1, '2026-10-25 22:19:28'),
(2, 'mo.m3aty@gmail.com', '197.54.14.218', 0, '2026-10-31 19:02:07'),
(3, 'mo.m3aty@gmail.com', '197.54.14.218', 0, '2026-10-31 19:02:13'),
(4, 'mo.m3aty@gmail.com', '197.54.14.218', 0, '2026-10-31 19:02:22'),
(5, 'mo.m3aty@gmail.com', '197.54.14.218', 0, '2026-10-31 19:02:33'),
(6, 'mo.m3aty@gmail.com', '197.54.14.218', 0, '2026-10-31 19:02:41'),
(7, 'mo.m3aty@gmail.com', '197.54.14.218', 1, '2026-10-31 21:03:11'),
(8, 'mo.m3aty@gmail.com', '197.54.14.218', 0, '2026-10-31 21:11:59'),
(9, 'mo.m3aty@gmail.com', '197.54.14.218', 1, '2026-10-31 21:12:07'),
(10, 'mo.m3aty@gmail.com', '197.54.14.218', 0, '2026-10-31 21:27:54'),
(11, 'mo.m3aty@gmail.com', '197.54.14.218', 1, '2026-10-31 21:27:59'),
(12, 'mo.m3aty@gmail.com', '197.54.14.218', 1, '2026-10-31 21:29:13'),
(13, 'mo.m3aty@gmail.com', '197.54.14.218', 1, '2026-11-01 16:06:32'),
(14, 'mo.m3aty@gmail.com', '128.234.24.67', 1, '2026-11-01 22:01:44'),
(15, 'mo.m3aty@gmail.com', '128.234.24.67', 1, '2026-11-01 22:42:14'),
(16, 'ahmed.oman.aa@gmail.com', '128.234.24.67', 0, '2026-11-01 22:44:16'),
(17, 'ahmed.oman.aa@gmail.com', '128.234.24.67', 0, '2026-11-01 22:44:37'),
(18, 'mo.m3aty@gmail.com', '128.234.24.67', 1, '2026-11-01 22:45:06'),
(19, 'ahmed.oman.aa@gmail.com', '128.234.24.67', 0, '2026-11-01 22:46:40'),
(20, 'ahmed.oman.aa@gmail.com', '128.234.24.67', 0, '2026-11-01 22:47:08'),
(21, 'mo.m3aty@gmail.com', '2.90.0.148', 1, '2026-11-02 21:54:27'),
(22, 'mo.m3aty@gmail.com', '2.90.0.148', 1, '2026-11-03 13:46:59'),
(23, 'workahmed372@gmail.com', '2.90.0.148', 1, '2026-11-03 13:48:30'),
(24, 'mo.m3aty@gmail.com', '2.90.37.166', 1, '2026-11-05 13:08:39'),
(25, 'mo.m3aty@gmail.com', '197.54.6.91', 0, '2026-11-06 19:02:55'),
(26, 'mo.m3aty@gmail.com', '197.54.6.91', 1, '2026-11-06 19:03:02');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `size` int(10) UNSIGNED DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `filename`, `original_name`, `path`, `mime_type`, `size`, `width`, `height`, `uploaded_by`, `created_at`) VALUES
(1, '20261106203201_aab1ebd3.jpg', 'favicon.png', '/uploads/media/2026/11/20261106203201_aab1ebd3.jpg', 'image/jpeg', 206316, NULL, NULL, 1, '2026-11-06 17:32:01'),
(2, '20261106204505_0c932f68.jpg', 'favicon.png', '/uploads/media/2026/11/20261106204505_0c932f68.jpg', 'image/jpeg', 206316, NULL, NULL, 1, '2026-11-06 17:45:05');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, NULL, 'system', 'مرحباً بك في لوحة التحكم', 'يمكنك البدء بإضافة مشروعك الأول من قسم \"المشاريع\".', 1, '2026-10-25 21:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(150) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`, `content`, `meta_title`, `meta_description`, `status`, `updated_at`) VALUES
(1, 'privacy', 'سياسة الخصوصية', '<p>سيتم إضافة سياسة الخصوصية النهائية من قبل الإدارة.</p>', NULL, NULL, 'published', '2026-10-25 21:46:11'),
(2, 'terms', 'الشروط والأحكام', '<p>سيتم إضافة الشروط والأحكام النهائية من قبل الإدارة.</p>', NULL, NULL, 'published', '2026-10-25 21:46:11'),
(3, 'about', 'عن الجمعية', '<p>نحن جمعية خيرية تهدف إلى مساعدة المحتاجين...</p>', NULL, NULL, 'published', '2026-11-06 17:57:19');

-- --------------------------------------------------------

--
-- Table structure for table `page_sections`
--

CREATE TABLE `page_sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_key` varchar(100) NOT NULL,
  `section_key` varchar(100) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_sections`
--

INSERT INTO `page_sections` (`id`, `page_key`, `section_key`, `title`, `subtitle`, `content`, `image`, `sort_order`, `is_active`, `updated_at`) VALUES
(1, 'about', 'story', 'من فكرة إلى أثر', NULL, '<p>انطلقت جمعية نور للأعمال الخيرية إيماناً منّا بأن العطاء لا يعرف حدوداً، وأن العمل الخيري المنظم قادر على تغيير حياة إنسان. اليوم نعمل على مشاريع في مجالات الغذاء، الصحة، التعليم، المياه، وكفالة الأيتام.</p><p>نعتمد على فريق من المتطوعين المخلصين، وشراكة مع متبرعين كرماء، ونظام شفافية كامل يتيح تتبع كل ريال حتى وصوله لمستحقيه.</p>', NULL, 1, 1, '2026-10-25 21:46:11'),
(2, 'about', 'vision', 'رؤيتنا', NULL, '<p>مجتمع إنساني متكافل، لا يُترك فيه محتاج دون عون.</p>', NULL, 2, 1, '2026-10-25 21:46:11'),
(3, 'about', 'mission', 'رسالتنا', NULL, '<p>تقديم مساعدات إنسانية موثقة بشفافية لرفع المعاناة عن الأسر والأطفال المحتاجين.</p>', NULL, 3, 1, '2026-10-25 21:46:11'),
(4, 'about', 'values', 'قيمنا', NULL, '<p>الشفافية، الرحمة، الاحترافية، الأمانة، احترام الكرامة الإنسانية.</p>', NULL, 4, 1, '2026-10-25 21:46:11'),
(5, 'about', 'objectives', 'أهدافنا', NULL, '<ul><li>تقديم المساعدات الغذائية والصحية للأسر المحتاجة</li><li>كفالة الأيتام ورعاية الطلاب المحتاجين</li><li>تنفيذ مشاريع المياه والبنية التحتية</li><li>تأهيل وتدريب المتطوعين</li><li>تعزيز ثقافة العمل الخيري</li></ul>', NULL, 5, 1, '2026-10-25 21:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `logo`, `url`, `sort_order`, `is_active`) VALUES
(1, 'mohamed Aboelmaaty', '/uploads/partners/2026/11/20261106203628_ee9f1a64.webp', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `donation_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(150) DEFAULT NULL,
  `gateway` varchar(50) DEFAULT NULL,
  `amount` decimal(14,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'SAR',
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `response` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Manage Projects', 'projects.manage', ''),
(2, 'Manage Donations', 'donations.manage', ''),
(3, 'Verify Transfers', 'transfers.verify', ''),
(4, 'Manage Campaigns', 'campaigns.manage', ''),
(5, 'Manage Content', 'content.manage', ''),
(6, 'Manage Media', 'media.manage', ''),
(7, 'Manage News', 'news.manage', ''),
(8, 'Manage Volunteers', 'volunteers.manage', ''),
(9, 'Manage Assistance', 'assistance.manage', ''),
(10, 'Manage Partners', 'partners.manage', ''),
(11, 'Manage Users', 'users.manage', ''),
(12, 'Manage Settings', 'settings.manage', ''),
(13, 'View Reports', 'reports.view', ''),
(14, 'View Audit Logs', 'audit.view', '');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `objectives` longtext DEFAULT NULL,
  `beneficiaries` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `target_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `raised_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `currency` char(3) NOT NULL DEFAULT 'SAR',
  `status` enum('draft','active','funded','completed','paused','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `category_id`, `title`, `slug`, `short_description`, `description`, `objectives`, `beneficiaries`, `cover_image`, `target_amount`, `raised_amount`, `currency`, `status`, `is_featured`, `start_date`, `end_date`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'كفالة يتيم - دفعة 1448هـ', 'sponsor-orphan', 'كفالة شاملة ليتيم تشمل المأكل والملبس والتعليم.', '<p>قال رسول الله ﷺ: \"أنا وكافل اليتيم في الجنة هكذا\". نسعى في هذا المشروع لتوفير حياة كريمة لـ 100 يتيم في مختلف المناطق.</p>', '', NULL, '/uploads/projects/2026/11/20261103181955_dc9ee13c.jpg', 150000.00, 75000.00, 'SAR', 'active', 1, NULL, NULL, NULL, NULL, '2026-10-31 21:09:55', '2026-11-05 13:10:17', NULL),
(2, 6, 'أهداء كفالة طالب ووقف مصحف', 'dig-water-well', 'اكفل طالباً، وأهدِ مصحفاً، وازرع أثراً لا ينقطع', '<p>اجعل هديتك نوراً يرافق طالباً في رحلته مع كتاب الله؛ ساهم في كفالته وتوفير مصحف له ليحفظ ويتعلم ويتدبر آيات القرآن الكريم. هدية مباركة تجمع بين دعم العلم ونشر كلام الله، ونسأل الله أن يجعل لك أجر كل آية تُتلى وكل حرف يُتعلم</p>', '<p>تمكين الطالب من تعلم القرآن الكريم وحفظه من خلال كفالته وتوفير مصحف له، ليكون المتبرع شريكاً في رحلة تعليمية وإيمانية مباركة، يُرجى له أجرها وثوابها من الله تعالى.</p>\r\n<ul>\r\n<li>\r\n<div>\r\n<div>\r\n<p>دعم حلقات تحفيظ القرآن الكريم واستمرار برامجها التعليمية.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تعزيز قيم الصدقة والإحسان والمشاركة المجتمعية.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>المساهمة في تخريج جيل يتعلم القرآن ويعمل بتعاليمه وينشر الخير بين الناس</p>\r\n</div>\r\n</div>\r\n</li>\r\n</ul>', 'الأطفال والناشئة: الراغبون في تعلم القرآن وحفظه منذ الصغر.\r\n\r\n\r\n\r\nالأيتام: توفير كفالة تعليمية ومصحف يعينهم على حفظ كتاب الله.\r\n\r\n\r\n\r\nأبناء الأسر محدودة الدخل: الطلاب غير القادرين على تحمل رسوم حلقات التحفيظ.\r\n\r\n\r\n\r\nطلاب وطالبات حلقات التحفيظ: دعم مسيرتهم التعليمية وتوفير المصاحف لهم.\r\n\r\n\r\n\r\nالطلاب والطالبات المتفوقون في الحفظ: تشجيعهم على مواصلة الحفظ والإتقان.\r\n\r\n\r\n\r\nالكبار وكبار السن: الراغبون في تعلم القراءة الصحيحة وحفظ القرآن الكريم.\r\n\r\n\r\n\r\nالراغبون في تصحيح التلاوة: ممن يحتاجون إلى تأسيس قرآني ومتابعة تعليمية.', '/uploads/projects/2026/11/20261103174025_aeac1b2a.jpg', 85000.00, 42000.00, 'SAR', 'active', 1, '2026-11-01', '2028-01-03', NULL, NULL, '2026-10-31 21:09:55', '2026-11-03 14:40:25', NULL),
(3, 3, 'سلة غذائية للأسر المتعففة', 'food-basket', 'توفير احتياجات غذائية أساسية لشهر كامل.', '<p>توفير سلال تحتوي على الأرز، الدقيق، الزيت، والمواد الأساسية لدعم الأسر الأشد احتياجاً.</p>', '', NULL, '/uploads/projects/2026/11/20261103175058_71025b19.jpg', 50000.00, 48500.00, 'SAR', 'active', 1, NULL, NULL, NULL, NULL, '2026-10-31 21:09:55', '2026-11-03 14:50:58', NULL),
(4, 6, 'كفالة طالب لتعلم القران', 'heart-surgery', 'توفير فرص تعليم القرآن الكريم للطلاب غير القادرين على تحمل الرسوم الدراسية', '<p data-pm-slice=\"1 1 []\">بمساهمتك في كفالة طالب يتعلم القرآن الكريم ويحفظ آياته، أنت لا تقدم دعماً تعليمياً فحسب، بل تساهم في بناء جيل يرتبط بكتاب الله حفظاً وتلاوةً وعملاً.</p>\r\n<p>تتكفل مساهمتك بتوفير فرصة تعليمية للطالب تشمل تعليم القراءة الصحيحة، وتحفيظ القرآن الكريم، ومتابعة الحفظ والمراجعة، وتصحيح التلاوة، على أيدي معلمين ومعلمات مؤهلين.</p>\r\n<p>وقد يكون هذا الطالب سبباً في تعليم غيره مستقبلاً، ونشر الخير في أسرته ومجتمعه، فتكون مساهمتك بإذن الله بداية لأثر يمتد ولا ينقطع.</p>', '<p>تمكين الطلاب والطالبات من تعلم القرآن الكريم وحفظه وإتقان تلاوته، من خلال توفير الرعاية التعليمية والتربوية لهم، وتأمين تكاليف تعليمهم ومتابعتهم على أيدي معلمين ومعلمات مؤهلين، بما يسهم في بناء جيل مرتبط بكتاب الله، يعمل بتعاليمه وينشر نوره في المجتمع.</p>\r\n<p>الأهداف الفرعية:</p>\r\n<ol>\r\n<li>\r\n<div>\r\n<div>\r\n<p>توفير فرص تعليم القرآن الكريم للطلاب غير القادرين على تحمل الرسوم الدراسية.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تشجيع حفظ القرآن الكريم وإتقانه ومراجعته.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تعليم أحكام التجويد وتصحيح التلاوة.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>غرس القيم الإسلامية والأخلاق القرآنية في نفوس الطلاب.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تخفيف الأعباء المالية عن الأسر المحتاجة.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تشجيع المحسنين على المساهمة في نشر تعليم القرآن الكريم.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>إيجاد أثر مستدام يمتد من الطالب إلى أسرته ومجتمعه.</p>\r\n</div>\r\n</div>\r\n</li>\r\n</ol>\r\n<p>الهدف الأسمى:</p>\r\n<p>أن يكون كل طالب نكفله مشروع حافظٍ لكتاب الله، وأن تكون مساهمة المتبرع سبباً في استمرار رحلته مع القرآن الكريم، ونيل الأجر والثواب من الله تعالى.</p>', 'يستهدف المشروع الطلاب والطالبات الراغبين في تعلم القرآن الكريم وحفظه، مع إعطاء الأولوية للفئات الأكثر احتياجاً للدعم والرعاية التعليمية.\r\n\r\nالفئات المستهدفة:\r\n1. الأطفال: من عمر 3 إلى 12 سنة، لتأسيسهم في القراءة الصحيحة وتعليمهم القرآن الكريم والحفظ المبكر.\r\n2. الناشئة والشباب: من عمر 13 إلى 18 سنة، لتنمية مهارات الحفظ والتلاوة والتجويد.\r\n3. الأسر محدودة الدخل: أبناء الأسر غير القادرة على تحمل رسوم حلقات تحفيظ القرآن.\r\n4. الأيتام: توفير فرص تعليمية ورعاية قرآنية لهم.\r\n5. الطلاب والطالبات من ذوي الحاجة: ممن لديهم رغبة جادة في تعلم القرآن ولا تسمح ظروفهم المادية بالالتحاق بالحلقات.\r\n6. الكبار وكبار السن: من عمر 19 إلى 70 سنة، الراغبين في تعلم القراءة الصحيحة وحفظ القرآن الكريم ومراجعته.\r\n7. الراغبون في تصحيح التلاوة: ممن يحتاجون إلى تأسيس قرآني أو تحسين القراءة وأحكام التجويد.\r\nالأولوية في الاستفادة\r\n- الأيتام وأبناء الأسر الأشد احتياجاً.\r\n- الطلاب والطالبات غير القادرين على دفع الرسوم.\r\n- أصحاب الهمة والرغبة الجادة في حفظ كتاب الله.\r\n- من لم تتح لهم فرص سابقة لتعلم القرآن الكريم', '/uploads/projects/2026/11/20261102011631_ffb2a88e.jpg', 60000.00, 15000.00, 'SAR', 'active', 0, NULL, NULL, NULL, NULL, '2026-10-31 21:09:55', '2026-11-01 22:21:44', NULL),
(5, 5, 'بناء مسجد في قرية فقيرة', 'build-mosque', 'صدقة جارية تخدم أهالي القرية للأجيال.', '<p>بناء مسجد بمساحة 200 متر مربع ليكون مركزاً للعبادة وتحفيظ القرآن للأطفال.</p>', '', NULL, '/uploads/projects/2026/11/20261103182133_01ff6c48.jpg', 300000.00, 120000.00, 'SAR', 'active', 0, NULL, NULL, NULL, NULL, '2026-10-31 21:09:55', '2026-11-03 15:21:33', NULL),
(6, 6, 'حقيبة مدرسية للطلاب الأيتام', 'school-bag', 'تجهيز الطلاب بالزي والحقائب والأدوات.', '<p>مع بداية العام الدراسي، نهدف لتجهيز 500 طالب يتيم بكافة مستلزماتهم المدرسية.</p>', '', NULL, '/uploads/projects/2026/11/20261103175407_916b5a3b.jpg', 75000.00, 30000.00, 'SAR', 'active', 1, NULL, NULL, NULL, NULL, '2026-10-31 21:09:55', '2026-11-03 14:54:07', NULL),
(7, 2, 'ســقيا الحجاج والمعــتمرين والزوار', 'ســقيا-الحجاج-والمعــتمرين-والزوار-للحرمين-الشريفين-مكة-والمدينة-المنورة', 'سقيا الماء لضيوف الرحمن\r\n\r\nساهم في توفير الماء البارد للحجاج والمعتمرين وزوار الحرمين الشريفين في مكة المكرمة والمدينة المنورة. اجعل تبرعك سبباً في إرواء عطشان، وتفريج حاجة، ونيل أجر السقيا بإذن الله.\r\n\r\nاسقِهم ماءً، وابتغِ الأجر من الله', '<p>سقيا الماء لضيوف الرحمن</p>\r\n<p>ساهم في توفير الماء البارد للحجاج والمعتمرين وزوار الحرمين الشريفين في مكة المكرمة والمدينة المنورة. اجعل تبرعك سبباً في إرواء عطشان، وتفريج حاجة، ونيل أجر السقيا بإذن الله.</p>\r\n<p>اسقِهم ماءً، وابتغِ الأجر من الله</p>', '<ul>\r\n<li>\r\n<div>\r\n<div>\r\n<p>توفير مياه الشرب: توفير مياه نظيفة وباردة للحجاج والمعتمرين وزوار المسجد الحرام والمسجد النبوي.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>خدمة ضيوف الرحمن: المساهمة في تيسير أداء مناسك الحج والعمرة وتخفيف مشقة العطش والتعب.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تعزيز التكافل الاجتماعي: إتاحة الفرصة للمحسنين للمشاركة في خدمة ضيوف الرحمن.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>نشر ثقافة الصدقة: تشجيع أفراد المجتمع على المساهمة في سقيا الماء واغتنام فضلها.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>الوصول إلى المحتاجين: إيصال المياه إلى المواقع التي تزداد فيها حاجة الزوار والحجاج والمعتمرين.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تعظيم الأجر والثواب: إتاحة باب من أبواب الخير والصدقة ابتغاء مرضاة الله.</p>\r\n</div>\r\n</div>\r\n</li>\r\n</ul>', 'يستهدف المشروع الفئات التالية في مكة المكرمة والمدينة المنورة:\r\n1. الحجاج: ضيوف الرحمن القادمون من داخل المملكة وخارجها لأداء مناسك الحج.\r\n2. المعتمرون: القادمون لأداء مناسك العمرة من مختلف دول العالم.\r\n3. زوار الحرمين الشريفين: زوار المسجد الحرام والمسجد النبوي.\r\n4. كبار السن: ممن يحتاجون إلى توفير المياه خلال تنقلاتهم وأداء العبادات.\r\n5. الأشخاص ذوو الإعاقة: لتيسير حصولهم على مياه الشرب.\r\n6. العاملون والمتطوعون في خدمة ضيوف الرحمن: ممن يحتاجون إلى المياه أثناء أداء مهامهم الميدانية', '/uploads/projects/2026/11/20261102013050_c7a189d9.png', 10000.00, 0.00, '', 'active', 0, '2026-11-01', '2027-12-02', NULL, NULL, '2026-11-01 22:30:50', '2026-11-01 22:31:46', NULL),
(8, NULL, 'أهــداء أوقف مصحفا', 'أهــداء-أوقف-مصحفا', 'أهدِ مصحفاً.. واترك أثراً لا ينقطع', '<p>ساهم في إهداء المصاحف لتصل إلى من يرغب في قراءة كتاب الله وتدبّره، واجعل هديتك باباً للخير وأملاً في الأجر كلما تُليت آية وقُرئت سورة. فقد تكون هديتك بداية رحلة إنسان مع القرآن الكريم.</p>', '<ul>\r\n<li>\r\n<div>\r\n<div>\r\n<p>وفير المصاحف للأشخاص الذين لا يملكون نسخاً من القرآن الكريم.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>نشر ثقافة قراءة القرآن الكريم وتدبر آياته.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>دعم حلقات تحفيظ القرآن الكريم بالمساجد ودور التعليم.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>إيصال المصاحف إلى المحتاجين والطلاب والطالبات.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تعزيز ارتباط الأطفال والناشئة بكتاب الله منذ الصغر.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>إحياء سنة إهداء المصحف وجعلها هدية نافعة ومباركة.</p>\r\n</div>\r\n</div>\r\n</li>\r\n<li>\r\n<div>\r\n<div>\r\n<p>تشجيع المحسنين على المساهمة في نشر القرآن الكريم وطباعته وتوزيعه</p>\r\n</div>\r\n</div>\r\n</li>\r\n</ul>', 'طلاب وطالبات حلقات تحفيظ القرآن الكريم: لدعم رحلتهم في الحفظ والتلاوة والمراجعة.\r\n\r\n\r\n\r\nالأطفال والناشئة: لتشجيعهم على تعلم القرآن الكريم والارتباط به منذ الصغر.\r\n\r\n\r\n\r\nالمساجد والجوامع: تزويدها بالمصاحف لتكون متاحة للمصلين ومرتاديها.\r\n\r\n\r\n\r\nدور تحفيظ القرآن الكريم: توفير المصاحف للطلاب والمعلمين والمعلمات.\r\n\r\n\r\n\r\nالأسر محدودة الدخل: توفير المصاحف للأسر التي لا تستطيع شراءها.\r\n\r\n\r\n\r\nالأيتام والمحتاجون: تمكينهم من امتلاك مصحف خاص بهم.\r\n\r\n\r\n\r\nكبار السن: توفير المصاحف المناسبة لهم، بما فيها المصاحف كبيرة الخط.\r\n\r\n\r\n\r\nالمسلمون الجدد: مساعدتهم على التعرف إلى كتاب الله والبدء في قراءته وتعلمه.\r\n\r\n\r\n\r\nالحجاج والمعتمرون وزوار الحرمين الشريفين: إهداؤهم المصاحف للاستفادة منها في رحلتهم الإيمانية.', '/uploads/projects/2026/11/20261103171447_c024fa58.jpg', 5000.00, 0.00, '', 'active', 0, '2026-11-01', '2027-06-03', NULL, NULL, '2026-11-03 14:14:47', '2026-11-03 14:14:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_images`
--

CREATE TABLE `project_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_updates`
--

CREATE TABLE `project_updates` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Super Admin', 'super_admin', 'Full access', '2026-10-25 21:46:11'),
(2, 'Administrator', 'administrator', 'Manage all except critical', '2026-10-25 21:46:11'),
(3, 'Content Editor', 'content_editor', 'Manage content, news, pages', '2026-10-25 21:46:11'),
(4, 'Donation Manager', 'donation_manager', 'Manage donations & payments', '2026-10-25 21:46:11'),
(5, 'Volunteer Manager', 'volunteer_manager', 'Manage volunteer applications', '2026-10-25 21:46:11'),
(6, 'Viewer', 'viewer', 'Read-only access', '2026-10-25 21:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 2),
(4, 2),
(1, 3),
(2, 3),
(4, 3),
(1, 4),
(2, 4),
(4, 4),
(1, 5),
(2, 5),
(3, 5),
(1, 6),
(2, 6),
(3, 6),
(1, 7),
(2, 7),
(3, 7),
(1, 8),
(2, 8),
(5, 8),
(1, 9),
(2, 9),
(1, 10),
(2, 10),
(1, 11),
(1, 12),
(1, 13),
(2, 13),
(4, 13),
(6, 13),
(1, 14),
(2, 14);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` longtext DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `type` varchar(20) NOT NULL DEFAULT 'text',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `type`, `updated_at`) VALUES
(1, 'site.name_ar', '', 'brand', 'text', '2026-10-31 22:14:15'),
(2, 'site.name_en', '', 'brand', 'text', '2026-10-31 22:14:15'),
(3, 'site.assoc_name_ar', '', 'brand', 'text', '2026-10-31 22:14:15'),
(4, 'site.description', '', 'brand', 'textarea', '2026-10-31 22:14:15'),
(5, 'site.logo', '/assets/images/logo.png', 'brand', 'image', '2026-10-25 21:46:11'),
(6, 'site.favicon', '/assets/images/favicon.png', 'brand', 'image', '2026-10-25 21:46:11'),
(7, 'brand.primary', '', 'brand', 'color', '2026-10-31 21:19:22'),
(8, 'brand.navy', '', 'brand', 'color', '2026-10-25 22:31:39'),
(9, 'brand.secondary', '', 'brand', 'color', '2026-10-25 22:31:39'),
(10, 'brand.ice', '', 'brand', 'color', '2026-10-25 22:31:39'),
(11, 'brand.gold', '', 'brand', 'color', '2026-10-31 21:19:22'),
(12, 'site.phone', '0501234567', 'contact', 'text', '2026-11-06 17:12:07'),
(13, 'site.whatsapp', '0501234567', 'contact', 'text', '2026-11-06 17:12:07'),
(14, 'site.email', 'info@nourcharity.com', 'contact', 'text', '2026-11-06 17:12:07'),
(15, 'site.address', 'المملكة العربية السعودية، مكة المكرمة، حي الشوقية', 'contact', 'textarea', '2026-11-06 17:12:07'),
(16, 'site.facebook', 'https://facebook.com', 'social', 'text', '2026-10-27 17:48:39'),
(17, 'site.twitter', 'https://twitter.com/nourcharity', 'social', 'text', '2026-11-06 17:12:07'),
(18, 'site.instagram', 'https://instagram.com', 'social', 'text', '2026-10-27 17:48:39'),
(19, 'site.youtube', '', 'social', 'text', '2026-10-25 21:46:11'),
(20, 'site.currency', '', 'general', 'text', '2026-10-31 22:14:15'),
(21, 'site.currency_symbol', '', 'general', 'text', '2026-10-31 22:14:15'),
(22, 'seo.default_title', '', 'seo', 'text', '2026-11-01 22:10:05'),
(23, 'seo.default_description', '', 'seo', 'textarea', '2026-11-01 22:10:05'),
(24, 'maintenance.mode', '0', 'general', 'boolean', '2026-10-25 21:46:11'),
(25, 'hero.title', 'معاً نصنع الأمل', 'hero', 'text', '2026-10-31 19:00:24'),
(26, 'hero.description', 'عطاؤك يضيء دروبهم، شاركنا الآن في مشاريعنا الخيرية المستدامة التي تلامس احتياجات الإنسان.', 'hero', 'textarea', '2026-10-31 19:00:24'),
(27, 'hero.cta_primary_text', 'تبرع الآن', 'hero', 'text', '2026-10-25 21:46:11'),
(28, 'hero.cta_primary_link', '/donate', 'hero', 'text', '2026-10-25 21:46:11'),
(29, 'hero.cta_secondary_text', 'اكتشف مشاريعنا', 'hero', 'text', '2026-10-25 21:46:11'),
(30, 'hero.cta_secondary_link', '/projects', 'hero', 'text', '2026-10-25 21:46:11'),
(31, 'donation.presets', '100,300,500,1000,5000', 'donations', 'text', '2026-10-31 19:00:24'),
(32, 'donation.instructions', 'يرجى تحويل المبلغ إلى الحساب البنكي ثم رفع صورة التحويل لتأكيد التبرع.', 'donations', 'textarea', '2026-10-25 21:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_receipts`
--

CREATE TABLE `transfer_receipts` (
  `id` int(10) UNSIGNED NOT NULL,
  `donation_id` int(10) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp(),
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int(10) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_receipts`
--

INSERT INTO `transfer_receipts` (`id`, `donation_id`, `file_path`, `original_name`, `mime_type`, `uploaded_at`, `verified_at`, `verified_by`, `notes`) VALUES
(1, 10, 'receipts/2026/11/rcpt_20261103175632_0d429879.png', 'صورة البوث من الامام.png', 'image/png', '2026-11-03 14:56:32', NULL, NULL, NULL),
(2, 12, 'receipts/2026/11/rcpt_20261106203809_ac473a87.jpg', 'Gemini_Generated_Image_quyi73quyi73quyi.jpg', 'image/jpeg', '2026-11-06 17:38:09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `lang` char(2) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `avatar`, `role_id`, `is_active`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'mohamed Aboelmaaty', 'mo.m3aty@gmail.com', '$2y$10$b2p31ljOrRPkJLK2Eylt0eZC/BwPxvYRLaR4LPs8RTJUeHVLdA1FS', NULL, NULL, 1, 1, '2026-11-06 19:03:02', '2026-10-25 22:19:15', '2026-11-06 19:03:02', NULL),
(2, 'mohamed Aboelmaaty', 'mo.m3aty@outlook.com', '$2y$10$GGkzhKZMwHnTJyjBeVUECOzwWEptNBuTirpl9V0wdX2J14/EGq5bm', NULL, NULL, 6, 1, '2026-10-31 19:28:18', '2026-10-31 19:28:06', '2026-10-31 19:28:18', NULL),
(3, 'ِNour', 'ahmed.oman.aa@gmail.com', '$2y$10$ktr6Io537/PBU8H2ERQE2uHj1lEJ4A1JoFmo/yVOu.i.Op7ioLO92', NULL, NULL, 1, 1, NULL, '2026-11-01 22:43:32', '2026-11-01 22:43:32', NULL),
(4, 'Ahmed', 'workahmed372@gmail.com', '$2y$10$SiqekKgzt0wan2eH/6Og5uzH7gX1RNzAJZWFwPAYTLAMqRM4xjoaK', NULL, NULL, 1, 1, '2026-11-03 13:48:30', '2026-11-03 13:48:09', '2026-11-03 13:48:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_applications`
--

CREATE TABLE `volunteer_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `opportunity_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `availability` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `status` enum('new','reviewing','accepted','rejected') NOT NULL DEFAULT 'new',
  `internal_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_opportunities`
--

CREATE TABLE `volunteer_opportunities` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `commitment` varchar(100) DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `volunteer_opportunities`
--

INSERT INTO `volunteer_opportunities` (`id`, `title`, `slug`, `description`, `location`, `commitment`, `status`, `created_at`) VALUES
(1, 'منسق حملات ميدانية', 'field-coordinator', 'مطلوب متطوعين للمساعدة في تنظيم وتوزيع المساعدات الميدانية.', 'الرياض', NULL, 'open', '2026-10-31 21:09:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_art_cat` (`category_id`);

--
-- Indexes for table `article_categories`
--
ALTER TABLE `article_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `assistance_documents`
--
ALTER TABLE `assistance_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_adoc_req` (`request_id`);

--
-- Indexes for table `assistance_requests`
--
ALTER TABLE `assistance_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_number` (`request_number`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_camp_project` (`project_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `donation_number` (`donation_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_project` (`project_id`),
  ADD KEY `idx_campaign` (`campaign_id`),
  ADD KEY `idx_donor` (`donor_id`),
  ADD KEY `fk_don_verified` (`verified_by`);

--
-- Indexes for table `donation_items`
--
ALTER TABLE `donation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ditem_don` (`donation_id`),
  ADD KEY `fk_ditem_project` (`project_id`);

--
-- Indexes for table `donation_packages`
--
ALTER TABLE `donation_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pkg_project` (`project_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `gift_donations`
--
ALTER TABLE `gift_donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `share_token` (`share_token`),
  ADD KEY `fk_gift_don` (`donation_id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_ip` (`email`,`ip_address`,`created_at`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_media_user` (`uploaded_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_user` (`user_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `page_sections`
--
ALTER TABLE `page_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_page_section` (`page_key`,`section_key`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_token` (`token`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tx_don` (`donation_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_category` (`category_id`);

--
-- Indexes for table `project_images`
--
ALTER TABLE `project_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pimg_project` (`project_id`);

--
-- Indexes for table `project_updates`
--
ALTER TABLE `project_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pupd_project` (`project_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `fk_rp_perm` (`permission_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `transfer_receipts`
--
ALTER TABLE `transfer_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_receipt_don` (`donation_id`),
  ADD KEY `fk_receipt_ver` (`verified_by`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_translation` (`lang`,`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role_id`);

--
-- Indexes for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vol_opp` (`opportunity_id`);

--
-- Indexes for table `volunteer_opportunities`
--
ALTER TABLE `volunteer_opportunities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `article_categories`
--
ALTER TABLE `article_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `assistance_documents`
--
ALTER TABLE `assistance_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assistance_requests`
--
ALTER TABLE `assistance_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `donation_items`
--
ALTER TABLE `donation_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donation_packages`
--
ALTER TABLE `donation_packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gift_donations`
--
ALTER TABLE `gift_donations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `page_sections`
--
ALTER TABLE `page_sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_images`
--
ALTER TABLE `project_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_updates`
--
ALTER TABLE `project_updates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `transfer_receipts`
--
ALTER TABLE `transfer_receipts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `volunteer_opportunities`
--
ALTER TABLE `volunteer_opportunities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_art_cat` FOREIGN KEY (`category_id`) REFERENCES `article_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `assistance_documents`
--
ALTER TABLE `assistance_documents`
  ADD CONSTRAINT `fk_adoc_req` FOREIGN KEY (`request_id`) REFERENCES `assistance_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `fk_camp_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `fk_don_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_don_verified` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `donation_items`
--
ALTER TABLE `donation_items`
  ADD CONSTRAINT `fk_ditem_don` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ditem_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `donation_packages`
--
ALTER TABLE `donation_packages`
  ADD CONSTRAINT `fk_pkg_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gift_donations`
--
ALTER TABLE `gift_donations`
  ADD CONSTRAINT `fk_gift_don` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `fk_tx_don` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_project_cat` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_images`
--
ALTER TABLE `project_images`
  ADD CONSTRAINT `fk_pimg_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_updates`
--
ALTER TABLE `project_updates`
  ADD CONSTRAINT `fk_pupd_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_receipts`
--
ALTER TABLE `transfer_receipts`
  ADD CONSTRAINT `fk_receipt_don` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_receipt_ver` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  ADD CONSTRAINT `fk_vol_opp` FOREIGN KEY (`opportunity_id`) REFERENCES `volunteer_opportunities` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
