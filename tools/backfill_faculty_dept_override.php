<?php
/**
 * One-time: set faculty_profiles.dept_override=1 when profile department differs from HRMS.
 *
 * Usage: php tools/backfill_faculty_dept_override.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

require $root . '/vendor/autoload.php';
require $root . '/app/Helpers/faculty_helper.php';

$dbPath = $root . '/writable/dms.db';
$env = @file_get_contents($root . '/.env') ?: '';
if (preg_match("/database\\.default\\.database\\s*=\\s*['\"]?([^'\\\"\\r\\n]+)/", $env, $m)) {
    $p = trim($m[1], " '\"");
    if ($p !== '' && ! str_contains($p, 'mysql')) {
        $dbPath = str_starts_with($p, '/') || preg_match('/^[A-Za-z]:/', $p) ? $p : ($root . '/' . $p);
    }
}

$db = new SQLite3($dbPath);
$cols = [];
$info = $db->query('PRAGMA table_info(faculty_profiles)');
while ($row = $info->fetchArray(SQLITE3_ASSOC)) {
    $cols[$row['name']] = true;
}
if (empty($cols['dept_override'])) {
    fwrite(STDERR, "dept_override column missing — run: php spark migrate\n");
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
}
if (! $employees) {
    fwrite(STDERR, "No HRMS data available\n");
    exit(1);
}

$hrmsByEmp = [];
foreach ($employees as $emp) {
    if (! is_array($emp)) {
        continue;
    }
    $empId = trim((string) ($emp['empId'] ?? ''));
    if ($empId === '') {
        continue;
    }
    $hrmsByEmp[$empId] = faculty_canonical_dept((string) ($emp['depName'] ?? ''));
}

$updated = 0;
$res = $db->query('SELECT id, emp_id, name, department_name, dept_override FROM faculty_profiles WHERE emp_id IS NOT NULL AND emp_id != \'\'');
$stmt = $db->prepare('UPDATE faculty_profiles SET dept_override = 1 WHERE id = :id');

while ($p = $res->fetchArray(SQLITE3_ASSOC)) {
    if (! empty($p['dept_override'])) {
        continue;
    }
    $empId = trim((string) $p['emp_id']);
    $cvDept = faculty_canonical_dept((string) ($p['department_name'] ?? ''));
    $hrmsDept = $hrmsByEmp[$empId] ?? '';
    if ($empId === '' || $cvDept === '' || $hrmsDept === '') {
        continue;
    }
    if (strcasecmp($hrmsDept, $cvDept) === 0) {
        continue;
    }
    $stmt->bindValue(':id', (int) $p['id'], SQLITE3_INTEGER);
    $stmt->execute();
    $updated++;
    echo "override empId={$empId} {$p['name']}: HRMS={$hrmsDept} → profile={$cvDept}\n";
}

echo "Done. Updated {$updated} profile(s).\n";
