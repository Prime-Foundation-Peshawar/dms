<?php
/**
 * Shared shell for cms_pages rows (set $cmsPage before include).
 */
if (empty($cmsPage) || !is_array($cmsPage)) {
    echo 'Page not found.';
    return;
}

$heroTitle = (string) ($cmsPage['hero_title'] ?? $cmsPage['title'] ?? 'Page');
$crumb = (string) ($cmsPage['breadcrumb_label'] ?? $heroTitle);
$bodyHtml = (string) ($cmsPage['body_html'] ?? '');
$showSidebar = !empty($cmsPage['show_sidebar']);

if (!empty($cmsPage['meta_title'])) {
    $page_title = (string) $cmsPage['meta_title'];
}
if (!empty($cmsPage['meta_description'])) {
    $page_description = (string) $cmsPage['meta_description'];
}

include __DIR__ . '/includes/header.php';
?>
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1><?= htmlspecialchars($heroTitle) ?></h1>
    <div class="breadcrumb-pmc">
      <a href="./">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= htmlspecialchars($crumb) ?></span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="<?= $showSidebar ? 'col-lg-8' : 'col-lg-12' ?>">
        <div class="page-content fu">
          <?= $bodyHtml ?>
        </div>
      </div>
      <?php if ($showSidebar): ?>
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
