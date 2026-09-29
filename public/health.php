<?php
/**
 * Temporary staging diagnostics (no secrets). Delete after go-live.
 */
header('Content-Type: text/plain; charset=utf-8');
header('X-Robots-Tag: noindex');

echo 'php=' . PHP_VERSION . PHP_EOL;
echo 'sapi=' . PHP_SAPI . PHP_EOL;

$root = dirname(__DIR__);
echo 'root=' . $root . PHP_EOL;
echo 'env_exists=' . (is_file($root . '/.env') ? 'yes' : 'no') . PHP_EOL;
echo 'vendor_exists=' . (is_dir($root . '/vendor/codeigniter4/framework') ? 'yes' : 'no') . PHP_EOL;
echo 'public_index=' . (is_file(__DIR__ . '/index.php') ? 'yes' : 'no') . PHP_EOL;

foreach (['writable', 'writable/cache', 'writable/logs', 'writable/session'] as $dir) {
    $path = $root . '/' . $dir;
    $exists = is_dir($path) ? 'yes' : 'no';
    $writable = (is_dir($path) && is_writable($path)) ? 'yes' : 'no';
    echo $dir . '_exists=' . $exists . ' writable=' . $writable . PHP_EOL;
}

if (is_file($root . '/.env')) {
    $raw = @file_get_contents($root . '/.env') ?: '';
    echo 'env_has_baseURL=' . (preg_match('/^\s*app\.baseURL\s*=/m', $raw) ? 'yes' : 'no') . PHP_EOL;
    echo 'env_has_encryption=' . (preg_match('/^\s*encryption\.key\s*=/m', $raw) ? 'yes' : 'no') . PHP_EOL;
    echo 'env_has_db=' . (preg_match('/database\.default\.database\s*=|^\s*DB_DATABASE\s*=/m', $raw) ? 'yes' : 'no') . PHP_EOL;
    echo 'env_has_cms_password=' . (preg_match('/^\s*CMS_ADMIN_PASSWORD\s*=\s*(?!change_me\s*$).+/m', $raw) ? 'yes' : 'no') . PHP_EOL;
}

// Try booting CI briefly and catch Throwable
try {
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    require $root . '/app/Config/Paths.php';
    $paths = new Config\Paths();
    require rtrim($paths->systemDirectory, '\\/ ') . '/Boot.php';
    echo 'boot_autoload=ok' . PHP_EOL;
} catch (Throwable $e) {
    echo 'boot_error=' . $e->getMessage() . PHP_EOL;
    echo 'boot_file=' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
}
