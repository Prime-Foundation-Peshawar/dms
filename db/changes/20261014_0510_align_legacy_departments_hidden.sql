-- @db default
-- Legacy departments table may lack CI4 visibility column.

SET @db := DATABASE();
SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'departments' AND COLUMN_NAME = 'hidden'
);
SET @sql := IF(@exists = 0,
  'ALTER TABLE `departments` ADD COLUMN `hidden` TINYINT(1) NOT NULL DEFAULT 0',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;