-- Category for department activities, set from the public update-activity form.
-- Keep the values in sync with App\Controllers\ActivityUpdate::CATEGORIES.
-- Existing rows receive the DEFAULT ('CPD Activities').
ALTER TABLE `department_activities`
  ADD COLUMN `category` ENUM('CPD Activities', 'Special Events') NOT NULL DEFAULT 'CPD Activities' AFTER `title`;
