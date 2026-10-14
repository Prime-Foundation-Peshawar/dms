<?php
/**
 * Export local SQLite DB to MySQL-compatible SQL for staging reset.
 * Usage: php tools/export_sqlite_to_mysql.php [writable/dms.db] [deploy/local-mysql-snapshot.sql]
 */
$root = dirname(__DIR__);
$sqlite = $argv[1] ?? ($root . '/writable/dms.db');
$out    = $argv[2] ?? ($root . '/deploy/local-mysql-snapshot.sql');

if (! is_file($sqlite)) {
    fwrite(STDERR, "SQLite not found: {$sqlite}\n");
    exit(1);
}

$pdo = new PDO('sqlite:' . $sqlite);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$skip = ['sqlite_sequence', 'sqlite_stat1', 'sqlite_stat4'];
$tables = [];
foreach ($pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name") as $row) {
    $name = $row['name'];
    if (in_array($name, $skip, true) || str_starts_with($name, 'sqlite_')) {
        continue;
    }
    $tables[] = $name;
}

$fh = fopen($out, 'wb');
if ($fh === false) {
    fwrite(STDERR, "Cannot write {$out}\n");
    exit(1);
}

fwrite($fh, "-- Exported from {$sqlite} at " . gmdate('c') . "\n");
fwrite($fh, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\nSET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n");

foreach ($tables as $table) {
    $mysqlCreate = sqliteCreateToMysql($table, $pdo);
    fwrite($fh, "DROP TABLE IF EXISTS `{$table}`;\n");
    fwrite($fh, $mysqlCreate . ";\n\n");

    $cols = [];
    foreach ($pdo->query('PRAGMA table_info(' . quoteIdent($table) . ')') as $col) {
        $cols[] = $col['name'];
    }
    if ($cols === []) {
        continue;
    }
    $colList = '`' . implode('`,`', $cols) . '`';
    $stmt = $pdo->query('SELECT * FROM ' . quoteIdent($table));
    $batch = [];
    $n = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $vals = [];
        foreach ($cols as $c) {
            $vals[] = mysqlLiteral($row[$c] ?? null);
        }
        $batch[] = '(' . implode(',', $vals) . ')';
        $n++;
        if (count($batch) >= 100) {
            fwrite($fh, "INSERT INTO `{$table}` ({$colList}) VALUES\n" . implode(",\n", $batch) . ";\n");
            $batch = [];
        }
    }
    if ($batch) {
        fwrite($fh, "INSERT INTO `{$table}` ({$colList}) VALUES\n" . implode(",\n", $batch) . ";\n");
    }
    fwrite($fh, "-- {$table}: {$n} rows\n\n");
}

fwrite($fh, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($fh);
echo 'Wrote ' . $out . ' (' . number_format(filesize($out)) . ' bytes) tables=' . count($tables) . "\n";

function quoteIdent(string $name): string
{
    return '"' . str_replace('"', '""', $name) . '"';
}

function mysqlLiteral(mixed $v): string
{
    if ($v === null) {
        return 'NULL';
    }
    if (is_int($v) || is_float($v)) {
        return (string) $v;
    }
    $s = (string) $v;
    $s = str_replace(["\\", "\0", "\n", "\r", "'", '"', "\x1a"], ["\\\\", '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $s);

    return "'" . $s . "'";
}

function sqliteCreateToMysql(string $table, PDO $pdo): string
{
    $info = $pdo->query('PRAGMA table_info(' . quoteIdent($table) . ')')->fetchAll(PDO::FETCH_ASSOC);
    $pkCount = 0;
    foreach ($info as $col) {
        if ((int) $col['pk'] > 0) {
            $pkCount++;
        }
    }

    $cols = [];
    $pk = [];
    foreach ($info as $col) {
        $name = $col['name'];
        $type = strtoupper((string) $col['type']);
        $notnull = (int) $col['notnull'] === 1;
        $dflt = $col['dflt_value'];
        $isPk = (int) $col['pk'] > 0;

        if ($type === '' || $type === 'TEXT' || str_contains($type, 'CHAR') || str_contains($type, 'CLOB')) {
            $mysqlType = str_contains($type, 'VARCHAR') ? $type : 'TEXT';
            if ($mysqlType === 'TEXT' && in_array($name, ['slug', 'status', 'email', 'hostname'], true)) {
                $mysqlType = 'VARCHAR(191)';
            }
        } elseif (str_contains($type, 'INT')) {
            $mysqlType = $isPk ? 'BIGINT UNSIGNED' : 'BIGINT';
        } elseif (str_contains($type, 'REAL') || str_contains($type, 'FLOA') || str_contains($type, 'DOUB')) {
            $mysqlType = 'DOUBLE';
        } elseif (str_contains($type, 'BLOB')) {
            $mysqlType = 'LONGBLOB';
        } else {
            $mysqlType = 'TEXT';
        }

        if (str_ends_with($name, '_at') || in_array($name, ['created_at', 'updated_at', 'expires_at', 'published_at'], true)) {
            $mysqlType = 'DATETIME';
        }
        if (in_array($name, ['is_public', 'is_hod', 'hidden', 'use_cms'], true) || str_starts_with($name, 'is_')) {
            $mysqlType = 'TINYINT(1)';
        }

        $line = "`{$name}` {$mysqlType}";
        if ($isPk && $pkCount === 1 && str_contains($mysqlType, 'INT')) {
            $line .= ' NOT NULL AUTO_INCREMENT';
        } elseif ($notnull && ! $isPk) {
            $line .= ' NOT NULL';
        }
        if ($dflt !== null && $dflt !== '') {
            $d = trim((string) $dflt, " '\"");
            if (strcasecmp($d, 'NULL') === 0) {
                $line .= ' DEFAULT NULL';
            } elseif (is_numeric($d)) {
                $line .= ' DEFAULT ' . $d;
            } else {
                $line .= " DEFAULT '" . str_replace("'", "\\'", $d) . "'";
            }
        } elseif (! $notnull && ! $isPk) {
            $line .= ' NULL';
        }
        $cols[] = $line;
        if ($isPk) {
            $pk[(int) $col['pk']] = $name;
        }
    }
    ksort($pk);
    if ($pk) {
        $cols[] = 'PRIMARY KEY (`' . implode('`,`', $pk) . '`)';
    }

    return "CREATE TABLE `{$table}` (\n  " . implode(",\n  ", $cols) . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
}
