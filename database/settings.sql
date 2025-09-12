-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2025 at 10:52 PM
-- Server version: 9.4.0
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trypbug`
--

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'site_logo', 'storage/logos/7Hx0NJN8GwxI1srCWrhfQQpt1hJx7CS4fu33xzhC.png', 'appearance', NULL, '2025-05-30 03:37:28'),
(2, 'site_logo_mobile', 'storage/logos/OclrFfvEuEAL70hVIGHzfj7S1Gr9BTzTnZZjMwLk.png', 'appearance', NULL, '2025-05-30 03:37:28'),
(3, 'header_bg_color_from', '#3e68bb', 'appearance', NULL, '2025-05-26 12:55:59'),
(4, 'header_bg_color_to', '#3e68bb', 'appearance', NULL, '2025-05-26 12:55:59'),
(5, 'footer_bg_color_from', '#5a82d3', 'appearance', NULL, '2025-05-26 12:55:59'),
(6, 'footer_bg_color_to', '#5a82d3', 'appearance', NULL, '2025-05-26 12:55:59'),
(7, 'company_name', 'Tryp Bug', 'general', NULL, '2025-06-11 03:31:31'),
(8, 'company_address', '7901 4th St N  St. Petersburg, FL  33702', 'contact', NULL, '2025-06-11 03:32:59'),
(9, 'company_phone', '1-800-918-9975', 'contact', NULL, '2025-06-11 03:32:59'),
(10, 'company_email', 'info@mytravel.com', 'contact', NULL, '2025-05-30 12:11:49'),
(11, 'office_hours', 'Everyday: 10AM-10PM EST', 'contact', NULL, '2025-06-11 03:32:59'),
(12, 'about_us_short', 'TrypBug has been helping travelers explore the world for over 15 years. We\'re committed to providing unforgettable experiences.', 'general', NULL, '2025-06-11 03:31:31'),
(13, 'hero_heading', 'Discover Your Perfect Getaway', 'appearance', '2025-05-02 03:15:00', '2025-05-26 11:41:31'),
(14, 'hero_subheading', 'Explore exclusive vacation packages and create memories that last a lifetime', 'appearance', '2025-05-02 03:15:00', '2025-05-26 11:41:31'),
(15, 'hero_bg_image', 'storage/hero/7uY5jJLbY1RSPtxRZ8xcxt9h16RsjEUdUHu0tTTV.jpg', 'appearance', '2025-05-02 03:15:00', '2025-05-30 12:10:54'),
(16, 'home_bundles_count', '6', 'display', NULL, '2025-05-30 12:13:45'),
(17, 'home_destinations_count', '6', 'display', NULL, '2025-05-30 12:13:45'),
(18, 'testimonials_count', '8', 'display', NULL, '2025-05-30 12:13:45'),
(19, 'primary_button_color', '#2263fc', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(20, 'primary_button_hover_color', '#f8c621', 'appearance', '2025-05-25 03:16:15', '2025-05-27 05:42:22'),
(21, 'primary_gradient_from', '#2263fc', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(22, 'primary_gradient_to', '#335fc7', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(23, 'primary_gradient_hover_from', '#335fc7', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(24, 'primary_gradient_hover_to', '#2263fc', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(25, 'secondary_gradient_from', '#f8c621', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(26, 'secondary_gradient_to', '#fac822', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(27, 'secondary_gradient_hover_from', '#c49f23', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(28, 'secondary_gradient_hover_to', '#c49f23', 'appearance', '2025-05-25 03:16:15', '2025-05-26 12:55:59'),
(39, 'icon_color_primary', '#5a82d3', 'appearance', NULL, '2025-05-26 12:55:59'),
(40, 'overlay_bg_color_from', '#808080', 'appearance', '2025-05-26 10:36:23', '2025-05-26 11:41:31'),
(41, 'overlay_bg_color_to', '#808080', 'appearance', '2025-05-26 10:36:23', '2025-05-26 11:41:31'),
(42, 'page_title_bg_color_from', '#d2c151', 'appearance', '2025-05-26 10:36:23', '2025-05-26 12:55:59'),
(43, 'page_title_bg_color_to', '#d2c151', 'appearance', '2025-05-26 10:36:23', '2025-05-26 12:55:59'),
(44, 'css_version', '1748607054', 'appearance', '2025-05-26 10:36:23', '2025-05-30 12:10:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
