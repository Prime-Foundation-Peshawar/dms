<?php
/**
 * Seed departments + activities from legacy static array (idempotent).
 * Run after SQL migrations: php db/seed_departments.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
require_once $root . '/legacy/includes/db.php';
require_once $root . '/legacy/includes/departments-data.php';

if (!isset($academic_departments) || !is_array($academic_departments) || $academic_departments === []) {
    fwrite(STDERR, "No static departments to seed.\n");
    exit(1);
}

$pdo = dms_db();
$existing = (int) $pdo->query('SELECT COUNT(*) FROM departments')->fetchColumn();
if ($existing > 0) {
    echo "departments already seeded ({$existing} rows) — skipping.\n";
    exit(0);
}

$oricMap = [
    'anatomy' => 4,
    'physiology' => 5,
    'biochemistry' => 3,
    'pathology' => 8,
    'pharmacology' => 5010,
    'forensic-medicine' => 5011,
    'chs' => 5007,
    'dhpe' => 11,
    'medicine' => 1,
    'surgery' => 5001,
    'gynaecology' => 5003,
    'paediatrics' => 5002,
    'ent' => 5020,
    'ophthalmology' => 5030,
    'orthopaedics' => 5027,
    'radiology' => 5004,
    'psychiatry' => 9,
    'cardiology' => 5035,
];

$insDept = $pdo->prepare(
    'INSERT INTO departments
      (slug, name, icon, dept_group, hod_name, intro, faculty_fallback, oric_id, sort_order, updated_on, is_active, created_at, updated_at)
     VALUES
      (:slug, :name, :icon, :dept_group, :hod_name, :intro, :faculty_fallback, :oric_id, :sort_order, :updated_on, 1, NOW(), NOW())'
);
$insAct = $pdo->prepare(
    'INSERT INTO department_activities
      (department_id, title, activity_date, body, sort_order, created_at, updated_at)
     VALUES
      (:department_id, :title, :activity_date, :body, :sort_order, NOW(), NOW())'
);

$pdo->beginTransaction();
$sort = 0;
$deptCount = 0;
$actCount = 0;
try {
    foreach ($academic_departments as $slug => $dept) {
        $sort++;
        $updated = $dept['updated'] ?? (defined('DEPARTMENTS_DEFAULT_UPDATED') ? DEPARTMENTS_DEFAULT_UPDATED : null);
        $insDept->execute([
            ':slug' => (string) $slug,
            ':name' => (string) ($dept['name'] ?? $slug),
            ':icon' => (string) ($dept['icon'] ?? 'bi-building'),
            ':dept_group' => (string) ($dept['group'] ?? 'Other'),
            ':hod_name' => ($dept['hod'] ?? null) !== '' ? (string) ($dept['hod'] ?? null) : null,
            ':intro' => json_encode(array_values($dept['intro'] ?? []), JSON_UNESCAPED_UNICODE),
            ':faculty_fallback' => json_encode(array_values($dept['faculty'] ?? []), JSON_UNESCAPED_UNICODE),
            ':oric_id' => $oricMap[$slug] ?? null,
            ':sort_order' => $sort,
            ':updated_on' => $updated,
        ]);
        $deptId = (int) $pdo->lastInsertId();
        $deptCount++;
        $aSort = 0;
        foreach (($dept['activities'] ?? []) as $activity) {
            if (!is_array($activity)) {
                continue;
            }
            $aSort++;
            $insAct->execute([
                ':department_id' => $deptId,
                ':title' => (string) ($activity['title'] ?? 'Activity'),
                ':activity_date' => (string) ($activity['date'] ?? ''),
                ':body' => (string) ($activity['text'] ?? ''),
                ':sort_order' => $aSort,
            ]);
            $actCount++;
        }
    }
    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    fwrite(STDERR, 'Seed failed: ' . $e->getMessage() . "\n");
    exit(1);
}

echo "Seeded {$deptCount} departments and {$actCount} activities.\n";
