<?php
/**
 * Wrap leftover bare PHP at the start of CI4 page views.
 */
declare(strict_types=1);

$dir = dirname(__DIR__) . '/app/Views/pages';
foreach (glob($dir . '/*.php') ?: [] as $path) {
    $src = (string) file_get_contents($path);
    if (!preg_match('/section\(\'content\'\)\s*\?>\s*\r?\n\$[a-zA-Z_]/', $src)) {
        continue;
    }
    // Insert opening php tag right after section content marker when bare assigns follow.
    $fixed = preg_replace(
        '/(section\(\'content\'\)\s*\?>\s*\r?\n)(\$[a-zA-Z_])/m',
        '$1<?php' . "\n" . '$2',
        $src,
        1,
        $count
    );
    if ($count && is_string($fixed)) {
        file_put_contents($path, $fixed);
        echo 'FIXED ' . basename($path) . "\n";
    }
}
echo "Done\n";
