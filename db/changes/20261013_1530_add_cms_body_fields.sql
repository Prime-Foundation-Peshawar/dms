-- ACP: dual text/html body fields + CMS render flag for pages
ALTER TABLE `pages`
  ADD COLUMN `body_text` LONGTEXT NULL AFTER `body_html`,
  ADD COLUMN `use_cms` TINYINT(1) NOT NULL DEFAULT 0 AFTER `body_text`;

ALTER TABLE `news_posts`
  ADD COLUMN `body_text` LONGTEXT NULL AFTER `body_html`;

ALTER TABLE `events`
  ADD COLUMN `body_text` LONGTEXT NULL AFTER `body_html`;
