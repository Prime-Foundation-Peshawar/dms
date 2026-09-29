<?php
/**
 * Idempotent seed: analytics snapshot + monthly contribution rankings (RCP MoM).
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$root = dirname(__DIR__);
require_once $root . '/legacy/includes/db.php';
$pdo = dms_db();
$now = date('Y-m-d H:i:s');
$month = date('Y-m');

$count = (int) $pdo->query('SELECT COUNT(*) FROM analytics_settings')->fetchColumn();
if ($count === 0) {
    $topPages = [
        ['path' => '/', 'views' => 4200],
        ['path' => '/admissions', 'views' => 2100],
        ['path' => '/vacant-seats', 'views' => 1800],
        ['path' => '/faculty', 'views' => 1500],
        ['path' => '/all-news', 'views' => 980],
    ];
    $pdo->prepare(
        'INSERT INTO analytics_settings
        (id, ga_measurement_id, ga_property_id, sessions_mtd, users_mtd, pageviews_mtd, bounce_rate, avg_session_sec, top_pages_json, notes, updated_at)
        VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    )->execute([
        'G-XXXXXXXXXX',
        '',
        12840,
        9230,
        41250,
        41.20,
        154,
        json_encode($topPages, JSON_UNESCAPED_UNICODE),
        'Snapshot editable in ACP until GA Data API is connected. Weekly Website Cell review should refresh these figures.',
        $now,
    ]);
    echo "analytics_settings: seeded\n";
} else {
    echo "analytics_settings: exists\n";
}

$chk = $pdo->prepare('SELECT COUNT(*) FROM website_contributions WHERE month_key = ?');
$chk->execute([$month]);
if ((int) $chk->fetchColumn() === 0) {
    $rows = [
        ['Student Affairs', 'unit', 6, 5, 5, 9.2, 'top', 'Admissions dates & deadlines'],
        ['ORIC', 'unit', 5, 5, 4, 9.0, 'top', 'Research publications & awards'],
        ['UMR', 'unit', 4, 4, 4, 8.8, 'top', 'Undergraduate research activities'],
        ['Literary Society', 'society', 3, 3, 3, 8.5, 'top', 'Society events'],
        ['QEC', 'unit', 3, 2, 2, 7.5, 'average', 'Workshops & accreditations'],
        ['HR', 'unit', 2, 2, 2, 7.2, 'average', 'Employment opportunities'],
        ['Sports Society', 'society', 2, 2, 1, 7.0, 'average', 'Sports activities'],
        ['Social Welfare Society', 'society', 2, 1, 1, 6.5, 'average', 'Community campaigns'],
        ['Finance', 'unit', 1, 1, 1, 6.0, 'average', 'Fee structure updates'],
        ['Kuwait Teaching Hospital', 'hospital', 1, 1, 0, 5.5, 'low', 'Clinical news pending'],
        ['Mercy Teaching Hospital', 'hospital', 1, 0, 0, 4.0, 'low', 'Awaiting approvals'],
        ['Prime Teaching Hospital', 'hospital', 0, 0, 0, 3.0, 'low', 'No submissions this month'],
    ];
    $ins = $pdo->prepare(
        'INSERT INTO website_contributions
        (month_key, unit_name, unit_type, submissions, approved, on_time, quality_score, rank_band, notes, created_at, updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)'
    );
    foreach ($rows as $r) {
        $ins->execute([$month, $r[0], $r[1], $r[2], $r[3], $r[4], $r[5], $r[6], $r[7], $now, $now]);
    }
    echo "website_contributions: seeded {$month}\n";
} else {
    echo "website_contributions: exists for {$month}\n";
}

echo "Done.\n";
