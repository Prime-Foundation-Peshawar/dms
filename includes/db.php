<?php
/**
 * PDO connection for DMS site.
 * Reads .env (or environment) using the same keys as db/apply.php.
 */

function dms_env_load(?string $root = null): void {
  static $loaded = false;
  if ($loaded) {
    return;
  }
  $loaded = true;
  $root = $root ?: dirname(__DIR__);
  $path = $root . '/.env';
  if (!is_file($path)) {
    return;
  }
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if ($lines === false) {
    return;
  }
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
      continue;
    }
    list($key, $val) = explode('=', $line, 2);
    $key = trim($key);
    $val = trim($val);
    if ($val !== '' && (($val[0] === '"' && substr($val, -1) === '"') || ($val[0] === "'" && substr($val, -1) === "'"))) {
      $val = substr($val, 1, -1);
    }
    if ($key === '') {
      continue;
    }
    if (getenv($key) === false) {
      putenv($key . '=' . $val);
      $_ENV[$key] = $val;
    }
  }
}

function dms_db_config(): array {
  dms_env_load();
  $host = getenv('DB_HOST') ?: (getenv('MYSQL_HOST') ?: 'localhost');
  $port = getenv('DB_PORT') ?: (getenv('MYSQL_PORT') ?: '3306');
  $name = getenv('DB_DATABASE') ?: (getenv('DB_NAME') ?: (getenv('MYSQL_DATABASE') ?: ''));
  $user = getenv('DB_USERNAME') ?: (getenv('DB_USER') ?: (getenv('MYSQL_USER') ?: ''));
  $pass = getenv('DB_PASSWORD') ?: (getenv('MYSQL_PASSWORD') ?: '');
  return [
    'host' => $host,
    'port' => $port,
    'database' => $name,
    'username' => $user,
    'password' => $pass,
  ];
}

/**
 * @throws PDOException|RuntimeException
 */
function dms_db(): PDO {
  static $pdo = null;
  if ($pdo instanceof PDO) {
    return $pdo;
  }
  $cfg = dms_db_config();
  if ($cfg['database'] === '' || $cfg['username'] === '') {
    throw new RuntimeException('Database is not configured (.env missing DB_* settings).');
  }
  $dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $cfg['host'],
    $cfg['port'],
    $cfg['database']
  );
  $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
  return $pdo;
}
