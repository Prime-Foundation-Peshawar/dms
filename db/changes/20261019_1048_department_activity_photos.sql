-- Publishable images for department academic activities
ALTER TABLE `department_activities`
  ADD COLUMN `photos_json` JSON NULL AFTER `body`;
