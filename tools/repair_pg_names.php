<?php
/** Restore empty PG faculty slots. Usage: php tools/repair_pg_names.php */
declare(strict_types=1);

$root = dirname(__DIR__);
$seedPath = $root . '/app/Database/Seeds/data/pages.json';
$pages = json_decode(file_get_contents($seedPath), true, 512, JSON_THROW_ON_ERROR);
$template = file_get_contents($root . '/app/Views/pages/pg-medical-education.php');
$render = static function (string $html): string {
    return preg_replace_callback(
        '/<\?=\s*faculty_profile_link_html\(\s*\'([^\']*)\'\s*(?:,\s*\'([^\']*)\'\s*)?\)\s*\?>/',
        static fn ($m) => '<span>' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . '</span>'
            . (!empty($m[2]) ? '<span class="pg-qual">' . htmlspecialchars($m[2], ENT_QUOTES, 'UTF-8') . '</span>' : ''),
        $html
    );
};
$pairs = [];
$pairs['<strong></strong>'] = '<strong><span>Prof. Dr. Mohsina Haq</span></strong>';
$pairs['Teaching staff of Basic Medical Sciences, under .'] = 'Teaching staff of Basic Medical Sciences, under <span>Prof. Dr. Mohsina Haq</span>.';
preg_match_all('/<h4>[^<]+<\/h4>\s*<ul class="pg-staff-list">[\s\S]*?<\/ul>/', $template, $groups);
foreach ($groups[0] as $group) {
    $blank = preg_replace('/<\?=[\s\S]*?\?>/', '', $group);
    $pairs[$blank] = $render($group);
    $pairs[str_replace("\r\n", "\n", $blank)] = str_replace("\r\n", "\n", $render($group));
}
$quote = static fn (string $s): string => "'" . str_replace("'", "''", $s) . "'";
// Locate each slot by its surrounding markup, then check that it is empty.
// Do not put literal multiline strings in SQL: Git/importers can change line endings.
$sql = "-- Run in the website database. Updates the CMS row, not the PHP view.\n"
    . "-- Whitespace independent; populated names and other page content are preserved.\n";
$compact = static fn (string $expr): string => "REPLACE(REPLACE(REPLACE(REPLACE($expr, CHAR(13), ''), CHAR(10), ''), CHAR(9), ''), ' ', '')";
$slotSql = static function (string $anchor, string $open, string $close, string $expected, string $replacement) use ($quote, $compact): string {
    $anchorPos = 'LOCATE(' . $quote($anchor) . ', body_html)';
    $start = 'LOCATE(' . $quote($open) . ', body_html, ' . $anchorPos . ')';
    $end = 'LOCATE(' . $quote($close) . ', body_html, ' . $start . ')';
    $length = '(' . $end . ' - ' . $start . ' + ' . strlen($close) . ')';
    $slot = 'SUBSTRING(body_html, ' . $start . ', ' . $length . ')';
    $expected = preg_replace('/\s+/', '', $expected);

    return 'UPDATE pages SET body_html = INSERT(body_html, ' . $start . ', ' . $length . ', ' . $quote($replacement) . ")\n"
        . "WHERE slug = 'pg-medical-education' AND $anchorPos > 0 AND $start > 0 AND $end > $start\n"
        . '  AND ' . $compact($slot) . ' = ' . $quote($expected) . ";\n\n";
};
$sql .= $slotSql('pg-director', '<strong', '</strong>', '<strong></strong>', '<strong><span>Prof. Dr. Mohsina Haq</span></strong>');
$intro = 'Teaching staff of Basic Medical Sciences, under';
$introStart = 'LOCATE(' . $quote($intro) . ', body_html)';
$introEnd = "LOCATE('</p>', body_html, $introStart)";
$introLength = "($introEnd - $introStart)";
$sql .= "UPDATE pages SET body_html = INSERT(body_html, $introStart, $introLength, "
    . $quote($intro . ' <span>Prof. Dr. Mohsina Haq</span>.') . ")\n"
    . "WHERE slug = 'pg-medical-education' AND $introStart > 0 AND $introEnd > $introStart\n"
    . '  AND ' . $compact("SUBSTRING(body_html, $introStart, $introLength)") . ' = ' . $quote(str_replace(' ', '', $intro) . '.') . ";\n\n";
