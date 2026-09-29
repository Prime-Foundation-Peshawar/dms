<?php
/**
 * Expand RCP report seeds: expected roster, missing-unit rows, reception log, integrity, suggestions.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$root = dirname(__DIR__);
require_once $root . '/legacy/includes/db.php';
require_once $root . '/app/Libraries/RcpWebsiteProtocol.php';

use App\Libraries\RcpWebsiteProtocol;

$pdo = dms_db();
$now = date('Y-m-d H:i:s');
$month = date('Y-m');

// Expected units roster
$chk = $pdo->prepare('SELECT id FROM website_expected_units WHERE unit_name = ? LIMIT 1');
$ins = $pdo->prepare(
    'INSERT INTO website_expected_units (unit_name, unit_type, institution, content_examples, is_active, sort_order, created_at, updated_at)
     VALUES (?,?,?,?,1,?,?,?)'
);
$added = 0;
foreach (RcpWebsiteProtocol::expectedRoster() as $u) {
    $chk->execute([$u['unit_name']]);
    if ($chk->fetch()) {
        continue;
    }
    $ins->execute([
        $u['unit_name'], $u['unit_type'], $u['institution'], $u['content_examples'],
        $u['sort_order'], $now, $now,
    ]);
    $added++;
}
echo "website_expected_units: +{$added}\n";

// Ensure contribution rows exist for ALL expected units this month (0 = missing)
$cChk = $pdo->prepare('SELECT id FROM website_contributions WHERE month_key = ? AND unit_name = ? LIMIT 1');
$cIns = $pdo->prepare(
    'INSERT INTO website_contributions
    (month_key, unit_name, unit_type, submissions, approved, on_time, quality_score, rank_band, notes, created_at, updated_at)
    VALUES (?,?,?,?,?,?,?,?,?,?,?)'
);

// Prefer richer seed values when inserting fresh month
$bootstrap = [
    'Student Affairs' => [6, 5, 5, 9.2, 'top', 'Admissions dates & deadlines'],
    'ORIC' => [5, 5, 4, 9.0, 'top', 'Research publications & awards'],
    'UMR' => [4, 4, 4, 8.8, 'top', 'Undergraduate research activities'],
    'Literary Society' => [3, 3, 3, 8.5, 'top', 'Society events'],
    'Quality Enhancement Cell' => [3, 2, 2, 7.5, 'average', 'Workshops & accreditations'],
    'QEC' => [3, 2, 2, 7.5, 'average', 'Alias of QEC'],
    'HR' => [2, 2, 2, 7.2, 'average', 'Employment opportunities'],
    'Sports Society' => [2, 2, 1, 7.0, 'average', 'Sports activities'],
    'Social Welfare Society' => [2, 1, 1, 6.5, 'average', 'Community campaigns'],
    'Finance' => [1, 1, 1, 6.0, 'low', 'Only 1 approved — below KPI of 2'],
    'Kuwait Teaching Hospital' => [1, 1, 0, 5.5, 'low', 'Clinical news pending / late'],
    'Mercy Teaching Hospital' => [1, 0, 0, 4.0, 'low', 'Awaiting approvals'],
    'Prime Teaching Hospital' => [0, 0, 0, 0, 'missing', 'No submissions this month'],
    'DHPE&R' => [0, 0, 0, 0, 'missing', 'No input to Website Manager'],
    'Quran and Sunnah Society' => [0, 0, 0, 0, 'missing', 'No society activity submitted'],
    'Debate Society' => [0, 0, 0, 0, 'missing', 'No society activity submitted'],
    'Excursions & Community Trips' => [0, 0, 0, 0, 'missing', 'No trip/competition report within 1-week SLA'],
    'Peshawar Medical College' => [2, 2, 2, 7.8, 'average', 'Institutional notifications'],
    'Peshawar Dental College' => [0, 0, 0, 0, 'missing', 'No institutional updates this month'],
];

$cAdded = 0;
foreach (RcpWebsiteProtocol::expectedRoster() as $u) {
    $name = $u['unit_name'];
    $cChk->execute([$month, $name]);
    if ($cChk->fetch()) {
        continue;
    }
    $b = $bootstrap[$name] ?? [0, 0, 0, 0, 'missing', 'No submissions this month — missing department/unit'];
    $band = $b[4];
    if ($band === 'missing' || ((int) $b[0] === 0 && (int) $b[1] === 0)) {
        $band = 'missing';
    }
    $cIns->execute([$month, $name, $u['unit_type'], $b[0], $b[1], $b[2], $b[3], $band, $b[5], $now, $now]);
    $cAdded++;
}
echo "website_contributions fill: +{$cAdded} for {$month}\n";

// Seed prior 2 months as low/missing for escalation demo on a few units
$priorUnits = ['Prime Teaching Hospital', 'Debate Society', 'DHPE&R', 'Peshawar Dental College'];
for ($i = 1; $i <= 2; $i++) {
    $mk = date('Y-m', strtotime("-{$i} month"));
    foreach ($priorUnits as $name) {
        $cChk->execute([$mk, $name]);
        if ($cChk->fetch()) {
            continue;
        }
        $type = 'unit';
        foreach (RcpWebsiteProtocol::expectedRoster() as $u) {
            if ($u['unit_name'] === $name) {
                $type = $u['unit_type'];
                break;
            }
        }
        $cIns->execute([$mk, $name, $type, 0, 0, 0, 0, 'missing', 'No submissions — consecutive KPI miss', $now, $now]);
    }
}
echo "website_contributions: prior months seeded for escalation demo\n";

// Activity reception log
$aCount = (int) $pdo->query("SELECT COUNT(*) FROM website_activity_log WHERE month_key = " . $pdo->quote($month))->fetchColumn();
if ($aCount === 0) {
    $acts = [
        ['Student Affairs', 'MBBS admissions calendar update', 4, 'published', 1],
        ['ORIC', 'Faculty publication highlight — Sep batch', 8, 'published', 1],
        ['UMR', 'Call for undergraduate research abstracts', 6, 'published', 0],
        ['Literary Society', 'Seerat week literary session photos', 30, 'published', 1],
        ['HR', 'Lecturer vacancies notice', 10, 'published', 0],
        ['Finance', 'Fee structure clarification (single update)', 20, 'published', 0],
    ];
    $aIns = $pdo->prepare(
        'INSERT INTO website_activity_log
        (month_key, unit_name, activity_title, received_at, approved_at, published_at, turnaround_hours, status, shared_social, notes, created_at, updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)'
    );
    foreach ($acts as $i => $a) {
        $recv = date('Y-m-d H:i:s', strtotime('-' . (12 - $i) . ' days'));
        $appr = date('Y-m-d H:i:s', strtotime($recv . ' +1 day'));
        $pub = date('Y-m-d H:i:s', strtotime($recv . ' +' . max(1, (int) ($a[2] / 8)) . ' day'));
        $aIns->execute([$month, $a[0], $a[1], $recv, $appr, $pub, $a[2], $a[3], $a[4], null, $now, $now]);
    }
    echo "website_activity_log: seeded\n";
} else {
    echo "website_activity_log: exists\n";
}

// Integrity checks
$iCount = (int) $pdo->query('SELECT COUNT(*) FROM website_integrity_checks')->fetchColumn();
if ($iCount === 0) {
    $iIns = $pdo->prepare(
        'INSERT INTO website_integrity_checks (check_date, check_type, status, checked_by, notes, created_at, updated_at)
         VALUES (?,?,?,?,?,?,?)'
    );
    for ($d = 0; $d < 7; $d++) {
        $date = date('Y-m-d', strtotime("-{$d} day"));
        $iIns->execute([$date, 'daily', 'clear', 'Website Manager', 'Daily scan — no unauthorized content', $now, $now]);
    }
    $iIns->execute([date('Y-m-d', strtotime('monday this week')), 'weekly', 'clear', 'Director IT', 'Weekly Director IT review completed', $now, $now]);
    echo "website_integrity_checks: seeded\n";
} else {
    echo "website_integrity_checks: exists\n";
}

// Suggestions
$sCount = (int) $pdo->query("SELECT COUNT(*) FROM website_suggestions WHERE month_key = " . $pdo->quote($month))->fetchColumn();
if ($sCount === 0) {
    $sIns = $pdo->prepare(
        'INSERT INTO website_suggestions (month_key, suggestion, source, status, created_at, updated_at) VALUES (?,?,?,?,?,?)'
    );
    $sIns->execute([$month, 'Add vacant-seats deadline reminder banner on homepage during migration window.', 'Website Cell weekly review', 'open', $now, $now]);
    $sIns->execute([$month, 'Chase PDC and Debate Society for overdue activity submissions.', 'Missing departments review', 'open', $now, $now]);
    $sIns->execute([$month, 'Publish SOP + standard template link on ACP for unit heads.', 'Steering Committee note', 'open', $now, $now]);
    echo "website_suggestions: seeded\n";
} else {
    echo "website_suggestions: exists\n";
}

echo "Done.\n";
