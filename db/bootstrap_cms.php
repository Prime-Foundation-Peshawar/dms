<?php
/**
 * Ensure staging/production .env has CMS admin credentials and cms_users matches them.
 * Safe to re-run: updates password hash to match .env.
 */
declare(strict_types=1);

$root = dirname(__DIR__);
$envPath = $root . '/.env';

$email = 'admin@riphahpsh.edu.pk';
$password = 'DmsAcp!Staging2026';

if (!is_file($envPath)) {
    fwrite(STDERR, ".env missing at {$envPath}\n");
    exit(1);
}

$raw = (string) file_get_contents($envPath);
$changed = false;

if (!preg_match('/^\s*CMS_ADMIN_EMAIL\s*=/m', $raw)) {
    $raw = rtrim($raw) . "\n\nCMS_ADMIN_EMAIL = {$email}\n";
    $changed = true;
} else {
    $raw = preg_replace('/^\s*CMS_ADMIN_EMAIL\s*=\s*.*$/m', 'CMS_ADMIN_EMAIL = ' . $email, $raw, 1) ?? $raw;
    $changed = true;
}

if (!preg_match('/^\s*CMS_ADMIN_PASSWORD\s*=/m', $raw)) {
    $raw = rtrim($raw) . "\nCMS_ADMIN_PASSWORD = {$password}\n";
    $changed = true;
} else {
    $raw = preg_replace('/^\s*CMS_ADMIN_PASSWORD\s*=\s*.*$/m', 'CMS_ADMIN_PASSWORD = ' . $password, $raw, 1) ?? $raw;
    $changed = true;
}

if ($changed) {
    if (@file_put_contents($envPath, $raw) === false) {
        fwrite(STDERR, "Could not write .env (check permissions).\n");
        exit(1);
    }
    echo "Updated CMS_* keys in .env\n";
} else {
    echo "CMS_* keys already present\n";
}

require_once $root . '/legacy/includes/db.php';

try {
    $pdo = dms_db();
} catch (Throwable $e) {
    fwrite(STDERR, 'DB: ' . $e->getMessage() . "\n");
    exit(1);
}

// Ensure table exists (migration may not have run yet on first boot).
$hasTable = (int) $pdo->query(
    "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'cms_users'"
)->fetchColumn();
if ($hasTable < 1) {
    echo "cms_users table missing — run db/apply.php first.\n";
    exit(0);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$emailNorm = strtolower($email);

$existing = $pdo->prepare('SELECT id FROM cms_users WHERE email = ? LIMIT 1');
$existing->execute([$emailNorm]);
$id = $existing->fetchColumn();

if ($id) {
    $stmt = $pdo->prepare(
        'UPDATE cms_users SET password_hash = ?, name = ?, role = ?, is_active = 1, updated_at = NOW() WHERE id = ?'
    );
    $stmt->execute([$hash, 'DMS Admin', 'admin', (int) $id]);
    echo "Updated cms_users #{$id} password from .env\n";
} else {
    $stmt = $pdo->prepare(
        'INSERT INTO cms_users (name, email, password_hash, role, is_active, created_at, updated_at)
         VALUES (?, ?, ?, ?, 1, NOW(), NOW())'
    );
    $stmt->execute(['DMS Admin', $emailNorm, $hash, 'admin']);
    echo "Created cms_users admin from .env\n";
}

echo "ACP login: {$emailNorm} / (password in .env CMS_ADMIN_PASSWORD)\n";
