<?php
/** Vacant seats — rendered from settings.vacant_seats */
$vs = is_array($vacant_seats ?? null) ? $vacant_seats : [];
$intro = (string) ($vs['intro'] ?? 'Riphah International University Rule: Applicant having less than 60% marks in any subject/paper shall not be eligible for migration.');
$heading = (string) ($vs['heading'] ?? 'Vacant Seats in Riphah International University - Peshawar Campus');
$rows = is_array($vs['rows'] ?? null) ? $vs['rows'] : [];
$notices = is_array($vs['notices'] ?? null) ? $vs['notices'] : [];
$lastDate = (string) ($vs['last_date'] ?? '');
$applyUrl = (string) ($vs['apply_url'] ?? '');
$applyLabel = (string) ($vs['apply_label'] ?? 'Apply Online for Migration');
?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Vacant Seats (Migration)</h1>
    <div class="breadcrumb-pmc"><a href="<?= site_url() ?>">Home</a><span class="sep"><i
          class="bi bi-chevron-right"></i></span><span class="current">Vacant Seats – Migration</span></div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content fu">

          <?php if ($intro !== ''): ?>
          <div class="alert mb-4"
            style="background:linear-gradient(135deg,#fff3e0,#fff);border:1px solid rgba(230,81,0,.25);border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
            <i class="bi bi-exclamation-triangle-fill" style="color:#e65100;font-size:1.5rem;flex-shrink:0;"></i>
            <span style="font-family:var(--font-body);font-size:.9rem;color:var(--gray-dark);line-height:1.6;">
              <?= esc($intro) ?>
            </span>
          </div>
          <?php endif; ?>

          <h2 class="sec-title" style="font-size:1.6rem;"><?= esc($heading) ?></h2>

          <div class="table-responsive mb-4">
            <table class="table table-bordered info-table" style="background:#fff;">
              <thead style="background:var(--navy);color:white;">
                <tr>
                  <th style="padding:14px;">Programme / Year</th>
                  <th style="padding:14px;">Session</th>
                  <th style="padding:14px;text-align:center;">Vacant Seats</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($rows): ?>
                  <?php foreach ($rows as $i => $row): ?>
                <tr<?= $i % 2 ? ' style="background:#f9fafb;"' : '' ?>>
                  <td style="padding:13px;font-weight:600;"><?= esc($row['year'] ?? '') ?></td>
                  <td style="padding:13px;"><?= esc($row['session'] ?? '') ?></td>
                  <td style="padding:13px;text-align:center;font-weight:700;color:var(--gray-dark);"><?= esc($row['seats'] ?? '0') ?></td>
                </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                <tr><td colspan="3" style="padding:13px;text-align:center;">No vacant seat data published yet.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <?php if ($notices): ?>
          <h2 class="sec-title" style="font-size:1.5rem;">INSTRUCTIONS FOR MIGRATION ON VACANTS</h2>
          <div class="pmc-card mb-4"
            style="border-radius:12px;background:#f4f7f9;border:1px solid var(--border-color);padding:22px 24px;">
            <ul style="list-style:none;padding-left:0;margin-bottom:0;">
              <?php foreach ($notices as $n): ?>
              <li
                style="display:flex;align-items:flex-start;gap:10px;margin-bottom:13px;font-family:var(--font-body);font-size:.9rem;color:var(--gray-dark);line-height:1.7;">
                <i class="bi bi-dot" style="font-size:1.6rem;color:var(--navy);line-height:1;flex-shrink:0;"></i>
                <span><?= esc($n) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <?php if ($lastDate !== ''): ?>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <div class="pmc-card text-center"
                style="border-radius:12px;padding:18px;background:#fff;border:1px solid var(--border-color);">
                <div
                  style="font-family:var(--font-body);font-size:.78rem;color:var(--gray-mid);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">
                  Last Date to Apply Online</div>
                <div style="font-family:var(--font-head);font-size:1.4rem;font-weight:900;color:#d32f2f;"><?= esc($lastDate) ?></div>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <div class="mt-4">
            <?php if ($applyUrl !== ''): ?>
            <a href="<?= esc($applyUrl) ?>" target="_blank" class="btn-pmc btn-pmc-primary" style="font-size:.95rem;padding:13px 30px;">
              <i class="bi bi-pencil-square"></i> <?= esc($applyLabel) ?>
            </a>
            <?php endif; ?>
            <a href="<?= site_url('admissions') ?>" class="btn-pmc btn-pmc-outline ms-2" style="font-size:.95rem;padding:13px 30px;">
              <i class="bi bi-arrow-left-circle"></i> Back to Admissions
            </a>
          </div>

        </div>
      </div>
      <?= $this->include('partials/sidebar') ?>
    </div>
  </div>
</section>
