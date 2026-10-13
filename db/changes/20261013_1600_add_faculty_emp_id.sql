ALTER TABLE `faculty_profiles`
  ADD COLUMN `emp_id` VARCHAR(40) NULL AFTER `slug`;

CREATE UNIQUE INDEX `faculty_profiles_emp_id_unique` ON `faculty_profiles` (`emp_id`);
