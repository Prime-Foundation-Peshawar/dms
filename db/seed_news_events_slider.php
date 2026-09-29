<?php
/**
 * Idempotent seed for news_posts, events, homepage_slides.
 * Safe to re-run: skips when slug/slide already exists.
 *
 * Usage (from repo root, staging PHP):
 *   php db/seed_news_events_slider.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

$root = dirname(__DIR__);
require_once $root . '/legacy/includes/db.php';

$pdo = dms_db();
$now = date('Y-m-d H:i:s');

function seed_upsert_news(PDO $pdo, array $row, string $now): void
{
    $chk = $pdo->prepare('SELECT id FROM news_posts WHERE slug = ? LIMIT 1');
    $chk->execute([$row['slug']]);
    if ($chk->fetch()) {
        return;
    }
    $stmt = $pdo->prepare(
        'INSERT INTO news_posts
        (slug, title, excerpt, body_html, author, category, status, published_at, read_minutes,
         cover_image, card_icon, card_gradient, link_url, link_label, deadline_label, tags,
         is_featured, show_on_home, home_section, sort_order, created_at, updated_at)
         VALUES
        (:slug, :title, :excerpt, :body_html, :author, :category, :status, :published_at, :read_minutes,
         :cover_image, :card_icon, :card_gradient, :link_url, :link_label, :deadline_label, :tags,
         :is_featured, :show_on_home, :home_section, :sort_order, :created_at, :updated_at)'
    );
    $stmt->execute([
        ':slug' => $row['slug'],
        ':title' => $row['title'],
        ':excerpt' => $row['excerpt'] ?? null,
        ':body_html' => $row['body_html'] ?? ($row['excerpt'] ?? null),
        ':author' => $row['author'] ?? null,
        ':category' => $row['category'] ?? 'general',
        ':status' => $row['status'] ?? 'published',
        ':published_at' => $row['published_at'] ?? $now,
        ':read_minutes' => $row['read_minutes'] ?? null,
        ':cover_image' => $row['cover_image'] ?? null,
        ':card_icon' => $row['card_icon'] ?? null,
        ':card_gradient' => $row['card_gradient'] ?? null,
        ':link_url' => $row['link_url'] ?? null,
        ':link_label' => $row['link_label'] ?? null,
        ':deadline_label' => $row['deadline_label'] ?? null,
        ':tags' => isset($row['tags']) ? json_encode($row['tags'], JSON_UNESCAPED_UNICODE) : null,
        ':is_featured' => !empty($row['is_featured']) ? 1 : 0,
        ':show_on_home' => !empty($row['show_on_home']) ? 1 : 0,
        ':home_section' => $row['home_section'] ?? null,
        ':sort_order' => (int) ($row['sort_order'] ?? 0),
        ':created_at' => $now,
        ':updated_at' => $now,
    ]);
    echo "news: {$row['slug']}\n";
}

function seed_upsert_event(PDO $pdo, array $row, string $now): void
{
    $chk = $pdo->prepare('SELECT id FROM events WHERE slug = ? LIMIT 1');
    $chk->execute([$row['slug']]);
    if ($chk->fetch()) {
        return;
    }
    $stmt = $pdo->prepare(
        'INSERT INTO events
        (slug, title, excerpt, body_html, author, category, status, published_at, event_date, event_end_date,
         venue, read_minutes, cover_image, card_icon, card_gradient, link_url, link_label, tags,
         is_featured, sort_order, created_at, updated_at)
         VALUES
        (:slug, :title, :excerpt, :body_html, :author, :category, :status, :published_at, :event_date, :event_end_date,
         :venue, :read_minutes, :cover_image, :card_icon, :card_gradient, :link_url, :link_label, :tags,
         :is_featured, :sort_order, :created_at, :updated_at)'
    );
    $stmt->execute([
        ':slug' => $row['slug'],
        ':title' => $row['title'],
        ':excerpt' => $row['excerpt'] ?? null,
        ':body_html' => $row['body_html'] ?? ($row['excerpt'] ?? null),
        ':author' => $row['author'] ?? null,
        ':category' => $row['category'] ?? 'general',
        ':status' => $row['status'] ?? 'published',
        ':published_at' => $row['published_at'] ?? $now,
        ':event_date' => $row['event_date'] ?? null,
        ':event_end_date' => $row['event_end_date'] ?? null,
        ':venue' => $row['venue'] ?? null,
        ':read_minutes' => $row['read_minutes'] ?? null,
        ':cover_image' => $row['cover_image'] ?? null,
        ':card_icon' => $row['card_icon'] ?? null,
        ':card_gradient' => $row['card_gradient'] ?? null,
        ':link_url' => $row['link_url'] ?? null,
        ':link_label' => $row['link_label'] ?? null,
        ':tags' => isset($row['tags']) ? json_encode($row['tags'], JSON_UNESCAPED_UNICODE) : null,
        ':is_featured' => !empty($row['is_featured']) ? 1 : 0,
        ':sort_order' => (int) ($row['sort_order'] ?? 0),
        ':created_at' => $now,
        ':updated_at' => $now,
    ]);
    echo "event: {$row['slug']}\n";
}

$rankingBody = <<<'HTML'
<p>Peshawar Medical College has once again been ranked <strong>first among all private medical colleges in Khyber Pakhtunkhwa</strong>, following the latest inspection by the Pakistan Medical &amp; Dental Council (PM&amp;DC). The college secured a score exceeding <strong>80%</strong> — the highest recorded by any private medical institution in the province — reaffirming PMC's position as the benchmark of private medical education in KP.</p>
<p>This achievement comes in the wake of an extensive and rigorous multi-day inspection covering every facet of college operations: faculty qualifications, clinical exposure at affiliated teaching hospitals, laboratory and library infrastructure, student welfare services, and academic outcomes.</p>
<h3>About the PM&amp;DC Inspection</h3>
<p>PM&amp;DC conducts annual inspections of all recognised medical colleges to ensure compliance with national standards for medical education.</p>
<ul>
<li><strong>Faculty Strength &amp; Qualifications</strong></li>
<li><strong>Clinical Training Facilities</strong></li>
<li><strong>Infrastructure</strong></li>
<li><strong>Academic Outcomes</strong></li>
<li><strong>Student Welfare</strong></li>
</ul>
<blockquote><p>"This recognition is a reflection of the combined dedication of our faculty, staff, and students."</p><cite>— Principal, Peshawar Medical College</cite></blockquote>
<p>PMC continues to welcome applications from local, overseas Pakistani, and international students. Eligible students are encouraged to <a href="admissions">apply now</a>.</p>
HTML;

$umrBody = <<<'HTML'
<p>Peshawar Medical College's <strong>Undergraduate Medical Research (UMR) Society</strong> successfully organised its Annual Medical Research Conference 2025 — a landmark event demonstrating the vibrant research culture nurtured within the college.</p>
<p>The conference brought together students and faculty, with research presentations spanning all five years of the MBBS program.</p>
<h3>Keynote Address</h3>
<blockquote><p>"Research is the backbone of evidence-based medicine. PMC has always believed that medical students who learn to think critically, question, and investigate will become better doctors."</p><cite>— Principal, Peshawar Medical College</cite></blockquote>
<p>For more information visit the <a href="umr">UMR Society page</a>.</p>
HTML;

$news = [
    // Homepage notices
    [
        'slug' => 'mphil-basic-medical-sciences-fall-2026',
        'title' => 'MPhil Basic Medical Sciences',
        'excerpt' => 'Extended schedule: test 04 Sep 2026, interview 05 Sep 2026. Includes DMJ.',
        'author' => 'Admissions Office',
        'category' => 'admissions',
        'published_at' => '2026-08-01 10:00:00',
        'link_url' => 'https://riphahpsh.edu.pk/assets/images/news/pg-medical-dental-ad-fall-2026.png',
        'link_label' => 'Read more',
        'deadline_label' => 'Apply by 03 Sep 2026',
        'show_on_home' => 1,
        'home_section' => 'notices',
        'sort_order' => 10,
        'card_icon' => 'bi-mortarboard-fill',
        'card_gradient' => 'linear-gradient(135deg,#0A1628,#1a3a6b)',
    ],
    [
        'slug' => 'positions-vacant-sep-2026',
        'title' => 'Positions Vacant',
        'excerpt' => 'View the current advertisement for open positions.',
        'author' => 'HR',
        'category' => 'career',
        'published_at' => '2026-08-15 10:00:00',
        'link_url' => 'https://careers.riphahpsh.edu.pk/public/uploads/jobs/1789971570_2dbc46f772d7adefd3f6.jpeg',
        'link_label' => 'View advertisement',
        'deadline_label' => 'Apply by 30 Sep 2026',
        'show_on_home' => 1,
        'home_section' => 'notices',
        'sort_order' => 20,
        'card_icon' => 'bi-briefcase-fill',
        'card_gradient' => 'linear-gradient(135deg,#37474F,#546E7A)',
    ],
    [
        'slug' => 'explore-open-roles-online',
        'title' => 'Explore Open Roles Online',
        'excerpt' => 'Browse current vacancies and submit applications through the official Career Portal.',
        'author' => 'HR',
        'category' => 'career',
        'published_at' => '2026-08-15 11:00:00',
        'link_url' => 'https://careers.riphahpsh.edu.pk',
        'link_label' => 'Visit Career Portal',
        'show_on_home' => 1,
        'home_section' => 'notices',
        'sort_order' => 30,
        'card_icon' => 'bi-box-arrow-up-right',
        'card_gradient' => 'linear-gradient(135deg,#0d4f5f,#1b7a9e)',
    ],
    // Homepage campus
    [
        'slug' => '19th-umr-research-conference',
        'title' => '19th UMR Research Conference',
        'excerpt' => 'Charting the Cosmos of Knowledge: from Research to Reality',
        'author' => 'UMR Society',
        'category' => 'research',
        'published_at' => '2026-04-01 10:00:00',
        'link_url' => 'https://umr.prime.edu.pk/conference/19th-umr/',
        'link_label' => 'Read more',
        'show_on_home' => 1,
        'home_section' => 'campus',
        'sort_order' => 10,
        'card_icon' => 'bi-flask-fill',
        'card_gradient' => 'linear-gradient(135deg,#00695C,#00897B)',
    ],
    [
        'slug' => 'sports-society-highlight',
        'title' => 'Sports Society',
        'excerpt' => 'It provides opportunities to participate in various indoor and outdoor sports.',
        'author' => 'Sports Society',
        'category' => 'campus',
        'published_at' => '2025-11-01 10:00:00',
        'link_url' => 'https://riphahpsh.edu.pk/sports-society',
        'link_label' => 'Read More',
        'show_on_home' => 1,
        'home_section' => 'campus',
        'sort_order' => 20,
        'card_icon' => 'bi-trophy-fill',
        'card_gradient' => 'linear-gradient(135deg,#6A1B9A,#7B1FA2)',
    ],
    // all-news listing
    [
        'slug' => 'mbbs-admissions-applications-closed-2025',
        'title' => 'MBBS Admissions — Applications Closed',
        'excerpt' => 'Applications for MBBS Session 2025–26 are closed. Review eligibility, merit criteria, and guidance for the next admissions cycle on the Admissions page.',
        'author' => 'PMC Admin',
        'category' => 'admissions',
        'published_at' => '2025-06-15 10:00:00',
        'read_minutes' => 2,
        'is_featured' => 1,
        'sort_order' => 10,
        'card_icon' => 'bi-mortarboard-fill',
        'card_gradient' => 'linear-gradient(135deg,#0A1628,#1a3a6b)',
        'body_html' => '<p>Applications for MBBS Session 2025–26 are closed. Review eligibility, merit criteria, and guidance for the next admissions cycle on the <a href="admissions">Admissions</a> page.</p>',
    ],
    [
        'slug' => 'pmc-retains-number-1-ranking-kp',
        'title' => 'PMC Retains #1 Ranking Among Private Medical Colleges in KP',
        'excerpt' => 'Following the latest PM&DC inspection, PMC secured the highest score among all private medical colleges in KP with over 80%.',
        'author' => 'PMC Communications',
        'category' => 'achievement',
        'published_at' => '2025-05-15 10:00:00',
        'read_minutes' => 3,
        'sort_order' => 20,
        'card_icon' => 'bi-award-fill',
        'card_gradient' => 'linear-gradient(135deg,#C9A84C,#e0c068)',
        'body_html' => $rankingBody,
        'tags' => ['Achievement', 'PM&DC', 'Ranking', 'PMC', 'KP Medical Colleges'],
    ],
    [
        'slug' => 'umr-society-annual-conference-2025-news',
        'title' => 'UMR Society Annual Medical Research Conference 2025',
        'excerpt' => 'The PMC Undergraduate Medical Research Society organised its annual conference showcasing student projects.',
        'author' => 'UMR Society',
        'category' => 'research',
        'published_at' => '2025-04-22 10:00:00',
        'read_minutes' => 5,
        'sort_order' => 30,
        'card_icon' => 'bi-flask-fill',
        'card_gradient' => 'linear-gradient(135deg,#00695C,#00897B)',
        'body_html' => $umrBody,
    ],
    [
        'slug' => 'international-medical-education-symposium-2025',
        'title' => 'International Medical Education Symposium 2025 — PMC Hosts Delegates',
        'excerpt' => 'PMC hosted an international symposium on modern medical education, attended by 200+ delegates.',
        'author' => 'PMC Admin',
        'category' => 'conference',
        'published_at' => '2025-02-18 10:00:00',
        'read_minutes' => 4,
        'sort_order' => 40,
        'card_icon' => 'bi-mic-fill',
        'card_gradient' => 'linear-gradient(135deg,#1565C0,#1976D2)',
    ],
    [
        'slug' => 'annual-sports-gala-2025-news',
        'title' => 'Annual Sports Gala 2025 — PMC Sports Society',
        'excerpt' => 'A week-long celebration of sportsmanship and team spirit featuring cricket, football, and more.',
        'author' => 'Sports Society',
        'category' => 'society',
        'published_at' => '2025-03-10 10:00:00',
        'read_minutes' => 3,
        'sort_order' => 50,
        'card_icon' => 'bi-trophy-fill',
        'card_gradient' => 'linear-gradient(135deg,#6A1B9A,#7B1FA2)',
    ],
    [
        'slug' => 'sws-blood-donation-drive-winter-2025-news',
        'title' => 'SWS Blood Donation Drive — Winter 2025 Exceeds Target',
        'excerpt' => 'The Social Welfare Society conducted a successful blood donation drive with over 150 units collected.',
        'author' => 'Social Welfare Society',
        'category' => 'society',
        'published_at' => '2025-01-28 10:00:00',
        'read_minutes' => 2,
        'sort_order' => 60,
        'card_icon' => 'bi-heart-fill',
        'card_gradient' => 'linear-gradient(135deg,#C62828,#E53935)',
    ],
    [
        'slug' => 'annual-convocation-2024-news',
        'title' => 'Annual Convocation 2024 — 90+ Doctors Graduate from PMC',
        'excerpt' => 'PMC celebrated another cohort at the Annual Convocation 2024 with degrees conferred to over 90 new MBBS graduates.',
        'author' => 'PMC Admin',
        'category' => 'general',
        'published_at' => '2024-12-05 10:00:00',
        'read_minutes' => 3,
        'sort_order' => 70,
        'card_icon' => 'bi-mortarboard-fill',
        'card_gradient' => 'linear-gradient(135deg,#37474F,#546E7A)',
    ],
    [
        'slug' => 'pmc-scholarship-policy-2024-25',
        'title' => 'PMC Scholarship Policy Updated for Session 2024–25',
        'excerpt' => 'The updated scholarship policy for MBBS Session 2024–25 has been announced.',
        'author' => 'Admissions Office',
        'category' => 'admissions',
        'published_at' => '2024-10-01 10:00:00',
        'read_minutes' => 3,
        'sort_order' => 80,
        'card_icon' => 'bi-award',
        'card_gradient' => 'linear-gradient(135deg,#00695C,#2E7D32)',
    ],
    [
        'slug' => 'pmc-faculty-research-international-journals',
        'title' => 'PMC Faculty Research Published in International Peer-Reviewed Journals',
        'excerpt' => 'Multiple PMC faculty members have had research papers published in internationally peer-reviewed medical journals.',
        'author' => 'PMC Communications',
        'category' => 'research',
        'published_at' => '2024-08-14 10:00:00',
        'read_minutes' => 4,
        'sort_order' => 90,
        'card_icon' => 'bi-journal-richtext',
        'card_gradient' => 'linear-gradient(135deg,#0A1628,#00695C)',
    ],
];

$events = [
    [
        'slug' => 'pmc-retains-number-1-ranking-event',
        'title' => 'PMC Retains #1 Ranking Among Private Medical Colleges in KP',
        'excerpt' => 'Following the latest PM&DC inspection visit, Peshawar Medical College has once again secured the highest score among all private medical colleges in Khyber Pakhtunkhwa with over 80%.',
        'author' => 'PMC Communications',
        'category' => 'achievement',
        'published_at' => '2025-05-15 10:00:00',
        'event_date' => '2025-05-15',
        'read_minutes' => 3,
        'is_featured' => 1,
        'sort_order' => 5,
        'card_icon' => 'bi-award-fill',
        'card_gradient' => 'linear-gradient(135deg,#C9A84C,#e0c068)',
        'body_html' => $rankingBody,
    ],
    [
        'slug' => 'mbbs-admissions-open-2026-27',
        'title' => 'MBBS Admissions Open for Session 2026-27',
        'excerpt' => 'Peshawar Medical College invites applications from eligible students — local, overseas Pakistani, and international — for the upcoming MBBS session.',
        'author' => 'PMC Admin',
        'category' => 'admissions',
        'published_at' => '2025-06-15 10:00:00',
        'event_date' => '2025-06-15',
        'sort_order' => 10,
        'card_icon' => 'bi-mortarboard-fill',
        'card_gradient' => 'linear-gradient(135deg,#0A1628,#122040)',
        'venue' => 'Peshawar Medical College',
    ],
    [
        'slug' => 'umr-society-annual-conference-2025',
        'title' => 'UMR Society Annual Medical Research Conference 2025',
        'excerpt' => 'The PMC Undergraduate Medical Research Society organised its annual conference showcasing research projects across all five years of the MBBS program.',
        'author' => 'UMR Society',
        'category' => 'research',
        'published_at' => '2025-04-22 10:00:00',
        'event_date' => '2025-04-22',
        'venue' => 'PMC Main Auditorium, Warsak Road',
        'read_minutes' => 5,
        'sort_order' => 20,
        'card_icon' => 'bi-flask-fill',
        'card_gradient' => 'linear-gradient(135deg,#00695C,#00897B)',
        'body_html' => $umrBody,
        'tags' => ['Research', 'UMR Society', 'Conference', 'MBBS Students', 'PMC Events'],
    ],
    [
        'slug' => 'annual-sports-gala-2025',
        'title' => 'Annual Sports Gala 2025 — PMC Sports Society',
        'excerpt' => 'A week-long celebration of sportsmanship, team spirit, and healthy competition featuring cricket, football, basketball, badminton and athletics.',
        'author' => 'Sports Society',
        'category' => 'society',
        'published_at' => '2025-03-10 10:00:00',
        'event_date' => '2025-03-10',
        'venue' => 'PMC Sports Ground',
        'sort_order' => 30,
        'card_icon' => 'bi-trophy-fill',
        'card_gradient' => 'linear-gradient(135deg,#6A1B9A,#7B1FA2)',
    ],
    [
        'slug' => 'international-medical-education-symposium-pmc-2025',
        'title' => 'International Medical Education Symposium — PMC 2025',
        'excerpt' => 'PMC hosted an international symposium on modern medical education practices, attended by faculty, students, and delegates from medical institutions across Pakistan and abroad.',
        'author' => 'PMC Admin',
        'category' => 'conference',
        'published_at' => '2025-02-18 10:00:00',
        'event_date' => '2025-02-18',
        'sort_order' => 40,
        'card_icon' => 'bi-mic-fill',
        'card_gradient' => 'linear-gradient(135deg,#1565C0,#1976D2)',
    ],
    [
        'slug' => 'sws-blood-donation-drive-winter-2025',
        'title' => 'SWS Blood Donation Drive — Winter 2025',
        'excerpt' => 'The Social Welfare Society conducted a successful blood donation drive with over 150 units collected for affiliated teaching hospitals.',
        'author' => 'Social Welfare Society',
        'category' => 'society',
        'published_at' => '2025-01-28 10:00:00',
        'event_date' => '2025-01-28',
        'sort_order' => 50,
        'card_icon' => 'bi-heart-fill',
        'card_gradient' => 'linear-gradient(135deg,#E65100,#F57C00)',
    ],
    [
        'slug' => 'annual-convocation-2024',
        'title' => 'Annual Convocation 2024 — Graduation Ceremony',
        'excerpt' => 'PMC celebrated another cohort of graduating doctors at the Annual Convocation 2024, with degrees conferred to over 90 newly qualified MBBS graduates.',
        'author' => 'PMC Admin',
        'category' => 'general',
        'published_at' => '2024-12-05 10:00:00',
        'event_date' => '2024-12-05',
        'sort_order' => 60,
        'card_icon' => 'bi-mortarboard-fill',
        'card_gradient' => 'linear-gradient(135deg,#37474F,#546E7A)',
    ],
];

foreach ($news as $row) {
    seed_upsert_news($pdo, $row, $now);
}
foreach ($events as $row) {
    seed_upsert_event($pdo, $row, $now);
}

$slideChk = $pdo->query('SELECT COUNT(*) FROM homepage_slides')->fetchColumn();
if ((int) $slideChk === 0) {
    $pdo->prepare(
        'INSERT INTO homepage_slides
        (sort_order, is_active, media_type, poster_path, poster_webp_path, video_720_path, video_480_path,
         brand, title_html, body, body_sub, cta1_label, cta1_url, cta1_style, cta2_label, cta2_url, cta2_style,
         aria_label, created_at, updated_at)
         VALUES
        (10, 1, \'video\',
         \'assets/images/slider/pmc-hero-poster.jpg\', \'assets/images/slider/pmc-hero-poster.webp\',
         \'assets/videos/pmc-hero-720.mp4\', \'assets/videos/pmc-hero-480.mp4\',
         \'Department of Medical Sciences\',
         \'Your <span class="hl-teal">MBBS</span> Journey Starts Here\',
         \'Peshawar Medical College offers a PM&DC-recognized five-year MBBS — rigorous basic sciences, early clinical exposure, and mentors who teach medicine with integrity.\',
         \'Study at Riphah International University – Peshawar Campus and build the competence to serve communities across KP and beyond.\',
         \'Admissions Info\', \'admissions\', \'primary\',
         \'About PMC\', \'pmc\', \'outline-white\',
         \'Peshawar Medical College campus film\',
         ?, ?)'
    )->execute([$now, $now]);
    echo "slide: hero-video\n";
} else {
    echo "slides: already seeded\n";
}

echo "Done.\n";
