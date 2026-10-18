-- Radiology faculty CVs from departmental Word drop (Majid, Zeenat Adil, Ambreen Muhammad, Aziz Zia).
-- Regenerated 2026-10-18T09:00:19 from local DB after cleanup.

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '313' AND `slug` <> 'abdul-majid';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'abdul-majid', '313', 'Abdul Majid', 'radiology',
  'Radiology', 'Professor',
  'assets/images/faculty/abdul-majid.jpeg', 1, 1,
  '["MBBS", "FCPS", "Fellowship Vascular & Interventional Radiology"]', '["16 years"]', '["Expert in all interventional Radiology"]',
  '["Diagnostic accuracy of MRI in mesorectal fascia involvement --- Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Radiological assessment of respectability status in patient with pancreatic cancer –Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Initial results from uterine fibroid embolization for symptomatic leiomyomata. Khyber Journal of Medical Sciences (KJMS) Jan –March 2025. Volume 18. No 1.", "Average kidney size in local Pediatric population by ultrasography Medical Forum Volume 36. No 5 May 2025.", "Role of MRI in evaluation of traumatic knee joint injury. Medical Forum volume 36. No 6 June 2025", "Ultrasound guided Biopsy (Targeted biopsy): A Useful Method Of Extraction Of Sample Tissues To Examine The Diagnostic Accuracy Of Malignant And Benign Lesions. ABDUL MAJID , SHAUKAT MAHMOOD ¸ MAHWASH MANSOOR Pakistan journal of medical and health sciences Vol 21, Oct- December 2018.", "COMPARISON OF IMAGE FINDINGS WITH HISTOPATHOLOGICAL DIAGNOSIS ON ULTRASOUND GUIDED PERCUTANEOUS LIVER BIOPSY IN RELATION TO HEPTAOCELLULAR CARCINOMA 12 ABDUL MAJID, MUHAMMAD MUMTAZ KHAN , 12 ABDUL AZIZ ZIA , SADAF ALAM . KMUL 2018 Vol 10, issue 4, Oct- December", "Accuracy of sonourethrography in evaluation of anterior urethral stricture BIOMEDICA Vol; 32 ISSUE 3 JULY–SEP 2016.", "Ultrasonographically detectable fetal congenital anomalies associated with polyhydramnios. BIOMEDICA Vol; 31 ISSUE 2 APRIL –JUNE 2015.", "Role of ultrasound in IUGR BIOMEDICA Vol; 30 ISSUE 4 OCT-DECEMBER 2014.", "Extra corporeal shockwave lithotripsy. Monthly , The PROFESSIONAL medical journal Vol 22, ISSUE 1 January 2015", "Determining the efficacy of trnasarterial chemo embolization for hepatocellular carcinoma on follow up Quadriphasic computed tomography. PJR July –September 2019, 29 (3).", "View of Comparative Diagnostic permomance of CT and MRI in acute ischemic Stroke: A Retrospective Analysis From A Tertiary Care Center https://share. google / b GGNGOYn4ERs6qfeF", "Frequency of hepataocellular carcinoma in local population using Triphasic CT as diagnostic. (KJMS) September –December 2018. Vol. 10 . No 3.", "Post –chemo lung toxicity A less visited domain vol. 10 (2020). General of saidu medical college.", "To determine frequency of distribution of breast carcinoma patient based on American joint committee (AJCC) on cancer. A single central experience KJMS Vol. 14 No.1 (2021) January- March."]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_hod` = GREATEST(`is_hod`, VALUES(`is_hod`)),
  `photo` = IF(VALUES(`photo`) <> '', VALUES(`photo`), `photo`),
  `is_public` = 1,
  `updated_at` = NOW();
