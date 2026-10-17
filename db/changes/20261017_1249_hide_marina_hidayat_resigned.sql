-- Hide Marina Hidayat (resigned) from public faculty directory / CV lists.
UPDATE `faculty_profiles`
SET `is_public` = 0, `updated_at` = NOW()
WHERE `slug` = 'marina-hidayat';
