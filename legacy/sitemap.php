<?php
$host = strtolower($_SERVER['HTTP_HOST'] ?? '');
$is_staging = ($host === 'staging.riphahpsh.edu.pk' || $host === 'www.staging.riphahpsh.edu.pk');
if (!defined('base_url')) {
  define('base_url', $is_staging
    ? 'https://staging.riphahpsh.edu.pk/dms/'
    : 'https://dms.riphahpsh.edu.pk/');
}

require_once __DIR__ . '/includes/seo.php';

header('Content-Type: application/xml; charset=UTF-8');

$pages = seo_pages();
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $slug => $meta) {
  if (isset($meta['in_sitemap']) && $meta['in_sitemap'] === false) {
    continue;
  }
  $file = seo_page_filepath($slug);
  if ($file !== '' && !is_file($file)) {
    continue;
  }

  $loc = htmlspecialchars(seo_page_url($slug, $meta), ENT_XML1 | ENT_QUOTES, 'UTF-8');
  $priority = htmlspecialchars($meta['priority'] ?? '0.5', ENT_XML1, 'UTF-8');
  $changefreq = htmlspecialchars($meta['changefreq'] ?? 'monthly', ENT_XML1, 'UTF-8');
  $lastmod = is_file($file) ? date('Y-m-d', filemtime($file)) : date('Y-m-d');

  echo "  <url>\n";
  echo "    <loc>{$loc}</loc>\n";
  echo "    <lastmod>{$lastmod}</lastmod>\n";
  echo "    <changefreq>{$changefreq}</changefreq>\n";
  echo "    <priority>{$priority}</priority>\n";
  echo "  </url>\n";
}

echo '</urlset>';
