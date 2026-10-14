<?php
/**
 * Force staging/production app.baseURL + app.indexPage in .env (upsert).
 * Usage: php deploy/sync-env-app.php /path/to/.env https://staging.riphahpsh.edu.pk/dms/
 */
$path = $argv[1] ?? (dirname(__DIR__) . '/.env');
$base = rtrim((string) ($argv[2] ?? ''), '/') . '/';

if (! is_file($path)) {
    fwrite(STDERR, "env app sync: missing {$path}\n");
    exit(0);
}
if ($base === '/') {
    fwrite(STDERR, "env app sync: base URL required\n");
    exit(1);
}

$text = file_get_contents($path);
if ($text === false) {
    fwrite(STDERR, "env app sync: cannot read {$path}\n");
    exit(1);
}

$set = static function (string $text, string $key, string $value): string {
    $k = preg_quote($key, '/');
    $line = $key . " = '" . str_replace("'", "\\'", $value) . "'";
    if (preg_match('/(?:^|\n)[ \t]*' . $k . '[ \t]*=/i', $text)) {
        return preg_replace('/(?:^|\n)[ \t]*' . $k . '[ \t]*=[^\r\n]*/i', "\n" . $line, $text, 1) ?? $text;
    }
    if ($text !== '' && substr($text, -1) !== "\n") {
        $text .= "\n";
    }

    return $text . $line . "\n";
};

$text = $set($text, 'app.baseURL', $base);
$text = $set($text, 'app.indexPage', '');
file_put_contents($path, $text);
echo "env app sync: app.baseURL={$base} app.indexPage=''\n";
