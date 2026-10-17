-- Shehnaz Parveen CV linked to HRMS Shahnaz Parveen empId 312 (spelling variant).
UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '312' AND `slug` <> 'shehnaz-parveen';
UPDATE `faculty_profiles` SET `emp_id` = '312', `updated_at` = NOW()
WHERE `slug` = 'shehnaz-parveen' AND (`emp_id` IS NULL OR TRIM(`emp_id`) = '');

SET @pid := (SELECT `id` FROM `faculty_profiles` WHERE `slug` = 'shehnaz-parveen' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'shahnaz-parveen' FROM DUAL
WHERE @pid IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'shahnaz-parveen');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'shahnaz-parveen' AND @pid IS NOT NULL;
