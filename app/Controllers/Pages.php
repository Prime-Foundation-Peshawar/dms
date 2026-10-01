<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Static / CMS-backed public pages.
 */
class Pages extends PublicController
{
    /** @var list<string> */
    protected array $staticSlugs = [
        'about', 'vision-mission', 'contact', 'admissions', 'curriculum', 'pmc',
        'examinations', 'e-health', 'clinical-skill-labs', 'education-literature',
        'literary-society', 'social-welfare', 'sports-society', 'student-exchange',
        'student-guide', 'student-research', 'umr', 'virtual-museum',
        'pg-medical-education', 'pg-surgery-residency', 'faculty-research',
        'portal', 'portal-login', 'sitemap-page',
    ];

    public function show(string $slug)
    {
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/[^a-z0-9\-\_]/', '', $slug) ?? '';

        if ($slug === 'medical-education') {
            return $this->redirectLegacy('pmc#mbbs', 301);
        }
        if ($slug === 'portal_login') {
            return $this->redirectLegacy('portal-login', 301);
        }

        // Built-in static views win over CMS so ported pages never 500 on a missing sidebar include.
        $view = 'pages/' . $slug;
        $hasStatic = is_file(APPPATH . 'Views/' . $view . '.php');

        // CMS override only for slugs that are not reserved and have no static view.
        if (! $hasStatic && ! $this->isReserved($slug)) {
            require_once ROOTPATH . 'legacy/includes/cms-content.php';
            $cmsPage = dms_cms_page_by_slug($slug);
            if (is_array($cmsPage)) {
                return $this->renderCms($cmsPage);
            }
        }

        if (! $hasStatic) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($slug);
        }

        $data = $this->metaFromLegacy($slug);

        return $this->renderPage($view, $data);
    }

    public function cms(): string
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';
        $slug = trim((string) ($this->request->getGet('slug') ?? ''));
        $cmsPage = $slug !== '' ? dms_cms_page_by_slug($slug) : null;
        if (! is_array($cmsPage)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($slug ?: 'cms');
        }

        return $this->renderCms($cmsPage);
    }

    /**
     * @param array<string,mixed> $cmsPage
     */
    protected function renderCms(array $cmsPage): string
    {
        $heroTitle = (string) ($cmsPage['hero_title'] ?? $cmsPage['title'] ?? 'Page');
        $crumb = (string) ($cmsPage['breadcrumb_label'] ?? $heroTitle);
        $bodyHtml = (string) ($cmsPage['body_html'] ?? '');
        $showSidebar = ! empty($cmsPage['show_sidebar']);
        $page_title = ! empty($cmsPage['meta_title']) ? (string) $cmsPage['meta_title'] : null;
        $page_description = ! empty($cmsPage['meta_description']) ? (string) $cmsPage['meta_description'] : null;

        return $this->renderPage('pages/cms-page', compact(
            'cmsPage',
            'heroTitle',
            'crumb',
            'bodyHtml',
            'showSidebar',
            'page_title',
            'page_description'
        ));
    }

    /**
     * @return array<string,mixed>
     */
    protected function metaFromLegacy(string $slug): array
    {
        $metaFile = APPPATH . 'Views/pages/' . $slug . '.meta.json';
        if (! is_file($metaFile)) {
            return [];
        }
        $json = json_decode((string) file_get_contents($metaFile), true);
        if (! is_array($json) || empty($json['meta']) || ! is_array($json['meta'])) {
            return [];
        }
        $out = [];
        foreach ($json['meta'] as $key => $expr) {
            // meta values are PHP expressions like "'About | …'" — eval safely via string trim of quotes.
            $expr = trim((string) $expr);
            if (preg_match('/^([\'"])(.*)\\1$/s', $expr, $m)) {
                $out[$key] = $m[2];
            }
        }

        return $out;
    }

    protected function isReserved(string $slug): bool
    {
        static $reserved = [
            'index', 'home',
            'all-news', 'single-news', 'events', 'event-single',
            'gallery', 'vacant-seats', 'newsletter',
            'departments', 'department', 'department-activity',
            'faculty', 'faculty-profile', 'faculty-all', 'faculty-update',
            'faculty-update-submit', 'faculty_api', 'faculty-profiles-api', 'faculty-proxy',
            'e-health', 'examinations', 'faculty-research',
            'portal', 'portal-login', 'portal_login',
            'sitemap', 'sitemap-page', 'robots',
            'medical-education', 'cms-page', 'acp',
        ];

        return in_array($slug, $reserved, true);
    }
}