foreach ($groups[0] as $group) {
    preg_match('/<h4>[^<]+<\/h4>/', $group, $heading);
    $ul = substr($group, strpos($group, '<ul'));
    $blank = preg_replace('/<\?=[\s\S]*?\?>/', '', $ul);
    $replacement = preg_replace('/\s+</', '<', $render($ul));
    $sql .= $slotSql($heading[0], '<ul', '</ul>', $blank, $replacement);
}
$sql .= "-- A missing row means this is the wrong database or the page has a different slug.\n"
    . "SELECT slug, use_cms, LOCATE('Prof. Dr. Mohsina Haq', body_html) > 0 AS director_present,\n"
    . "       LOCATE('Dr. Nouman Ullah Wazir', body_html) > 0 AS anatomy_present,\n"
    . "       LOCATE('Dr. Zahoor Ullah', body_html) > 0 AS biochemistry_present\n"
    . "FROM pages WHERE slug = 'pg-medical-education';\n";
file_put_contents($root . '/db/changes/20261105_1200_pg_faculty_names.sql', $sql);
$repair = static function (string $html, int &$count) use ($pairs): string {
    $count = 0;
    foreach ($pairs as $before => $after) {
        $parts = preg_split('/\s+/', $before);
        $pattern = '~' . implode('\\s*', array_map(static fn ($s) => preg_quote($s, '~'), $parts)) . '~';
        $html = preg_replace_callback($pattern, static fn () => $after, $html, -1, $n);
        $count += $n;
    }
    return $html;
};
$count = 0;
foreach ($pages as &$page) {
    if ($page['slug'] !== 'pg-medical-education') {
        continue;
    }
    $page['body_html'] = $repair($page['body_html'], $count);
    $text = html_entity_decode(strip_tags($page['body_html']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace("/[ \t]+/", ' ', $text);
    $page['body_text'] = trim(preg_replace("/\n{3,}/", "\n\n", $text));
    echo "Seed slots restored: {$count}\n";
}
unset($page);
file_put_contents($seedPath, json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

define('FCPATH', $root . '/public/');
define('ENVIRONMENT', 'development');
require $root . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootConsole($paths);
$db = db_connect();
$row = $db->table('pages')->where('slug', 'pg-medical-education')->get()->getRowArray();
if (!$row) {
    throw new RuntimeException('PG page not found in database.');
}
$html = $repair($row['body_html'], $count);
if ($count) {
    // Keep a copy so the content edit can be reversed if needed.
    if (!is_file(WRITEPATH . 'cache/pg-medical-education-before-repair.json')) {
        file_put_contents(WRITEPATH . 'cache/pg-medical-education-before-repair.json', json_encode($row, JSON_PRETTY_PRINT));
    }
    $db->table('pages')->where('id', $row['id'])->update([
        'body_html' => $html,
        'body_text' => trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
    ]);
}
echo "Database slots restored: {$count}; CMS enabled: {$row['use_cms']}\n";
if (substr_count($html, 'pg-qual') !== 9 || !str_contains($html, 'Prof. Dr. Mohsina Haq')) {
    throw new RuntimeException('Unexpected PG faculty content after repair.');
}
echo "Verified director and all 12 faculty entries.\n";
$row['body_html'] = $html;
helper(['url', 'hub']);
$output = view('pages/cms_content', ['page' => $row]);
preg_match_all('/faculty_profile_link_html\(\s*\'([^\']+)\'/', $template, $names);
foreach (array_unique($names[1]) as $name) {
    if (!str_contains($output, htmlspecialchars($name, ENT_QUOTES, 'UTF-8'))) {
        throw new RuntimeException('Name missing from CMS output: ' . $name);
    }
}
echo "Verified all names in rendered CMS HTML.\n";
