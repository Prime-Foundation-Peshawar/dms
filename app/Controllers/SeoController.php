<?php

namespace App\Controllers;

use App\Models\PageModel;

class SeoController extends BaseController
{
    public function robots()
    {
        $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
        $isStaging = str_contains($host, 'staging');
        $body = $isStaging
            ? "User-agent: *\nDisallow: /\n"
            : "User-agent: *\nAllow: /\nDisallow: /faculty-update\nDisallow: /faculty-update-submit\nDisallow: /faculty-profiles-api\nSitemap: " . site_url('sitemap.xml') . "\n";
        return $this->response->setContentType('text/plain')->setBody($body);
    }

    public function sitemap()
    {
        $pages = model(PageModel::class)->sitemapRows();
        $xml = ['<?xml version="1.0" encoding="UTF-8"?>', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'];
        foreach ($pages as $p) {
            $slug = $p['slug'] === 'home' ? '' : $p['slug'];
            $loc = site_url($slug);
            $xml[] = '<url><loc>' . htmlspecialchars($loc) . '</loc><changefreq>' . htmlspecialchars((string) $p['changefreq']) . '</changefreq><priority>' . htmlspecialchars((string) $p['priority']) . '</priority></url>';
        }
        $xml[] = '</urlset>';
        return $this->response->setContentType('application/xml')->setBody(implode("\n", $xml));
    }
}
