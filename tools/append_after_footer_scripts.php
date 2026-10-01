<?php
/**
 * Append legacy post-footer <script> blocks into CI4 view scripts sections.
 */
declare(strict_types=1);

$root = dirname(__DIR__);
$legacyDir = $root . '/legacy';
$viewsDir = $root . '/app/Views/pages';

$footerRe = '/include(?:_once)?\s*(?:\(?\s*[\'"]includes\/footer\.php[\'"]\s*\)?|\s+__DIR__\s*\.\s*[\'"]\/includes\/footer\.php[\'"])\s*;/i';

$map = [
    'faculty.php' => 'faculty',
    'faculty-profile.php' => 'faculty-profile',
    'gallery.php' => 'gallery',
    'newsletter.php' => 'newsletter',
    'education-literature.php' => 'education-literature',
    'portal-login.php' => 'portal-login',
    'portal.php' => 'portal',
    'examinations.php' => 'examinations',
];

foreach ($map as $file => $slug) {
    $srcPath = $legacyDir . '/' . $file;
    $viewPath = $viewsDir . '/' . $slug . '.php';
    if (!is_file($srcPath) || !is_file($viewPath)) {
        echo "SKIP missing $file / $slug\n";
        continue;
    }
    $src = (string) file_get_contents($srcPath);
    if (!preg_match($footerRe, $src, $m, PREG_OFFSET_CAPTURE)) {
        echo "SKIP no footer $file\n";
        continue;
    }
    $after = trim(substr($src, $m[0][1] + strlen($m[0][0])));
    $after = preg_replace('/^\s*\?>\s*/', '', $after) ?? $after;
    if ($after === '') {
        echo "SKIP empty after $file\n";
        continue;
    }

    $view = (string) file_get_contents($viewPath);
    // Remove existing scripts section if present.
    $view = preg_replace('/\n?<\?=\s*\$this->section\(\'scripts\'\)\s*\?>.*?<\?=\s*\$this->endSection\(\)\s*\?>\s*$/s', "\n", $view) ?? $view;
    $view = rtrim($view) . "\n\n";
    $view .= "<?= \$this->section('scripts') ?>\n";
    $view .= $after . "\n";
    $view .= "<?= \$this->endSection() ?>\n";
    file_put_contents($viewPath, $view);
    echo "OK $slug (+" . strlen($after) . ")\n";
}

echo "Done\n";
