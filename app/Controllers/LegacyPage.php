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

        return (string) ob_get_clean();
    }
}
