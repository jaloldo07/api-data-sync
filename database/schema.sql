-- ============================================
-- API DATA SYNC - DATABASE SCHEMA
-- ============================================
-- Agar migration ishlamasa, bu SQL ni qo'lda ishlatishingiz mumkin
-- ============================================

-- 1. SALES jadvali (Sotuvlar)
CREATE TABLE IF NOT EXISTS `sales` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL COMMENT 'API dan kelgan to\'liq ma\'lumot',
  `sale_date` date DEFAULT NULL COMMENT 'Sotuv sanasi',
  `external_id` varchar(255) DEFAULT NULL COMMENT 'API dagi ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_external_id_sale_date_unique` (`external_id`, `sale_date`),
  KEY `sales_external_id_index` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ORDERS jadvali (Buyurtmalar)
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL COMMENT 'API dan kelgan to\'liq ma\'lumot',
  `order_date` date DEFAULT NULL COMMENT 'Buyurtma sanasi',
  `external_id` varchar(255) DEFAULT NULL COMMENT 'API dagi ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_external_id_order_date_unique` (`external_id`, `order_date`),
  KEY `orders_external_id_index` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. STOCKS jadvali (Omborlar)
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL COMMENT 'API dan kelgan to\'liq ma\'lumot',
  `stock_date` date DEFAULT NULL COMMENT 'Ombor sanasi',
  `external_id` varchar(255) DEFAULT NULL COMMENT 'API dagi ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stocks_external_id_stock_date_unique` (`external_id`, `stock_date`),
  KEY `stocks_external_id_index` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. INCOMES jadvali (Daromadlar)
CREATE TABLE IF NOT EXISTS `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL COMMENT 'API dan kelgan to\'liq ma\'lumot',
  `income_date` date DEFAULT NULL COMMENT 'Daromad sanasi',
  `external_id` varchar(255) DEFAULT NULL COMMENT 'API dagi ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `incomes_external_id_income_date_unique` (`external_id`, `income_date`),
  KEY `incomes_external_id_index` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. MIGRATIONS jadvali (Laravel uchun)
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- FOYDALI SO'ROVLAR
-- ============================================

-- Har bir jadvaldagi yozuvlar soni
-- SELECT COUNT(*) FROM sales;
-- SELECT COUNT(*) FROM orders;
-- SELECT COUNT(*) FROM stocks;
-- SELECT COUNT(*) FROM incomes;

-- Oxirgi 10 ta sotuv
-- SELECT * FROM sales ORDER BY created_at DESC LIMIT 10;

-- Ma'lum sana oralig'idagi sotuvlar
-- SELECT * FROM sales WHERE sale_date BETWEEN '2024-01-01' AND '2024-01-31';

-- JSON ma'lumotdan biror qiymat olish (masalan)
-- SELECT id, JSON_EXTRACT(data, '$.price') as price FROM sales LIMIT 10;
