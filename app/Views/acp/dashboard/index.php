<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>

<div class="acp-hero-banner">
  <h2><i class="bi bi-broadcast-pin"></i> RCP Website Cell · Operations pulse</h2>
  <p>Structured monitoring from the Aug 2026 Website Management protocol: pending submissions, upcoming events, contributor rankings, website analytics, and KPI compliance (≥2 approved updates / month).</p>
  <span class="acp-chip"><i class="bi bi-calendar3"></i> Month <?= esc($monthKey) ?></span>
  <span class="acp-chip"><i class="bi bi-trophy"></i> KPI compliance <?= (int) $kpiRate ?>%</span>
</div>

<div class="acp-grid">
  <div class="acp-card acp-stat">
    <div class="acp-stat-top">
      <div class="acp-stat-ico"><i class="bi bi-inbox"></i></div>
      <em>Queue</em>
    </div>
    <div>
      <strong><?= (int) $pending ?></strong>
      <span>Pending faculty submissions</span>
    </div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top">
      <div class="acp-stat-ico"><i class="bi bi-people"></i></div>
      <em>Live</em>
    </div>
    <div>
      <strong><?= (int) $live ?></strong>
      <span>Live faculty profiles</span>
    </div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top">
      <div class="acp-stat-ico"><i class="bi bi-check2-circle"></i></div>
      <em>OK</em>
    </div>
    <div>
      <strong><?= (int) $approved ?></strong>
      <span>Approved submissions</span>
    </div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top">
      <div class="acp-stat-ico"><i class="bi bi-x-octagon"></i></div>
      <em>Rejected</em>
    </div>
    <div>
      <strong><?= (int) $rejected ?></strong>
      <span>Rejected submissions</span>
    </div>
  </div>
</div>

<h2 class="acp-section-title"><i class="bi bi-layers"></i> Content inventory</h2>
<div class="acp-grid">
  <?php foreach ($contentStats as $stat): ?>
    <div class="acp-card acp-stat">
      <div class="acp-stat-top">
        <div class="acp-stat-ico"><i class="bi <?= esc($stat['icon']) ?>"></i></div>
        <em><?= esc($stat['hint']) ?></em>
      </div>
      <div>
        <strong><?= (int) $stat['value'] ?></strong>
        <span><?= esc($stat['label']) ?></span>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="acp-grid-2" style="margin-top:18px">
  <div class="acp-card">
    <div class="acp-card-head">
      <h2><i class="bi bi-graph-up"></i> Google Analytics · MTD</h2>
      <a class="acp-btn acp-btn-ghost" href="<?= site_url('acp/analytics') ?>">Open GA</a>
    </div>
    <?php $a = $analytics ?? []; ?>
    <div class="acp-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));gap:12px">
      <div>
        <strong style="font-size:1.5rem;font-family:var(--font-display)"><?= number_format((int) ($a['sessions_mtd'] ?? 0)) ?></strong>
        <div class="acp-muted">Sessions</div>
      </div>
      <div>
        <strong style="font-size:1.5rem;font-family:var(--font-display)"><?= number_format((int) ($a['users_mtd'] ?? 0)) ?></strong>
        <div class="acp-muted">Users</div>
      </div>
      <div>
        <strong style="font-size:1.5rem;font-family:var(--font-display)"><?= number_format((int) ($a['pageviews_mtd'] ?? 0)) ?></strong>
        <div class="acp-muted">Pageviews</div>
      </div>
      <div>
        <strong style="font-size:1.5rem;font-family:var(--font-display)"><?= esc((string) ($a['bounce_rate'] ?? 0)) ?>%</strong>
        <div class="acp-muted">Bounce rate</div>
      </div>
    </div>
    <div class="acp-spark" aria-hidden="true">
      <?php foreach ([40, 62, 48, 78, 55, 90, 70, 85, 60, 95, 72, 88] as $i => $h): ?>
        <i style="height:<?= (int) $h ?>%;animation-delay:<?= $i * 0.05 ?>s"></i>
      <?php endforeach; ?>
    </div>
    <p class="acp-muted" style="margin:12px 0 0">Measurement ID: <code><?= esc($a['ga_measurement_id'] ?? '—') ?></code></p>
  </div>

  <div class="acp-card">
    <div class="acp-card-head">
      <h2><i class="bi bi-flag"></i> Monthly KPI pulse</h2>
      <a class="acp-btn acp-btn-ghost" href="<?= site_url('acp/reports') ?>">Full report</a>
    </div>
    <p class="acp-muted" style="margin-top:0">Units meeting ≥2 approved website updates this month.</p>
    <strong style="font-size:2rem;font-family:var(--font-display)"><?= (int) $unitsMeetingKpi ?> / <?= (int) $unitsTotal ?></strong>
    <div class="acp-meter" style="--w: <?= (int) $kpiRate ?>%"><span></span></div>
    <p style="margin:10px 0 0;font-weight:700;color:var(--acp-teal-deep)"><?= (int) $kpiRate ?>% compliance</p>

    <h3 style="margin:18px 0 8px;font-size:.95rem">Weekly review checklist</h3>
    <ul class="acp-checklist">
      <li><i class="bi bi-check2-square"></i><span>Pending submissions — <?= (int) $pending ?> in faculty queue</span></li>
      <li><i class="bi bi-calendar2-week"></i><span>Upcoming events — <?= count($upcomingEvents) ?> scheduled</span></li>
      <li><i class="bi bi-exclamation-triangle"></i><span>Low contributors — <?= count($lowContributors) ?> units flagged</span></li>
      <li><i class="bi bi-bar-chart"></i><span>Website analytics — refresh GA snapshot</span></li>
      <li><i class="bi bi-lightbulb"></i><span>Improvement suggestions — capture in monthly report</span></li>
    </ul>
  </div>
