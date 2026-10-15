ALTER TABLE `news_posts`
  ADD COLUMN `expires_at` DATETIME NULL AFTER `published_at`;
