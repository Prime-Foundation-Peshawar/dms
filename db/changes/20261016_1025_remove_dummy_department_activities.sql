-- Remove the placeholder activities that were copied onto every department.
DELETE FROM department_activities
WHERE title IN (
  'Teaching Sessions',
  'Case / Skill Discussions',
  'Departmental Academic Activities'
);
