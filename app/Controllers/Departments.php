<?php

namespace App\Controllers;

class Departments extends PublicController
{
    public function index(): string
    {
        require_once ROOTPATH . 'legacy/includes/departments-data.php';
        require_once ROOTPATH . 'legacy/includes/faculty-lib.php';
        $groups = academic_department_groups(academic_departments_all());

        return $this->renderPage('pages/departments', compact('groups'));
    }

    public function show()
    {
        require_once ROOTPATH . 'legacy/includes/departments-data.php';
        require_once ROOTPATH . 'legacy/includes/faculty-lib.php';

        $slug = strtolower(trim((string) ($this->request->getGet('slug') ?? '')));
        $dept = $slug !== '' ? get_academic_department($slug) : null;
        if (! $dept) {
            return $this->redirectLegacy('departments', 302);
        }

        $faculty = faculty_for_department_page(
            $slug,
            (string) ($dept['name'] ?? ''),
            $dept['faculty'] ?? [],
            (string) ($dept['hod'] ?? '')
        );
        $activities = $dept['activities'] ?? [];
        $intro = $dept['intro'] ?? [];
        $facultyCount = count($faculty);
        $activityCount = count($activities);
        $pubsUrl = department_oric_publications_url($slug);
        $hasOricDept = department_oric_id($slug) !== null;

        return $this->renderPage('pages/department', compact(
            'slug',
            'dept',
            'faculty',
            'activities',
            'intro',
            'facultyCount',
            'activityCount',
            'pubsUrl',
            'hasOricDept'
        ));
    }

    public function activity()
    {
        require_once ROOTPATH . 'legacy/includes/departments-data.php';

        $slug = strtolower(trim((string) ($this->request->getGet('slug') ?? '')));
        $id = (int) ($this->request->getGet('id') ?? -1);
        $payload = ($slug !== '' && $id >= 0) ? get_department_activity($slug, $id) : null;
        if (! $payload) {
            $to = $slug !== '' ? 'department?slug=' . rawurlencode($slug) . '#activities' : 'departments';

            return $this->redirectLegacy($to, 302);
        }

        $dept = $payload['dept'];
        $activity = $payload['activity'];
        $title = trim($activity['title'] ?? 'Department Activity');
        $date = trim($activity['date'] ?? 'TBA');
        $text = trim($activity['text'] ?? '');
        $details = trim($activity['details'] ?? '');
        $body = $details !== '' ? $details : $text;
        $page_title = $title . ' | ' . ($dept['name'] ?? 'Department') . ' | PMC';

        return $this->renderPage('pages/department-activity', compact(
            'slug',
            'id',
            'dept',
            'activity',
            'title',
            'date',
            'text',
            'details',
            'body',
            'page_title'
        ));
    }
}
