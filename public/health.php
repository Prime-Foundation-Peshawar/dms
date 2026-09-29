<?php
/**
 * Temporary staging diagnostics (no secrets). Delete after go-live.
 */
header('Content-Type: text/plain; charset=utf-8');
header('X-Robots-Tag: noindex');
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo 'php=' . PHP_VERSION . PHP_EOL;
echo 'sapi=' . PHP_SAPI . PHP_EOL;

$root = dirname(__DIR__);
echo 'root=' . $root . PHP_EOL;
echo 'env_exists=' . (is_file($root . '/.env') ? 'yes' : 'no') . PHP_EOL;
echo 'vendor_exists=' . (is_dir($root . '/vendor/codeigniter4/framework') ? 'yes' : 'no') . PHP_EOL;

foreach (['writable', 'writable/cache', 'writable/logs', 'writable/session'] as $dir) {
    $path = $root . '/' . $dir;
    echo $dir . '_writable=' . ((is_dir($path) && is_writable($path)) ? 'yes' : 'no') . PHP_EOL;
}

if (is_file($root . '/.env')) {
    $raw = @file_get_contents($root . '/.env') ?: '';
    echo 'env_has_baseURL=' . (preg_match('/^\s*app\.baseURL\s*=/m', $raw) ? 'yes' : 'no') . PHP_EOL;
    echo 'env_has_encryption=' . (preg_match('/^\s*encryption\.key\s*=/m', $raw) ? 'yes' : 'no') . PHP_EOL;
    echo 'env_has_db=' . (preg_match('/database\.default\.database\s*=|^\s*DB_DATABASE\s*=/m', $raw) ? 'yes' : 'no') . PHP_EOL;
    echo 'env_CI_ENVIRONMENT=' . (preg_match('/^\s*CI_ENVIRONMENT\s*=\s*(\S+)/m', $raw, $m) ? $m[1] : 'unset') . PHP_EOL;
}

echo '--- boot ---' . PHP_EOL;
try {
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    if (!defined('COMPOSER_PATH')) {
        // Minimal constants from public/index.php flow
    }
    require $root . '/vendor/autoload.php';
    require $root . '/app/Config/Paths.php';
    $paths = new Config\Paths();
    require $paths->systemDirectory . '/Boot.php';

    // Mirror index.php constants
    if (!defined('ENVIRONMENT')) {
        $env = 'production';
        if (is_file($root . '/.env')) {
            foreach (file($root . '/.env', FILE_IGNORE_NEW_LINES) as $line) {
                if (preg_match('/^\s*CI_ENVIRONMENT\s*=\s*(.+)$/', $line, $m)) {
                    $env = trim($m[1], " \t\"'");
                }
            }
        }
        define('ENVIRONMENT', $env);
    }

    // Load DotEnv like CI does
    $dotenv = new CodeIgniter\Config\DotEnv($root);
    $dotenv->load();

    $app = new Config\App();
    echo 'baseURL=' . $app->baseURL . PHP_EOL;

    $enc = new Config\Encryption();
    echo 'encryption_key_set=' . ($enc->key !== '' ? 'yes' : 'no') . PHP_EOL;

    $db = new Config\Database();
    echo 'db_name=' . ($db->default['database'] !== '' ? 'set' : 'empty') . PHP_EOL;
    echo 'db_user=' . ($db->default['username'] !== '' ? 'set' : 'empty') . PHP_EOL;

    // Try including Routes to catch fatal parse issues
    require $root . '/app/Config/Routes.php';
    echo 'routes=ok' . PHP_EOL;

    // Attempt a DB ping
    try {
        $pdo = new PDO(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $db->default['hostname'] ?: 'localhost',
                $db->default['port'] ?: 3306,
                $db->default['database']
            ),
            $db->default['username'],
            $db->default['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo 'db_ping=ok' . PHP_EOL;
    } catch (Throwable $dbErr) {
        echo 'db_ping_error=' . $dbErr->getMessage() . PHP_EOL;
    }
} catch (Throwable $e) {
    echo 'boot_error=' . $e->getMessage() . PHP_EOL;
    echo 'boot_file=' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
    echo 'boot_trace=' . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}

// Latest CI log line if any
$logDir = $root . '/writable/logs';
if (is_dir($logDir)) {
    $logs = glob($logDir . '/log-*.log');
    if ($logs) {
        rsort($logs);
        $tail = @file($logs[0]);
        if (is_array($tail) && $tail) {
            echo '--- log_tail ---' . PHP_EOL;
            echo implode('', array_slice($tail, -40));
        }
    }
}
