-- Idempotent: emp_id column/index may already exist.
SET @db := DATABASE();

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'emp_id'
    ),
    'SELECT 1',
    'ALTER TABLE `faculty_profiles` ADD COLUMN `emp_id` VARCHAR(40) NULL AFTER `slug`'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND INDEX_NAME = 'faculty_profiles_emp_id_unique'
    ),
    'SELECT 1',
    'CREATE UNIQUE INDEX `faculty_profiles_emp_id_unique` ON `faculty_profiles` (`emp_id`)'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
