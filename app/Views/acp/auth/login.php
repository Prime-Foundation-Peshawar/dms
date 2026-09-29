<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>Sign in · DMS ACP</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="<?= esc(base_url('assets/css/acp.css')) ?>" rel="stylesheet">
</head>
<body>
  <div class="acp-login">
    <div class="acp-login-card">
      <h1>DMS ACP</h1>
      <p>Sign in to manage website content and faculty profile reviews.</p>
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
        <button class="acp-btn acp-btn-primary" type="submit">Sign in</button>
      </form>
    </div>
  </div>
</body>
</html>
