<?php
$root = dirname(__DIR__);
@mkdir($root . '/app/Views/layouts', 0775, true);
@mkdir($root . '/app/Views/partials', 0775, true);

function fix_urls(string $html): string
{
    $html = preg_replace('/href=("|\')index\.php\1/', 'href="$1<?= site_url() ?>$1', $html) ?? $html;
    $html = preg_replace_callback(
        '/href=("|\')([a-z0-9\-]+)\.php(#[^"\']*)?\1/i',
        static function ($m) {
            return 'href=' . $m[1] . '<?= site_url(\'' . $m[2] . '\') ?>' . ($m[3] ?? '') . $m[1];
        },
        $html
    ) ?? $html;
    $html = preg_replace_callback(
        '/\b(src|href)=("|\')assets\/([^"\']+)\2/i',
        static function ($m) {
            return $m[1] . '=' . $m[2] . '<?= base_url(\'assets/' . addslashes($m[3]) . '\') ?>' . $m[2];
        },
        $html
    ) ?? $html;
    return $html;
}

$header = (string) file_get_contents($root . '/_legacy/includes/header.php');
$footer = (string) file_get_contents($root . '/_legacy/includes/footer.php');
$sidebar = (string) file_get_contents($root . '/_legacy/includes/sidebar.php');

$pos = strpos($header, '<!DOCTYPE');
$headHtml = $pos !== false ? substr($header, $pos) : $header;
$headHtml = fix_urls($headHtml);
$footer = fix_urls($footer);
$sidebar = fix_urls($sidebar);
$headHtml = str_replace("dms_asset('assets/", "dms_asset('", $headHtml);

$pre = <<<'PHP'
<?php
helper('faculty');
$host = strtolower($_SERVER['HTTP_HOST'] ?? '');
$is_staging = ($host === 'staging.riphahpsh.edu.pk' || $host === 'www.staging.riphahpsh.edu.pk');
$hub_base = $is_staging
    ? 'https://staging.riphahpsh.edu.pk/riphahpsh/'
    : 'https://riphahpsh.edu.pk/';
$page_title = $page_title ?? 'Peshawar Medical College';
$page_description = $page_description ?? '';
$canonical_url = $canonical_url ?? current_url();
$og_type = $og_type ?? 'website';
$page_image = $page_image ?? base_url('assets/images/logo/favicon-logo.jpg');
$robots_content = $is_staging ? 'noindex, nofollow' : ($robots ?? '');
if (!defined('GOOGLE_SITE_VERIFICATION')) {
    define('GOOGLE_SITE_VERIFICATION', '');
}
if (!function_exists('seo_json_ld')) {
    function seo_json_ld(array $graph): string {
        return json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
    }
}
$ld_graph = $ld_graph ?? [];
?>
PHP;

$mid = "\n<?= \$this->renderSection('content') ?>\n";
file_put_contents($root . '/app/Views/layouts/main.php', $pre . $headHtml . $mid . $footer);
file_put_contents($root . '/app/Views/partials/sidebar.php', $sidebar);
echo "layout written bytes=" . filesize($root . '/app/Views/layouts/main.php') . PHP_EOL;
