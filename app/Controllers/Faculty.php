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
        $profileSvc = new FacultyProfileService();
        $dirSvc = new FacultyDirectoryService();
        $extra = $n !== '' ? $profileSvc->lookup($n) : null;
        $directory = $n !== '' ? null : null;
        // directory stub if no CV
        helper('faculty');
        $dirRow = null;
        if ($n !== '') {
            foreach ($dirSvc->publicDirectory() as $row) {
                if (faculty_slugs_match(faculty_slug($n), faculty_slug((string) $row['empName']))) {
                    $dirRow = $row;
                    break;
                }
            }
        }
        return view('faculty/profile', [
            'page_title' => ($extra['name'] ?? $dirRow['empName'] ?? 'Faculty') . ' | Peshawar Medical College',
            'page_description' => 'Faculty profile at Peshawar Medical College.',
            'canonical_url' => site_url('faculty-profile?n=' . rawurlencode($n)),
            'extra' => $extra,
            'directory' => $dirRow,
            'slug' => faculty_slug($n),
        ]);
    }
}
