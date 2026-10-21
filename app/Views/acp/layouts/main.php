<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'ACP') ?> · DMS Admin</title>
  <link rel="icon" type="image/jpeg" href="<?= base_url('assets/images/logo/favicon-logo.jpg') ?>" sizes="32x32">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo/favicon-logo.jpg') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/acp.css') ?>?v=<?= @filemtime(FCPATH . 'assets/css/acp.css') ?: time() ?>" rel="stylesheet">
</head>
<body class="acp-body">
<?php
$uri = uri_string();
$navGroups = [
    'Overview' => [
        ['Dashboard', 'acp', 'bi-speedometer2'],
    ],
    'Content' => [
        ['Pages', 'acp/pages', 'bi-file-earmark-text'],
        ['News', 'acp/news', 'bi-newspaper'],
        ['Events', 'acp/events', 'bi-calendar-event'],
    ],
    'Site modules' => [
        ['Gallery', 'acp/gallery', 'bi-images'],
        ['Vacant seats', 'acp/vacant-seats', 'bi-person-plus'],
    ],
    'Catalog' => [
        ['Departments', 'acp/departments', 'bi-building'],
        ['Faculty', 'acp/faculty', 'bi-people'],
        ['Faculty updates', 'acp/faculty/submissions', 'bi-inbox'],
        ['Activity submissions', 'acp/activities/submissions', 'bi-clipboard-check'],
    ],
    'System' => [
        ['Settings', 'acp/settings', 'bi-gear'],
        ['Users', 'acp/users', 'bi-person-gear'],
    ],
];
?>
<div class="acp-shell">
  <aside class="acp-side">
    <div class="brand">
      <div class="brand-mark"><span><i class="bi bi-heart-pulse"></i></span> DMS · ACP</div>
      <p class="brand-sub">Medical Sciences</p>
    </div>
    <?php foreach ($navGroups as $group => $items): ?>
      <div class="acp-nav-label"><?= esc($group) ?></div>
      <?php foreach ($items as [$label, $path, $icon]): ?>
        <?php
          $active = ($path === 'acp' && ($uri === 'acp' || $uri === 'acp/'))
              || ($path === 'acp/faculty' && str_starts_with($uri, 'acp/faculty') && ! str_starts_with($uri, 'acp/faculty/submissions'))
              || ($path === 'acp/faculty/submissions' && str_starts_with($uri, 'acp/faculty/submissions'))
              || ($path === 'acp/activities/submissions' && str_starts_with($uri, 'acp/activities/submissions'))
              || ($path !== 'acp' && $path !== 'acp/faculty' && $path !== 'acp/faculty/submissions' && $path !== 'acp/activities/submissions' && str_starts_with($uri, $path));
        ?>
        <a href="<?= site_url($path) ?>" class="acp-nav <?= $active ? 'active' : '' ?>">
          <i class="bi <?= $icon ?>"></i><?= esc($label) ?>
        </a>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <div class="acp-side-foot">
      <a href="<?= site_url('/') ?>" target="_blank" class="acp-nav"><i class="bi bi-box-arrow-up-right"></i>View site</a>
      <a href="<?= site_url('acp/logout') ?>" class="acp-nav"><i class="bi bi-box-arrow-right"></i>Logout</a>
    </div>
  </aside>
  <div class="acp-main">
    <div class="acp-top">
      <h1><?= esc($title ?? 'ACP') ?></h1>
      <span class="acp-user-chip"><i class="bi bi-person-circle"></i><?= esc($acp_user_name ?? 'Admin') ?></span>
    </div>
    <div class="acp-content">
      <?php if ($msg = session()->getFlashdata('success')): ?>
        <div class="acp-flash acp-flash-success"><?= esc($msg) ?></div>
      <?php endif; ?>
      <?php if ($msg = session()->getFlashdata('error')): ?>
        <div class="acp-flash acp-flash-error"><?= esc($msg) ?></div>
      <?php endif; ?>
      <?= $this->renderSection('content') ?>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js"></script>
<script src="<?= base_url('assets/js/acp.js') ?>?v=<?= @filemtime(FCPATH . 'assets/js/acp.js') ?: time() ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
