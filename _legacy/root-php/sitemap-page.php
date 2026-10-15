<?php include('includes/header.php'); ?>
<style>
.sitemap-list { list-style: none; padding: 0; margin: 0; }
.sitemap-list li { margin-bottom: 10px; }
.sitemap-list a {
  font-family: var(--font-body);
  font-size: .9rem;
  font-weight: 500;
  color: var(--navy);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 6px;
}
.sitemap-list a::before { content: '›'; color: var(--teal); }
.sitemap-list a:hover { color: var(--teal); }
</style>
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department of Medical Sciences</span>
    <h1>Sitemap</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= htmlspecialchars(base_url) ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Sitemap</span>
    </div>
  </div>
</div>
<section class="pmc-section">
  <div class="container">
    <div class="page-content">
      <p>Browse public pages of Peshawar Medical College and the Department of Medical Sciences.</p>
      <div class="row g-4">
        <?php
        $section_order = ['College', 'Academics', 'News'];
        $grouped = [];
        foreach (seo_pages() as $slug => $meta) {
          if ($slug === 'sitemap-page' || (isset($meta['in_sitemap']) && $meta['in_sitemap'] === false)) {
            continue;
          }
          if (!is_file(seo_page_filepath($slug))) {
            continue;
          }
          $grouped[$meta['section'] ?? 'College'][] = [
            'url' => seo_page_url($slug, $meta),
            'label' => $meta['label'] ?? $slug,
          ];
        }
        foreach ($section_order as $section):
          if (empty($grouped[$section])) continue;
        ?>
        <div class="col-md-4">
          <h2 class="sec-title" style="font-size:1.15rem;"><?= htmlspecialchars($section) ?></h2>
          <ul class="sitemap-list">
            <?php foreach ($grouped[$section] as $item): ?>
            <li><a href="<?= htmlspecialchars($item['url']) ?>"><?= htmlspecialchars($item['label']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php include('includes/footer.php'); ?>
