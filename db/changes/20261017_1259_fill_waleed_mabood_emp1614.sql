-- Waleed Mabood empId 1614 from HRMS tbl_employee (not in website API: campus/websiteActive/IsActive filter).
UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1614' AND `slug` NOT IN ('waleed-mabood', 'waleed-mahbood');
UPDATE `faculty_profiles`
SET `emp_id` = '1614', `name` = 'Waleed Mabood', `slug` = 'waleed-mabood', `updated_at` = NOW()
WHERE `slug` IN ('waleed-mabood', 'waleed-mahbood');
