<?php
/**
 * One-time: set faculty_profiles.emp_id from HRMS where slug match is unique.
 * Runtime HRMS↔profile matching uses empId only.
 *
 * Usage: php tools/backfill_faculty_emp_ids.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

// Minimal CI4 boot via spark's preload
define('FCPATH', $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require $root . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
// Boot::bootSpark isn't right — use CodeIgniter directly through spark db query instead.

// Fallback: direct SQLite + cached/live HRMS JSON without full framework.
require $root . '/vendor/autoload.php';

function faculty_slug_local(string $name): string
{
    $n = trim($name);
    $titles = '/^(associate professor|assistant professor|senior registrar|senior lecturer|assoc\.?\s*prof\.?|asst\.?\s*prof\.?|sr\.?\s*registrar|sr\.?\s*lecturer|professor|prof\.?|dr\.?)\s+/i';
    while (preg_match($titles, $n)) {
        $n = preg_replace($titles, '', $n, 1) ?? $n;
    }
    $n = strtolower($n);
    $n = preg_replace('/[^a-z0-9]+/', '-', $n) ?? $n;
    $n = trim($n, '-');
    $n = preg_replace('/^(mohammad|muhammed)-/', 'muhammad-', $n) ?? $n;

    return $n;
}

// Load .env-ish DB path
$dbPath = $root . '/writable/dms.db';
$env = @file_get_contents($root . '/.env') ?: '';
if (preg_match("/database\\.default\\.database\\s*=\\s*['\"]?([^'\\\"\\r\\n]+)/", $env, $m)) {
    $p = trim($m[1], " '\"");
    if ($p !== '' && ! str_contains($p, 'mysql')) {
        $dbPath = str_starts_with($p, '/') || preg_match('/^[A-Za-z]:/', $p) ? $p : ($root . '/' . $p);
    }
}

$db = new SQLite3($dbPath);
$db->exec('PRAGMA foreign_keys = ON');

// Ensure column exists
$cols = [];
$info = $db->query('PRAGMA table_info(faculty_profiles)');
while ($row = $info->fetchArray(SQLITE3_ASSOC)) {
    $cols[$row['name']] = true;
}
if (empty($cols['emp_id'])) {
    fwrite(STDERR, "emp_id column missing — run: php spark migrate\n");
    exit(1);
}

$cache = $root . '/writable/cache/dms_hrms_faculty_cache.json';
$employees = [];
if (is_file($cache)) {
    $employees = json_decode((string) file_get_contents($cache), true) ?: [];
}
if (! $employees) {
    $url = 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php';
    if (preg_match("/hrms\\.apiUrl\\s*=\\s*['\"]?([^'\\\"\\r\\n]+)/", $env, $um)) {
        $url = trim($um[1], " '\"");
    }
    $raw = @file_get_contents($url);
    $employees = json_decode((string) $raw, true) ?: [];
    if ($employees) {
        @file_put_contents($cache, json_encode($employees));
    }
}
if (! $employees) {
    fwrite(STDERR, "No HRMS data available\n");
    exit(1);
}

$bySlug = [];
$res = $db->query('SELECT id, slug, name, emp_id FROM faculty_profiles');
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $bySlug[$row['slug']][] = $row;
    $ar = $db->query("SELECT alias FROM faculty_profile_aliases WHERE profile_id=" . (int) $row['id']);
    while ($a = $ar->fetchArray(SQLITE3_ASSOC)) {
        $bySlug[$a['alias']][] = $row;
    }
}

$linked = 0;
$skipped = 0;
$ambiguous = 0;
$stmt = $db->prepare('UPDATE faculty_profiles SET emp_id = :e WHERE id = :id AND (emp_id IS NULL OR emp_id = \'\')');

foreach ($employees as $emp) {
    if (! is_array($emp)) {
        continue;
    }
    $empId = trim((string) ($emp['empId'] ?? ''));
    $name  = trim((string) ($emp['empName'] ?? ''));
    if ($empId === '' || $name === '') {
        continue;
    }
    $exists = $db->querySingle("SELECT id FROM faculty_profiles WHERE emp_id=" . $db->escapeString($empId));
    // escapeString doesn't add quotes — use prepare
    $chk = $db->prepare('SELECT id FROM faculty_profiles WHERE emp_id = :e');
    $chk->bindValue(':e', $empId, SQLITE3_TEXT);
    $already = $chk->execute()->fetchArray();
    if ($already) {
        $skipped++;
        continue;
    }
    $slug = faculty_slug_local($name);
    $cands = $bySlug[$slug] ?? [];
    $uniq = [];
    foreach ($cands as $c) {
        $uniq[(int) $c['id']] = $c;
    }
    $cands = array_values($uniq);
    if (count($cands) !== 1) {
        if (count($cands) > 1) {
            $ambiguous++;
            echo "ambiguous empId={$empId} name={$name}\n";
        }
        continue;
    }
    $stmt->bindValue(':e', $empId, SQLITE3_TEXT);
    $stmt->bindValue(':id', (int) $cands[0]['id'], SQLITE3_INTEGER);
    $stmt->execute();
    $linked++;
    echo "linked empId={$empId} -> #{$cands[0]['id']} {$cands[0]['name']}\n";
}

echo "Done. linked={$linked} already={$skipped} ambiguous={$ambiguous}\n";
