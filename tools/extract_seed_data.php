<?php
/**
 * Extract seed JSON + CI4 page views from _legacy site content.
 */
declare(strict_types=1);

$root = dirname(__DIR__);
$legacy = $root . '/_legacy';
$seedDir = $root . '/app/Database/Seeds/data';
$viewsDir = $root . '/app/Views/pages';
@mkdir($seedDir, 0775, true);
@mkdir($viewsDir, 0775, true);

require $legacy . '/includes/departments-data.php';
$oricMap = [
    'anatomy' => 4, 'physiology' => 5, 'biochemistry' => 3, 'pathology' => 8,
    'pharmacology' => 5010, 'forensic-medicine' => 5011, 'chs' => 5007, 'dhpe' => 11,
    'medicine' => 1, 'surgery' => 5001, 'gynaecology' => 5003, 'paediatrics' => 5002,
    'ent' => 5020, 'ophthalmology' => 5030, 'orthopaedics' => 5027, 'radiology' => 5004,
    'psychiatry' => 9, 'cardiology' => 5035,
];
$deptsOut = [];
foreach ($academic_departments as $slug => $dept) {
    $deptsOut[] = [
        'slug' => $slug,
        'name' => $dept['name'] ?? $slug,
        'icon' => $dept['icon'] ?? null,
        'group_name' => $dept['group'] ?? 'Other',
        'hod' => $dept['hod'] ?? null,
        'intro' => $dept['intro'] ?? [],
        'faculty' => $dept['faculty'] ?? [],
        'activities' => $dept['activities'] ?? [],
        'hidden' => !empty($dept['hidden']) ? 1 : 0,
        'oric_id' => $oricMap[$slug] ?? null,
        'content_updated_at' => $dept['updated'] ?? '2026-08-09',
    ];
}
file_put_contents($seedDir . '/departments.json', json_encode($deptsOut, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo 'departments: ' . count($deptsOut) . PHP_EOL;

$profilesPath = $root . '/public/assets/data/faculty-profiles.json';
$pack = json_decode((string) file_get_contents($profilesPath), true) ?: [];
file_put_contents($seedDir . '/faculty-profiles.json', json_encode($pack, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo 'faculty profiles: ' . count($pack['profiles'] ?? []) . PHP_EOL;

$seoPages = require $legacy . '/includes/seo-pages.php';
$pagesOut = [];
foreach ($seoPages as $slug => $meta) {
    $viewKey = $slug === '' ? 'home' : $slug;
    $pagesOut[] = [
        'slug' => $slug === '' ? 'home' : $slug,
        'title' => $meta['title'] ?? $viewKey,
        'label' => $meta['label'] ?? null,
        'section' => $meta['section'] ?? null,
        'description' => $meta['description'] ?? null,
        'view_key' => $viewKey,
        'seo' => $meta,
        'status' => 'published',
        'priority' => $meta['priority'] ?? '0.5',
        'changefreq' => $meta['changefreq'] ?? 'monthly',
    ];
}
file_put_contents($seedDir . '/pages.json', json_encode($pagesOut, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo 'pages: ' . count($pagesOut) . PHP_EOL;

$nlFile = (string) file_get_contents($legacy . '/root-php/newsletter.php');
$newsletters = [];
if (preg_match('/\$newsletters_all\s*=\s*(\[[\s\S]*?\n\]);/m', $nlFile, $m)) {
    $arr = null;
    eval('$arr = ' . $m[1] . ';');
    if (is_array($arr)) {
        $newsletters = $arr;
    }
}
file_put_contents($seedDir . '/newsletters.json', json_encode($newsletters, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo 'newsletters: ' . count($newsletters) . PHP_EOL;

$skip = [
    'faculty-proxy.php', 'faculty-all.php', 'faculty-all copy.php', 'faculty_api.php',
    'robots.php', 'sitemap.php', 'medical-education.php', 'portal_login.php',
    'department.php', 'departments.php', 'department-activity.php',
    'faculty.php', 'faculty-profile.php',
];

$converted = 0;
foreach (glob($legacy . '/root-php/*.php') ?: [] as $file) {
    $base = basename($file);
    if (in_array($base, $skip, true)) {
        continue;
    }
    $raw = (string) file_get_contents($file);

    // Drop pure PHP preamble through first closing tag if it only sets vars / includes header
    $body = $raw;
    // Remove header/footer includes
    $body = preg_replace('/<\?php\s+include\(\s*[\'"]includes\/header\.php[\'"]\s*\);\s*\?>/i', '', $body) ?? $body;
    $body = preg_replace('/<\?php\s+include\(\s*[\'"]includes\/footer\.php[\'"]\s*\);\s*\?>/i', '', $body) ?? $body;
    $body = preg_replace(
        '/<\?php\s+include\(\s*[\'"]includes\/sidebar\.php[\'"]\s*\);\s*\?>/i',
        '<?= $this->include(\'partials/sidebar\') ?>',
        $body
    ) ?? $body;

    // Strip leading PHP variable blocks (keep executable PHP later in file)
    if (preg_match('/^<\?php\b/', ltrim($body))) {
        // Keep PHP that is more than includes/vars if it has substantial logic — for home etc.
        // Replace include paths for departments-data / faculty-lib with no-ops for view-only pages
        $body = preg_replace(
            '/require_once\s+__DIR__\s*\.\s*[\'"]\/includes\/(departments-data|faculty-lib)\.php[\'"]\s*;/',
            '// legacy require removed in CI4 view',
            $body
        ) ?? $body;
    }

    // Rewrite .php links to site_url()
    $body = preg_replace_callback(
        '/\bhref=("|\')(?:\.\/)?([a-z0-9\-]+)\.php(\?[^"\']*)?(#[^"\']*)?\1/i',
        static function ($m) {
            $q = $m[1];
            $slug = $m[2];
            $query = $m[3] ?? '';
            $hash = $m[4] ?? '';
            if ($slug === 'index') {
                return 'href=' . $q . '<?= site_url() ?>' . $hash . $q;
            }
            if ($query !== '') {
                // keep query string for department?slug= etc
                $qs = ltrim($query, '?');
                return 'href=' . $q . '<?= site_url(\'' . $slug . '?' . $qs . '\') ?>' . $hash . $q;
            }
            return 'href=' . $q . '<?= site_url(\'' . $slug . '\') ?>' . $hash . $q;
        },
        $body
    ) ?? $body;

    // Rewrite assets/ paths
    $body = preg_replace_callback(
        '/\b(src|href|poster)=("|\')assets\/([^"\']+)\2/i',
        static function ($m) {
            return $m[1] . '=' . $m[2] . '<?= base_url(\'assets/' . addslashes($m[3]) . '\') ?>' . $m[2];
        },
        $body
    ) ?? $body;

    $slug = basename($base, '.php');
    if ($slug === 'index') {
        $slug = 'home';
    }
    $out = "<?php\n/** Auto-ported content body from legacy {$base} */\n?>\n" . $body;
    file_put_contents($viewsDir . '/' . $slug . '.php', $out);
    $converted++;
}
echo 'page views: ' . $converted . PHP_EOL;
echo "Done.\n";
