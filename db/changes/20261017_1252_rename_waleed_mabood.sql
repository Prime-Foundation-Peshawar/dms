-- Correct spelling: Waleed Mabood (was Mahbood). empId still blank — not in HRMS yet.
UPDATE `faculty_profiles`
SET `name` = 'Waleed Mabood', `slug` = 'waleed-mabood', `updated_at` = NOW()
WHERE `slug` = 'waleed-mahbood';

SET @pid := (SELECT `id` FROM `faculty_profiles` WHERE `slug` = 'waleed-mabood' LIMIT 1);

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'waleed-mahbood' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'waleed-mahbood');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'waleed-mahbood' AND @pid IS NOT NULL;

INSERT INTO `faculty_profile_aliases` (`profile_id`, `alias`)
SELECT @pid, 'waleed-mahboob' FROM DUAL
WHERE @pid IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `faculty_profile_aliases` WHERE `alias` = 'waleed-mahboob');
UPDATE `faculty_profile_aliases` SET `profile_id` = @pid WHERE `alias` = 'waleed-mahboob' AND @pid IS NOT NULL;
