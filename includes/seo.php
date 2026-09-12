<?php
if (!defined('GOOGLE_SITE_VERIFICATION')) {
  define('GOOGLE_SITE_VERIFICATION', '');
}
if (!defined('SEO_ORG_NAME')) {
  define('SEO_ORG_NAME', 'Peshawar Medical College');
}
if (!defined('SEO_DEFAULT_TITLE')) {
  define('SEO_DEFAULT_TITLE', 'Peshawar Medical College | MBBS in Peshawar');
}
if (!defined('SEO_DEFAULT_DESCRIPTION')) {
  define('SEO_DEFAULT_DESCRIPTION', 'Peshawar Medical College — a top PM&DC recognized medical college in Peshawar and KP. MBBS and postgraduate programmes at Riphah Peshawar Campus, Warsak Road.');
}

function seo_pages(): array
{
  static $pages = null;
  if ($pages === null) {
    $pages = require __DIR__ . '/seo-pages.php';
  }
  return $pages;
}

function seo_current_slug(): string
{
  $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
  $base = basename($script, '.php');
  if ($base === '' || strtolower($base) === 'index') {
    return '';
  }
  return $base;
}

function seo_canonical_url(string $slug): string
{
  if ($slug === '') {
    return base_url;
  }
  return rtrim(base_url, '/') . '/' . $slug;
}

function seo_page_url(string $slug, ?array $meta = null): string
{
  if ($meta === null) {
    $meta = seo_pages()[$slug] ?? [];
  }
  if (!empty($meta['url'])) {
    return $meta['url'];
  }
  return seo_canonical_url($slug);
}

function seo_page_filepath(string $slug): string
{
  $root = dirname(__DIR__);
  return $slug === '' ? $root . DIRECTORY_SEPARATOR . 'index.php' : $root . DIRECTORY_SEPARATOR . $slug . '.php';
}

function seo_apply(): array
{
  $slug = seo_current_slug();
  $meta = seo_pages()[$slug] ?? [];

  return [
    'slug' => $slug,
    'label' => $meta['label'] ?? '',
    'title' => $meta['title'] ?? SEO_DEFAULT_TITLE,
    'description' => $meta['description'] ?? SEO_DEFAULT_DESCRIPTION,
    'og_type' => $meta['og_type'] ?? 'website',
    'schema' => $meta['schema'] ?? null,
    'course_name' => $meta['course_name'] ?? null,
    'canonical' => seo_canonical_url($slug),
  ];
}

function seo_json_ld(array $graph): string
{
  return json_encode(
    ['@context' => 'https://schema.org', '@graph' => $graph],
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP
  );
}
