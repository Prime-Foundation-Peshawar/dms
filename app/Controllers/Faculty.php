<?php

namespace App\Controllers;

class Faculty extends PublicController
{
    public function index(): string
    {
        return $this->renderPage('pages/faculty');
    }

    public function all()
    {
        return $this->redirectLegacy('faculty', 301);
    }

    public function profile()
    {
        require_once ROOTPATH . 'legacy/includes/faculty-lib.php';

        $slug = faculty_slug((string) ($this->request->getGet('n') ?? ''));
        if ($slug === '') {
            return $this->redirectLegacy('faculty', 302);
        }

        $extra = function_exists('faculty_profile_lookup_cv')
            ? faculty_profile_lookup_cv($slug)
            : faculty_profile_lookup($slug);
        if (! $extra) {
            return $this->redirectLegacy('faculty', 302);
        }

        $display_name = $extra['name'] ?? '';
        $page_title = ($display_name !== '' ? $display_name . ' — Faculty' : 'Faculty') . ' | Department of Medical Sciences';
        $page_description = $display_name !== ''
            ? $display_name . ' teaches at Peshawar Medical College, Riphah Peshawar Campus.'
            : 'Faculty at the Department of Medical Sciences, Peshawar Medical College.';

        $photo = faculty_photo_url($extra['photo'] ?? '');
        $desig = faculty_normalize_designation((string) ($extra['designation'] ?? ''));
        $dept = (string) ($extra['department'] ?? '');
        $is_hod = ! empty($extra['hod']);

        $quals = function_exists('faculty_normalize_qualifications')
            ? faculty_normalize_qualifications($extra['qualifications'] ?? [])
            : (function_exists('faculty_list_as_written') ? faculty_list_as_written($extra['qualifications'] ?? []) : []);
        $skills = function_exists('faculty_normalize_skills')
            ? faculty_normalize_skills($extra['skills'] ?? [])
            : (function_exists('faculty_list_as_written') ? faculty_list_as_written($extra['skills'] ?? []) : []);
        $research = faculty_research_preferences($extra);
        $pubs = function_exists('faculty_explode_publications')
            ? faculty_explode_publications($extra['publications'] ?? [])
            : (function_exists('faculty_publications_as_written') ? faculty_publications_as_written($extra['publications'] ?? []) : []);
        $projects = function_exists('faculty_list_as_written')
            ? faculty_list_as_written($extra['current_research_projects'] ?? [])
            : [];

        $initials = 'F';
        if ($display_name !== '') {
            $parts = preg_split('/\s+/', $display_name) ?: [];
            $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1] ?? '', 0, 1));
        }

        return $this->renderPage('pages/faculty-profile', compact(
            'slug',
            'extra',
            'display_name',
            'page_title',
            'page_description',
            'photo',
            'desig',
            'dept',
            'is_hod',
            'quals',
            'skills',
            'research',
            'pubs',
            'projects',
            'initials'
        ));
    }

    public function research(): string
    {
        return $this->renderPage('pages/faculty-research');
    }

    public function profilesApi()
    {
        require_once ROOTPATH . 'legacy/includes/faculty-lib.php';
        $pack = faculty_profiles_pack();

        return $this->response
            ->setHeader('Cache-Control', 'no-store')
            ->setJSON($pack);
    }

    public function proxy()
    {
        // Keep behaviour identical to legacy/faculty-proxy.php
        ob_start();
        include ROOTPATH . 'legacy/faculty-proxy.php';
        $body = (string) ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Cache-Control', 'no-store')
            ->setBody($body);
    }

    public function apiPage(): string
    {
        // legacy faculty_api.php is an HTML page on this branch
        if (is_file(APPPATH . 'Views/pages/faculty_api.php')) {
            return $this->renderPage('pages/faculty_api');
        }

        return $this->redirectLegacy('faculty', 301);
    }
}
