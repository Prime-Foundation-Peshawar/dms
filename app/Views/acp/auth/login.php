<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>Sign in · DMS ACP</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= esc(base_url('assets/css/acp.css')) ?>" rel="stylesheet">
</head>
<body>
  <div class="acp-login">
    <div class="acp-login-card">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
        <span class="acp-brand-mark" style="width:48px;height:48px">PMC</span>
        <div>
          <strong style="display:block">RCP Website Cell</strong>
          <small class="acp-muted">Department of Medical Sciences</small>
        </div>
      </div>
      <h1>Welcome back</h1>
      <p>Sign in to manage content, review faculty profiles, analytics, and monthly KPI reports.</p>
      <?php if (!empty($error)): ?>
        <div class="acp-alert acp-alert-err"><?= esc($error) ?></div>
      <?php endif; ?>
      <form class="acp-form" method="post" action="<?= site_url('acp/login') ?>">
        <?= csrf_field() ?>
        <label>Email
          <input type="email" name="email" required autocomplete="username" placeholder="admin@riphahpsh.edu.pk">
        </label>
        <label>Password
          <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button class="acp-btn acp-btn-teal" type="submit" style="width:100%"><i class="bi bi-shield-lock"></i> Sign in</button>
      </form>
    </div>
  </div>
</body>
</html>
