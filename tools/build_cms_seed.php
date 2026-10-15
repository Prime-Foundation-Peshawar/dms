<?php
/**
 * Build CMS seed JSON from existing static views / legacy news+events markup.
 */
declare(strict_types=1);

$root = dirname(__DIR__);
$seedDir = $root . '/app/Database/Seeds/data';
$viewsDir = $root . '/app/Views/pages';
$legacyNews = $viewsDir . '/_legacy_static/all-news.php';
$legacyEvents = $viewsDir . '/_legacy_static/events.php';

function dms_slug(string $title): string
{
    $s = strtolower(trim($title));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';

    return trim($s, '-') ?: 'item-' . substr(md5($title), 0, 8);
}

function dms_text_from_html(string $html): string
{
    $t = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $t = preg_replace("/[ \t]+/", ' ', $t) ?? $t;
    $t = preg_replace("/\n{3,}/", "\n\n", $t) ?? $t;

    return trim($t);
}

function dms_clean_view_html(string $raw): string
{
    $body = preg_replace('/^<\?php[\s\S]*?\?>\s*/', '', $raw) ?? $raw;
    $body = preg_replace('/<\?=\s*site_url\(\)\s*\?>/', '/', $body) ?? $body;
    $body = preg_replace('/<\?=\s*site_url\(\s*[\'"]([^\'"]+)[\'"]\s*\)\s*\?>/', '/$1', $body) ?? $body;
    $body = preg_replace('/<\?=\s*base_url\(\s*[\'"]([^\'"]+)[\'"]\s*\)\s*\?>/', '/$1', $body) ?? $body;
    $body = preg_replace('/<\?=[\s\S]*?\?>/', '', $body) ?? $body;
    $body = preg_replace('/<\?[\s\S]*?\?>/', '', $body) ?? $body;

    // Keep a single page chrome later via cms_content — drop embedded heroes from import.
    // (Full original layout is preserved when body still contains pmc-section.)
    return trim($body);
}

function dms_plain(string $html): string
{
    return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
}

$templateOnly = [
    'home', 'all-news', 'single-news', 'events', 'event-single',
    'faculty-research', 'examinations', 'newsletter', 'faculty', 'departments',
];

