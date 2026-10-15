-- @db default
-- Add CI4 columns onto legacy faculty_profiles (CREATE IF NOT EXISTS left old tables untouched).

SET @db := DATABASE();

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'is_public'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `is_public` TINYINT(1) NOT NULL DEFAULT 1',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'is_hod'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `is_hod` TINYINT(1) NOT NULL DEFAULT 0',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'department_name'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `department_name` VARCHAR(191) NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'department_slug'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `department_slug` VARCHAR(100) NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @has_dept := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'department'
);
SET @sql := IF(@has_dept > 0,
  'UPDATE `faculty_profiles` SET `department_name` = `department` WHERE (`department_name` IS NULL OR `department_name` = '''') AND `department` IS NOT NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'qualifications_json'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `qualifications_json` LONGTEXT NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'experience_json'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `experience_json` LONGTEXT NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'skills_json'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `skills_json` LONGTEXT NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'publications_json'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `publications_json` LONGTEXT NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'extra_json'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `faculty_profiles` ADD COLUMN `extra_json` LONGTEXT NULL',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
