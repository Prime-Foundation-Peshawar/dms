<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= esc($title ?? 'ACP') ?> · DMS Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,600;8..60,700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= esc(base_url('assets/css/acp.css')) ?>" rel="stylesheet">
</head>
<body class="acp">
  <aside class="acp-nav" aria-label="ACP navigation">
    <div class="acp-brand">
      <span class="acp-brand-mark">PMC</span>
      <div>
        <strong>DMS ACP</strong>
        <small>Admin Control Panel</small>
      </div>
    </div>
    <nav>
      <a href="<?= site_url('acp') ?>" class="<?= ($nav ?? '') === 'dashboard' ? 'is-active' : '' ?>">Dashboard</a>
      <p class="acp-nav-label">Faculty</p>
      <a href="<?= site_url('acp/faculty/submissions') ?>" class="<?= ($nav ?? '') === 'submissions' ? 'is-active' : '' ?>">Submissions</a>
      <a href="<?= site_url('acp/faculty/profiles') ?>" class="<?= ($nav ?? '') === 'profiles' ? 'is-active' : '' ?>">Live profiles</a>
      <p class="acp-nav-label">Content</p>
      <a href="<?= site_url('acp/modules/news') ?>">News</a>
      <a href="<?= site_url('acp/modules/events') ?>">Events</a>
      <a href="<?= site_url('acp/modules/gallery') ?>">Gallery</a>
      <a href="<?= site_url('acp/departments') ?>" class="<?= ($nav ?? '') === 'departments' ? 'is-active' : '' ?>">Departments</a>
      <a href="<?= site_url('acp/modules/vacant-seats') ?>">Vacant seats</a>
      <a href="<?= site_url('acp/modules/newsletters') ?>">Newsletters</a>
      <a href="<?= site_url('acp/modules/pages') ?>">Pages</a>
    </nav>
    <div class="acp-nav-foot">
      <a href="<?= site_url('/') ?>" target="_blank" rel="noopener">View website</a>
      <a href="<?= site_url('acp/logout') ?>">Sign out</a>
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
        <small><?= esc(session('cms_user_email') ?: '') ?></small>
      </div>
    </header>
    <main class="acp-content">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</body>
</html>
