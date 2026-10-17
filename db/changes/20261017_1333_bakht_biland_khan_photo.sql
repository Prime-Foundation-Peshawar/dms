-- Update Bakht Biland Khan (empId 80) photograph from departmental photo drop.
UPDATE `faculty_profiles`
SET `photo` = 'assets/images/faculty/bakht-biland-khan.jpeg', `updated_at` = NOW()
WHERE `slug` = 'bakht-biland-khan' OR `emp_id` = '80';
