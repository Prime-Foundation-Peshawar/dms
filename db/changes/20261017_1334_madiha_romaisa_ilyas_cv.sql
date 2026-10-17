-- Madiha Romaisa Ilyas (Senior Registrar) CV from provided profile text. empId 686 (Madiha Romasa Ilyas, Radiology).
UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '686' AND `slug` <> 'madiha-romaisa-ilyas';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'madiha-romaisa-ilyas', '686', 'Madiha Romaisa Ilyas', 'radiology', 'Radiology',
  'Senior Registrar', '', 0, 1,
  '["MBBS", "MCPS"]', '["10 years of experience"]', '[]',
  '[]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = IF(`department_slug` IS NULL OR `department_slug` = '', VALUES(`department_slug`), `department_slug`),
  `department_name` = IF(`department_name` IS NULL OR `department_name` = '', VALUES(`department_name`), `department_name`),
  `designation` = IF(`designation` IS NULL OR `designation` = '', VALUES(`designation`), `designation`),
  `is_public` = 1,
  `qualifications_json` = IF(`qualifications_json` IS NULL OR `qualifications_json` IN ('', '[]'), VALUES(`qualifications_json`), `qualifications_json`),
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), VALUES(`experience_json`), `experience_json`),
  `updated_at` = NOW();

SET @pid := (SELECT `id` FROM `faculty_profiles` WHERE `slug` = 'madiha-romaisa-ilyas' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'madiha-ilyas' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'madiha-ilyas');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'madiha-ilyas' AND @pid IS NOT NULL;
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'madiha-romaisa' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'madiha-romaisa');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'madiha-romaisa' AND @pid IS NOT NULL;
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'madiha-romaisa-ilyas' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'madiha-romaisa-ilyas');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'madiha-romaisa-ilyas' AND @pid IS NOT NULL;
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'romaisa-ilyas' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'romaisa-ilyas');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'romaisa-ilyas' AND @pid IS NOT NULL;

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'madiha-romasa-ilyas' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'madiha-romasa-ilyas');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'madiha-romasa-ilyas' AND @pid IS NOT NULL;

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'madiha-romasa' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'madiha-romasa');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'madiha-romasa' AND @pid IS NOT NULL;
