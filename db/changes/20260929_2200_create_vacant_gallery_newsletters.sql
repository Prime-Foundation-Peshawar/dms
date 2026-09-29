CREATE TABLE IF NOT EXISTS `vacant_seats` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `programme` VARCHAR(190) NOT NULL,
  `session_label` VARCHAR(64) NULL,
  `seats` INT NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_vacant_seats_active` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vacant_seats_settings` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `alert_html` TEXT NULL,
  `intro_html` TEXT NULL,
  `instructions_json` JSON NULL,
  `apply_deadline` VARCHAR(120) NULL,
  `apply_deadline_note` VARCHAR(120) NULL,
  `merit_date` VARCHAR(120) NULL,
  `merit_date_note` VARCHAR(120) NULL,
  `apply_url` VARCHAR(500) NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(190) NOT NULL,
  `date_label` VARCHAR(120) NULL,
  `pdf_url` VARCHAR(500) NOT NULL,
  `cover_url` VARCHAR(500) NULL,
  `published_at` DATE NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_newsletters_active` (`is_active`, `sort_order`, `published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gallery_albums` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(64) NOT NULL,
  `title` VARCHAR(190) NOT NULL,
  `category` VARCHAR(64) NOT NULL,
  `icon` VARCHAR(64) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_gallery_albums_slug` (`slug`),
  KEY `idx_gallery_albums_active` (`is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `album_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(500) NULL,
  `image_path` VARCHAR(500) NOT NULL,
  `span_class` VARCHAR(32) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_images_album` (`album_id`, `sort_order`),
  CONSTRAINT `fk_gallery_images_album`
    FOREIGN KEY (`album_id`) REFERENCES `gallery_albums` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
