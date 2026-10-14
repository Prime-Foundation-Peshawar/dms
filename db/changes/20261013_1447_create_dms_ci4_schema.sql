-- @db default
-- CI4 public-site schema (mirror of app/Database/Migrations/2026-10-13-143000_CreateDmsSchema.php)
-- Prefer `php spark migrate` on CI4 hosts; this file keeps existing db/apply.php deploy compatible.
-- BIGINT primary/FK columns match legacy pre-CI4 staging tables (INT FKs fail against existing BIGINT ids).

CREATE TABLE IF NOT EXISTS `departments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(100) NOT NULL,
  `name` VARCHAR(191) NOT NULL,
  `icon` VARCHAR(80) NULL,
  `group_name` VARCHAR(50) NOT NULL DEFAULT 'Other',
  `hod` VARCHAR(191) NULL,
  `intro_json` LONGTEXT NULL,
  `faculty_seed_json` LONGTEXT NULL,
  `hidden` TINYINT(1) NOT NULL DEFAULT 0,
  `oric_id` INT UNSIGNED NULL,
  `content_updated_at` DATE NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `group_name` (`group_name`),
  KEY `hidden` (`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `department_activities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `date_label` VARCHAR(100) NULL,
  `body` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `department_activities_department_id_foreign`
    FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `faculty_profiles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(150) NOT NULL,
  `name` VARCHAR(191) NOT NULL,
  `department_slug` VARCHAR(100) NULL,
  `department_name` VARCHAR(191) NULL,
  `designation` VARCHAR(191) NULL,
  `photo` VARCHAR(255) NULL,
  `is_hod` TINYINT(1) NOT NULL DEFAULT 0,
  `is_public` TINYINT(1) NOT NULL DEFAULT 1,
  `qualifications_json` LONGTEXT NULL,
  `experience_json` LONGTEXT NULL,
  `skills_json` LONGTEXT NULL,
  `publications_json` LONGTEXT NULL,
  `extra_json` LONGTEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `department_slug` (`department_slug`),
  KEY `is_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `faculty_profile_aliases` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` BIGINT UNSIGNED NOT NULL,
  `alias` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `profile_id` (`profile_id`),
  CONSTRAINT `faculty_profile_aliases_profile_id_foreign`
    FOREIGN KEY (`profile_id`) REFERENCES `faculty_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(120) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `label` VARCHAR(120) NULL,
  `section` VARCHAR(80) NULL,
  `description` TEXT NULL,
  `view_key` VARCHAR(120) NULL,
  `body_html` LONGTEXT NULL,
  `seo_json` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'published',
  `priority` DECIMAL(2,1) NOT NULL DEFAULT 0.5,
  `changefreq` VARCHAR(20) NOT NULL DEFAULT 'monthly',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news_posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(160) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NULL,
  `body_html` LONGTEXT NULL,
  `category` VARCHAR(80) NULL,
  `cover_image` VARCHAR(255) NULL,
  `published_at` DATETIME NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'published',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `news_media` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `news_id` BIGINT UNSIGNED NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  CONSTRAINT `news_media_news_id_foreign`
    FOREIGN KEY (`news_id`) REFERENCES `news_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(160) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NULL,
  `body_html` LONGTEXT NULL,
  `category` VARCHAR(80) NULL,
  `cover_image` VARCHAR(255) NULL,
  `event_date` DATE NULL,
  `location` VARCHAR(191) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'published',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `event_media` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` BIGINT UNSIGNED NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `event_media_event_id_foreign`
    FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(100) NOT NULL,
  `value` LONGTEXT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(191) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `name` VARCHAR(120) NULL,
  `role` VARCHAR(40) NOT NULL DEFAULT 'admin',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
