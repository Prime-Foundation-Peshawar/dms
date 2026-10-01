<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

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
        <?= $this->include('partials/sidebar') ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
