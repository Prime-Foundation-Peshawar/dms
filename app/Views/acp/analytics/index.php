<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>

<div class="acp-hero-banner">
  <h2><i class="bi bi-graph-up-arrow"></i> Google Analytics workspace</h2>
  <p>Weekly Website Cell review item: website analytics. Link your GA4 measurement ID and keep an MTD snapshot for steering-committee reporting until live Data API sync is enabled.</p>
  <span class="acp-chip"><i class="bi bi-google"></i> GA4 ready</span>
</div>

<div class="acp-grid">
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-activity"></i></div><em>MTD</em></div>
    <div><strong><?= number_format((int) ($row['sessions_mtd'] ?? 0)) ?></strong><span>Sessions</span></div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-people"></i></div><em>MTD</em></div>
    <div><strong><?= number_format((int) ($row['users_mtd'] ?? 0)) ?></strong><span>Users</span></div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-eye"></i></div><em>MTD</em></div>
    <div><strong><?= number_format((int) ($row['pageviews_mtd'] ?? 0)) ?></strong><span>Pageviews</span></div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-arrow-return-left"></i></div><em>Rate</em></div>
    <div><strong><?= esc((string) ($row['bounce_rate'] ?? 0)) ?>%</strong><span>Bounce rate</span></div>
  </div>
</div>

<div class="acp-grid-2" style="margin-top:18px">
  <div class="acp-card">
    <div class="acp-card-head"><h2>GA connection &amp; snapshot</h2></div>
    <form class="acp-form" method="post" action="<?= site_url('acp/analytics') ?>">
      <?= csrf_field() ?>
      <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
        <label>GA4 Measurement ID<input type="text" name="ga_measurement_id" value="<?= esc($row['ga_measurement_id'] ?? '') ?>" placeholder="G-XXXXXXXXXX"></label>
        <label>GA4 Property ID<input type="text" name="ga_property_id" value="<?= esc($row['ga_property_id'] ?? '') ?>" placeholder="properties/123456789"></label>
        <label>Sessions (MTD)<input type="number" name="sessions_mtd" value="<?= esc((string) ($row['sessions_mtd'] ?? 0)) ?>"></label>
        <label>Users (MTD)<input type="number" name="users_mtd" value="<?= esc((string) ($row['users_mtd'] ?? 0)) ?>"></label>
        <label>Pageviews (MTD)<input type="number" name="pageviews_mtd" value="<?= esc((string) ($row['pageviews_mtd'] ?? 0)) ?>"></label>
        <label>Bounce rate %<input type="number" step="0.01" name="bounce_rate" value="<?= esc((string) ($row['bounce_rate'] ?? 0)) ?>"></label>
        <label>Avg session (sec)<input type="number" name="avg_session_sec" value="<?= esc((string) ($row['avg_session_sec'] ?? 0)) ?>"></label>
      </div>
      <?php
        $lines = [];
        foreach (($row['top_pages_json'] ?? []) as $p) {
            $lines[] = ($p['path'] ?? '/') . '|' . (int) ($p['views'] ?? 0);
        }
      ?>
      <label>Top pages <span class="acp-muted">(one per line: path|views)</span>
        <textarea name="top_pages" style="min-height:120px"><?= esc(implode("\n", $lines)) ?></textarea>
      </label>
      <label>Notes<textarea name="notes" style="min-height:80px"><?= esc($row['notes'] ?? '') ?></textarea></label>
      <div class="acp-actions">
        <button class="acp-btn acp-btn-teal" type="submit"><i class="bi bi-save2"></i> Save analytics</button>
        <?php if (!empty($row['ga_measurement_id']) && str_starts_with((string) $row['ga_measurement_id'], 'G-') && $row['ga_measurement_id'] !== 'G-XXXXXXXXXX'): ?>
          <a class="acp-btn" href="https://analytics.google.com/" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Open GA console</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <div class="acp-card">
    <div class="acp-card-head"><h2>Top pages</h2></div>
    <?php if (empty($row['top_pages_json'])): ?>
      <div class="acp-ga-frame">
        <div>
          <i class="bi bi-pie-chart" style="font-size:2rem;color:var(--acp-teal)"></i>
          <p class="acp-muted">Add top pages from GA to populate this panel.</p>
        </div>
      </div>
    <?php else: ?>
      <table class="acp-table">
        <thead><tr><th>Path</th><th>Views</th></tr></thead>
        <tbody>
          <?php foreach ($row['top_pages_json'] as $p): ?>
            <tr>
              <td><code><?= esc($p['path'] ?? '/') ?></code></td>
              <td><?= number_format((int) ($p['views'] ?? 0)) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="acp-spark" aria-hidden="true" style="margin-top:16px">
        <?php foreach ([55, 70, 48, 82, 60, 95, 75, 88, 52, 90, 68, 80] as $i => $h): ?>
          <i style="height:<?= (int) $h ?>%;animation-delay:<?= $i * 0.04 ?>s"></i>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <h3 style="margin:20px 0 8px;font-size:.95rem">Weekly analytics agenda</h3>
    <ul class="acp-checklist">
      <li><i class="bi bi-1-circle"></i><span>Review sessions / users vs last week</span></li>
      <li><i class="bi bi-2-circle"></i><span>Check admissions &amp; vacant-seats funnel pages</span></li>
      <li><i class="bi bi-3-circle"></i><span>Note anomalous bounce spikes or broken URLs</span></li>
      <li><i class="bi bi-4-circle"></i><span>Feed insights into monthly Website Cell report</span></li>
    </ul>
  </div>
</div>
<?= $this->endSection() ?>