UPDATE `faculty_profiles`
SET
  `is_hod` = GREATEST(`is_hod`, 1),
  `qualifications_json` = '["MBBS", "FCPS", "Fellowship Vascular & Interventional Radiology"]',
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), '["16 years"]', `experience_json`),
  `skills_json` = IF(`skills_json` IS NULL OR `skills_json` IN ('', '[]'), '["Expert in all interventional Radiology"]', `skills_json`),
  `publications_json` = '["Diagnostic accuracy of MRI in mesorectal fascia involvement --- Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Radiological assessment of respectability status in patient with pancreatic cancer –Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Initial results from uterine fibroid embolization for symptomatic leiomyomata. Khyber Journal of Medical Sciences (KJMS) Jan –March 2025. Volume 18. No 1.", "Average kidney size in local Pediatric population by ultrasography Medical Forum Volume 36. No 5 May 2025.", "Role of MRI in evaluation of traumatic knee joint injury. Medical Forum volume 36. No 6 June 2025", "Ultrasound guided Biopsy (Targeted biopsy): A Useful Method Of Extraction Of Sample Tissues To Examine The Diagnostic Accuracy Of Malignant And Benign Lesions. ABDUL MAJID , SHAUKAT MAHMOOD ¸ MAHWASH MANSOOR Pakistan journal of medical and health sciences Vol 21, Oct- December 2018.", "COMPARISON OF IMAGE FINDINGS WITH HISTOPATHOLOGICAL DIAGNOSIS ON ULTRASOUND GUIDED PERCUTANEOUS LIVER BIOPSY IN RELATION TO HEPTAOCELLULAR CARCINOMA 12 ABDUL MAJID, MUHAMMAD MUMTAZ KHAN , 12 ABDUL AZIZ ZIA , SADAF ALAM . KMUL 2018 Vol 10, issue 4, Oct- December", "Accuracy of sonourethrography in evaluation of anterior urethral stricture BIOMEDICA Vol; 32 ISSUE 3 JULY–SEP 2016.", "Ultrasonographically detectable fetal congenital anomalies associated with polyhydramnios. BIOMEDICA Vol; 31 ISSUE 2 APRIL –JUNE 2015.", "Role of ultrasound in IUGR BIOMEDICA Vol; 30 ISSUE 4 OCT-DECEMBER 2014.", "Extra corporeal shockwave lithotripsy. Monthly , The PROFESSIONAL medical journal Vol 22, ISSUE 1 January 2015", "Determining the efficacy of trnasarterial chemo embolization for hepatocellular carcinoma on follow up Quadriphasic computed tomography. PJR July –September 2019, 29 (3).", "View of Comparative Diagnostic permomance of CT and MRI in acute ischemic Stroke: A Retrospective Analysis From A Tertiary Care Center https://share. google / b GGNGOYn4ERs6qfeF", "Frequency of hepataocellular carcinoma in local population using Triphasic CT as diagnostic. (KJMS) September –December 2018. Vol. 10 . No 3.", "Post –chemo lung toxicity A less visited domain vol. 10 (2020). General of saidu medical college.", "To determine frequency of distribution of breast carcinoma patient based on American joint committee (AJCC) on cancer. A single central experience KJMS Vol. 14 No.1 (2021) January- March."]',
  `updated_at` = NOW()
