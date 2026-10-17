-- Feed Radiology (Adnan Yousaf, Saima Rabbani) + Cardiac Surgery (Aamir Zeb) from departmental Word templates.
-- Generated 2026-10-17T13:31:35

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1535' AND `slug` <> 'adnan-yousaf';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'adnan-yousaf', '1535', 'Adnan Yousaf', 'radiology', 'Radiology',
  'Assistant Professor', 'assets/images/faculty/adnan-yousaf.jpeg', 0, 1,
  '["MBBS", "FCPS(DGR)"]', '["More than 7 years"]', '[]',
  '["Frequency of Malignant Renal Cystic lesions based on Bosniak Grading system.", "Diagnostic Accuracy of Antenatal Ultrasound in Posterior Urethral Valves.", "Positive Predictive Value of Magnetic Resonance Imaging in Intraspinal Tumors Taking Histopathology as Gold Standard.", "Role of ultrasound in the diagnosis, treatment and complications of non alcoholic fatty liver disease", "Interventional radiology in hemodialysis access management", "Contrast induced nephropathy,risk, prevention and imaging alternatives."]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `photo` = IF(VALUES(`photo`) <> '', VALUES(`photo`), `photo`),
  `is_public` = 1,
  `qualifications_json` = IF(`qualifications_json` IS NULL OR `qualifications_json` IN ('', '[]'), VALUES(`qualifications_json`), `qualifications_json`),
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), VALUES(`experience_json`), `experience_json`),
  `skills_json` = IF(`skills_json` IS NULL OR `skills_json` IN ('', '[]'), VALUES(`skills_json`), `skills_json`),
  `publications_json` = IF(`publications_json` IS NULL OR `publications_json` IN ('', '[]'), VALUES(`publications_json`), `publications_json`),
  `updated_at` = NOW();
SET @pid := (SELECT id FROM faculty_profiles WHERE slug = 'adnan-yousaf' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'adnan-yousaf' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'adnan-yousaf');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'adnan-yousaf' AND @pid IS NOT NULL;

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1868' AND `slug` <> 'saima-rabbani';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'saima-rabbani', '1868', 'Saima Rabbani', 'radiology', 'Radiology',
  'Assistant Professor', '', 0, 1,
  '["MBBS", "FCPS(DGR)"]', '["03 years"]', '[]',
  '["Role of ultrasound in the dignosis,treatment and complications of non alcoholic fatty liver disease", "Interventional radiology in hemodialysis access managment", "Contrast induced nephropathy,risk,prevention and imaging alternatives."]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `photo` = IF(VALUES(`photo`) <> '', VALUES(`photo`), `photo`),
  `is_public` = 1,
  `qualifications_json` = IF(`qualifications_json` IS NULL OR `qualifications_json` IN ('', '[]'), VALUES(`qualifications_json`), `qualifications_json`),
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), VALUES(`experience_json`), `experience_json`),
  `skills_json` = IF(`skills_json` IS NULL OR `skills_json` IN ('', '[]'), VALUES(`skills_json`), `skills_json`),
  `publications_json` = IF(`publications_json` IS NULL OR `publications_json` IN ('', '[]'), VALUES(`publications_json`), `publications_json`),
  `updated_at` = NOW();
SET @pid := (SELECT id FROM faculty_profiles WHERE slug = 'saima-rabbani' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'saima-rabbani' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'saima-rabbani');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'saima-rabbani' AND @pid IS NOT NULL;

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1941' AND `slug` <> 'aamir-zeb';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'aamir-zeb', '1941', 'Aamir Zeb', 'cardiac-surgery', 'Cardiac Surgery',
  'Assistant Professor', 'assets/images/faculty/aamir-zeb.jpeg', 0, 1,
  '["FCPS Cardiac Surgery"]', '["Faculty Membership For 2 Years & 8 Months"]', '["OFF PUMP Coronary Artery Bypass", "Minimally Invasive Congenital & Valvular Replacements", "Coronary, Valvular & Congenital Heart Surgeries"]',
  '["Effect Of KDIGO-Guideline-Based Preventive Bundle On Acute Kidney Injury After Cardiiac Surgery In High Risk Patients: Randomized Controlled Trial.", "Coronary Artery Bypass Grafting (CABG) Outcomes Of OFF PUMP VS ON PUMP CABG In High Risk Patients"]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `photo` = IF(VALUES(`photo`) <> '', VALUES(`photo`), `photo`),
  `is_public` = 1,
  `qualifications_json` = IF(`qualifications_json` IS NULL OR `qualifications_json` IN ('', '[]'), VALUES(`qualifications_json`), `qualifications_json`),
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), VALUES(`experience_json`), `experience_json`),
  `skills_json` = IF(`skills_json` IS NULL OR `skills_json` IN ('', '[]'), VALUES(`skills_json`), `skills_json`),
  `publications_json` = IF(`publications_json` IS NULL OR `publications_json` IN ('', '[]'), VALUES(`publications_json`), `publications_json`),
  `updated_at` = NOW();
SET @pid := (SELECT id FROM faculty_profiles WHERE slug = 'aamir-zeb' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'aamir-zeb' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'aamir-zeb');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'aamir-zeb' AND @pid IS NOT NULL;

