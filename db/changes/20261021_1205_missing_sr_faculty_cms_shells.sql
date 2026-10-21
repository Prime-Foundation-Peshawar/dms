-- Keep Asif Ali in CMS but clear thin quals-only CV (needs education+bio to be clickable).
UPDATE `faculty_profiles`
SET `qualifications_json` = '[]',
    `experience_json` = '[]',
    `skills_json` = '[]',
    `publications_json` = '[]',
    `updated_at` = NOW()
WHERE `emp_id` = '2020' OR `slug` = 'asif-ali';

-- Add missing SR+ CMS faculty shells (DMS)
-- Empty CV shells for ACP editing. Public site stays non-clickable until
-- education (qualifications) AND bio (experience/skills/publications) are filled.

-- Salma Khalid emp 439
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '439' AND `slug` <> 'salma-khalid';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'salma-khalid', '439', 'Salma Khalid', 'administration', 'Administration', 0,
  'Associate Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Ambereen Humayun emp 665
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '665' AND `slug` <> 'ambereen-humayun';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'ambereen-humayun', '665', 'Ambereen Humayun', 'anatomy', 'Anatomy', 0,
  'Associate Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Farooq Khan emp 469
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '469' AND `slug` <> 'farooq-khan';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'farooq-khan', '469', 'Farooq Khan', 'anatomy', 'Anatomy', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Hina Hakim emp 1552
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1552' AND `slug` <> 'hina-hakim';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'hina-hakim', '1552', 'Hina Hakim', 'biochemistry', 'Biochemistry', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Zahoor Ullah emp 8
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '8' AND `slug` <> 'zahoor-ullah';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'zahoor-ullah', '8', 'Zahoor Ullah', 'biochemistry', 'Biochemistry', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Muhammad Ziyad emp 1722
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1722' AND `slug` <> 'muhammad-ziyad';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'muhammad-ziyad', '1722', 'Muhammad Ziyad', 'cardiology', 'Cardiology', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Neelofar Shaheen emp 1374
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1374' AND `slug` <> 'neelofar-shaheen';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'neelofar-shaheen', '1374', 'Neelofar Shaheen', 'dhpe', 'DHPE & R', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Urooj Saleem emp 871
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '871' AND `slug` <> 'urooj-saleem';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'urooj-saleem', '871', 'Urooj Saleem', 'dhpe', 'DHPE & R', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Uzma Siddique emp 1704
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1704' AND `slug` <> 'uzma-siddique';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'uzma-siddique', '1704', 'Uzma Siddique', 'dhpe', 'DHPE & R', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Qadeer Muhamamd Khan emp 2084
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '2084' AND `slug` <> 'qadeer-muhammad-khan';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'qadeer-muhammad-khan', '2084', 'Qadeer Muhamamd Khan', 'endocrinology', 'Endocrinology', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Yasir Abbas emp 1605
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1605' AND `slug` <> 'yasir-abbas';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'yasir-abbas', '1605', 'Yasir Abbas', 'gastroenterology', 'Gastroenterology', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Aimen Adil emp 1719
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1719' AND `slug` <> 'aimen-adil';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'aimen-adil', '1719', 'Aimen Adil', 'gynaecology', 'Gynae and Obstetrics', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Humaira Aman emp 1545
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1545' AND `slug` <> 'humaira-aman';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'humaira-aman', '1545', 'Humaira Aman', 'gynaecology', 'Gynae and Obstetrics', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Rabia Kareem emp 1297
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1297' AND `slug` <> 'rabia-kareem';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'rabia-kareem', '1297', 'Rabia Kareem', 'gynaecology', 'Gynae and Obstetrics', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Sana Nafees emp 2075
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '2075' AND `slug` <> 'sana-nafees';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'sana-nafees', '2075', 'Sana Nafees', 'gynaecology', 'Gynae and Obstetrics', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Sobia Siraj emp 1705
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1705' AND `slug` <> 'sobia-siraj';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'sobia-siraj', '1705', 'Sobia Siraj', 'gynaecology', 'Gynae and Obstetrics', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Hashmat Ali emp 1238
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1238' AND `slug` <> 'hashmat-ali';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'hashmat-ali', '1238', 'Hashmat Ali', 'islamic-studies', 'Islamic Studies', 0,
  'Associate Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Sahib Islam emp 1156
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1156' AND `slug` <> 'sahib-islam';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'sahib-islam', '1156', 'Sahib Islam', 'islamic-studies', 'Islamic Studies', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Shams Ul Haq Hanif emp 467
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '467' AND `slug` <> 'shams-ul-haq-hanif';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'shams-ul-haq-hanif', '467', 'Shams Ul Haq Hanif', 'islamic-studies', 'Islamic Studies', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Abdul Samad emp 18
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '18' AND `slug` <> 'abdul-samad';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'abdul-samad', '18', 'Abdul Samad', 'medicine', 'Medicine', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Faridullah Shah emp 101
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '101' AND `slug` <> 'faridullah-shah';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'faridullah-shah', '101', 'Faridullah Shah', 'medicine', 'Medicine', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Muhammad Qasim emp 1629
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1629' AND `slug` <> 'muhammad-qasim';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'muhammad-qasim', '1629', 'Muhammad Qasim', 'medicine', 'Medicine', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Muhammad Subhan emp 15
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '15' AND `slug` <> 'muhammad-subhan';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'muhammad-subhan', '15', 'Muhammad Subhan', 'medicine', 'Medicine', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Noor Mohammad emp 614
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '614' AND `slug` <> 'noor-mohammad';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'noor-mohammad', '614', 'Noor Mohammad', 'medicine', 'Medicine', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Saima Mehboob emp 965
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '965' AND `slug` <> 'saima-mehboob';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'saima-mehboob', '965', 'Saima Mehboob', 'medicine', 'Medicine', 0,
  'Associate Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Hala Rajab emp 1287
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1287' AND `slug` <> 'hala-rajab';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'hala-rajab', '1287', 'Hala Rajab', 'molecular-biology', 'Molecular Biology', 0,
  'Associate Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Wasifa Naushad emp 1354
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1354' AND `slug` <> 'wasifa-naushad';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'wasifa-naushad', '1354', 'Wasifa Naushad', 'molecular-biology', 'Molecular Biology', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Ayesha Sumera emp 68
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '68' AND `slug` <> 'ayesha-sumera';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'ayesha-sumera', '68', 'Ayesha Sumera', 'ophthalmology', 'Ophthalmology', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Anwar Zeb emp 1245
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1245' AND `slug` <> 'anwar-zeb';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'anwar-zeb', '1245', 'Anwar Zeb', 'paediatrics', 'Paediatrics', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Ashfaq Ahmad emp 1269
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1269' AND `slug` <> 'ashfaq-ahmad';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'ashfaq-ahmad', '1269', 'Ashfaq Ahmad', 'pathology', 'Pathology', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Razia Aamir emp 1328
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1328' AND `slug` <> 'razia-aamir';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'razia-aamir', '1328', 'Razia Aamir', 'pathology', 'Pathology', 0,
  'Senior Lecturer', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Sadaf Alam emp 274
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '274' AND `slug` <> 'sadaf-alam';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'sadaf-alam', '274', 'Sadaf Alam', 'pathology', 'Pathology', 0,
  'Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Amna Umer emp 1142
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1142' AND `slug` <> 'amna-umer';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'amna-umer', '1142', 'Amna Umer', 'pharmacology', 'Pharmacology', 0,
  'Assistant Professor', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Mehmood Khattak emp 1919
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1919' AND `slug` <> 'mehmood-khattak';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'mehmood-khattak', '1919', 'Mehmood Khattak', 'pulmonology', 'Pulmonology', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();

-- Sayed Ghafar Shah emp 1421
UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1421' AND `slug` <> 'sayed-ghafar-shah';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `dept_override`,
  `designation`, `photo`, `is_hod`, `is_public`,
  `qualifications_json`, `experience_json`, `skills_json`, `publications_json`, `extra_json`,
  `created_at`, `updated_at`
) VALUES (
  'sayed-ghafar-shah', '1421', 'Sayed Ghafar Shah', 'urology', 'Urology', 0,
  'Senior Registrar', NULL, 0, 1,
  '[]', '[]', '[]', '[]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_public` = VALUES(`is_public`),
  `updated_at` = NOW();
