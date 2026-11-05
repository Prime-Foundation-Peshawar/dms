<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= esc(dms_asset('assets/css/career-slider.css'), 'attr') ?>">
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Careers</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url('/') ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right" aria-hidden="true"></i></span>
      <span class="current">Careers</span>
    </div>
  </div>
</div>
<section class="pmc-section bg-off">
  <div class="container">
    <div class="row align-items-end mb-4 mb-lg-5">
      <div class="col-lg-8">
        <span class="sec-eyebrow">Join Our Team</span>
        <h2 class="sec-title">Career Opportunities</h2>
        <p class="sec-desc mb-0">Explore faculty and staff opportunities at Peshawar Dental College, Riphah International University &ndash; Peshawar Campus. The latest vacancies appear first.</p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="https://careers.riphahpsh.edu.pk" target="_blank" rel="noopener" class="btn-pmc btn-pmc-outline"><i class="bi bi-briefcase"></i> Career Portal</a>
      </div>
    </div>
    <?php if (!empty($jobs)): ?>
      <p class="small text-muted mb-4"><?= count($jobs) ?> <?= count($jobs) === 1 ? 'opportunity' : 'opportunities' ?></p>
      <div class="row g-4">
        <?php foreach ($jobs as $job): ?>
          <?= view('pages/partials/job_card', ['job' => $job]) ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-light border" role="status">
        <i class="bi bi-briefcase me-2" aria-hidden="true"></i>
        <?= !empty($jobs_unavailable) ? 'Vacancies are temporarily unavailable. Please visit the Career Portal or try again later.' : 'There are no vacancies at the moment. Please check back for new opportunities.' ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?= $this->endSection() ?>
