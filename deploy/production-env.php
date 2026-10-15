<?php
/**
 * Production .env helpers used by the production deploy.
 *
 *   php deploy/production-env.php ensure-key /path/to/.env
 *   php deploy/production-env.php check-db /path/to/.env
 *   php deploy/production-env.php write-db /path/to/.env db user pass
 *
 * check-db exits 0 when the database has tables, 3 when it is empty,
 * and 2 when the login is missing or rejected.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$cmd = $argv[1] ?? '';
$path = $argv[2] ?? '';

if ($cmd === '' || $path === '') {
    fwrite(STDERR, "usage: production-env.php ensure-key|check-db|write-db .env [db user pass]\n");
    exit(1);
}

$text = is_file($path) ? file_get_contents($path) : false;
if ($text === false) {
    fwrite(STDERR, "production-env: cannot read {$path}\n");
    exit(1);
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

$set = static function (string $text, string $key, string $value): string {
    $line = $key . " = '" . str_replace("'", "\\'", $value) . "'";
    $k = preg_quote($key, '/');
    if (preg_match('/(?:^|\n)[ \t]*' . $k . '[ \t]*=/i', $text)) {
        return preg_replace('/(?:^|\n)[ \t]*' . $k . '[ \t]*=[^\r\n]*/i', "\n" . $line, $text, 1) ?? $text;
    }

    return rtrim($text, "\r\n") . "\n" . $line . "\n";
};

if ($cmd === 'ensure-key') {
    if (preg_match('/(?:^|\n)\s*encryption\.key\s*=\s*\S+/', $text)) {
        echo "encryption key already set\n";
        exit(0);
    }
    $line = 'encryption.key = hex2bin:' . bin2hex(random_bytes(32));
    if (preg_match('/(?:^|\n)\s*encryption\.key\s*=[^\n]*/', $text)) {
        $text = preg_replace('/(?:^|\n)\s*encryption\.key\s*=[^\n]*/', "\n" . $line, $text, 1) ?? $text;
    } else {
        $text = rtrim($text, "\r\n") . "\n" . $line . "\n";
    }
    file_put_contents($path, $text);
    echo "encryption key generated\n";
    exit(0);
}

if ($cmd === 'write-db') {
    $name = $argv[3] ?? '';
    $user = $argv[4] ?? '';
    $pass = $argv[5] ?? '';
    if ($name === '' || $user === '' || $pass === '') {
        fwrite(STDERR, "production-env: write-db needs database, user, and password\n");
        exit(1);
    }
    foreach (['database.default.database', 'DB_DATABASE'] as $key) {
        $text = $set($text, $key, $name);
    }
    foreach (['database.default.username', 'DB_USERNAME'] as $key) {
        $text = $set($text, $key, $user);
    }
    foreach (['database.default.password', 'DB_PASSWORD'] as $key) {
        $text = $set($text, $key, $pass);
    }
    $text = $set($text, 'database.default.hostname', 'localhost');
    $text = $set($text, 'DB_HOST', 'localhost');
    file_put_contents($path, $text);
    echo "database credentials written for {$name}\n";
    exit(0);
}

if ($cmd === 'check-db') {
    $host = $get($text, ['database.default.hostname', 'DB_HOST']) ?: 'localhost';
    $user = $get($text, ['database.default.username', 'DB_USERNAME', 'DB_USER']);
    $pass = $get($text, ['database.default.password', 'DB_PASSWORD']);
    $name = $get($text, ['database.default.database', 'DB_DATABASE', 'DB_NAME']);
    $port = (int) ($get($text, ['database.default.port', 'DB_PORT']) ?: '3306');
    if ($user === '' || $name === '' || $pass === '' || $pass === 'change_me') {
        fwrite(STDERR, "production-env: database login is not set\n");
        exit(2);
    }
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = @new mysqli($host, $user, $pass, $name, $port);
    if ($mysqli->connect_error) {
        fwrite(STDERR, "production-env: database login failed\n");
        exit(2);
    }
    $res = $mysqli->query('SHOW TABLES');
    $n = $res ? $res->num_rows : 0;
    echo "database {$name} has {$n} tables\n";
    exit($n > 0 ? 0 : 3);
}

fwrite(STDERR, "production-env: unknown command {$cmd}\n");
exit(1);
