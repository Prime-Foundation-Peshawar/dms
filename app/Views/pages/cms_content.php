<?php
/** @var array<string,mixed>|null $page */
$title = $page['title'] ?? ($page_title ?? 'Page');
$label = $page['label'] ?? $title;
$body  = (string) ($page['body_html'] ?? '');
if ($body === '' && ! empty($page['body_text'])) {
    $parts = preg_split("/\n{2,}/", (string) $page['body_text']) ?: [];
    $body  = implode("\n", array_map(
        static fn ($p) => '<p>' . nl2br(esc(trim($p))) . '</p>',
        array_filter(array_map('trim', $parts))
    ));
}

// Prefill from static views often includes its own hero/layout — don't double-wrap.
$hasOwnHero   = str_contains($body, 'page-hero');
$hasOwnLayout = str_contains($body, 'pmc-section') || str_contains($body, 'col-lg-8');

// Seeded CMS HTML often kept col-lg-8 but dropped the PHP sidebar include.
// Inject after the content col-lg-8 (inside pmc-section), never the hero title column.
if ($hasOwnLayout && str_contains($body, 'col-lg-8') && ! str_contains($body, 'sidebar-widget')) {
    $sidebarHtml = view('partials/sidebar');
    $searchFrom = 0;
    $sectionPos = stripos($body, 'pmc-section');
    if ($sectionPos !== false) {
        $searchFrom = $sectionPos;
    }
    if (preg_match('/<div class="col-lg-8\b[^"]*">/i', $body, $m, PREG_OFFSET_CAPTURE, $searchFrom)) {
        $pos   = $m[0][1] + strlen($m[0][0]);
        $depth = 1;
        $len   = strlen($body);
        while ($pos < $len && $depth > 0) {
            $nextOpen  = stripos($body, '<div', $pos);
            $nextClose = stripos($body, '</div>', $pos);
            if ($nextClose === false) {
                break;
            }
            if ($nextOpen !== false && $nextOpen < $nextClose) {
                $depth++;
                $pos = $nextOpen + 4;
                continue;
            }
            $depth--;
            $pos = $nextClose + 6;
        }
        if ($depth === 0) {
            $body = substr($body, 0, $pos) . "\n" . $sidebarHtml . substr($body, $pos);
        }
    }
}
?>
<?php if ($hasOwnLayout || $hasOwnHero): ?>
  <?= $body ?>
<?php else: ?>
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department of Medical Sciences</span>
    <h1><?= esc($label) ?></h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= esc($label) ?></span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content cms-page-body">
          <?= $body ?>
        </div>
      </div>
      <?= $this->include('partials/sidebar') ?>
    </div>
  </div>
</section>
<?php endif; ?>
