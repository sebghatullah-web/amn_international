-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2026 at 11:44 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amn_international`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(120) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` datetime DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` varchar(300) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `excerpt`, `body`, `image`, `author`, `category`, `is_published`, `published_at`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(2, 'PHP FIRM WORKS', 'php-firm-works', 'Laravel Laravel is a highly popular framework known for its elegant syntax and robust ecosystem.', 'Laravel Laravel is a highly popular framework known for its elegant syntax and robust ecosystem. It supports MVC architecture and includes features like routing, caching, and authentication. Its Blade templating engine and Eloquent ORM simplify database interactions. However, it has a steeper learning curve for beginners and relies heavily on third-party tools. Example Use Cases: E-commerce platforms, enterprise applications. Symfony Symfony is a modular framework ideal for large-scale applications. It offers extensive libraries, easy API building, and database-engine independence. Its Doctrine ORM and Twig templating engine are powerful tools for developers. However, it can be resource-intensive for smaller projects and requires expertise for optimal use. Example Use Cases: Corporate applications, CMS platforms. CodeIgniter CodeIgniter is a lightweight framework with a small footprint, making it ideal for simple, dynamic websites. It offers fast performance, minimal setup, and built-in error handling. However, it lacks built-in ORM support and is less suited for enterprise-level applications. Example Use Cases: Small to medium-sized web applications. CakePHP CakePHP is known for its simplicity and rapid development capabilities. It follows the MVC architecture and includes built-in ORM and security features like CSRF protection. However, it may not be as flexible as modern frameworks and can be slower for high-traffic applications.', 'uploads/articles/20261031-12f0329bcb92864e.png', '', '', 1, '2026-10-31 15:01:12', '', '', '2026-10-31 15:01:12', '2026-10-31 15:01:12');

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
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 'electronics', '', 'uploads/categories/20261031-e1360b0520d7328c.jpg', 1, 4, '2026-10-19 00:02:13', '2026-10-19 00:02:13'),
(2, 'Clothing', 'clothing', '', 'uploads/categories/20261031-74c7c9144ea5598d.jpg', 1, 4, '2026-10-19 00:02:13', '2026-10-19 00:02:13'),
(3, 'Home & Kitchen', 'home-kitchen', '', 'uploads/categories/20261031-43855ef42a309d74.jpg', 1, 3, '2026-10-19 00:02:13', '2026-10-19 00:02:13'),
(4, 'Sports & Outdoors', 'sports-outdoors', '', 'uploads/categories/20261031-ad1de327cd18b2ba.jpg', 1, 4, '2026-10-19 00:02:13', '2026-10-19 00:02:13'),
(7, 'Woodworking tools', 'woodworking tools', '', 'uploads/categories/20261031-30e982eac6c3023b.jpg', 1, 0, '2026-10-31 14:12:51', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `usd_to_afn` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `usd_to_afn`, `updated_at`) VALUES
(1, '7045.5000', '2026-10-18 23:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `alt` varchar(200) DEFAULT NULL,
  `group_name` varchar(120) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `title`, `slug`, `description`, `path`, `alt`, `group_name`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(3, 'Electrical appliances', 'electrical-appliances', NULL, 'uploads/gallery/20261031-0f51978d153e7482.jpg', 'Electrical appliances', 'electrical appliances', 1, 0, '2026-10-31 14:52:14', '2026-10-31 14:52:14');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('quote','contact') NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(60) DEFAULT NULL,
  `country` varchar(80) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` varchar(60) DEFAULT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `type`, `name`, `email`, `phone`, `country`, `subject`, `product_id`, `quantity`, `message`, `ip_address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'quote', 'John Smith', 'john@example.com', '+1234567890', 'USA', 'Wholesale electronics order', 1, '100 units', 'Looking for a wholesale order of 100 units of wireless headphones.', '192.168.1.100', 'replied', '2026-10-19 00:02:13', '2026-10-31 15:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `process_steps`
--

CREATE TABLE `process_steps` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `group_name` varchar(80) DEFAULT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `process_steps`
--

INSERT INTO `process_steps` (`id`, `title`, `group_name`, `description`, `icon`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'Sourcing', '', 'Product sourcing from reliable manufacturers worldwide.', 'search', 'uploads/steps/20261031-1949be280caec15b.jpg', 1, 2, '2026-10-19 00:02:13', '2026-10-31 14:49:04'),
(3, 'Quality Check', '', 'Inspection and quality assurance before shipping.', 'shield', 'uploads/steps/20261031-5a91b7b94c09cf3f.jpg', 1, 3, '2026-10-19 00:02:13', '2026-10-31 14:49:39'),
(4, 'Shipping', '', 'Safe and timely delivery to your destination.', 'truck', 'uploads/steps/20261031-166b8020f6e455a1.jpg', 1, 4, '2026-10-19 00:02:13', '2026-10-31 14:50:15'),
(6, 'Consultation', '', 'Consultation Consultation', NULL, 'uploads/steps/20261031-ed5ce81ffb0721b0.jpg', 1, 3, '2026-10-31 14:48:24', '2026-10-31 14:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `country_of_origin` varchar(100) DEFAULT NULL,
  `short_description` varchar(500) NOT NULL,
  `price_usd` decimal(12,2) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `availability_status` enum('in_stock','out_of_stock','made_to_order','unavailable') NOT NULL DEFAULT 'in_stock',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_price_published` tinyint(1) NOT NULL DEFAULT 1,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` varchar(300) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `country_of_origin`, `short_description`, `price_usd`, `featured_image`, `stock`, `availability_status`, `is_active`, `is_featured`, `is_new`, `is_popular`, `is_price_published`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Wireless Headphones Pro', 'wireless-headphones-pro', 'High-quality wireless headphones with noise cancellation and 30-hour battery life.', '', 'Premium wireless headphones with ANC', '89.99', 'headphones.jpg', 150, 'in_stock', 1, 1, 1, 0, 1, '', '', '2026-10-19 00:02:13', '2026-10-31 14:01:37'),
(3, 3, 'Coffee Maker DX', 'coffee-maker-dx', 'Programmable coffee maker with grinder.', '', 'Programmable coffee maker', '129.99', 'coffee-maker.jpg', 75, 'in_stock', 1, 1, 0, 0, 1, '', '', '2026-10-19 00:02:13', '2026-10-31 14:01:21'),
(4, 4, 'Yoga Mat Premium', 'yoga-mat-premium', 'Eco-friendly yoga mat with excellent grip.', '', 'Premium eco-friendly yoga mat', '29.99', 'yoga-mat.jpg', 300, 'in_stock', 1, 0, 1, 0, 1, '', '', '2026-10-19 00:02:13', '2026-10-31 14:00:32'),
(6, 2, 'Clothing', 'clothing', '', '', '', '29.99', NULL, 0, 'in_stock', 1, 0, 1, 0, 1, '', '', '2026-10-31 14:02:41', '2026-10-31 14:02:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `path`, `alt`, `is_primary`, `created_at`) VALUES
(1, 4, 'uploads/products/20261031-0ac20fccc8ff3109.jpg', 'Yoga Mat Premium', 1, '0000-00-00 00:00:00'),
(2, 3, 'uploads/products/20261031-7915e474fa44c47d.jpg', 'Coffee Maker DX', 1, '0000-00-00 00:00:00'),
(3, 1, 'uploads/products/20261031-8f47ae8db0bf4c70.jpg', 'Wireless Headphones Pro', 1, '0000-00-00 00:00:00'),
(4, 6, 'uploads/products/20261031-342d071244d93b14.jpg', 'Clothing', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_specs`
--

