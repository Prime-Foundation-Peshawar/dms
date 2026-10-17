-- Faculty photograph for abdul-majid (Majid).
UPDATE `faculty_profiles`
SET `photo` = 'assets/images/faculty/abdul-majid.jpeg', `is_public` = 1, `updated_at` = NOW()
WHERE `slug` = 'abdul-majid' OR `emp_id` = '313';
