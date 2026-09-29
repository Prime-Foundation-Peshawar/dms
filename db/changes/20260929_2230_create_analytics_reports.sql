CREATE TABLE IF NOT EXISTS `analytics_settings` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `ga_measurement_id` VARCHAR(32) NULL,
  `ga_property_id` VARCHAR(64) NULL,
  `sessions_mtd` INT NOT NULL DEFAULT 0,
  `users_mtd` INT NOT NULL DEFAULT 0,
  `pageviews_mtd` INT NOT NULL DEFAULT 0,
  `bounce_rate` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `avg_session_sec` INT NOT NULL DEFAULT 0,
  `top_pages_json` JSON NULL,
  `notes` TEXT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_contributions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `month_key` CHAR(7) NOT NULL,
  `unit_name` VARCHAR(190) NOT NULL,
  `unit_type` VARCHAR(64) NOT NULL DEFAULT 'unit',
  `submissions` INT NOT NULL DEFAULT 0,
  `approved` INT NOT NULL DEFAULT 0,
  `on_time` INT NOT NULL DEFAULT 0,
  `quality_score` DECIMAL(4,1) NOT NULL DEFAULT 0,
  `rank_band` VARCHAR(16) NOT NULL DEFAULT 'average',
  `notes` VARCHAR(500) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_contrib_month` (`month_key`, `rank_band`),
  KEY `idx_contrib_unit` (`unit_type`, `unit_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
