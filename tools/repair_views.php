<?php
declare(strict_types=1);

$root = dirname(__DIR__);

function write_view(string $dest, string $body): void
{
    $body = trim($body);
    $out = "<?= \$this->extend('layouts/public') ?>\n";
    $out .= "<?= \$this->section('content') ?>\n\n";
    $out .= $body . "\n\n";
    $out .= "<?= \$this->endSection() ?>\n";
    file_put_contents($dest, $out);
    echo "Wrote $dest (" . strlen($body) . " bytes body)\n";
}

function extract_between_header_footer(string $src): ?array
{
    $headerRe = '/include(?:_once)?\s*(?:\(?\s*[\'"]includes\/header\.php[\'"]\s*\)?|\s+__DIR__\s*\.\s*[\'"]\/includes\/header\.php[\'"])\s*;/i';
    $footerRe = '/include(?:_once)?\s*(?:\(?\s*[\'"]includes\/footer\.php[\'"]\s*\)?|\s+__DIR__\s*\.\s*[\'"]\/includes\/footer\.php[\'"])\s*;/i';
    if (!preg_match($headerRe, $src, $hm, PREG_OFFSET_CAPTURE)) {
        return null;
    }
    if (!preg_match($footerRe, $src, $fm, PREG_OFFSET_CAPTURE)) {
        return null;
    }
    $pre = substr($src, 0, $hm[0][1]);
    $body = substr($src, $hm[0][1] + strlen($hm[0][0]), $fm[0][1] - ($hm[0][1] + strlen($hm[0][0])));
    $body = preg_replace('/^\s*\?>\s*/', '', $body) ?? $body;
    $body = preg_replace('/\s*<\?php\s*$/', '', $body) ?? $body;

    // Leading <style> blocks from before header
    $styles = '';
    if (preg_match_all('/<style\b[^>]*>.*?<\/style>/is', $pre, $sm)) {
        $styles = implode("\n\n", $sm[0]) . "\n\n";
    }

    // Drop leftover PHP assigns that controllers now own (best-effort)
    $body = preg_replace('/^\$slideCount\s*=.*?\?>\s*/s', '', $body) ?? $body;

    return ['body' => $styles . trim($body), 'pre' => $pre];
}

$targets = [
    'index.php' => 'home',
    'events.php' => 'events',
    'gallery.php' => 'gallery',
    'all-news.php' => 'all-news',
    'faculty-profile.php' => 'faculty-profile',
    'faculty-update.php' => 'faculty-update',
    'single-news.php' => 'single-news',
    'event-single.php' => 'event-single',
    'department.php' => 'department',
    'department-activity.php' => 'department-activity',
    'departments.php' => 'departments',
    'faculty.php' => 'faculty',
    'vacant-seats.php' => 'vacant-seats',
    'newsletter.php' => 'newsletter',
    'cms-page.php' => 'cms-page',
];

foreach ($targets as $file => $slug) {
    $src = file_get_contents($root . '/legacy/' . $file);
    if ($src === false) {
        echo "MISSING $file\n";
        continue;
    }
    $ex = extract_between_header_footer($src);
    if (!$ex) {
        echo "FAIL $file\n";
        continue;
    }
    write_view($root . '/app/Views/pages/' . $slug . '.php', $ex['body']);
}

echo "Done\n";