CREATE TABLE `product_specs` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(120) NOT NULL,
  `value` varchar(500) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `slug`, `description`, `short_description`, `image`, `icon`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Trade Facilitation', 'trade-facilitation', 'We assist in export and import coordination across key markets.', '', 'uploads/services/20261031-0b0a03d824873368.jpg', 'ship', 1, 1, '2026-10-19 00:02:13', '2026-10-31 14:53:32'),
(2, 'Advisory & Consulting', 'advisory-consulting', 'Strategic insight and risk analysis for complex decisions.', '', 'uploads/services/20261031-1ce6a316b969dd57.jpg', 'shield', 1, 2, '2026-10-19 00:02:13', '2026-10-31 14:56:34'),
(3, 'Dialogue & Conferences', 'dialogue-conferences', 'Focused forums on global business and emerging market dynamics.', '', 'uploads/services/20261031-307451080305761d.jpg', 'message', 1, 3, '2026-10-19 00:02:13', '2026-10-31 14:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `skey` varchar(120) NOT NULL,
  `svalue` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `skey`, `svalue`) VALUES
(54, 'company_name', 'AMN International'),
(55, 'company_slogan', 'Electronic Safety Solutions for a Static-Free World'),
(56, 'site_language', 'en'),
(57, 'admin_email', 'admin@amn-international.com'),
(58, 'phone', '+93 (0) 79 134 9150'),
(59, 'whatsapp', '+93 (0) 76 775 9104'),
(60, 'email', 'info@amn-international.com'),
(61, 'office_address', 'Plot 121, Street 15, Diplomatic Area, Kabul, Afghanistan'),
(62, 'working_hours', 'Mon - Fri: 08:00 - 17:00 (Afghanistan Time)'),
(63, 'hero_title', 'Protecting Electronics from Static Damage'),
(64, 'hero_subtitle', 'AMN International is a Trusted Distributor of High-Quality ESD Products and Solutions'),
(65, 'hero_cta', 'Request a Quote'),
(66, 'company_logo', 'uploads/site/20261025-c831475d637dfae5.svg'),
(67, 'cta_image', 'uploads/site/cta-fallback.svg'),
(68, 'announcement_1', 'World Class ESD Products and Solutions'),
(69, 'announcement_2', 'ESD Floor Mats, Footwear, and Equipment'),
(70, 'announcement_3', 'Global Distribution - Free Consultation'),
(71, 'newsletter_text', 'Stay informed. Subscribe to our newsletter for the latest ESD news and product updates.'),
(72, 'show_demo_disclaimer', ''),
(73, 'currency_usd_code', 'USD'),
(74, 'currency_afn_code', 'AFN'),
(75, 'usd_afn_rate', '7045.5000'),
(76, 'usd_afn_updated_at', '2026-10-18 00:00:00'),
(77, 'seo_home_title', 'AMN International - Security Solutions'),
(78, 'seo_home_description', 'Trusted distributor of electronic safety products in Afghanistan and worldwide.'),
(79, 'seo_about_title', 'About AMN International'),
(80, 'seo_about_description', 'We have been delivering quality safety solutions since 2012.'),
(81, 'seo_products_title', 'ESD Products - AMN International'),
(82, 'seo_products_description', 'Anti-static bags, strapping, mats, footwear, and testing equipment.'),
(83, 'seo_services_title', 'Our Services'),
(84, 'seo_services_description', 'Consultancy, sourcing, training, and compliance audits.'),
(85, 'seo_process_title', 'Our Process'),
(86, 'seo_process_description', 'Request a quote, we source, we produce, we deliver.'),
(87, 'seo_gallery_title', 'Image Gallery'),
(88, 'seo_gallery_description', 'See our products and solutions in action.'),
(89, 'seo_news_title', 'News & Articles'),
(90, 'seo_news_description', 'Latest updates from AMN International.'),
(91, 'seo_contact_title', 'Contact Us'),
(92, 'seo_contact_description', 'Questions? Reach out to our team today.'),
(93, 'seo_quote_title', 'Request a Quote'),
(94, 'seo_quote_description', 'Get a fast, no-obligation quote for your ESD needs.'),
(95, 'hero_image', ''),
(96, 'categories_title', 'Categories'),
(97, 'featured_title', 'Featured Products'),
(98, 'new_title', 'New Arrivals'),
(99, 'popular_title', 'Popular Products'),
(100, 'stats_years', '15+'),
(101, 'stats_clients', '500+'),
(102, 'stats_shipments', '10000+'),
(103, 'stats_countries', '20+'),
(104, 'footer_about', 'We provide the highest quality electronic safety products and solutions.'),
(105, 'nav_links', 'Home|/\nProducts|/products\nServices|/services\nImport Process|/import-process\nGallery|/gallery\nNews & Articles|/news\nAbout Us|/about\nContact Us|/contact'),
(114, 'hero_eyebrow', ''),
(116, 'hero_description', ''),
(117, 'hero_alt', ''),
(118, 'cta_title', ''),
(119, 'cta_text', ''),
(120, 'cta_alt', ''),
(121, 'service_1_title', ''),
(122, 'service_1_text', ''),
(123, 'service_2_title', ''),
(124, 'service_2_text', ''),
(125, 'service_3_title', ''),
(126, 'service_3_text', ''),
(143, 'whatsapp_link', ''),
(147, 'social_facebook', ''),
(148, 'social_instagram', ''),
(149, 'social_linkedin', ''),
(150, 'social_youtube', ''),
(151, 'social_x', '');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(190) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `is_active`, `created_at`) VALUES
(1, 'newsletter@example.com', 1, '2026-10-19 00:02:13'),
(2, 'test@amn-international.com', 1, '2026-10-19 13:18:28'),
(3, 'admin@amn-international.com', 1, '2026-10-31 15:11:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_banned` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_active`, `is_banned`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Demo Admin', 'admin@amn-international.com', '$2y$10$EOdCkFQ6bXyeBfZHKU4kQ.0IYzEE.ghxk0JRV.WTFoeJx2Pd1ALca', 'admin', 1, 0, '2026-10-25 04:23:21', '2026-10-18 23:59:55', '2026-10-18 23:59:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_articles_slug` (`slug`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categories_slug` (`slug`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_exrates_single` (`id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_gallery_slug` (`slug`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inquiries_product` (`product_id`);

--
-- Indexes for table `process_steps`
--
ALTER TABLE `process_steps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_category` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_images_product` (`product_id`);

--
-- Indexes for table `product_specs`
--
ALTER TABLE `product_specs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_specs_product` (`product_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_services_slug` (`slug`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_settings_key` (`skey`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_subscribers_email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `process_steps`
--
ALTER TABLE `process_steps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_specs`
--
ALTER TABLE `product_specs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
