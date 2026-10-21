<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ACP Login · DMS</title>
  <link rel="icon" type="image/jpeg" href="<?= base_url('assets/images/logo/favicon-logo.jpg') ?>" sizes="32x32">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo/favicon-logo.jpg') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/acp.css') ?>?v=<?= @filemtime(FCPATH . 'assets/css/acp.css') ?: time() ?>" rel="stylesheet">
  <style>
    body.acp-login {
      min-height: 100vh;
      margin: 0;
      display: grid;
      place-items: center;
      font-family: var(--font-body);
      background:
        radial-gradient(1200px 600px at 10% 0%, rgba(42,135,168,.35), transparent 55%),
        radial-gradient(900px 500px at 90% 100%, rgba(196,135,58,.28), transparent 50%),
        linear-gradient(145deg, var(--navy-deep), var(--navy) 45%, var(--teal));
    }
    .login-card {
      width: min(420px, 92vw);
      background: #fff;
      border-radius: 18px;
      padding: 1.75rem 1.6rem;
      box-shadow: 0 18px 50px rgba(7,22,36,.35);
      animation: acpFadeUp .45s ease both;
    }
    .login-card h1 {
      font-family: var(--font-head);
      font-size: 1.35rem;
      color: var(--navy);
      margin: 0;
    }
    .login-accent {
      width: 48px; height: 4px; border-radius: 99px;
      background: linear-gradient(90deg, var(--teal), var(--gold));
      margin: .65rem 0 1rem;
    }
  </style>
</head>
<body class="acp-login">
  <div class="login-card">
    <h1>DMS Admin</h1>
    <div class="login-accent"></div>
    <p class="text-muted small mb-3">Sign in to the Academic Control Panel</p>
    <?php if (! empty($error)): ?>
      <div class="acp-flash acp-flash-error"><?= esc($error) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('acp/login') ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn-acp btn-acp-primary w-100 justify-content-center" type="submit">Sign in</button>
    </form>
  </div>
</body>
</html>
