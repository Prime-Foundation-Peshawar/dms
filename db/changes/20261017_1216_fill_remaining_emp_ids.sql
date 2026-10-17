-- Fill remaining emp_ids PMC+PDC
-- 2026-10-17T12:16:33

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1261' AND `slug` <> 'sarah-yousuf';
UPDATE `faculty_profiles` SET `emp_id` = '1261', `updated_at` = NOW() WHERE `slug` = 'sarah-yousuf' AND (`emp_id` IS NULL OR TRIM(`emp_id`) = '');

