-- ACP: dual text/html body fields + CMS render flag for pages
-- Idempotent: columns may already exist on staging/prod from an earlier partial apply.
SET @db := DATABASE();

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'pages' AND COLUMN_NAME = 'body_text'
    ),
    'SELECT 1',
    'ALTER TABLE `pages` ADD COLUMN `body_text` LONGTEXT NULL AFTER `body_html`'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'pages' AND COLUMN_NAME = 'use_cms'
    ),
    'SELECT 1',
    'ALTER TABLE `pages` ADD COLUMN `use_cms` TINYINT(1) NOT NULL DEFAULT 0 AFTER `body_text`'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'news_posts' AND COLUMN_NAME = 'body_text'
    ),
    'SELECT 1',
    'ALTER TABLE `news_posts` ADD COLUMN `body_text` LONGTEXT NULL AFTER `body_html`'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1 FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'events' AND COLUMN_NAME = 'body_text'
    ),
    'SELECT 1',
    'ALTER TABLE `events` ADD COLUMN `body_text` LONGTEXT NULL AFTER `body_html`'
  )
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
