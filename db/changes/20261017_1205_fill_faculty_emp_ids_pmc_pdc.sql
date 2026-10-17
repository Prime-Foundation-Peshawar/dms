-- Fill blank faculty emp_id from PMC+PDC HRMS APIs (DMS)
-- Generated 2026-10-17T12:05:22

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '890' AND `slug` <> 'munaza-khattak';
UPDATE `faculty_profiles` SET `emp_id` = '890', `updated_at` = NOW() WHERE `slug` = 'munaza-khattak' AND (`emp_id` IS NULL OR TRIM(`emp_id`) = '');

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1373' AND `slug` <> 'nasir-bakhtiar';
UPDATE `faculty_profiles` SET `emp_id` = '1373', `updated_at` = NOW() WHERE `slug` = 'nasir-bakhtiar' AND (`emp_id` IS NULL OR TRIM(`emp_id`) = '');

