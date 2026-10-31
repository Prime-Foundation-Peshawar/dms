-- News and Events become one list. An item is an event when it has an event date.
ALTER TABLE `news_posts`
  ADD COLUMN `start_date` DATE NULL AFTER `expires_at`,
  ADD COLUMN `event_date` DATE NULL AFTER `start_date`,
  ADD COLUMN `location` VARCHAR(191) NULL AFTER `event_date`;
