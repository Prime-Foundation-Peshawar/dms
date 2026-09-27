<?php
/**
 * Run new files in db/changes/ against this environment's database.
 * Already-applied filenames are skipped via table `_sql_changes`.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$root = dirname(__DIR__);
$changesDir = $root . '/db/changes';
$namePattern = '/^[0-9]{8}_[0-9]{4}_[a-z0-9_]+\.sql$/';

function fail($msg)
{
    fwrite(STDERR, "SQL changes: $msg\n");
    exit(1);
}

function info($msg)
{
    fwrite(STDOUT, "SQL changes: $msg\n");
}

if (!is_dir($changesDir)) {
    info('no db/changes folder; skip');
    exit(0);
}

$files = glob($changesDir . '/*.sql') ?: array();
sort($files, SORT_STRING);
if (!$files) {
    info('no .sql files; skip');
    exit(0);
}

foreach ($files as $path) {
    $base = basename($path);
    if (!preg_match($namePattern, $base)) {
        fail("invalid filename '$base'. Use YYYYMMDD_HHMM_short_description.sql");
    }
}

function stripSqlComments($sql)
{
    $sql = preg_replace('/\/\*.*?\*\//s', ' ', $sql);
    $sql = preg_replace('/^[ \t]*--[^\n]*$/m', ' ', $sql);
    $sql = preg_replace('/^[ \t]*#[^\n]*$/m', ' ', $sql);
    return $sql;
}

function parseAtDb($sql)
{
    if (preg_match('/^\s*--\s*@db\s+([a-zA-Z0-9_]+)/', $sql, $m)) {
        return strtolower($m[1]);
    }
    return 'default';
}

function envValue($text, $keys)
{
    foreach ((array) $keys as $key) {
        $k = preg_quote($key, '/');
        if (preg_match('/(?:^|\n)[ \t]*' . $k . '[ \t]*=[ \t]*("([^"]*)"|\'([^\']*)\'|([^\r\n#]*))/i', $text, $m)) {
            if (isset($m[2]) && isset($m[1][0]) && $m[1][0] === '"') {
                return $m[2];
            }
            if (isset($m[3]) && isset($m[1][0]) && $m[1][0] === "'") {
                return $m[3];
            }
            return trim($m[4]);
        }
    }
    return '';
}

function phpAssign($text, $patterns)
{
    foreach ((array) $patterns as $pattern) {
        if (preg_match($pattern, $text, $m)) {
            return $m[1];
        }
    }
    return '';
}

function parseEnvFile($text)
{
    $host = envValue($text, array('database.default.hostname', 'database.default.host', 'DB_HOST', 'MYSQL_HOST'));
    $user = envValue($text, array('database.default.username', 'DB_USERNAME', 'DB_USER', 'MYSQL_USER'));
    $pass = envValue($text, array('database.default.password', 'DB_PASSWORD', 'MYSQL_PASSWORD'));
    $name = envValue($text, array('database.default.database', 'DB_DATABASE', 'DB_NAME', 'MYSQL_DATABASE'));
    $port = envValue($text, array('database.default.port', 'DB_PORT', 'MYSQL_PORT'));
    return array($host, $user, $pass, $name, $port);
}

function parsePhpConfig($text)
{
    $host = phpAssign($text, array(
        '/\$hostname_primeDb\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$hostname_conn\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$hostname_\w+\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$db\s*\[\s*[\'"]default[\'"]\s*\]\s*\[\s*[\'"]hostname[\'"]\s*\]\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/[\'"]hostname[\'"]\s*=>\s*[\'"]([^\'"]*)[\'"]/',
        '/mysqli_connect\s*\(\s*[\'"]([^\'"]*)[\'"]/',
        '/new\s+mysqli\s*\(\s*[\'"]([^\'"]*)[\'"]/',
        '/mysql:host=([^;\'"\s]+)/',
        '/\$host\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$servername\s*=\s*[\'"]([^\'"]*)[\'"]/',
    ));
    $user = phpAssign($text, array(
        '/\$username_primeDb\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$username_conn\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$username_\w+\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$db\s*\[\s*[\'"]default[\'"]\s*\]\s*\[\s*[\'"]username[\'"]\s*\]\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/[\'"]username[\'"]\s*=>\s*[\'"]([^\'"]*)[\'"]/',
        '/mysqli_connect\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/new\s+mysqli\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/new\s+PDO\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/\$username\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$db_user\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$user\s*=\s*[\'"]([^\'"]*)[\'"]/',
    ));
    $pass = phpAssign($text, array(
        '/\$password_primeDb\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$password_conn\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$password_\w+\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$db\s*\[\s*[\'"]default[\'"]\s*\]\s*\[\s*[\'"]password[\'"]\s*\]\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/[\'"]password[\'"]\s*=>\s*[\'"]([^\'"]*)[\'"]/',
        '/mysqli_connect\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/new\s+mysqli\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/new\s+PDO\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/\$password\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$db_pass\s*=\s*[\'"]([^\'"]*)[\'"]/',
    ));
    $name = phpAssign($text, array(
        '/\$database_primeDb\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$database_conn\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$database_\w+\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$db\s*\[\s*[\'"]default[\'"]\s*\]\s*\[\s*[\'"]database[\'"]\s*\]\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/[\'"]database[\'"]\s*=>\s*[\'"]([^\'"]*)[\'"]/',
        '/mysqli_connect\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/new\s+mysqli\s*\(\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"][^\'"]*[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]/',
        '/dbname=([^;\'"\s]+)/',
        '/\$database\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$dbname\s*=\s*[\'"]([^\'"]*)[\'"]/',
        '/\$db_name\s*=\s*[\'"]([^\'"]*)[\'"]/',
    ));
    $port = phpAssign($text, array(
        '/\$db\s*\[\s*[\'"]default[\'"]\s*\]\s*\[\s*[\'"]port[\'"]\s*\]\s*=\s*[\'"]?([0-9]+)[\'"]?/',
        '/[\'"]port[\'"]\s*=>\s*[\'"]?([0-9]+)[\'"]?/',
        '/port=([0-9]+)/',
    ));
    return array($host, $user, $pass, $name, $port);
}

function readCreds($root, $target)
{
    $map = array(
        'default' => array(
            '.env',
            'application/config/database.php',
            'Connections/primeDb.php',
            'Connections/conn.php',
            'includes/database.php',
        ),
        'tms' => array('tms/application/config/database.php'),
        'backend' => array('backend/.env'),
    );
    if (!isset($map[$target])) {
        fail("unknown @db target '$target'");
    }
    foreach ($map[$target] as $rel) {
        $path = $root . '/' . $rel;
        if (!is_file($path)) {
            continue;
        }
        $text = file_get_contents($path);
        if ($text === false) {
            fail("could not read $rel");
        }
        if (substr($rel, -4) === '.env') {
            $creds = parseEnvFile($text);
        } else {
            $creds = parsePhpConfig($text);
        }
        if ($creds[0] !== '' && $creds[1] !== '' && $creds[3] !== '') {
            if ($creds[4] === '') {
                $creds[4] = '3306';
            }
            return $creds;
        }
    }
    if ($target === 'default') {
        fail('could not read database login from .env, database.php, primeDb.php, conn.php, or includes/database.php');
    }
    fail("could not read database login for @db $target");
}

$groups = array();
foreach ($files as $path) {
    $sql = file_get_contents($path);
    if ($sql === false) {
        fail('could not read ' . basename($path));
    }
    if (trim($sql) === '') {
        fail(basename($path) . ' is empty');
    }
    $stripped = stripSqlComments($sql);
    if (preg_match('/\bDROP\s+(DATABASE|SCHEMA)\b/i', $stripped)) {
        fail(basename($path) . ' is not allowed: DROP DATABASE / DROP SCHEMA');
    }
    $target = parseAtDb($sql);
    $groups[$target][] = array($path, $sql);
}

foreach ($groups as $target => $items) {
    list($host, $user, $pass, $name, $port) = readCreds($root, $target);
    $mysqli = @new mysqli($host, $user, $pass, $name, (int) $port);
    if ($mysqli->connect_error) {
        fail("could not connect to database for target '$target'");
    }
    $mysqli->set_charset('utf8mb4');
    $lock = $mysqli->query("SELECT GET_LOCK('sql_changes_apply', 60)");
    if (!$lock || !($row = $lock->fetch_row()) || (string) $row[0] !== '1') {
        fail('could not get apply lock');
    }
    $ok = $mysqli->query(
        'CREATE TABLE IF NOT EXISTS `_sql_changes` (
            `filename` VARCHAR(255) NOT NULL,
            `checksum` CHAR(64) NOT NULL,
            `applied_at` DATETIME NOT NULL,
            PRIMARY KEY (`filename`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );
    if ($ok === false) {
        fail('could not create _sql_changes: ' . $mysqli->error);
    }

    $ran = 0;
    foreach ($items as $item) {
        list($path, $sql) = $item;
        $base = basename($path);
        $checksum = hash('sha256', str_replace(array("\r\n", "\r"), "\n", $sql));
        $stmt = $mysqli->prepare('SELECT checksum FROM `_sql_changes` WHERE filename = ?');
        if (!$stmt) {
            fail($mysqli->error);
        }
        $stmt->bind_param('s', $base);
        $stmt->execute();
        $stmt->bind_result($existing);
        $found = $stmt->fetch();
        $stmt->close();
        if ($found) {
            if (!hash_equals($existing, $checksum)) {
                fail("$base was already applied but the file changed. Add a new SQL file instead of editing this one.");
            }
            info("skip $base (already applied)");
            continue;
        }
        info("apply $base");
        if (!$mysqli->multi_query($sql)) {
            fail("$base failed: " . $mysqli->error);
        }
        do {
            if ($res = $mysqli->store_result()) {
                $res->free();
            }
            if ($mysqli->errno) {
                fail("$base failed: " . $mysqli->error);
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
        if ($mysqli->errno) {
            fail("$base failed: " . $mysqli->error);
        }
        $ins = $mysqli->prepare('INSERT INTO `_sql_changes` (filename, checksum, applied_at) VALUES (?, ?, NOW())');
        if (!$ins) {
            fail($mysqli->error);
        }
        $ins->bind_param('ss', $base, $checksum);
        if (!$ins->execute()) {
            fail('could not record ' . $base . ': ' . $ins->error);
        }
        $ins->close();
        $ran++;
    }
    $mysqli->query("SELECT RELEASE_LOCK('sql_changes_apply')");
    $mysqli->close();
    info("done target=$target applied=$ran");
}

info('finished');
exit(0);
