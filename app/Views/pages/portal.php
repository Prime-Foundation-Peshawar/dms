<?php
/**
 * Portal Login — DMS-themed replacement for https://pmc.prime.edu.pk/portal_login.php
 * Links mirror the live page's active buttons (Student, Faculty, Parents (SIS)).
 */
$portals = [
    [
        'key'   => 'student',
        'icon'  => 'bi-mortarboard-fill',
        'title' => 'Student',
        'desc'  => 'Login Portals',
        'url'   => 'https://prime.edu.pk/sis/login',
        'btn'   => 'Student Login',
    ],
    [
        'key'   => 'faculty',
        'icon'  => 'bi-person-workspace',
        'title' => 'Faculty',
        'desc'  => 'Login Portals',
        'url'   => 'https://www.prime.edu.pk/meis',
        'btn'   => 'Faculty Login',
    ],
    [
        'key'   => 'parent',
        'icon'  => 'bi-people-fill',
        'title' => 'Parents',
        'desc'  => 'Login Portals',
        'url'   => 'https://prime.edu.pk/sis/login/parent',
        'btn'   => 'Parent Login',
    ],
];
?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Riphah International University (Peshawar Campus)</span>
    <h1>Portal Login</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Portal Login</span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">

    <div class="portal-intro fu">
      <!-- <span class="portal-badge"><i class="bi bi-shield-lock-fill"></i> MEIS</span> -->
      <h2 class="sec-title">Portal Logins</h2>
      <p class="portal-lead">
        All portals are listed below
      </p>
    </div>

    <div class="portal-grid">
      <?php foreach ($portals as $i => $p): ?>
        <a class="portal-card portal-card--<?= esc($p['key']) ?> fu fu-delay-<?= $i + 1 ?>"
           href="<?= esc($p['url']) ?>" target="_blank" rel="noopener">
          <span class="portal-card-icon"><i class="bi <?= esc($p['icon']) ?>"></i></span>
          <h3><?= esc($p['title']) ?></h3>
          <p><?= esc($p['desc']) ?></p>
          <span class="portal-card-btn">
            <i class="bi bi-box-arrow-in-right"></i> <?= esc($p['btn']) ?>
            <i class="bi bi-arrow-up-right portal-card-ext" aria-hidden="true"></i>
          </span>
          <span class="visually-hidden">(opens in a new tab)</span>
        </a>
      <?php endforeach; ?>
    </div>


  </div>
</section>

<style>
.portal-intro { text-align: center; max-width: 720px; margin: 0 auto 40px; }
.portal-intro .sec-title { font-size: 1.75rem; margin: 12px 0 10px; }
.portal-lead { font-family: var(--font-body); font-size: 1.05rem; font-weight: 600; color: var(--navy); margin: 0 auto; }
.portal-badge {
  display: inline-flex; align-items: center; gap: 6px;
  font-family: var(--font-head); font-size: .72rem; font-weight: 700;
  letter-spacing: .12em; text-transform: uppercase;
  color: var(--teal); background: var(--teal-pale);
  padding: 6px 14px; border-radius: 999px;
}

.portal-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 24px;
}
.portal-card {
  --accent: var(--teal);
  --accent-pale: var(--teal-pale);
  position: relative; overflow: hidden;
  display: flex; flex-direction: column; align-items: center; text-align: center;
  background: var(--white); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: 36px 26px 28px;
  box-shadow: var(--shadow-sm); color: var(--text); text-decoration: none;
  transition: transform var(--ease), box-shadow var(--ease), border-color var(--ease);
}
.portal-card::before {
  content: ""; position: absolute; inset: 0 0 auto 0; height: 4px; background: var(--accent);
}
.portal-card--faculty { --accent: var(--navy); --accent-pale: var(--gray-light); }
.portal-card--parent  { --accent: var(--gold); --accent-pale: var(--gold-pale); }
.portal-card:hover, .portal-card:focus-visible {
  transform: translateY(-6px); box-shadow: var(--shadow-lg);
  border-color: var(--accent); color: var(--text);
}
.portal-card:focus-visible { outline: 3px solid var(--accent); outline-offset: 3px; }

.portal-card-icon {
  width: 72px; height: 72px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  background: var(--accent-pale); color: var(--accent); font-size: 1.9rem;
  margin-bottom: 18px; transition: background var(--ease), color var(--ease);
}
.portal-card:hover .portal-card-icon { background: var(--accent); color: var(--white); }
.portal-card h3 {
  font-family: var(--font-head); font-size: 1.15rem; font-weight: 800;
  color: var(--navy); margin: 0 0 8px;
}
.portal-card p {
  font-family: var(--font-body); font-size: .9rem; line-height: 1.6;
  color: var(--gray-mid); margin: 0 0 22px; flex: 1;
}
.portal-card-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%;
  font-family: var(--font-head); font-size: .85rem; font-weight: 700;
  padding: 12px 16px; border-radius: var(--r-sm);
  background: var(--accent); color: var(--white);
}
.portal-card-ext { font-size: .75rem; opacity: .75; }


@media (max-width: 991px) {
  .portal-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 575px) {
  .portal-grid { grid-template-columns: 1fr; }
  .portal-intro .sec-title { font-size: 1.4rem; }
}
</style>