WHERE `slug` = 'abdul-majid' OR `emp_id` = '313';
SET @pid := (SELECT id FROM faculty_profiles WHERE slug = 'abdul-majid' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'abdul-majid' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'abdul-majid');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'abdul-majid' AND @pid IS NOT NULL;

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1108' AND `slug` <> 'zeenat-adil';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'zeenat-adil', '1108', 'Zeenat Adil', 'radiology',
  'Radiology', 'Associate Professor',
  '', 0, 1,
  '["MBBS", "DMRD", "EDiR", "FRCR (UK)", "CHPE"]', '["19 years"]', '["Diagnostic Radiology"]',
  '["Sites of primary malignancies in patient with brain metastasis. JPMI 2003 Vol. 19. No 3: 317-322.", "Prevalence of various type of intra articular injuries detected by MRI in trauma to the knee joint. Journal of medical sciences. July 2005, Vol. 13. No .2.", "Diagnostic adequacy and safety of image guided trucut biopsy. PPMJ vol. 27. No .3. July September 2016.", "Frequency of circumferential resection margin involvement on MRI in rectal cancer (KJMS) September –December 2017. Vol. 10 . No 3.", "Frequency of hepataocellular carcinoma in local population using Triphasic CT as diagnostic. (KJMS) September –December 2018. Vol. 10 . No 3.", "Determining the efficacy of trnasarterial chemo embolization for the hepatocellular carcinoma on follow up Quadriphasic computed tomography. PJR July –September 2019, 29 (3).", "Post –chemo lung toxicity A less visited domain vol. 10 (2020). General of saidu medical college.", "To determine frequency of distribution of breast carcinoma patient based on American joint committee (AJCC) on cancer. A single central experience KJMS Vol. 14 No.1 (2021) January- March.", "Pre operative evaluation and tumor node metastasis staging of gastric carcinoma using 16 slices multi-detector- ROW computed tomography vol.13. No 3 (2021): KMUJ 2021, Vol 13, issue 3- July September.", "Diagnostic accuracy of MRI in mesorectal fascia involvement --- Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Radiological assessment of respectability status in patient with pancreatic cancer –Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Initial results from uterine fibroid embolization for symptomatic leiomyomata. Khyber Journal of Medical Sciences.2025 18 (1) ; 65- 72 https://doi. org/10.70520/kjms.v18i1.633. View of Comparative Diagnostic permomance of CT and MRI in acute ischemic Stroke: A Retrospective Analysis From A Tertiary Care Center https://share. google / b GGNGOYn4ERs6qfeF", "Average kidney size in local Pediatric population by ultrasography Medical Forum Volume 36. No 5 May 2025.", "Role of MRI in evaluation of traumatic knee joint injury. Medical Forum volume 36. No 6 June 2025"]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_hod` = GREATEST(`is_hod`, VALUES(`is_hod`)),
  `photo` = IF(VALUES(`photo`) <> '', VALUES(`photo`), `photo`),
  `is_public` = 1,
  `updated_at` = NOW();
UPDATE `faculty_profiles`
SET
  `is_hod` = GREATEST(`is_hod`, 0),
  `qualifications_json` = '["MBBS", "DMRD", "EDiR", "FRCR (UK)", "CHPE"]',
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), '["19 years"]', `experience_json`),
  `skills_json` = IF(`skills_json` IS NULL OR `skills_json` IN ('', '[]'), '["Diagnostic Radiology"]', `skills_json`),
  `publications_json` = '["Sites of primary malignancies in patient with brain metastasis. JPMI 2003 Vol. 19. No 3: 317-322.", "Prevalence of various type of intra articular injuries detected by MRI in trauma to the knee joint. Journal of medical sciences. July 2005, Vol. 13. No .2.", "Diagnostic adequacy and safety of image guided trucut biopsy. PPMJ vol. 27. No .3. July September 2016.", "Frequency of circumferential resection margin involvement on MRI in rectal cancer (KJMS) September –December 2017. Vol. 10 . No 3.", "Frequency of hepataocellular carcinoma in local population using Triphasic CT as diagnostic. (KJMS) September –December 2018. Vol. 10 . No 3.", "Determining the efficacy of trnasarterial chemo embolization for the hepatocellular carcinoma on follow up Quadriphasic computed tomography. PJR July –September 2019, 29 (3).", "Post –chemo lung toxicity A less visited domain vol. 10 (2020). General of saidu medical college.", "To determine frequency of distribution of breast carcinoma patient based on American joint committee (AJCC) on cancer. A single central experience KJMS Vol. 14 No.1 (2021) January- March.", "Pre operative evaluation and tumor node metastasis staging of gastric carcinoma using 16 slices multi-detector- ROW computed tomography vol.13. No 3 (2021): KMUJ 2021, Vol 13, issue 3- July September.", "Diagnostic accuracy of MRI in mesorectal fascia involvement --- Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Radiological assessment of respectability status in patient with pancreatic cancer –Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Initial results from uterine fibroid embolization for symptomatic leiomyomata. Khyber Journal of Medical Sciences.2025 18 (1) ; 65- 72 https://doi. org/10.70520/kjms.v18i1.633. View of Comparative Diagnostic permomance of CT and MRI in acute ischemic Stroke: A Retrospective Analysis From A Tertiary Care Center https://share. google / b GGNGOYn4ERs6qfeF", "Average kidney size in local Pediatric population by ultrasography Medical Forum Volume 36. No 5 May 2025.", "Role of MRI in evaluation of traumatic knee joint injury. Medical Forum volume 36. No 6 June 2025"]',
  `updated_at` = NOW()
