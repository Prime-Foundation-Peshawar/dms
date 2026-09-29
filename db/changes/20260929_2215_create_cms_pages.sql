CREATE TABLE IF NOT EXISTS `cms_pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(190) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `hero_title` VARCHAR(255) NULL,
  `breadcrumb_label` VARCHAR(255) NULL,
  `body_html` MEDIUMTEXT NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `status` VARCHAR(16) NOT NULL DEFAULT 'draft',
  `show_sidebar` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cms_pages_slug` (`slug`),
  KEY `idx_cms_pages_status` (`status`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
