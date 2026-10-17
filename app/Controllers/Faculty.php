<?php

namespace App\Controllers;

use App\Services\FacultyDirectoryService;
use App\Services\FacultyProfileService;

class Faculty extends BaseController
{
    public function redirectAll()
    {
        return redirect()->to(site_url('faculty'));
    }

    public function index()
    {
        helper('faculty');
        $faculty_photos = [];
        $faculty_cv_slugs = [];
        foreach (model(\App\Models\FacultyProfileModel::class)->publicRows() as $rec) {
            if (! faculty_profile_is_public($rec)) {
                continue;
            }
            $canon = trim((string) ($rec['slug'] ?? ''));
            $hasCv = ! empty($rec['photo'])
                || ! empty($rec['qualifications'])
                || ! empty($rec['experience'])
                || ! empty($rec['publications'])
                || ! empty($rec['skills']);
            if ($canon !== '' && $hasCv) {
                $faculty_cv_slugs[$canon] = $canon;
                foreach ($rec['aliases'] ?? [] as $alias) {
                    $aliasSlug = faculty_slug((string) $alias);
                    if ($aliasSlug !== '') {
                        $faculty_cv_slugs[$aliasSlug] = $canon;
                    }
                }
                $nameSlug = faculty_slug((string) ($rec['name'] ?? ''));
                if ($nameSlug !== '') {
                    $faculty_cv_slugs[$nameSlug] = $canon;
                }
            }

            $photo = trim((string) ($rec['photo'] ?? ''));
            if ($photo === '') {
                continue;
            }
            if (! preg_match('#^https?://#i', $photo)) {
                $photo = base_url(ltrim($photo, '/'));
            }
            $keys = array_merge([(string) ($rec['slug'] ?? '')], $rec['aliases'] ?? []);
            $keys[] = faculty_slug((string) ($rec['name'] ?? ''));
            foreach ($keys as $key) {
                $key = trim((string) $key);
                if ($key !== '') {
                    $faculty_photos[$key] = $photo;
                }
            }
        }
        return view('faculty/index', [
            'page_title' => 'Faculty | Peshawar Medical College',
            'page_description' => 'Meet PM&DC registered faculty at Peshawar Medical College.',
            'canonical_url' => site_url('faculty'),
            'faculty_photos' => $faculty_photos,
            'faculty_cv_slugs' => $faculty_cv_slugs,
        ]);
    }

    public function proxy()
    {
        $svc = new FacultyDirectoryService();
        return $this->response
            ->setContentType('application/json')
            ->setBody(json_encode($svc->publicDirectory(), JSON_UNESCAPED_UNICODE));
    }

    public function profile()
    {
        $n = (string) $this->request->getGet('n');
        helper('faculty');
        if ($n === '' || ! faculty_slug_is_public($n)) {
            return redirect()->to(site_url('faculty'));
        }
        $profileSvc = new FacultyProfileService();
        $dirSvc = new FacultyDirectoryService();
        $extra = $profileSvc->lookup($n);
        $dirRow = null;
        if ($n !== '') {
            $extraEmpId = trim((string) (($extra ?? [])['emp_id'] ?? ''));
            foreach ($dirSvc->publicDirectory() as $row) {
                $rowEmpId = trim((string) ($row['empId'] ?? ''));
                if ($extraEmpId !== '' && $rowEmpId !== '' && $extraEmpId === $rowEmpId) {
                    $dirRow = $row;
                    break;
                }
            }
            if ($dirRow === null) {
                foreach ($dirSvc->publicDirectory() as $row) {
                    if (faculty_slugs_match(faculty_slug($n), faculty_slug((string) $row['empName']))) {
                        $dirRow = $row;
                        break;
                    }
                }
            }
        }
        if ($extra && ! faculty_profile_is_public($extra)) {
            return redirect()->to(site_url('faculty'));
        }
        if (! $extra && ! $dirRow) {
            return redirect()->to(site_url('faculty'));
        }
        return view('faculty/profile', [
            'page_title' => ((($extra ?? [])['name'] ?? '') ?: ($dirRow['empName'] ?? 'Faculty')) . ' | Peshawar Medical College',
            'page_description' => 'Faculty profile at Peshawar Medical College.',
            'canonical_url' => site_url('faculty-profile?n=' . rawurlencode($n)),
            'extra' => $extra,
            'directory' => $dirRow,
            'slug' => faculty_slug($n),
        ]);
    }

    public function profilesApi()
    {
        helper('faculty');
        $profiles = [];
        $index = [];
        foreach (model(\App\Models\FacultyProfileModel::class)->publicRows() as $rec) {
            if (! faculty_slug_is_public((string) ($rec['slug'] ?? '')) || ! faculty_slug_is_public((string) ($rec['name'] ?? ''))) {
                continue;
            }
            $slug = (string) $rec['slug'];
            $profiles[$slug] = [
                'slug' => $slug,
                'name' => (string) ($rec['name'] ?? ''),
                'aliases' => array_values($rec['aliases'] ?? []),
                'department' => (string) ($rec['department_name'] ?? ''),
                'designation' => (string) ($rec['designation'] ?? ''),
                'qualifications' => array_values($rec['qualifications'] ?? []),
                'skills' => array_values($rec['skills'] ?? []),
                'research_preferences' => faculty_research_preferences($rec),
                'current_research_projects' => array_values(array_filter(array_map(
                    static fn ($item) => is_string($item) || is_numeric($item) ? trim((string) $item) : '',
                    is_array($rec['current_research_projects'] ?? null) ? $rec['current_research_projects'] : []
                ), static fn ($item) => $item !== '')),
                'publications_url' => (string) ($rec['publications_url'] ?? ''),
            ];
            $index[$slug] = $slug;
            foreach ($rec['aliases'] ?? [] as $alias) {
                $aliasSlug = faculty_slug((string) $alias);
                if ($aliasSlug !== '') {
                    $index[$aliasSlug] = $slug;
                }
            }
            $nameSlug = faculty_slug((string) ($rec['name'] ?? ''));
            if ($nameSlug !== '') {
                $index[$nameSlug] = $slug;
            }
        }

        return $this->response->setJSON([
            'profiles' => $profiles,
            'index' => $index,
        ]);
    }
}
