-- Add CMS profile for Dr Asif Ali (HRMS empId 2020, Accident and Emergency)
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
)
SELECT
  'asif-ali',
  '2020',
  'Asif Ali',
  'accident-emergency',
  'Accident and Emergency',
  0,
  'Senior Registrar',
  NULL,
  0,
  1,
  '["MBBS","FCPS"]',
  '[]',
  '[]',
  '[]',
  '{"aliases":["asif-ali","dr-asif-ali"]}',
  NOW(),
  NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `faculty_profiles`
  WHERE `emp_id` = '2020' OR `slug` = 'asif-ali'
);

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT p.`id`, 'dr-asif-ali'
FROM `faculty_profiles` p
WHERE p.`emp_id` = '2020'
  AND NOT EXISTS (
    SELECT 1 FROM `faculty_profile_aliases` a
    WHERE a.`profile_id` = p.`id` AND a.`alias` = 'dr-asif-ali'
  );
