<?php

namespace App\Controllers;

use App\Libraries\PublicSite;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Base for public Controllers + Views (replaces LegacyPage includes).
 */
abstract class PublicController extends BaseController
{
    protected function renderPage(string $view, array $data = []): string
    {
        PublicSite::boot();
        helper(['dms', 'public']);

        // Restore query string into $_GET for views that still read it.
        if (! empty($_SERVER['QUERY_STRING'])) {
            $fromQs = [];
            parse_str((string) $_SERVER['QUERY_STRING'], $fromQs);
            foreach ($fromQs as $key => $value) {
                if (! array_key_exists($key, $_GET)) {
                    $_GET[$key] = $value;
                }
                if (! array_key_exists($key, $_REQUEST)) {
                    $_REQUEST[$key] = $value;
                }
            }
        }

        try {
            foreach ($this->request->getGet() ?? [] as $key => $value) {
                $_GET[$key] = $value;
                $_REQUEST[$key] = $value;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $html = view($view, $data);

        return PublicSite::rewriteLegacyUrls($html);
    }

    protected function redirectLegacy(string $to, int $code = 302): ResponseInterface
    {
        return redirect()->to(site_url($to), $code);
    }
}
