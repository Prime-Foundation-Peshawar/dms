<?php
require_once __DIR__ . '/includes/departments-data.php';
$groups = academic_department_groups($academic_departments);
include('includes/header.php');
?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department of Medical Sciences</span>
    <h1>Academic Departments</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
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
          <h3><?= htmlspecialchars($groupName) ?></h3>
        </div>
        <div class="row g-3">
          <?php foreach ($depts as $slug => $dept):
            $facultyCount = count($dept['faculty'] ?? []);
            $activityCount = count($dept['activities'] ?? []);
            $updatedLabel = department_updated_label($dept);
          ?>
            <div class="col-md-6 col-lg-4">
              <a href="department.php?slug=<?= urlencode($slug) ?>" class="dept-index-card">
                <div class="dept-index-ico"><i class="bi <?= htmlspecialchars($dept['icon']) ?>"></i></div>
                <div class="dept-index-main">
                  <h4><?= htmlspecialchars($dept['name']) ?></h4>
                  <div class="dept-index-meta">
                    <span><i class="bi bi-people"></i> <?= (int)$facultyCount ?> faculty</span>
                    <span><i class="bi bi-calendar-event"></i> <?= (int)$activityCount ?> activities</span>
                    <span class="dept-index-updated"><i class="bi bi-clock-history"></i> <?= htmlspecialchars($updatedLabel) ?></span>
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

<?php include('includes/footer.php'); ?>
