<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- ═══ HERO ═══ -->
<section class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Newsletter</h1>
    <nav class="breadcrumb-pmc" aria-label="breadcrumb">
      <a href="./">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Newsletter</span>
    </nav>
  </div>
</section>

<!-- ═══ MAIN CONTENT ═══ -->
<section class="pmc-section bg-off">
  <div class="container">

    <!-- Intro -->
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8 text-center">
        <h2 class="section-heading mb-3">Stay Informed</h2>
        <p class="section-subhead">
          The official newsletter of the <strong>Department of Medical Sciences – Riphah International University (Peshawar Campus)</strong>
          keeps you up to date with admissions, academic achievements, research breakthroughs, and campus life.
          Each issue is available as a downloadable PDF.
        </p>
      </div>
    </div>

    <!-- Newsletter Cards -->
    <div class="row g-4">
      <?php if (empty($newsletters)): ?>
        <div class="col-12 text-center text-muted py-5">No newsletters published yet.</div>
      <?php endif; ?>
      <?php foreach ($newsletters as $issue): ?>
      <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
        <div class="card w-100 border-0 shadow-sm hover-shadow transition rounded-4 overflow-hidden">
          <div class="nl-card-img-container" style="height: 420px; background: #f8f9fa; overflow: hidden;">
            <?php if (!empty($issue['image'])): ?>
              <img src="<?= htmlspecialchars($issue['image']) ?>" alt="<?= htmlspecialchars($issue['title']) ?>"
                   loading="lazy"
                   style="width:100%; height:100%; object-fit: contain; display: block;">
            <?php else: ?>
              <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                <i class="bi bi-file-pdf" style="font-size:3rem;"></i>
              </div>
            <?php endif; ?>
          </div>
          <div class="card-body d-flex flex-column p-4">
            <small class="text-muted mb-2"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($issue['date']) ?></small>
            <h5 class="fw-bold mb-3"><?= htmlspecialchars($issue['title']) ?></h5>
            <a href="<?= htmlspecialchars($issue['pdf']) ?>" target="_blank" rel="noopener" class="btn btn-teal w-100 mt-auto">
              <i class="bi bi-file-earmark-pdf me-2"></i> Open PDF
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1 && $total_items > 0): ?>
    <div class="d-flex justify-content-center mt-5">
      <nav aria-label="Newsletter pages">
        <ul class="pagination">
          <li class="page-item <?= ($current_page == 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $current_page - 1 ?>" <?= ($current_page == 1) ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
              <i class="bi bi-chevron-left"></i>
            </a>
          </li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
          <?php endfor; ?>
          <li class="page-item <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $current_page + 1 ?>" <?= ($current_page == $total_pages) ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
              <i class="bi bi-chevron-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <?php endif; ?>

  </div>
</section>

<?= $this->endSection() ?>
