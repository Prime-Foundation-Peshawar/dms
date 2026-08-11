<?php
require_once __DIR__ . '/includes/departments-data.php';

$slug = isset($_GET['slug']) ? strtolower(trim($_GET['slug'])) : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
$payload = ($slug !== '' && $id >= 0) ? get_department_activity($slug, $id) : null;

if (!$payload) {
  header('Location: ' . ($slug !== '' ? 'department.php?slug=' . urlencode($slug) . '#activities' : 'departments.php'));
  exit;
}

$dept = $payload['dept'];
$activity = $payload['activity'];
$title = trim($activity['title'] ?? 'Department Activity');
$date = trim($activity['date'] ?? 'TBA');
$text = trim($activity['text'] ?? '');
$details = trim($activity['details'] ?? '');
$body = $details !== '' ? $details : $text;

$page_title = $title . ' | ' . ($dept['name'] ?? 'Department') . ' | DMS';
include('includes/header.php');
?>

<div class="page-hero dept-page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department Activity</span>
    <h1><?= htmlspecialchars($title) ?></h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="departments.php">Departments</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="department.php?slug=<?= urlencode($slug) ?>"><?= htmlspecialchars($dept['name']) ?></a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Activity</span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content">
          <div class="about-block fu">
            <div class="about-block-head">
              <h3><?= htmlspecialchars($title) ?></h3>
              <p><?= htmlspecialchars($dept['name'] ?? 'Academic Department') ?> · Department of Medical Sciences</p>
            </div>
            <div class="dept-activity-detail-meta">
              <span><i class="bi bi-calendar3"></i> <?= htmlspecialchars($date !== '' ? $date : 'TBA') ?></span>
              <span><i class="bi bi-building"></i> <?= htmlspecialchars($dept['name'] ?? 'Department') ?></span>
            </div>
            <?php if ($body !== ''): ?>
              <p class="dept-activity-detail-text"><?= nl2br(htmlspecialchars($body)) ?></p>
            <?php else: ?>
              <p class="dept-activity-detail-text">More details for this activity will be published soon.</p>
            <?php endif; ?>
            <div class="about-cta-row">
              <a href="department.php?slug=<?= urlencode($slug) ?>#activities" class="btn-pmc btn-pmc-primary"><i class="bi bi-arrow-left"></i> Back to Activities</a>
              <a href="department.php?slug=<?= urlencode($slug) ?>" class="btn-pmc btn-pmc-outline"><i class="bi bi-diagram-3"></i> Department Page</a>
            </div>
          </div>
        </div>
      </div>
      <?php include('includes/sidebar.php'); ?>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
