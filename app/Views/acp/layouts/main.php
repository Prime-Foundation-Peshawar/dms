<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= esc($title ?? 'ACP') ?> · DMS Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= esc(base_url('assets/css/acp.css')) ?>" rel="stylesheet">
</head>
<body class="acp">
  <aside class="acp-nav" aria-label="ACP navigation">
    <div class="acp-brand">
      <span class="acp-brand-mark">PMC</span>
      <div>
        <strong>DMS ACP</strong>
        <small>RCP Website Cell</small>
      </div>
    </div>
    <nav>
      <a href="<?= site_url('acp') ?>" class="<?= ($nav ?? '') === 'dashboard' ? 'is-active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="<?= site_url('acp/analytics') ?>" class="<?= ($nav ?? '') === 'analytics' ? 'is-active' : '' ?>"><i class="bi bi-graph-up-arrow"></i> Analytics · GA</a>
      <a href="<?= site_url('acp/reports') ?>" class="<?= ($nav ?? '') === 'reports' ? 'is-active' : '' ?>"><i class="bi bi-clipboard2-data"></i> Reports &amp; KPIs</a>

      <p class="acp-nav-label">Faculty</p>
      <a href="<?= site_url('acp/faculty/submissions') ?>" class="<?= ($nav ?? '') === 'submissions' ? 'is-active' : '' ?>"><i class="bi bi-inbox"></i> Submissions</a>
      <a href="<?= site_url('acp/faculty/profiles') ?>" class="<?= ($nav ?? '') === 'profiles' ? 'is-active' : '' ?>"><i class="bi bi-people"></i> Live profiles</a>

      <p class="acp-nav-label">Content</p>
      <a href="<?= site_url('acp/news') ?>" class="<?= ($nav ?? '') === 'news' ? 'is-active' : '' ?>"><i class="bi bi-newspaper"></i> News</a>
      <a href="<?= site_url('acp/events') ?>" class="<?= ($nav ?? '') === 'events' ? 'is-active' : '' ?>"><i class="bi bi-calendar-event"></i> Events</a>
      <a href="<?= site_url('acp/slider') ?>" class="<?= ($nav ?? '') === 'slider' ? 'is-active' : '' ?>"><i class="bi bi-images"></i> Slider</a>
      <a href="<?= site_url('acp/gallery') ?>" class="<?= ($nav ?? '') === 'gallery' ? 'is-active' : '' ?>"><i class="bi bi-camera"></i> Gallery</a>
      <a href="<?= site_url('acp/departments') ?>" class="<?= ($nav ?? '') === 'departments' ? 'is-active' : '' ?>"><i class="bi bi-building"></i> Departments</a>
      <a href="<?= site_url('acp/vacant-seats') ?>" class="<?= ($nav ?? '') === 'vacant-seats' ? 'is-active' : '' ?>"><i class="bi bi-person-bounding-box"></i> Vacant seats</a>
      <a href="<?= site_url('acp/newsletters') ?>" class="<?= ($nav ?? '') === 'newsletters' ? 'is-active' : '' ?>"><i class="bi bi-file-earmark-pdf"></i> Newsletters</a>
      <a href="<?= site_url('acp/pages') ?>" class="<?= ($nav ?? '') === 'pages' ? 'is-active' : '' ?>"><i class="bi bi-file-richtext"></i> Pages</a>
    </nav>
    <div class="acp-nav-foot">
      <a href="<?= site_url('/') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> View website</a>
      <a href="<?= site_url('acp/logout') ?>"><i class="bi bi-box-arrow-left"></i> Sign out</a>
    </div>
  </aside>
  <div class="acp-main">
    <header class="acp-top">
      <div>
        <h1><?= esc($title ?? 'ACP') ?></h1>
        <?php if (!empty($subtitle)): ?>
          <p><?= esc($subtitle) ?></p>
        <?php endif; ?>
      </div>
      <div class="acp-user">
        <span><?= esc(session('cms_user_name') ?: 'Admin') ?></span>
        <small><?= esc(session('cms_user_email') ?: 'Website Cell') ?></small>
      </div>
    </header>
    <main class="acp-content">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</body>
</html>
