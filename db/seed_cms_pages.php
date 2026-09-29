<?php
/**
 * Idempotent seed: import static legacy pages into cms_pages.
 *
 *   php db/seed_cms_pages.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$root = dirname(__DIR__);
require_once $root . '/legacy/includes/db.php';
$pdo = dms_db();
$now = date('Y-m-d H:i:s');
$legacyDir = $root . '/legacy';

$slugs = [
    'about',
    'pmc',
    'vision-mission',
    'admissions',
    'contact',
    'curriculum',
    'umr',
    'student-guide',
    'student-research',
    'pg-medical-education',
    'pg-surgery-residency',
    'literary-society',
    'sports-society',
    'social-welfare',
    'clinical-skill-labs',
    'virtual-museum',
    'student-exchange',
];

$seoMap = [];
$seoFile = $root . '/legacy/includes/seo-pages.php';
if (is_file($seoFile)) {
    $seoMap = require $seoFile;
    if (!is_array($seoMap)) {
        $seoMap = [];
    }
}

function dms_seed_extract_div_inner(string $html, string $classPrefix): ?string
{
    if (!preg_match('/<div\s+class="' . preg_quote($classPrefix, '/') . '[^"]*"[^>]*>/i', $html, $m, PREG_OFFSET_CAPTURE)) {
        return null;
    }
    $pos = $m[0][1] + strlen($m[0][0]);
    $depth = 1;
    $len = strlen($html);
    $i = $pos;
    while ($i < $len && $depth > 0) {
        if (!preg_match('/<\/?div\b[^>]*>/i', $html, $tm, PREG_OFFSET_CAPTURE, $i)) {
            break;
        }
        $tag = $tm[0][0];
        $at = (int) $tm[0][1];
        if (preg_match('/^<\//', $tag)) {
            $depth--;
            if ($depth === 0) {
                return trim(substr($html, $pos, $at - $pos));
            }
        } else {
            $depth++;
        }
        $i = $at + strlen($tag);
    }

    return null;
}

function dms_seed_decode(string $s): string
{
    return html_entity_decode(strip_tags($s), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$chk = $pdo->prepare('SELECT id FROM cms_pages WHERE slug = ? LIMIT 1');
$ins = $pdo->prepare(
    'INSERT INTO cms_pages
    (slug, title, hero_title, breadcrumb_label, body_html, meta_title, meta_description, status, show_sidebar, sort_order, created_at, updated_at)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)'
);

$added = 0;
$skipped = 0;
$failed = 0;
$order = 10;

foreach ($slugs as $slug) {
    $chk->execute([$slug]);
    if ($chk->fetch()) {
        $skipped++;
        $order += 10;
        continue;
    }

    $file = $legacyDir . '/' . $slug . '.php';
    if (!is_file($file)) {
        fwrite(STDERR, "missing file: {$slug}\n");
        $failed++;
        $order += 10;
        continue;
    }

    $src = (string) file_get_contents($file);
    $body = dms_seed_extract_div_inner($src, 'page-content');
    if ($body === null || $body === '') {
        fwrite(STDERR, "no page-content: {$slug}\n");
        $failed++;
        $order += 10;
        continue;
    }

    $hero = $slug;
    if (preg_match('/<div class="page-hero[\s\S]*?<h1>(.*?)<\/h1>/i', $src, $hm)) {
        $hero = trim(dms_seed_decode($hm[1]));
    }

    $crumb = $hero;
    if (preg_match('/class="current">(.*?)<\/span>/i', $src, $cm)) {
        $crumb = trim(dms_seed_decode($cm[1]));
    }

    $seo = $seoMap[$slug] ?? [];
    $title = (string) ($seo['label'] ?? $hero);
    $metaTitle = (string) ($seo['title'] ?? '');
    $metaDesc = (string) ($seo['description'] ?? '');

    $ins->execute([
        $slug,
        $title !== '' ? $title : $hero,
        $hero,
        $crumb,
        $body,
        $metaTitle !== '' ? $metaTitle : null,
        $metaDesc !== '' ? $metaDesc : null,
        'published',
        1,
        $order,
        $now,
        $now,
    ]);
    $added++;
    $order += 10;
    echo "cms_pages: +{$slug}\n";
}

echo "cms_pages: added={$added} skipped={$skipped} failed={$failed}\n";
echo "Done.\n";