WHERE `slug` = 'zeenat-adil' OR `emp_id` = '1108';
SET @pid := (SELECT id FROM faculty_profiles WHERE slug = 'zeenat-adil' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'zeenat-adil' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'zeenat-adil');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'zeenat-adil' AND @pid IS NOT NULL;

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1379' AND `slug` <> 'ambreen-muhammad';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'ambreen-muhammad', '1379', 'Ambreen Muhammad', 'radiology',
  'Radiology', 'Assistant Professor',
  '', 0, 1,
  '["MBBS", "FCPS", "CHPE"]', '["12 years"]', '["Diagnostic Radiology"]',
  '["Diagnostic accuracy of MRI in mesorectal fascia involvement --- Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Radiological assessment of respectability status in patient with pancreatic cancer –Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Initial results from uterine fibroid embolization for symptomatic leiomyomata. Khyber Journal of Medical Sciences.2025 18 (1) ; 65- 72 https://doi. org/10.70520/kjms.v18i1.633.", "Average kidney size in local Pediatric population by ultrasography Medical Forum Volume 36. No 5 May 2025.", "Role of MRI in evaluation of traumatic knee joint injury. Medical Forum volume 36. No 6 June 2025"]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_hod` = GREATEST(`is_hod`, VALUES(`is_hod`)),
  `photo` = IF(VALUES(`photo`) <> '', VALUES(`photo`), `photo`),
  `is_public` = 1,
  `updated_at` = NOW();
UPDATE `faculty_profiles`
SET
  `is_hod` = GREATEST(`is_hod`, 0),
  `qualifications_json` = '["MBBS", "FCPS", "CHPE"]',
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), '["12 years"]', `experience_json`),
  `skills_json` = IF(`skills_json` IS NULL OR `skills_json` IN ('', '[]'), '["Diagnostic Radiology"]', `skills_json`),
  `publications_json` = '["Diagnostic accuracy of MRI in mesorectal fascia involvement --- Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Radiological assessment of respectability status in patient with pancreatic cancer –Journal of Rehman Medical institute (JRMI). Volume 8, No.2 April –June 2022.", "Initial results from uterine fibroid embolization for symptomatic leiomyomata. Khyber Journal of Medical Sciences.2025 18 (1) ; 65- 72 https://doi. org/10.70520/kjms.v18i1.633.", "Average kidney size in local Pediatric population by ultrasography Medical Forum Volume 36. No 5 May 2025.", "Role of MRI in evaluation of traumatic knee joint injury. Medical Forum volume 36. No 6 June 2025"]',
  `updated_at` = NOW()
WHERE `slug` = 'ambreen-muhammad' OR `emp_id` = '1379';
SET @pid := (SELECT id FROM faculty_profiles WHERE slug = 'ambreen-muhammad' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'ambareen-muhammad' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'ambareen-muhammad');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'ambareen-muhammad' AND @pid IS NOT NULL;
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'ambreen-muhammad' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'ambreen-muhammad');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'ambreen-muhammad' AND @pid IS NOT NULL;

