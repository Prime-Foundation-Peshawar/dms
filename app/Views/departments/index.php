<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department of Medical Sciences</span>
    <h1>Academic Departments</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Departments</span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="home-sec-head text-center fu mb-5">
      <span class="sec-eyebrow">PMC</span>
      <h2 class="sec-title">Explore Our Departments</h2>
      <p class="sec-desc">Browse academic departments. Each page covers introduction, faculty, publications, and departmental activities.</p>
    </div>

    <?php foreach ($groups as $groupName => $depts): ?>
      <div class="dept-group fu mb-5">
        <div class="dept-group-head">
          <h3><?= esc($groupName) ?></h3>
        </div>
        <div class="row g-3">
          <?php foreach ($depts as $slug => $dept):
            $facultyCount = $counts[$slug] ?? 0;
            $activityCount = count($dept['activities'] ?? []);
            $updated = !empty($dept['content_updated_at']) ? date('j M Y', strtotime($dept['content_updated_at'])) : '';
          ?>
            <div class="col-md-6 col-lg-4">
              <a href="<?= site_url('department?slug=' . rawurlencode($slug)) ?>" class="dept-index-card">
                <div class="dept-index-ico"><i class="bi <?= esc($dept['icon'] ?? 'bi-building') ?>"></i></div>
                <div class="dept-index-main">
                  <h4><?= esc($dept['name']) ?></h4>
                  <div class="dept-index-meta">
                    <span><i class="bi bi-people"></i> <?= (int) $facultyCount ?> faculty</span>
                    <span><i class="bi bi-calendar-event"></i> <?= (int) $activityCount ?> activities</span>
                    <?php if ($updated !== ''): ?>
                      <span class="dept-index-updated"><i class="bi bi-clock-history"></i> <?= esc($updated) ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <i class="bi bi-arrow-right dept-index-arrow"></i>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?= $this->endSection() ?>
