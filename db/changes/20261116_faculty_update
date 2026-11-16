INSERT INTO faculty_profiles
  (slug, emp_id, name, department_slug, department_name, designation, photo,
   is_hod, is_public, dept_override, qualifications_json, experience_json,
   skills_json, publications_json, extra_json, created_at, updated_at)
SELECT 'muhammad-furqan', '97', 'Muhammad Furqan',
       'it-mi', 'IT & MI', 'Director IT', NULL,
       0, 1, 0, '["MS Computer Science"]', '[]',
       '[]', '[]', NULL, NOW(), NOW()
FROM faculty_profiles
WHERE NOT EXISTS (
  SELECT 1 FROM (SELECT emp_id FROM faculty_profiles) t WHERE t.emp_id = '97'
);