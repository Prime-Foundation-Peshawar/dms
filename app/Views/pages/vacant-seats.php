<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Vacant Seats (Migration)</h1>
    <div class="breadcrumb-pmc"><a href="./">Home</a><span class="sep"><i
          class="bi bi-chevron-right"></i></span><span class="current">Vacant Seats â€“ Migration</span></div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content fu">

          <?php if ($alertHtml !== ''): ?>
          <div class="alert mb-4"
            style="background:linear-gradient(135deg,#fff3e0,#fff);border:1px solid rgba(230,81,0,.25);border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:14px;">
            <i class="bi bi-exclamation-triangle-fill" style="color:#e65100;font-size:1.5rem;flex-shrink:0;"></i>
            <span style="font-family:var(--font-body);font-size:.9rem;color:var(--gray-dark);line-height:1.6;">
              <?= $alertHtml ?>
            </span>
          </div>
          <?php endif; ?>

          <h2 class="sec-title" style="font-size:1.6rem;">Vacant Seats in Riphah International University - Peshawar
            Campus</h2>

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
                <?php if (empty($seats)): ?>
                  <tr><td colspan="3" style="padding:13px;">No vacant seats published.</td></tr>
                <?php else: ?>
                  <?php foreach ($seats as $i => $seat): ?>
                    <?php $n = (int) ($seat['seats'] ?? 0); $alt = $i % 2 === 1; ?>
                    <tr<?= $alt ? ' style="background:#f9fafb;"' : '' ?>>
                      <td style="padding:13px;font-weight:600;"><?= htmlspecialchars($seat['programme'] ?? '') ?></td>
                      <td style="padding:13px;"><?= htmlspecialchars($seat['session_label'] ?? '') ?></td>
                      <td style="padding:13px;text-align:center;font-weight:700;<?= $n > 0 ? 'color:#2e7d32;font-size:1.1rem;' : 'color:var(--gray-dark);' ?>"><?= $n ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <h2 class="sec-title" style="font-size:1.5rem;">Instructions for Migration on Vacants</h2>
          <div class="pmc-card mb-4"
            style="border-radius:12px;background:#f4f7f9;border:1px solid var(--border-color);padding:22px 24px;">
            <?php if ($introHtml !== ''): ?>
            <p style="font-family:var(--font-body);font-size:.88rem;color:var(--gray-dark);margin-bottom:18px;">
              <i class="bi bi-journal-check" style="color:var(--teal);margin-right:6px;"></i>
              <?= $introHtml ?>
            </p>
            <?php endif; ?>

            <?php if (!empty($instructions)): ?>
            <ul style="list-style:none;padding-left:0;margin-bottom:0;">
              <?php foreach ($instructions as $idx => $line): ?>
              <li
                style="display:flex;align-items:flex-start;gap:10px;margin-bottom:<?= $idx === count($instructions) - 1 ? '0' : '13px' ?>;font-family:var(--font-body);font-size:.9rem;color:var(--gray-dark);line-height:1.7;">
                <i class="bi bi-dot" style="font-size:1.6rem;color:var(--navy);line-height:1;flex-shrink:0;"></i>
                <span><?= htmlspecialchars((string) $line) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>

          <?php if ($applyDeadline !== '' || $meritDate !== ''): ?>
          <div class="row g-3 mb-3">
            <?php if ($applyDeadline !== ''): ?>
            <div class="col-md-6">
              <div class="pmc-card text-center"
                style="border-radius:12px;padding:18px;background:#fff;border:1px solid var(--border-color);">
                <div
                  style="font-family:var(--font-body);font-size:.78rem;color:var(--gray-mid);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">
                  Last Date to Apply Online</div>
                <div style="font-family:var(--font-head);font-size:1.4rem;font-weight:900;color:#d32f2f;"><?= htmlspecialchars($applyDeadline) ?></div>
                <?php if ($applyDeadlineNote !== ''): ?>
                <div style="font-size:.75rem;color:var(--gray-mid);"><?= htmlspecialchars($applyDeadlineNote) ?></div>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>
            <?php if ($meritDate !== ''): ?>
            <div class="col-md-6">
              <div class="pmc-card text-center"
                style="border-radius:12px;padding:18px;background:#fff;border:1px solid var(--border-color);">
                <div
                  style="font-family:var(--font-body);font-size:.78rem;color:var(--gray-mid);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">
                  Merit List Display Date</div>
                <div style="font-family:var(--font-head);font-size:1.4rem;font-weight:900;color:var(--navy);"><?= htmlspecialchars($meritDate) ?></div>
                <?php if ($meritDateNote !== ''): ?>
                <div style="font-size:.75rem;color:var(--gray-mid);"><?= htmlspecialchars($meritDateNote) ?></div>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <div class="mt-4">
            <?php if ($applyUrl !== ''): ?>
            <a href="<?= htmlspecialchars($applyUrl) ?>" target="_blank" class="btn-pmc btn-pmc-primary" style="font-size:.95rem;padding:13px 30px;">
              <i class="bi bi-pencil-square"></i> Apply Online for Migration
            </a>
            <?php endif; ?>
            <a href="admissions" class="btn-pmc btn-pmc-outline ms-2" style="font-size:.95rem;padding:13px 30px;">
              <i class="bi bi-arrow-left-circle"></i> Back to Admissions
            </a>
          </div>

        </div>
      </div>
      <?= $this->include('partials/sidebar') ?>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
