<?php
/**
 * Set faculty_profiles.emp_id from PMC + PDC HRMS where slug match is unique.
 * Runtime HRMS↔profile matching uses empId only.
 *
 * Usage: php tools/backfill_faculty_emp_ids.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

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

function hrms_fetch(string $url): array
{
    $raw = false;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 40,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0'],
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
    } else {
        $raw = @file_get_contents($url);
    }
    $decoded = json_decode((string) $raw, true);
    if (is_array($decoded) && array_is_list($decoded)) {
        return $decoded;
    }
    if (is_array($decoded)) {
        return $decoded['employees'] ?? $decoded['data'] ?? [];
    }

    return [];
}

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

$urls = [
    'PMC' => 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php',
    'PDC' => 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfoPDC.php',
];
$employees = [];
$seen = [];
foreach ($urls as $source => $url) {
    foreach (hrms_fetch($url) as $emp) {
        if (! is_array($emp)) {
            continue;
        }
        $empId = trim((string) ($emp['empId'] ?? ''));
        $name = trim((string) ($emp['empName'] ?? ''));
        if ($empId === '' || $name === '') {
            continue;
        }
        $key = $empId . '|' . faculty_slug_local($name);
        if (isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;
        $emp['_source'] = $source;
        $employees[] = $emp;
    }
}
if (! $employees) {
    fwrite(STDERR, "No HRMS data from PMC/PDC APIs\n");
    exit(1);
}
echo 'HRMS rows (PMC+PDC unique): ' . count($employees) . "\n";

$bySlug = [];
$res = $db->query('SELECT id, slug, name, emp_id FROM faculty_profiles');
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $bySlug[$row['slug']][] = $row;
    $ar = $db->query('SELECT alias FROM faculty_profile_aliases WHERE profile_id=' . (int) $row['id']);
    while ($a = $ar->fetchArray(SQLITE3_ASSOC)) {
        $bySlug[$a['alias']][] = $row;
    }
}

$linked = 0;
$skipped = 0;
$ambiguous = 0;
$stmt = $db->prepare("UPDATE faculty_profiles SET emp_id = :e WHERE id = :id AND (emp_id IS NULL OR emp_id = '')");

foreach ($employees as $emp) {
    $empId = trim((string) ($emp['empId'] ?? ''));
    $name = trim((string) ($emp['empName'] ?? ''));
    $source = (string) ($emp['_source'] ?? '');
    $chk = $db->prepare('SELECT id FROM faculty_profiles WHERE emp_id = :e');
    $chk->bindValue(':e', $empId, SQLITE3_TEXT);
    if ($chk->execute()->fetchArray()) {
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
            echo "ambiguous empId={$empId} name={$name} source={$source}\n";
        }
        continue;
    }
    if (trim((string) ($cands[0]['emp_id'] ?? '')) !== '') {
        $skipped++;
        continue;
    }
    $stmt->bindValue(':e', $empId, SQLITE3_TEXT);
    $stmt->bindValue(':id', (int) $cands[0]['id'], SQLITE3_INTEGER);
    $stmt->execute();
    $linked++;
    echo "linked empId={$empId} ({$source}) -> #{$cands[0]['id']} {$cands[0]['name']}\n";
}

echo "Done. linked={$linked} already={$skipped} ambiguous={$ambiguous}\n";
