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
$res = $mysqli->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
while ($row = $res->fetch_row()) {
    $tables[] = $row[0];
}
foreach ($tables as $t) {
    $mysqli->query('DROP TABLE IF EXISTS `' . str_replace('`', '``', $t) . '`');
}
fwrite(STDOUT, 'reset: dropped ' . count($tables) . " tables\n");

$sql = file_get_contents($snapPath);
if ($sql === false || trim($sql) === '') {
    fwrite(STDERR, "reset: snapshot empty\n");
    exit(1);
}

// Prefer mysql CLI when available (handles large dumps / HTML bodies cleanly).
$mysqlBin = null;
foreach (['mysql', '/usr/bin/mysql', '/opt/cpanel/mysql/bin/mysql'] as $cand) {
    if ($cand === 'mysql') {
        $which = trim((string) shell_exec('command -v mysql 2>/dev/null'));
        if ($which !== '') {
            $mysqlBin = $which;
            break;
        }
    } elseif (is_executable($cand)) {
        $mysqlBin = $cand;
        break;
    }
}

if ($mysqlBin !== null) {
    $cmd = escapeshellarg($mysqlBin)
        . ' --protocol=TCP'
        . ' -h ' . escapeshellarg($host)
        . ' -P ' . escapeshellarg((string) $port)
        . ' -u ' . escapeshellarg($user)
        . ' ' . escapeshellarg($name);
    $descriptors = [
        0 => ['file', $snapPath, 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    $env = ['MYSQL_PWD' => $pass];
    $proc = proc_open($cmd, $descriptors, $pipes, null, $env + $_ENV);
    if (! is_resource($proc)) {
        fwrite(STDERR, "reset: failed to start mysql client\n");
        exit(1);
    }
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($proc);
    if ($code !== 0) {
        fwrite(STDERR, "reset: mysql client failed ({$code}): {$stderr}\n");
        exit(1);
    }
    fwrite(STDOUT, "reset: imported via mysql client\n");
} else {
    foreach (splitSqlStatements($sql) as $i => $chunk) {
        try {
            $mysqli->query($chunk);
        } catch (Throwable $e) {
            fwrite(STDERR, 'reset: statement #' . ($i + 1) . ' failed: ' . $e->getMessage() . "\n");
            fwrite(STDERR, 'reset: near: ' . substr($chunk, 0, 240) . "\n");
            exit(1);
        }
    }
    fwrite(STDOUT, 'reset: imported via PHP (' . count(splitSqlStatements($sql)) . " statements)\n");
}

$mysqli->query('SET FOREIGN_KEY_CHECKS=1');
fwrite(STDOUT, "reset: done\n");
$mysqli->close();

/**
 * Split SQL on semicolons outside of single-quoted strings.
 *
 * @return list<string>
 */
function splitSqlStatements(string $sql): array
{
    $stmts = [];
    $buf = '';
    $inQuote = false;
    $escape = false;
    $len = strlen($sql);
    for ($i = 0; $i < $len; $i++) {
        $ch = $sql[$i];
        if ($inQuote) {
            $buf .= $ch;
            if ($escape) {
                $escape = false;
                continue;
            }
            if ($ch === '\\') {
                $escape = true;
                continue;
            }
            if ($ch === "'") {
                // SQL doubled quote ''
                if ($i + 1 < $len && $sql[$i + 1] === "'") {
                    $buf .= "'";
                    $i++;
                    continue;
                }
                $inQuote = false;
            }
            continue;
        }
        if ($ch === '-' && $i + 1 < $len && $sql[$i + 1] === '-' && ($buf === '' || preg_match('/\s$/', $buf))) {
            // line comment
            while ($i < $len && $sql[$i] !== "\n") {
                $i++;
            }
            continue;
        }
        if ($ch === "'") {
            $inQuote = true;
            $buf .= $ch;
            continue;
        }
        if ($ch === ';') {
            $t = trim($buf);
            if ($t !== '') {
                $stmts[] = $t;
            }
            $buf = '';
            continue;
        }
        $buf .= $ch;
    }
    $t = trim($buf);
    if ($t !== '') {
        $stmts[] = $t;
    }

    return $stmts;
}
