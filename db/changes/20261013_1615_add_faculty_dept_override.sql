-- Idempotent: dept_override may already exist.
SET @db := DATABASE();
SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'faculty_profiles' AND COLUMN_NAME = 'dept_override'
    ),
    'SELECT 1',
    'ALTER TABLE `faculty_profiles` ADD COLUMN `dept_override` TINYINT(1) NOT NULL DEFAULT 0'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
