ALTER TABLE `faculty_profile_submissions`
  ADD COLUMN `review_note` TEXT NULL AFTER `status`,
  ADD COLUMN `reviewed_at` DATETIME NULL AFTER `review_note`,
  ADD COLUMN `reviewed_by` BIGINT UNSIGNED NULL AFTER `reviewed_at`;
