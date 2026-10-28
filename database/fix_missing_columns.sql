-- ============================================================
-- AMN International - Fix missing columns after schema_part3.sql
-- Run: mysql -u root -p amn_international < database/fix_missing_columns.sql
-- ============================================================

-- products.country_of_origin (used by ProductController::save + Product::adminSearch + seed.sql)
SET @col = (SELECT COUNT(*) FROM information_schema.columns
            WHERE table_schema = DATABASE()
              AND table_name   = 'products'
              AND column_name  = 'country_of_origin');
SET @sql = IF(@col = 0,
    'ALTER TABLE `products` ADD COLUMN `country_of_origin` varchar(100) DEFAULT NULL AFTER `description`',
    'SELECT ''products.country_of_origin already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- product_images.path (used by ProductImage::add / product_images queries)
SET @col = (SELECT COUNT(*) FROM information_schema.columns
            WHERE table_schema = DATABASE()
              AND table_name   = 'product_images'
              AND column_name  = 'path');
SET @sql = IF(@col = 0,
    'ALTER TABLE `product_images` ADD COLUMN `path` varchar(255) NOT NULL AFTER `product_id`',
    'SELECT ''product_images.path already exists'' AS msg');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
