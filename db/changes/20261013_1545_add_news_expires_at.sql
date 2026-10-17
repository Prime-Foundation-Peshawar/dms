-- Idempotent: expires_at may already exist from an earlier partial apply.
SET @db := DATABASE();
SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'news_posts' AND COLUMN_NAME = 'expires_at'
    ),
    'SELECT 1',
    'ALTER TABLE `news_posts` ADD COLUMN `expires_at` DATETIME NULL AFTER `published_at`'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