$pagesPath = $seedDir . '/pages.json';
$pages = json_decode((string) file_get_contents($pagesPath), true) ?: [];
$enriched = 0;
foreach ($pages as &$p) {
    $slug = (string) ($p['slug'] ?? '');
    $viewKey = (string) ($p['view_key'] ?? $slug);
    $viewFile = $viewsDir . '/' . $viewKey . '.php';
    $useCms = ! in_array($slug, $templateOnly, true) && is_file($viewFile);
    $bodyHtml = '';
    if ($useCms) {
        $bodyHtml = dms_clean_view_html((string) file_get_contents($viewFile));
        if (strlen($bodyHtml) < 40) {
            $useCms = false;
            $bodyHtml = '';
        } else {
            $enriched++;
        }
    }
    $p['use_cms'] = $useCms ? 1 : 0;
    $p['body_html'] = $bodyHtml !== '' ? $bodyHtml : null;
    $p['body_text'] = $bodyHtml !== '' ? dms_text_from_html($bodyHtml) : null;
}
unset($p);
file_put_contents($pagesPath, json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "pages enriched with CMS body: {$enriched}/" . count($pages) . PHP_EOL;

$months = [
    'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
    'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12',
];

/**
 * Parse explicit deadline phrases from static copy.
 * Examples: "Apply by 09 Nov 2026", "Last Date for Registration is October 27, 2026"
 */
function dms_parse_deadline(string $text): ?string
{
    $patterns = [
        // Day Month Year — "09 Nov 2026", "27 October 2026"
        '/Apply by\s+(\d{1,2}\s+[A-Za-z]{3,9}\s+\d{4})/i',
        '/Last Date(?: for Registration)?(?: is|:)?\s+(\d{1,2}\s+[A-Za-z]{3,9},?\s+\d{4})/i',
        // Month Day, Year — "October 27, 2026"
        '/Apply by\s+([A-Za-z]{3,9}\s+\d{1,2},?\s+\d{4})/i',
        '/Last Date(?: for Registration)?(?: is|:)?\s+([A-Za-z]{3,9}\s+\d{1,2},?\s+\d{4})/i',
        '/(?:deadline|expires?|valid until|until)\s*:?\s*(\d{1,2}\s+[A-Za-z]{3,9},?\s+\d{4})/i',
        '/(?:deadline|expires?|valid until|until)\s*:?\s*([A-Za-z]{3,9}\s+\d{1,2},?\s+\d{4})/i',
        '/(\d{1,2}\s+[A-Za-z]{3,9}\s+\d{4})\s*\(deadline\)/i',
    ];
    foreach ($patterns as $re) {
        if (preg_match($re, $text, $m)) {
            $ts = strtotime(str_replace(',', '', $m[1]) . ' 23:59:59');
            if ($ts !== false) {
                return date('Y-m-d H:i:s', $ts);
            }
        }
    }

    return null;
}

/** Infer expiry when static page had no explicit deadline. */
function dms_infer_expiry(string $title, string $excerpt, string $category, string $publishedAt): ?string
{
    $blob = $title . ' ' . $excerpt . ' ' . $category;
    // Normalize en/em dashes so "2024–25" matches ASCII rules
    $blob = preg_replace('/\x{2013}|\x{2014}/u', '-', $blob) ?? $blob;

    $explicit = dms_parse_deadline($blob);
    if ($explicit !== null) {
        return $explicit;
    }

    // Closed admissions notices were already past usefulness on publish day.
    if (preg_match('/applications?\s+closed/i', $blob)) {
        return date('Y-m-d 23:59:59', strtotime($publishedAt) ?: time());
    }

    // Session-bound scholarship / admissions cycles → end of academic year (30 Jun).
    if (preg_match('/session\s*20(\d{2})\s*-\s*(?:20)?(\d{2})/i', $blob, $m)) {
        $endYear = 2000 + (int) $m[2];

        return sprintf('%04d-06-30 23:59:59', $endYear);
    }

    // One-off campus events / drives / conferences → 30 days after published date.
    if (preg_match('/conference|symposium|gala|donation|convocation|sports/i', $blob)) {
        $ts = strtotime($publishedAt . ' +30 days');

        return $ts ? date('Y-m-d 23:59:59', $ts) : null;
    }

    // Evergreen (rankings, general announcements) → no auto-expiry
    return null;
}

function dms_push_news(array &$news, array &$seen, array $item): void
{
    $slug = $item['slug'];
    if (isset($seen[$slug])) {
        // Prefer item that already has expires_at / richer body when merging home over archive.
        $idx = $seen[$slug];
        if (empty($news[$idx]['expires_at']) && ! empty($item['expires_at'])) {
            $news[$idx]['expires_at'] = $item['expires_at'];
        }
        if (empty($news[$idx]['cover_image']) && ! empty($item['cover_image'])) {
            $news[$idx]['cover_image'] = $item['cover_image'];
        }
        // Newer published_at from home wins when present
        if (! empty($item['published_at']) && ($item['published_at'] > ($news[$idx]['published_at'] ?? ''))) {
            $news[$idx]['published_at'] = $item['published_at'];
            $news[$idx]['title'] = $item['title'];
            $news[$idx]['excerpt'] = $item['excerpt'];
            $news[$idx]['body_html'] = $item['body_html'];
            $news[$idx]['body_text'] = $item['body_text'];
            $news[$idx]['category'] = $item['category'];
            if (! empty($item['expires_at'])) {
                $news[$idx]['expires_at'] = $item['expires_at'];
            }
        }

        return;
    }
    $seen[$slug] = count($news);
    $news[] = $item;
}

$news = [];
$seenNews = [];

// 1) Home page live cards (deadlines / current notices) — highest priority
$homeFile = $viewsDir . '/home.php';
if (is_file($homeFile)) {
    $home = (string) file_get_contents($homeFile);
    // Strip HTML comments so commented-out cards are ignored
    $homeLive = preg_replace('/<!--[\s\S]*?-->/', '', $home) ?? $home;
    preg_match_all(
        '/<article class="news-card[^"]*">([\s\S]*?)<\/article>/',
        $homeLive,
        $articles,
        PREG_SET_ORDER
    );
    foreach ($articles as $art) {
        $block = $art[1];
        if (! preg_match('/<h3 class="nc-title">(.*?)<\/h3>/s', $block, $tm)) {
            continue;
        }
        $title = dms_plain($tm[1]);
        if ($title === '' || preg_match('/explore open roles/i', $title)) {
            continue; // career portal promo, not news
        }
        $excerpt = '';
        if (preg_match('/<p class="nc-excerpt">(.*?)<\/p>/s', $block, $em)) {
            $excerpt = dms_plain($em[1]);
        }
        $deadlineLine = '';
        if (preg_match('/<p class="nc-deadline">([\s\S]*?)<\/p>/', $block, $dm)) {
            $deadlineLine = dms_plain($dm[1]);
            if ($excerpt === '') {
                $excerpt = $deadlineLine;
            } else {
                $excerpt = $deadlineLine . ' ' . $excerpt;
            }
        }
        $cat = 'News';
        if (preg_match('/nc-cat[^>]*>(.*?)<\/span>/s', $block, $cm)) {
            $cat = dms_plain($cm[1]) ?: $cat;
        }
        $publishedAt = date('Y-m-d') . ' 10:00:00';
        if (preg_match('/Published:\s*([A-Za-z]{3,9})\s+(\d{4})/i', $block, $pm)) {
            $mon = $months[substr(ucfirst(strtolower($pm[1])), 0, 3)] ?? '01';
            // full month names
            $monMap = [
                'january' => '01', 'february' => '02', 'march' => '03', 'april' => '04',
                'may' => '05', 'june' => '06', 'july' => '07', 'august' => '08',
                'september' => '09', 'october' => '10', 'november' => '11', 'december' => '12',
            ];
            $key = strtolower($pm[1]);
            $mon = $monMap[$key] ?? ($months[substr(ucfirst($key), 0, 3)] ?? $mon);
            $publishedAt = "{$pm[2]}-{$mon}-01 10:00:00";
        } elseif (preg_match('/>([A-Za-z]{3})\s+(\d{4})</', $block, $pm2)) {
            $mon = $months[$pm2[1]] ?? '01';
            $publishedAt = "{$pm2[2]}-{$mon}-01 10:00:00";
        }
        $cover = null;
        if (preg_match('/assets\/images\/news\/[^"\']+/', $block, $cim)) {
            $cover = $cim[0];
        }
        $slug = dms_slug($title);
        $expires = dms_infer_expiry($title, $excerpt . ' ' . $deadlineLine, $cat, $publishedAt);
        $bodyBits = array_filter([$deadlineLine, $excerpt]);
        $bodyText = implode("\n\n", $bodyBits);
        $bodyHtml = implode("\n", array_map(
            static fn ($p) => '<p>' . htmlspecialchars($p, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</p>',
            $bodyBits
        ));
        dms_push_news($news, $seenNews, [
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $excerpt,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
            'category' => $cat,
            'cover_image' => $cover,
            'published_at' => $publishedAt,
            'expires_at' => $expires,
            'status' => 'published',
        ]);
    }
}

// 2) Legacy all-news archive cards
if (is_file($legacyNews)) {
    $html = (string) file_get_contents($legacyNews);
    preg_match_all(
        '/<div class="nc-img-placeholder"[^>]*>\s*<i[^>]*><\/i><span>([^<]+)<\/span>\s*<div class="nc-date-pill"><span class="day">(\d+)<\/span><span class="month">([A-Za-z]+)<\/span><\/div>\s*<\/div>\s*<div class="nc-body">\s*<div class="nc-title">(.*?)<\/div>\s*<div class="nc-excerpt">(.*?)<\/div>/s',
        $html,
        $cards,
        PREG_SET_ORDER
    );
    foreach ($cards as $c) {
        $title = dms_plain($c[4]);
        $excerpt = dms_plain($c[5]);
        $cat = trim($c[1]);
        $day = str_pad($c[2], 2, '0', STR_PAD_LEFT);
        $mon = $months[$c[3]] ?? '01';
        $year = (in_array($c[3], ['Aug', 'Oct', 'Dec'], true) && ! str_contains($title, '2025')) ? '2024' : '2025';
        if (str_contains($title, '2024')) {
            $year = '2024';
        }
        $slug = dms_slug($title);
        $publishedAt = "{$year}-{$mon}-{$day} 10:00:00";
        $expires = dms_infer_expiry($title, $excerpt, $cat, $publishedAt);
        $bodyHtml = '<p>' . htmlspecialchars($excerpt, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</p>';
        dms_push_news($news, $seenNews, [
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $excerpt,
            'body_html' => $bodyHtml,
            'body_text' => $excerpt,
            'category' => $cat,
            'cover_image' => null,
            'published_at' => $publishedAt,
            'expires_at' => $expires,
            'status' => 'published',
        ]);
    }
}
file_put_contents($seedDir . '/news.json', json_encode($news, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
$withExpiry = count(array_filter($news, static fn ($n) => ! empty($n['expires_at'])));
echo 'news: ' . count($news) . " (with expires_at: {$withExpiry})" . PHP_EOL;

$events = [];
if (is_file($legacyEvents)) {
    $html = (string) file_get_contents($legacyEvents);
    preg_match_all(
        '/<div class="col-md-6 ev-item[^"]*"([^>]*)>[\s\S]*?<div class="ec-title">(.*?)<\/div>\s*<div class="ec-excerpt">(.*?)<\/div>/s',
        $html,
        $cards,
        PREG_SET_ORDER
    );
    $seen = [];
    foreach ($cards as $c) {
        $attrs = $c[1];
        $title = dms_plain($c[2]);
        $excerpt = dms_plain($c[3]);
        preg_match('/data-cat="([^"]+)"/', $attrs, $cm);
        preg_match('/data-date="([^"]+)"/', $attrs, $dm);
        $cat = $cm[1] ?? 'general';
        $date = $dm[1] ?? null;
        $location = null;
        if (preg_match('/bi-geo-alt"><\/i>\s*([^<]+)/', $c[0], $lm)) {
            $location = trim($lm[1]);
        }
        $slug = dms_slug($title);
        if (isset($seen[$slug])) {
            continue;
        }
        $seen[$slug] = true;
        $bodyHtml = '<p>' . htmlspecialchars($excerpt, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</p>';
        $events[] = [
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $excerpt,
            'body_html' => $bodyHtml,
            'body_text' => $excerpt,
            'category' => ucfirst($cat),
            'cover_image' => null,
            'event_date' => $date,
            'location' => $location,
            'status' => 'published',
        ];
    }
}
file_put_contents($seedDir . '/events.json', json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo 'events: ' . count($events) . PHP_EOL;
echo "Done.\n";
