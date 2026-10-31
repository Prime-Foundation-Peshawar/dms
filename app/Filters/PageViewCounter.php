<?php

namespace App\Filters;

use App\Models\PageViewModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Counts public HTML page views (per path per day) for the ACP reports.
 */
class PageViewCounter implements FilterInterface
{
    /** Pages whose ?slug= identifies a distinct page worth counting separately. */
    private const SLUG_PAGES = ['department', 'department-activity', 'faculty-profile', 'single-news'];

    public function before(RequestInterface $request, $arguments = null)
    {
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if (! $request instanceof IncomingRequest || $request->getMethod() !== 'GET' || $request->isAJAX()) {
            return null;
        }
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        $type = $response->getHeaderLine('Content-Type');
        if ($type !== '' && ! str_contains($type, 'text/html')) {
            return null;
        }
        if ($request->getHeaderLine('Purpose') === 'prefetch' || $request->getHeaderLine('Sec-Purpose') !== '') {
            return null;
        }
        $ua = (string) $request->getUserAgent();
        if ($ua === '' || preg_match('/bot|crawl|spider|slurp|preview|monitor|curl|wget|python|headless/i', $ua)) {
            return null;
        }

        $path = trim($request->getPath(), '/');
        if ($path === '') {
            $path = 'home';
        }
        if (str_starts_with($path, 'acp')) {
            return null;
        }

        $deptSlug = null;
        if (in_array($path, self::SLUG_PAGES, true)) {
            $slug = strtolower(trim((string) $request->getGet('slug')));
            $slug = preg_replace('/[^a-z0-9\-]+/', '', $slug) ?? '';
            if ($slug !== '') {
                $path .= '?slug=' . $slug;
                if (str_starts_with($path, 'department')) {
                    $deptSlug = $slug;
                }
            }
        }

        try {
            model(PageViewModel::class)->record($path, $deptSlug);
        } catch (\Throwable $e) {
            log_message('error', 'Page view count failed: ' . $e->getMessage());
        }

        return null;
    }
}
