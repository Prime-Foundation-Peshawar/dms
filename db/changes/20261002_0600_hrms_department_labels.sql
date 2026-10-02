-- Align public department labels with HRMS depName values.
UPDATE `departments` SET `name` = 'CHS', `updated_at` = NOW()
WHERE `slug` = 'chs' AND `name` <> 'CHS';

UPDATE `departments` SET `name` = 'DHPE & R', `updated_at` = NOW()
WHERE `slug` = 'dhpe' AND `name` <> 'DHPE & R';

UPDATE `departments` SET `name` = 'Gynae and Obstetrics', `updated_at` = NOW()
WHERE `slug` = 'gynaecology' AND `name` <> 'Gynae and Obstetrics';

UPDATE `departments` SET `name` = 'Orthopedics', `updated_at` = NOW()
WHERE `slug` = 'orthopaedics' AND `name` <> 'Orthopedics';

-- Add HRMS-only departments missing from the catalog.
INSERT INTO `departments`
  (`slug`, `name`, `icon`, `dept_group`, `hod_name`, `intro`, `faculty_fallback`, `oric_id`, `sort_order`, `updated_on`, `is_active`, `created_at`, `updated_at`)
SELECT
  'accident-emergency',
  'Accident and Emergency',
  'bi-ambulance',
  'Clinical',
  'Assistant Professor Dr. Muhammad Ishfaq',
  '["Accident and Emergency provides teaching in acute assessment, triage, and initial management of medical and surgical emergencies.","Students learn structured approaches to common emergency presentations in affiliated teaching hospitals."]',
  '[{"name":"Assistant Professor Dr. Muhammad Ishfaq","qualification":"MBBS, FCPS","reg":"19531-N"},{"name":"Senior Registrar Dr. Asif Ali","qualification":"MBBS, FCPS","reg":"26366-N"},{"name":"Senior Registrar Dr. Shumaila Farman","qualification":"MBBS, FCPS","reg":"27920-N"}]',
  NULL,
  200,
  '2026-08-09',
  1,
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `departments` WHERE `slug` = 'accident-emergency');

INSERT INTO `departments`
  (`slug`, `name`, `icon`, `dept_group`, `hod_name`, `intro`, `faculty_fallback`, `oric_id`, `sort_order`, `updated_on`, `is_active`, `created_at`, `updated_at`)
SELECT
  'paeds-cardiology',
  'Paeds Cardiology',
  'bi-heart',
  'Clinical',
  'Assistant Professor Dr. Zia Ur Rahman',
  '["Paeds Cardiology covers diagnosis and management of congenital and acquired heart disease in children.","Teaching links paediatric medicine with cardiac assessment in tertiary-care settings."]',
  '[{"name":"Assistant Professor Dr. Zia Ur Rahman","qualification":"MBBS, FCPS","reg":"16502-N"}]',
  NULL,
  201,
  '2026-08-09',
  1,
  NOW(),
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM `departments` WHERE `slug` = 'paeds-cardiology');

-- Default activities for newly inserted departments (skip if already present).
INSERT INTO `department_activities` (`department_id`, `title`, `activity_date`, `body`, `sort_order`, `created_at`, `updated_at`)
SELECT d.id, 'Ward & Clinic Teaching', 'Ongoing 2026',
  'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.', 1, NOW(), NOW()
FROM `departments` d
WHERE d.slug IN ('accident-emergency', 'paeds-cardiology')
  AND NOT EXISTS (
    SELECT 1 FROM `department_activities` a WHERE a.department_id = d.id AND a.title = 'Ward & Clinic Teaching'
  );

INSERT INTO `department_activities` (`department_id`, `title`, `activity_date`, `body`, `sort_order`, `created_at`, `updated_at`)
SELECT d.id, 'Case Presentations', 'Weekly',
  'Student case presentations with consultant feedback to strengthen clinical reasoning.', 2, NOW(), NOW()
FROM `departments` d
WHERE d.slug IN ('accident-emergency', 'paeds-cardiology')
  AND NOT EXISTS (
    SELECT 1 FROM `department_activities` a WHERE a.department_id = d.id AND a.title = 'Case Presentations'
  );

INSERT INTO `department_activities` (`department_id`, `title`, `activity_date`, `body`, `sort_order`, `created_at`, `updated_at`)
SELECT d.id, 'CME / Departmental Meetings', '2025–26',
  'Continuing medical education sessions and departmental academic meetings.', 3, NOW(), NOW()
FROM `departments` d
WHERE d.slug IN ('accident-emergency', 'paeds-cardiology')
  AND NOT EXISTS (
    SELECT 1 FROM `department_activities` a WHERE a.department_id = d.id AND a.title = 'CME / Departmental Meetings'
  );
