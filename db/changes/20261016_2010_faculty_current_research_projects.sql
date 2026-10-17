-- Idempotent: current_research_projects may already exist.
SET @db := DATABASE();
SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db
        AND TABLE_NAME = 'faculty_profile_submissions'
        AND COLUMN_NAME = 'current_research_projects'
    ),
    'SELECT 1',
    'ALTER TABLE `faculty_profile_submissions` ADD COLUMN `current_research_projects` JSON NULL AFTER `research_preferences`'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
