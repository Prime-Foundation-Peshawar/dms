<?php

namespace App\Controllers;

/**
 * Serve unported public pages from /legacy while CI4 owns routing.
 */
class LegacyPage extends BaseController
{
    public function show(?string $page = null)
    {
        $page = $page ?: 'index';
        $page = strtolower(trim($page));
        $page = preg_replace('/[^a-z0-9\-\_]/', '', $page) ?? 'index';

        // /dms/index.php becomes "indexphp" after sanitizing — treat as home.
        if ($page === '' || $page === 'indexphp' || $page === 'home') {
            $page = 'index';
        }

        // Some CGI/FPM + rewrite setups leave $_GET empty; restore from QUERY_STRING.
        if (!empty($_SERVER['QUERY_STRING'])) {
            $fromQs = [];
            parse_str((string) $_SERVER['QUERY_STRING'], $fromQs);
            foreach ($fromQs as $key => $value) {
                if (!array_key_exists($key, $_GET)) {
                    $_GET[$key] = $value;
                }
                if (!array_key_exists($key, $_REQUEST)) {
                    $_REQUEST[$key] = $value;
                }
            }
        }

        // Prefer CI request query values when present.
        try {
            $req = service('request');
            foreach ($req->getGet() ?? [] as $key => $value) {
                $_GET[$key] = $value;
                $_REQUEST[$key] = $value;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Prefer published cms_pages over hard-coded legacy files (except reserved modules).
        if (! $this->isReservedCmsSlug($page)) {
            try {
                require_once ROOTPATH . 'legacy/includes/cms-content.php';
                $cmsPage = dms_cms_page_by_slug($page);
                if (is_array($cmsPage)) {
                    return $this->response->setBody($this->renderLegacy(
                        ROOTPATH . 'legacy/cms-page.php',
                        ['cmsPage' => $cmsPage]
                    ));
                }
            } catch (\Throwable $e) {
                // Fall through to legacy file.
            }
        }

        // Map clean URLs to legacy PHP scripts
        $map = [
            'index' => 'index.php',
            'home'  => 'index.php',
        ];

        $file = $map[$page] ?? ($page . '.php');
        $path = ROOTPATH . 'legacy/' . $file;

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($page);
        }

        // Legacy scripts expect to live next to includes/ and echo HTML.
        return $this->response->setBody($this->renderLegacy($path));
    }

    /**
     * Slugs owned by other modules / APIs — never serve from cms_pages.
     */
    protected function isReservedCmsSlug(string $page): bool
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

        return in_array($page, $reserved, true);
    }

    /**
     * @param array<string,mixed> $vars
     */
    protected function renderLegacy(string $path, array $vars = []): string
    {
        // Ensure legacy helpers see CI constants.
        if (!defined('base_url')) {
            define('base_url', rtrim(base_url(), '/') . '/');
        }
        if (!defined('hub_base')) {
            define('hub_base', dms_hub_base());
        }

        if ($vars !== []) {
            extract($vars, EXTR_SKIP);
        }

        ob_start();
        include $path;
        $html = (string) ob_get_clean();

        return $this->rewriteLegacyUrls($html);
    }

    /**
     * Convert relative *.php links/actions to clean CI4 routes.
     * Leaves absolute external URLs untouched.
     */
    protected function rewriteLegacyUrls(string $html): string
    {
        $home = rtrim(base_url(), '/') . '/';

        return (string) preg_replace_callback(
            '/\b(href|action)=([\'"])([^\'"\s]+)\2/i',
            static function (array $m) use ($home): string {
                $attr = $m[1];
                $quote = $m[2];
                $url = $m[3];

                // External, protocol-relative, mailto/tel, or pure hash
                if ($url === '' || $url[0] === '#' || preg_match('#^(?:[a-z][a-z0-9+.-]*:|//)#i', $url)) {
                    return $m[0];
                }

                $path = $url;
                $suffix = '';
                if (preg_match('/^([^?#]+)([?#].*)$/', $url, $parts)) {
                    $path = $parts[1];
                    $suffix = $parts[2];
                }

                if (!preg_match('/\.php$/i', $path) && strtolower(basename(str_replace('\\', '/', $path))) !== 'index') {
                    return $m[0];
                }

                $clean = (string) preg_replace('/\.php$/i', '', $path);
                $base = strtolower(basename(str_replace('\\', '/', $clean)));
                if ($base === 'index') {
                    // index / index.php / index.php#section → site home
                    $clean = ($suffix === '')
                        ? $home
                        : rtrim($home, '/') . '/' . $suffix;

                    return $attr . '=' . $quote . $clean . $quote;
                }

                return $attr . '=' . $quote . $clean . $suffix . $quote;
            },
            $html
        );
    }
}
