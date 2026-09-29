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

    protected function renderLegacy(string $path): string
    {
        // Ensure legacy helpers see CI constants.
        if (!defined('base_url')) {
            define('base_url', rtrim(base_url(), '/') . '/');
        }
        if (!defined('hub_base')) {
            define('hub_base', dms_hub_base());
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