UPDATE `faculty_profiles` SET `emp_id` = NULL WHERE `emp_id` = '1068' AND `slug` <> 'abdul-aziz-zia';
INSERT INTO `faculty_profiles` (
  `slug`, `emp_id`, `name`, `department_slug`, `department_name`, `designation`, `photo`,
  `is_hod`, `is_public`, `qualifications_json`, `experience_json`, `skills_json`,
  `publications_json`, `extra_json`, `created_at`, `updated_at`
) VALUES (
  'abdul-aziz-zia', '1068', 'Abdul Aziz Zia', 'radiology',
  'Radiology', 'Assistant Professor',
  'assets/images/faculty/abdul-aziz-zia.jpg', 0, 1,
  '["MBBS", "DMRD"]', '["36 years"]', '["Administration", "Ultrasound"]',
  '["Comparison of image findings with histopathological diagnosis on ultrasound guided percutaneous liver biopsy in relation to hepatocellular carcinoma. KMUH Khyber Medical University. https://www.kmuj.kmu.edu.pk/article/view/18102", "Average kidney size in local pediatric population by ultrasonography. Medical Forum Monthly. DOI: 10.60110/medforum.360504. https://medicalforummonthly.com/index.php/mfm/article/view/5276/4747", "Integrated Radiologic and Intraoperative Assessment of Gallbladder Carcinoma: Evaluating the Role of Multidetector CT in T and N Staging. International Journal of Pharmacy Research and Technology. https://ijprt.org/index.php/pub/article/view/2995", "CT Detection of Subarachnoid Hemorrhage Correlated with Clinical Severity Using Hunt and Hess Grading. Pakistan Journal of Neurological Surgery (PJNS).", "Assessment of endometrial thickness and vascularity by transvaginal Doppler ultrasound and their relationship with endometrial microbial infection in reproductive-aged women with subclinical hypothyroidism: a comparative study. Genetics and Molecular Research (GMR). https://geneticsmr.com/index.php/gmr/article/view/2158"]', '{}', NOW(), NOW()
)
ON DUPLICATE KEY UPDATE
  `emp_id` = VALUES(`emp_id`),
  `name` = VALUES(`name`),
  `department_slug` = VALUES(`department_slug`),
  `department_name` = VALUES(`department_name`),
  `designation` = VALUES(`designation`),
  `is_hod` = GREATEST(`is_hod`, VALUES(`is_hod`)),
  `photo` = IF(VALUES(`photo`) <> '', VALUES(`photo`), `photo`),
  `is_public` = 1,
  `updated_at` = NOW();
UPDATE `faculty_profiles`
SET
  `is_hod` = GREATEST(`is_hod`, 0),
  `qualifications_json` = '["MBBS", "DMRD"]',
  `experience_json` = IF(`experience_json` IS NULL OR `experience_json` IN ('', '[]'), '["36 years"]', `experience_json`),
  `skills_json` = IF(`skills_json` IS NULL OR `skills_json` IN ('', '[]'), '["Administration", "Ultrasound"]', `skills_json`),
  `publications_json` = '["Comparison of image findings with histopathological diagnosis on ultrasound guided percutaneous liver biopsy in relation to hepatocellular carcinoma. KMUH Khyber Medical University. https://www.kmuj.kmu.edu.pk/article/view/18102", "Average kidney size in local pediatric population by ultrasonography. Medical Forum Monthly. DOI: 10.60110/medforum.360504. https://medicalforummonthly.com/index.php/mfm/article/view/5276/4747", "Integrated Radiologic and Intraoperative Assessment of Gallbladder Carcinoma: Evaluating the Role of Multidetector CT in T and N Staging. International Journal of Pharmacy Research and Technology. https://ijprt.org/index.php/pub/article/view/2995", "CT Detection of Subarachnoid Hemorrhage Correlated with Clinical Severity Using Hunt and Hess Grading. Pakistan Journal of Neurological Surgery (PJNS).", "Assessment of endometrial thickness and vascularity by transvaginal Doppler ultrasound and their relationship with endometrial microbial infection in reproductive-aged women with subclinical hypothyroidism: a comparative study. Genetics and Molecular Research (GMR). https://geneticsmr.com/index.php/gmr/article/view/2158"]',
  `updated_at` = NOW()
WHERE `slug` = 'abdul-aziz-zia' OR `emp_id` = '1068';
SET @pid := (SELECT id FROM faculty_profiles WHERE slug = 'abdul-aziz-zia' LIMIT 1);
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'abdul-aziz' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'abdul-aziz');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'abdul-aziz' AND @pid IS NOT NULL;
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'abdul-aziz-zia' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'abdul-aziz-zia');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'abdul-aziz-zia' AND @pid IS NOT NULL;
INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'aziz-zia' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'aziz-zia');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'aziz-zia' AND @pid IS NOT NULL;

