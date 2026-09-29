CREATE TABLE IF NOT EXISTS `departments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(64) NOT NULL,
  `name` VARCHAR(190) NOT NULL,
  `icon` VARCHAR(64) NULL,
  `dept_group` VARCHAR(64) NULL,
  `hod_name` VARCHAR(190) NULL,
  `intro` JSON NULL,
  `faculty_fallback` JSON NULL,
  `oric_id` INT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `updated_on` DATE NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_departments_slug` (`slug`),
  KEY `idx_departments_group` (`dept_group`),
  KEY `idx_departments_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `department_activities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `activity_date` VARCHAR(64) NULL,
  `body` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_dept_activities_dept` (`department_id`),
  CONSTRAINT `fk_dept_activities_department`
    FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
