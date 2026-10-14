<?php
/**
 * Ensure CI4 database.default.* keys exist in .env when only legacy DB_* keys are set.
 * Safe to re-run; never overwrites existing database.default.* lines.
 */
$path = $argv[1] ?? (dirname(__DIR__) . '/.env');
if (! is_file($path)) {
    fwrite(STDERR, "env sync: missing {$path}\n");
    exit(0);
}

$text = file_get_contents($path);
if ($text === false) {
    fwrite(STDERR, "env sync: cannot read {$path}\n");
    exit(0);
}

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

$has = static function (string $text, string $key): bool {
    return (bool) preg_match('/(?:^|\n)[ \t]*' . preg_quote($key, '/') . '[ \t]*=/i', $text);
};

$host = $get($text, ['database.default.hostname', 'DB_HOST']);
$user = $get($text, ['database.default.username', 'DB_USERNAME', 'DB_USER']);
$pass = $get($text, ['database.default.password', 'DB_PASSWORD']);
$name = $get($text, ['database.default.database', 'DB_DATABASE', 'DB_NAME']);
$port = $get($text, ['database.default.port', 'DB_PORT']) ?: '3306';

$lines = [];
if ($user !== '' && ! $has($text, 'database.default.username')) {
    $lines[] = 'database.default.username = ' . $user;
}
if ($name !== '' && ! $has($text, 'database.default.database')) {
    $lines[] = 'database.default.database = ' . $name;
}
if ($host !== '' && ! $has($text, 'database.default.hostname')) {
    $lines[] = 'database.default.hostname = ' . $host;
}
if ($pass !== '' && ! $has($text, 'database.default.password')) {
    $lines[] = "database.default.password = '" . str_replace("'", "\\'", $pass) . "'";
}
if (! $has($text, 'database.default.port')) {
    $lines[] = 'database.default.port = ' . $port;
}
if (! $has($text, 'database.default.DBDriver')) {
    $lines[] = 'database.default.DBDriver = MySQLi';
}

if ($lines === []) {
    echo 'env sync: database.default.* already present (user=' . ($user !== '' ? 'set' : 'empty') . ")\n";
    exit(0);
}

if ($text !== '' && substr($text, -1) !== "\n") {
    $text .= "\n";
}
$text .= "\n# synced from DB_* for CI4\n" . implode("\n", $lines) . "\n";
file_put_contents($path, $text);
echo 'env sync: appended ' . count($lines) . " database.default.* keys\n";