</div>

<div class="acp-grid-2" style="margin-top:18px">
  <div class="acp-card">
    <div class="acp-card-head">
      <h2><i class="bi bi-trophy"></i> Top contributors</h2>
      <span class="acp-badge top">Top</span>
    </div>
    <?php if (empty($topContributors)): ?>
      <p class="acp-muted">No contribution data for this month yet.</p>
    <?php else: ?>
      <?php foreach ($topContributors as $i => $c): ?>
        <div class="acp-rank top">
          <div class="acp-rank-pos"><?= $i + 1 ?></div>
          <div style="flex:1;min-width:0">
            <strong><?= esc($c['unit_name'] ?? '') ?></strong>
            <div class="acp-muted"><?= esc($c['unit_type'] ?? '') ?> · <?= (int) ($c['approved'] ?? 0) ?> approved · score <?= esc((string) ($c['quality_score'] ?? '0')) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="acp-card">
    <div class="acp-card-head">
      <h2><i class="bi bi-arrow-down-circle"></i> Needs attention</h2>
      <span class="acp-badge low">Low</span>
    </div>
    <?php if (empty($lowContributors)): ?>
      <p class="acp-muted">No low contributors flagged.</p>
    <?php else: ?>
      <?php foreach ($lowContributors as $i => $c): ?>
        <div class="acp-rank low">
          <div class="acp-rank-pos"><?= $i + 1 ?></div>
          <div style="flex:1;min-width:0">
            <strong><?= esc($c['unit_name'] ?? '') ?></strong>
            <div class="acp-muted"><?= esc($c['notes'] ?? 'Below KPI threshold') ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<div class="acp-card" style="margin-top:18px">
  <div class="acp-card-head">
    <h2><i class="bi bi-inbox-fill"></i> Pending faculty review</h2>
    <a class="acp-btn" href="<?= site_url('acp/faculty/submissions') ?>">Open queue</a>
  </div>
  <?php if (empty($recent)): ?>
    <p class="acp-muted">No pending submissions.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead>
        <tr>
          <th>Faculty</th>
          <th>Department</th>
          <th>Submitted</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent as $row): ?>
          <tr>
            <td>
              <strong><?= esc($row['emp_name'] ?? '') ?></strong><br>
              <span class="acp-muted"><?= esc($row['des_title'] ?? '') ?></span>
            </td>
            <td><?= esc($row['dep_name'] ?? '—') ?></td>
            <td><?= esc($row['submitted_at'] ?? '') ?></td>
            <td><a class="acp-btn" href="<?= site_url('acp/faculty/submissions/' . (int) $row['id']) ?>">Review</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php if (!empty($upcomingEvents)): ?>
<div class="acp-card" style="margin-top:18px">
  <div class="acp-card-head">
    <h2><i class="bi bi-calendar2-event"></i> Upcoming events</h2>
    <a class="acp-btn acp-btn-ghost" href="<?= site_url('acp/events') ?>">Manage</a>
  </div>
  <table class="acp-table">
    <thead><tr><th>Event</th><th>Date</th><th>Venue</th></tr></thead>
    <tbody>
      <?php foreach ($upcomingEvents as $ev): ?>
        <tr>
          <td><strong><?= esc($ev['title'] ?? '') ?></strong></td>
          <td><?= esc($ev['event_date'] ?? '—') ?></td>
          <td><?= esc($ev['venue'] ?? '—') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<h2 class="acp-section-title"><i class="bi bi-grid-1x2"></i> Modules</h2>
<div class="acp-modules">
  <?php foreach ($modules as $mod): ?>
    <a class="acp-module" href="<?= esc($mod['href']) ?>">
      <div class="acp-module-left">
        <div class="acp-module-ico"><i class="bi <?= esc($mod['icon'] ?? 'bi-box') ?>"></i></div>
        <div>
          <strong><?= esc($mod['label']) ?></strong>
          <span class="acp-muted"><?= $mod['status'] === 'live' ? 'Available now' : 'Coming next' ?></span>
        </div>
      </div>
      <span class="acp-badge <?= esc($mod['status']) ?>"><?= $mod['status'] === 'live' ? 'Live' : 'Soon' ?></span>
    </a>
  <?php endforeach; ?>
</div>
<?= $this->endSection() ?>
