-- Kept apart from the ALTER: db/apply.php records a file as applied when it tolerates a duplicate column,
-- which would skip everything after it. The events and event_media tables stay as a backup.

-- Same item in both lists: keep the news post, add the event details, fill anything it lacks.
-- An event that was live stays live.
UPDATE `news_posts` n
JOIN `events` e ON e.`slug` = n.`slug`
SET n.`event_date`  = IF(e.`event_date` REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}', LEFT(e.`event_date`, 10), NULL),
    n.`start_date`  = e.`start_date`,
    n.`location`    = NULLIF(e.`location`, ''),
    n.`excerpt`     = COALESCE(NULLIF(n.`excerpt`, ''), e.`excerpt`),
    n.`body_html`   = COALESCE(NULLIF(n.`body_html`, ''), e.`body_html`),
    n.`body_text`   = COALESCE(NULLIF(n.`body_text`, ''), e.`body_text`),
    n.`cover_image` = COALESCE(NULLIF(n.`cover_image`, ''), e.`cover_image`),
    n.`link_url`    = COALESCE(NULLIF(n.`link_url`, ''), e.`link_url`),
    n.`link_label`  = COALESCE(NULLIF(n.`link_label`, ''), e.`link_label`),
    n.`status`      = IF(e.`status` = 'published', 'published', n.`status`);

-- Events with no matching news post become new items, dated when first added or on the event day if earlier.
INSERT INTO `news_posts`
  (`slug`, `title`, `excerpt`, `body_html`, `body_text`, `category`, `cover_image`, `link_url`, `link_label`,
   `published_at`, `start_date`, `event_date`, `location`, `status`, `created_at`, `updated_at`)
SELECT x.`slug`, x.`title`, x.`excerpt`, x.`body_html`, x.`body_text`, x.`category`, x.`cover_image`, x.`link_url`, x.`link_label`,
       COALESCE(LEAST(x.`created_at`, x.`day`), x.`created_at`, x.`day`, NOW()),
       x.`start_date`, x.`day`, NULLIF(x.`location`, ''), x.`status`, x.`created_at`, x.`updated_at`
FROM (
  SELECT e.*, IF(e.`event_date` REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}', LEFT(e.`event_date`, 10), NULL) AS `day`
  FROM `events` e
  JOIN (SELECT MIN(`id`) AS `id` FROM `events` GROUP BY `slug`) one ON one.`id` = e.`id`
  WHERE NOT EXISTS (SELECT 1 FROM `news_posts` n WHERE n.`slug` = e.`slug`)
) x;

-- Move event photos across to the matching item.
INSERT INTO `news_media` (`news_id`, `path`, `caption`, `sort_order`)
SELECT n.`id`, m.`path`, m.`caption`, m.`sort_order`
FROM `event_media` m
JOIN `events` e ON e.`id` = m.`event_id`
JOIN (SELECT `slug`, MAX(`id`) AS `id` FROM `news_posts` GROUP BY `slug`) n ON n.`slug` = e.`slug`
WHERE NOT EXISTS (SELECT 1 FROM `news_media` o WHERE o.`news_id` = n.`id` AND o.`path` = m.`path`);

UPDATE `pages`
SET `title` = 'News & Events | Peshawar Medical College',
    `description` = 'News and events from Peshawar Medical College: admissions, conferences, research and campus updates.'
WHERE `slug` = 'all-news';
