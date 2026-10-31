-- First-party page view counts for the ACP reports (one row per path per day)
CREATE TABLE IF NOT EXISTS `page_views` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `day` DATE NOT NULL,
  `path` VARCHAR(191) NOT NULL,
  `dept_slug` VARCHAR(120) NULL,
  `views` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_day_path` (`day`, `path`),
  KEY `idx_dept_day` (`dept_slug`, `day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
