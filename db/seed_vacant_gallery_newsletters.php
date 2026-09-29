<?php
/**
 * Idempotent seed: vacant seats, newsletters, gallery.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$root = dirname(__DIR__);
require_once $root . '/legacy/includes/db.php';
$pdo = dms_db();
$now = date('Y-m-d H:i:s');

// Vacant seats rows
if ((int) $pdo->query('SELECT COUNT(*) FROM vacant_seats')->fetchColumn() === 0) {
    $rows = [
        ['1st Year MBBS', '2025-26', 0, 10],
        ['2nd Year MBBS', '2024-25', 3, 20],
        ['3rd Year MBBS', '2023-24', 0, 30],
        ['4th Year MBBS', '2022-23', 0, 40],
        ['Final Year MBBS', '2021-22', 0, 50],
    ];
    $stmt = $pdo->prepare('INSERT INTO vacant_seats (programme, session_label, seats, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,1,?,?)');
    foreach ($rows as $r) {
        $stmt->execute([$r[0], $r[1], $r[2], $r[3], $now, $now]);
    }
    echo "vacant_seats: seeded\n";
} else {
    echo "vacant_seats: exists\n";
}

$settingsCount = (int) $pdo->query('SELECT COUNT(*) FROM vacant_seats_settings')->fetchColumn();
if ($settingsCount === 0) {
    $instructions = [
        'Students are allowed to apply against the notified vacant seats (from 3rd Year MBBS to Final Year MBBS) with immediate effect. As per PM&DC Admission Regulations 2023, no transfer / migration shall be allowed in the first 2 years of MBBS.',
        'Students shall apply online for migration on vacant seats through the designated portal.',
        'Student will be admitted on merit based on average of professional examinations.',
        'As per Riphah International University rules, applicant having less than 60% marks in any subject/paper shall not be eligible for migration.',
        'No transfer shall be accepted by a college to a vacant seat until reviewed by the Authority to ensure transparency.',
    ];
    $pdo->prepare(
        'INSERT INTO vacant_seats_settings
        (id, alert_html, intro_html, instructions_json, apply_deadline, apply_deadline_note, merit_date, merit_date_note, apply_url, updated_at)
        VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    )->execute([
        '<strong>Riphah International University Rule:</strong> Applicant having less than <strong>60% marks in any subject/paper</strong> shall not be eligible for migration.',
        'Riphah International University - Peshawar Campus follows <strong>Pakistan Medical & Dental Council (PM&DC)</strong> rules and regulations in letter and spirit.',
        json_encode($instructions, JSON_UNESCAPED_UNICODE),
        '16 January 2026',
        '(Extended)',
        '21 January 2026',
        'Wednesday',
        'https://pmc.prime.edu.pk/vacant_admission/',
        $now,
    ]);
    echo "vacant_seats_settings: seeded\n";
} else {
    echo "vacant_seats_settings: exists\n";
}

// Newsletters
$newsletters = [
    ['July 2026', 'July, 2026', 'https://prime.edu.pk/pf/downloads/newsletters/July-2026-Newsletter.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/PRIME-JULY-Monthly-Newsletter.jpg', '2026-07-01', 10],
    ['June 2026', 'June, 2026', 'https://prime.edu.pk/pf/downloads/newsletters/June-2026-Newsletter-latest.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/June2026.jpg', '2026-06-01', 20],
    ['May 2026', 'May, 2026', 'https://prime.edu.pk/pf/downloads/newsletters/May%20Newsletter.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/May2026.jpg', '2026-05-01', 30],
    ['April 2026', 'April, 2026', 'https://prime.edu.pk/pf/downloads/newsletters/April-2026-Newsletter.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter-April-2026.jpg', '2026-04-01', 40],
    ['March 2026', 'March, 2026', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter_March_2026.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter_March_2026_title.jpg', '2026-03-01', 50],
    ['February 2026', 'February, 2026', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter_Feb._2026.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter-Feb.--2026-1.jpg', '2026-02-01', 60],
    ['January 2026', 'January, 2026', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter_January_2026.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/newsletter-12-1-2026.jpg', '2026-01-01', 70],
    ['9th Issue', 'Sep - Dec, 2025', 'https://prime.edu.pk/pf/downloads/newsletters/9th%20newsletter.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/newsletter-12-01-2026.jpg', '2025-12-01', 80],
    ['November 2025', 'November, 2025', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2001-11-2025.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2001-11-2025.jpg', '2025-11-01', 90],
    ['8th Issue', 'May - Aug, 2025', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2009-09-2025.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/newsletter-09-09-2025.jpg', '2025-09-01', 100],
    ['7th Issue', 'Jan - April, 2025', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2010-05-2025.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2010-05-2025.jpg', '2025-05-01', 110],
    ['6th Issue', 'Sep - Dec, 2024', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2009-01-2025.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2009-01-2025.jpg', '2025-01-01', 120],
    ['May - August 2024', 'May - August, 2024', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2005-09-2024-upd.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2005-09-2024.jpg', '2024-09-01', 130],
    ['January - April 2024', 'January - April, 2024', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2010-05-2024.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2010-05-2024.jpg', '2024-05-01', 140],
    ['Sep - Dec 2023', 'Sep - Dec, 2023', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2018-01-2024.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2018-01-2024.jpg', '2024-01-01', 150],
    ['May - Aug 2023', 'May - Aug, 2023', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2008-09-2023.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2008-09-2023_title.jpg', '2023-09-01', 160],
    ['Jan - April 2023', 'Jan - April, 2023', 'https://prime.edu.pk/pf/downloads/newsletters/Newsletter%2015-05-2023.pdf', 'https://prime.edu.pk/pf/downloads/newsletters/titles/Newsletter%2015-05-2023.jpg', '2023-05-01', 170],
];

$nlChk = $pdo->prepare('SELECT id FROM newsletters WHERE title = ? AND date_label = ? LIMIT 1');
$nlIns = $pdo->prepare('INSERT INTO newsletters (title, date_label, pdf_url, cover_url, published_at, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,?,?,1,?,?)');
$nlAdded = 0;
foreach ($newsletters as $n) {
    $nlChk->execute([$n[0], $n[1]]);
    if ($nlChk->fetch()) {
        continue;
    }
    $nlIns->execute([$n[0], $n[1], $n[2], $n[3], $n[4], $n[5], $now, $now]);
    $nlAdded++;
}
echo "newsletters: +{$nlAdded}\n";

// Gallery
if ((int) $pdo->query('SELECT COUNT(*) FROM gallery_albums')->fetchColumn() === 0) {
    $pdo->prepare('INSERT INTO gallery_albums (slug, title, category, icon, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,?,1,?,?)')
        ->execute(['pmc-campus', 'PMC Campus', 'campus', 'bi-buildings', 10, $now, $now]);
    $campusId = (int) $pdo->lastInsertId();
    $pdo->prepare('INSERT INTO gallery_albums (slug, title, category, icon, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,?,1,?,?)')
        ->execute(['facilities', 'Facilities', 'facilities', 'bi-buildings', 20, $now, $now]);
    $facId = (int) $pdo->lastInsertId();

    $imgIns = $pdo->prepare('INSERT INTO gallery_images (album_id, title, caption, image_path, span_class, sort_order, is_active, created_at, updated_at) VALUES (?,?,?,?,?,?,1,?,?)');
    $campusImgs = [
        ['Main Campus — Warsak Road', '25-Kanal campus on Warsak Road, Peshawar', 'assets/images/campus/pmc.jpg', 'span-2', 10],
        ['Department of Medical Sciences (DMS)', 'Modern Architecture', 'assets/images/campus/pmc-building.jpg', null, 20],
        ['Academic Block', 'Campus buildings', 'assets/images/campus/pmc-building2.jpg', null, 30],
    ];
    foreach ($campusImgs as $img) {
        $imgIns->execute([$campusId, $img[0], $img[1], $img[2], $img[3], $img[4], $now, $now]);
    }
    $facImgs = [
        ['Girls Hostel', 'Student accommodation', 'assets/images/campus/girls-hostel.png', 'span-2', 10],
        ['Campus life', 'Student facilities', 'assets/images/news/news3.jpg', null, 20],
        ['PMC campus view', 'Campus grounds', 'assets/images/news/pmc.jpg', null, 30],
        ['Campus overview', 'Warsak Road campus', 'assets/images/campus/pmc.jpg', null, 40],
        ['Library', 'Learning resources', 'assets/images/campus/library.jpg', null, 50],
        ['Library reading area', 'Study spaces', 'assets/images/campus/library.jpg', 'span-2', 60],
        ['Library stacks', 'Collections', 'assets/images/campus/library.jpg', null, 70],
        ['Library study hall', 'Quiet study', 'assets/images/campus/library.jpg', 'span-2', 80],
    ];
    foreach ($facImgs as $img) {
        $imgIns->execute([$facId, $img[0], $img[1], $img[2], $img[3], $img[4], $now, $now]);
    }
    echo "gallery: seeded\n";
} else {
    echo "gallery: exists\n";
}

echo "Done.\n";
