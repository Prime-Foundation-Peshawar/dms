ALTER TABLE `faculty_profiles`
  ADD COLUMN `dept_override` TINYINT(1) NOT NULL DEFAULT 0 AFTER `department_name`;
