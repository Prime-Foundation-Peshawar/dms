<?php
/**
 * One-shot: extract legacy page bodies into CI4 views.
 * Usage: php tools/port_legacy_to_ci4.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
$legacyDir = $root . '/legacy';
$viewsDir = $root . '/app/Views/pages';

if (!is_dir($viewsDir) && !mkdir($viewsDir, 0755, true) && !is_dir($viewsDir)) {
    fwrite(STDERR, "Cannot create $viewsDir\n");
    exit(1);
}

// Pages that need dedicated controllers (data loading) — still extract body.
$skipBodyOnly = [
    'faculty-update-submit.php', // POST handler, not a view
    'faculty_api.php',
    'faculty-profiles-api.php',
    'faculty-proxy.php',
    'robots.php',
    'sitemap.php',
    'cms-page.php',
    'faculty-all copy.php',
];

$files = glob($legacyDir . '/*.php') ?: [];
sort($files);
$made = 0;
$skipped = 0;

foreach ($files as $path) {
    $base = basename($path);
    if (in_array($base, $skipBodyOnly, true)) {
        echo "SKIP handler $base\n";
        $skipped++;
        continue;
    }

    $src = file_get_contents($path);
    if ($src === false) {
        continue;
    }

    $slug = preg_replace('/\.php$/i', '', $base) ?: $base;
    if ($slug === 'index') {
        $slug = 'home';
    }

    // Find header include and footer include (relative or __DIR__ form).
    $headerRe = '/include(?:_once)?\s*(?:\(?\s*[\'"]includes\/header\.php[\'"]\s*\)?|\s+__DIR__\s*\.\s*[\'"]\/includes\/header\.php[\'"])\s*;/i';
    $footerRe = '/include(?:_once)?\s*(?:\(?\s*[\'"]includes\/footer\.php[\'"]\s*\)?|\s+__DIR__\s*\.\s*[\'"]\/includes\/footer\.php[\'"])\s*;/i';
    if (!preg_match($headerRe, $src, $hm, PREG_OFFSET_CAPTURE)) {
        echo "NO_HEADER $base\n";
        $skipped++;
        continue;
    }
    $afterHeader = $hm[0][1] + strlen($hm[0][0]);

    if (!preg_match($footerRe, $src, $fm, PREG_OFFSET_CAPTURE)) {
        echo "NO_FOOTER $base\n";
        $skipped++;
        continue;
    }
    $beforeFooter = $fm[0][1];

    $preamble = substr($src, 0, $hm[0][1]);
    $body = substr($src, $afterHeader, $beforeFooter - $afterHeader);
    $body = trim($body);

    // Strip closing PHP / reopen noise at edges from preamble-driven pages.
    $body = preg_replace('/^\s*\?>\s*/', '', $body) ?? $body;
    $body = preg_replace('/\s*<\?php\s*$/', '', $body) ?? $body;

    // Capture preamble assigns (page_title etc.) for a small PHP head in the view.
    $meta = [];
    if (preg_match('/\$page_title\s*=\s*([^;]+);/', $preamble, $m)) {
        $meta['page_title'] = trim($m[1]);
    }
    if (preg_match('/\$page_description\s*=\s*([^;]+);/', $preamble, $m)) {
        $meta['page_description'] = trim($m[1]);
    }
    if (preg_match('/\$robots\s*=\s*([^;]+);/', $preamble, $m)) {
        $meta['robots'] = trim($m[1]);
    }

    $out = "<?= \$this->extend('layouts/public') ?>\n";
    $out .= "<?= \$this->section('content') ?>\n\n";
    $out .= $body . "\n\n";
    $out .= "<?= \$this->endSection() ?>\n";

    $dest = $viewsDir . '/' . $slug . '.php';
    file_put_contents($dest, $out);
    echo "OK $slug\n";
    $made++;

    // Write preamble sidecar for controller data bootstrap hints.
    $side = [
        'slug' => $slug,
        'source' => $base,
        'meta' => $meta,
        'has_preamble_logic' => (bool) preg_match('/require_once|dms_|faculty_|\$hero|\$pack|\$extra/', $preamble),
        'preamble_lines' => substr_count($preamble, "\n"),
    ];
    file_put_contents($viewsDir . '/' . $slug . '.meta.json', json_encode($side, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
}

echo "\nDone: made=$made skipped=$skipped\n";
