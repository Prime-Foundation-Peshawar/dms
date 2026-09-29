CREATE TABLE IF NOT EXISTS `website_expected_units` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_name` VARCHAR(190) NOT NULL,
  `unit_type` VARCHAR(64) NOT NULL DEFAULT 'unit',
  `institution` VARCHAR(120) NULL,
  `content_examples` VARCHAR(500) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_expected_unit_name` (`unit_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_activity_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `month_key` CHAR(7) NOT NULL,
  `unit_name` VARCHAR(190) NOT NULL,
  `activity_title` VARCHAR(255) NOT NULL,
  `received_at` DATETIME NULL,
  `approved_at` DATETIME NULL,
  `published_at` DATETIME NULL,
  `turnaround_hours` INT NULL,
  `status` VARCHAR(32) NOT NULL DEFAULT 'received',
  `shared_social` TINYINT(1) NOT NULL DEFAULT 0,
  `notes` VARCHAR(500) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_activity_month_unit` (`month_key`, `unit_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_integrity_checks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `check_date` DATE NOT NULL,
  `check_type` VARCHAR(16) NOT NULL DEFAULT 'daily',
  `status` VARCHAR(16) NOT NULL DEFAULT 'clear',
  `checked_by` VARCHAR(120) NULL,
  `notes` VARCHAR(500) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_integrity_date` (`check_date`, `check_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_suggestions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `month_key` CHAR(7) NOT NULL,
  `suggestion` TEXT NOT NULL,
  `source` VARCHAR(120) NULL,
  `status` VARCHAR(32) NOT NULL DEFAULT 'open',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_suggestions_month` (`month_key`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
