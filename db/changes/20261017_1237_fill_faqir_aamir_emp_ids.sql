-- Fill Faqir Anwar (90) and Aamir Zeb (1941) from PMC HRMS

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '90' AND `slug` <> 'faqir-muhammad-anwar';
UPDATE `faculty_profiles` SET `emp_id` = '90', `updated_at` = NOW() WHERE `slug` = 'faqir-muhammad-anwar' AND (`emp_id` IS NULL OR TRIM(`emp_id`) = '');

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1941' AND `slug` <> 'aamir-zeb';
UPDATE `faculty_profiles` SET `emp_id` = '1941', `updated_at` = NOW() WHERE `slug` = 'aamir-zeb' AND (`emp_id` IS NULL OR TRIM(`emp_id`) = '');

