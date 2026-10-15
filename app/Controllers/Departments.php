<?php

namespace App\Controllers;

use App\Services\DepartmentService;

class Departments extends BaseController
{
    public function index()
    {
        $svc = new DepartmentService();
        $groups = $svc->groups();
        $counts = [];
        foreach ($groups as $depts) {
            foreach ($depts as $slug => $dept) {
                $counts[$slug] = $svc->facultyCount($slug, (string) ($dept['name'] ?? ''), $dept['faculty'] ?? []);
            }
        }
        return view('departments/index', [
            'page_title' => 'Medical Departments | Peshawar Medical College',
            'page_description' => 'Basic and clinical medical departments at Peshawar Medical College.',
            'canonical_url' => site_url('departments'),
            'groups' => $groups,
            'counts' => $counts,
            'svc' => $svc,
        ]);
    }

    public function show()
    {
        $slug = strtolower(trim((string) $this->request->getGet('slug')));
        $svc = new DepartmentService();
        $dept = $slug !== '' ? $svc->find($slug) : null;
        if (! $dept) {
            return redirect()->to(site_url('departments'));
        }
        $facultySvc = new \App\Services\FacultyDirectoryService();
        $faculty = $facultySvc->forDepartment(
            $slug,
            (string) ($dept['name'] ?? ''),
            $dept['faculty'] ?? [],
            (string) ($dept['hod'] ?? '')
        );
        return view('departments/show', [
            'page_title' => ($dept['name'] ?? 'Department') . ' | Peshawar Medical College',
            'page_description' => is_array($dept['intro'] ?? null) ? implode(' ', $dept['intro']) : '',
            'canonical_url' => site_url('department?slug=' . rawurlencode($slug)),
            'dept' => $dept,
            'slug' => $slug,
            'faculty' => $faculty,
            'pubsUrl' => $svc->oricUrl(isset($dept['oric_id']) ? (int) $dept['oric_id'] : null),
            'hasOricDept' => ! empty($dept['oric_id']),
        ]);
    }

    public function activity()
    {
        $slug = strtolower(trim((string) $this->request->getGet('slug')));
        $index = (int) $this->request->getGet('i');
        $svc = new DepartmentService();
        $dept = $slug !== '' ? $svc->find($slug) : null;
        if (! $dept || empty($dept['activities'][$index])) {
            return redirect()->to(site_url('departments'));
        }
        return view('departments/activity', [
            'page_title' => ($dept['activities'][$index]['title'] ?? 'Activity') . ' | ' . ($dept['name'] ?? ''),
            'page_description' => $dept['activities'][$index]['text'] ?? '',
            'canonical_url' => site_url('department-activity?slug=' . rawurlencode($slug) . '&i=' . $index),
            'dept' => $dept,
            'slug' => $slug,
            'activity' => $dept['activities'][$index],
            'index' => $index,
        ]);
    }
}
