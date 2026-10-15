<?php
$dir = dirname(__DIR__) . '/app/Views/pages';
foreach (glob($dir . '/*.php') ?: [] as $file) {
    if (str_ends_with($file, '_wrap.php') || str_ends_with($file, 'content_wrap.php')) {
        continue;
    }
    $body = (string) file_get_contents($file);
    $orig = $body;
    $body = preg_replace('/include\s*\(\s*[\'"]includes\/header\.php[\'"]\s*\)\s*;/', '', $body) ?? $body;
    $body = preg_replace('/include\s*\(\s*[\'"]includes\/footer\.php[\'"]\s*\)\s*;/', '', $body) ?? $body;
    $body = preg_replace('/require_once\s+__DIR__\s*\.\s*[\'"]\/?includes\/[^\'"]+[\'"]\s*;/', '// legacy require removed', $body) ?? $body;
    $body = preg_replace('/\$preload_images\s*=\s*\[[^\]]*\];/', '', $body) ?? $body;
    if ($body !== $orig) {
        file_put_contents($file, $body);
        echo 'fixed ' . basename($file) . PHP_EOL;
    }
}
echo "done\n";
