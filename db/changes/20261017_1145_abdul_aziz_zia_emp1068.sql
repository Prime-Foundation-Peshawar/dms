-- Ensure Radiology CV for Abdul Aziz Zia is linked to HRMS empId 1068.
-- Clears any stale emp_id collision, then upserts by slug.

UPDATE `faculty_profiles`
SET `emp_id` = NULL
WHERE `emp_id` = '1068' AND `slug` <> 'abdul-aziz-zia';

INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'abdul-aziz-zia', '1068', 'Abdul Aziz Zia', 'radiology', 'Radiology',
  'Assistant Professor', 'assets/images/faculty/abdul-aziz-zia.jpg', 0, 1,
  '["MBBS", "DMRD"]', '["36 years"]', '["Administration", "Ultrasound"]',
  '["Comparison of image findings with histopathological diagnosis on ultrasound guided percutaneous liver biopsy in relation to hepatocellular carcinoma. KMUH Khyber Medical University. https://www.kmuj.kmu.edu.pk/article/view/18102", "Average kidney size in local pediatric population by ultrasonography. Medical Forum Monthly. DOI: 10.60110/medforum.360504. https://medicalforummonthly.com/index.php/mfm/article/view/5276/4747", "Integrated Radiologic and Intraoperative Assessment of Gallbladder Carcinoma: Evaluating the Role of Multidetector CT in T and N Staging. International Journal of Pharmacy Research and Technology. https://ijprt.org/index.php/pub/article/view/2995", "CT Detection of Subarachnoid Hemorrhage Correlated with Clinical Severity Using Hunt and Hess Grading. Pakistan Journal of Neurological Surgery (PJNS).", "Assessment of endometrial thickness and vascularity by transvaginal Doppler ultrasound and their relationship with endometrial microbial infection in reproductive-aged women with subclinical hypothyroidism: a comparative study. Genetics and Molecular Research (GMR). https://geneticsmr.com/index.php/gmr/article/view/2158"]', '{}',
  NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `photo` = VALUES(`photo`),
  `is_hod` = VALUES(`is_hod`),
  `is_public` = VALUES(`is_public`),
  `qualifications_json` = VALUES(`qualifications_json`),
  `experience_json` = VALUES(`experience_json`),
  `skills_json` = VALUES(`skills_json`),
  `publications_json` = VALUES(`publications_json`),
  `extra_json` = VALUES(`extra_json`),
  `updated_at` = NOW();

SET @pid := (SELECT `id` FROM `faculty_profiles` WHERE `slug` = 'abdul-aziz-zia' LIMIT 1);

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'abdul-aziz-zia' FROM DUAL
WHERE @pid IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'abdul-aziz-zia'
  );
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'abdul-aziz-zia' AND @pid IS NOT NULL;

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'abdul-aziz' FROM DUAL
WHERE @pid IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'abdul-aziz'
  );
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'abdul-aziz' AND @pid IS NOT NULL;

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'aziz-zia' FROM DUAL
WHERE @pid IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'aziz-zia'
  );
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'aziz-zia' AND @pid IS NOT NULL;
