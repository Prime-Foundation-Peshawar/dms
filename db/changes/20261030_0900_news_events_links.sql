-- Homepage cards: optional call-to-action button (e.g. "Apply Now" → external portal)
ALTER TABLE `news_posts`
  ADD COLUMN `link_url` VARCHAR(500) NULL AFTER `cover_image`,
  ADD COLUMN `link_label` VARCHAR(60) NULL AFTER `link_url`;
ALTER TABLE `events`
  ADD COLUMN `link_url` VARCHAR(500) NULL AFTER `cover_image`,
  ADD COLUMN `link_label` VARCHAR(60) NULL AFTER `link_url`;

-- Carry over the links that were hard-coded on the homepage
UPDATE `news_posts` SET `link_url` = 'https://caspr.kmu.edu.pk/', `link_label` = 'Apply Now',
  `excerpt` = 'Provisional Merit List: 10 November 2026.'
WHERE `slug` = 'mbbs-admissions-open-2026-27';
UPDATE `events` SET `link_url` = 'https://caspr.kmu.edu.pk/', `link_label` = 'Apply Now'
WHERE `slug` = 'mbbs-admissions-open-2026-27';
UPDATE `news_posts` SET `link_url` = 'https://convocation.riphahpsh.edu.pk/', `link_label` = 'Register Now'
WHERE `slug` = 'convocation-2026';
UPDATE `events` SET `link_url` = 'https://convocation.riphahpsh.edu.pk/', `link_label` = 'Register Now'
WHERE `slug` = 'convocation-2026';
