-- Multi-day events: start_date is the first day, event_date stays the day/last day
ALTER TABLE `events`
  ADD COLUMN `start_date` DATE NULL AFTER `link_label`;

UPDATE `events` SET `start_date` = '2026-10-05'
WHERE `slug` = 'mbbs-admissions-open-2026-27';
