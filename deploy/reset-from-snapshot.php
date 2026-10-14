<?php
/**
 * Drop all tables in the configured MySQL DB and import deploy/local-mysql-snapshot.sql
 * Usage: php deploy/reset-from-snapshot.php [/path/to/.env] [/path/to/snapshot.sql]
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$root = dirname(__DIR__);
$envPath = $argv[1] ?? ($root . '/.env');
$snapPath = $argv[2] ?? ($root . '/deploy/local-mysql-snapshot.sql');

if (! is_file($envPath)) {
    fwrite(STDERR, "reset: missing .env at {$envPath}\n");
    exit(1);
}
if (! is_file($snapPath)) {
    fwrite(STDERR, "reset: missing snapshot at {$snapPath}\n");
    exit(1);
}

$text = file_get_contents($envPath);
$get = static function (string $text, array $keys): string {
    foreach ($keys as $key) {
        $k = preg_quote($key, '/');
        if (preg_match('/(?:^|\n)[ \t]*' . $k . '[ \t]*=[ \t]*("([^"]*)"|\'([^\']*)\'|([^\r\n#]*))/i', $text, $m)) {
            if (isset($m[1][0]) && $m[1][0] === '"') {
                return $m[2];
            }
            if (isset($m[1][0]) && $m[1][0] === "'") {
                return $m[3];
            }

            return trim($m[4]);
        }
    }

    return '';
};

$host = $get($text, ['database.default.hostname', 'DB_HOST']) ?: 'localhost';
$user = $get($text, ['database.default.username', 'DB_USERNAME', 'DB_USER']);
$pass = $get($text, ['database.default.password', 'DB_PASSWORD']);
$name = $get($text, ['database.default.database', 'DB_DATABASE', 'DB_NAME']);
$port = (int) ($get($text, ['database.default.port', 'DB_PORT']) ?: '3306');

if ($user === '' || $name === '') {
    fwrite(STDERR, "reset: could not read MySQL credentials from .env\n");
    exit(1);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($host, $user, $pass, $name, $port);
$mysqli->set_charset('utf8mb4');

fwrite(STDOUT, "reset: connected to {$name}@{$host}\n");

$mysqli->query('SET FOREIGN_KEY_CHECKS=0');
$tables = [];
$res = $mysqli->query('SHOW FULL TABLES WHERE Table_type = \'BASE TABLE\'');
while ($row = $res->fetch_row()) {
    $tables[] = $row[0];
}
foreach ($tables as $t) {
    $mysqli->query('DROP TABLE IF EXISTS `' . $mysqli->real_escape_string($t) . '`');
}
fwrite(STDOUT, 'reset: dropped ' . count($tables) . " tables\n");

$sql = file_get_contents($snapPath);
if ($sql === false || trim($sql) === '') {
    fwrite(STDERR, "reset: snapshot empty\n");
    exit(1);
}

if (! $mysqli->multi_query($sql)) {
    fwrite(STDERR, 'reset: import failed: ' . $mysqli->error . "\n");
    exit(1);
}
do {
    if ($result = $mysqli->store_result()) {
        $result->free();
    }
} while ($mysqli->more_results() && $mysqli->next_result());

if ($mysqli->errno) {
    fwrite(STDERR, 'reset: import error: ' . $mysqli->error . "\n");
    exit(1);
}

$mysqli->query('SET FOREIGN_KEY_CHECKS=1');
fwrite(STDOUT, "reset: imported snapshot (" . number_format(strlen($sql)) . " bytes)\n");
$mysqli->close();
