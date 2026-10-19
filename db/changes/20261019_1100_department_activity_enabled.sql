-- Show / hide department activities on the public site without deleting
ALTER TABLE `department_activities`
  ADD COLUMN `enabled` TINYINT(1) NOT NULL DEFAULT 1 AFTER `sort_order`;
