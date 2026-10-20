-- ============================================================
-- Inquiries / Quote requests / Contact messages
-- ============================================================
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('quote','contact') NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(60) DEFAULT NULL,
  `country` varchar(80) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `product_id` int unsigned DEFAULT NULL,
  `quantity` varchar(60) DEFAULT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_inquiries_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Newsletter subscribers
-- ============================================================
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(190) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_subscribers_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Exchange rate (USD -> local currency for product pricing)
-- ============================================================
CREATE TABLE IF NOT EXISTS `exchange_rates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usd_to_afn` decimal(16,4) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_exrates_single` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo admin user (password: demo1234)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_active`, `is_banned`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Demo Admin', 'admin@amn-international.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 0, NULL, NOW(), NOW());

-- Demo exchange rate (AFN per 1 USD)
INSERT INTO `exchange_rates` (`id`, `usd_to_afn`, `updated_at`) VALUES (1, 7045.5000, NOW());


