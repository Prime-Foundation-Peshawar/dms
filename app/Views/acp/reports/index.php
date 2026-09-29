<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>

<div class="acp-hero-banner">
  <h2><i class="bi bi-clipboard2-pulse"></i> Website Cell monthly report</h2>
  <p>Drawn from the RCP Website Management minutes (6 Aug 2026): contributor dashboard (Top / Average / Low), KPI compliance (≥2 approved updates/month), weekly review items, and recognition shortlist for the Steering Committee.</p>
  <form method="get" action="<?= site_url('acp/reports') ?>" style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <label class="acp-chip" style="background:rgba(255,255,255,.16);gap:8px">
      Month
      <input type="month" name="month" value="<?= esc($monthKey) ?>" style="border:0;border-radius:8px;padding:4px 8px;font:inherit">
    </label>
    <button class="acp-btn acp-btn-gold" type="submit"><i class="bi bi-funnel"></i> Filter</button>
  </form>
</div>

<div class="acp-grid">
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-send-check"></i></div><em>Month</em></div>
    <div><strong><?= (int) $submissionsTotal ?></strong><span>Submissions received</span></div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-patch-check"></i></div><em>Published</em></div>
    <div><strong><?= (int) $approvedTotal ?></strong><span>Approved updates</span></div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-alarm"></i></div><em>SLA</em></div>
    <div><strong><?= (int) $onTimeTotal ?></strong><span>On-time submissions</span></div>
  </div>
  <div class="acp-card acp-stat">
    <div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-speedometer"></i></div><em>KPI</em></div>
    <div><strong><?= (int) $meetingKpi ?>/<?= (int) $unitsTotal ?></strong><span>Units ≥ <?= (int) $kpiTarget ?> updates</span></div>
  </div>
</div>

<div class="acp-grid-3" style="margin-top:18px">
  <div class="acp-card">
    <div class="acp-card-head"><h2>Top</h2><span class="acp-badge top"><?= count($top) ?></span></div>
    <?php if (empty($top)): ?><p class="acp-muted">None</p><?php endif; ?>
    <?php foreach ($top as $i => $c): ?>
      <div class="acp-rank top">
        <div class="acp-rank-pos"><?= $i + 1 ?></div>
        <div><strong><?= esc($c['unit_name']) ?></strong><div class="acp-muted"><?= (int) $c['approved'] ?> approved · Q<?= esc((string) $c['quality_score']) ?></div></div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="acp-card">
    <div class="acp-card-head"><h2>Average</h2><span class="acp-badge avg"><?= count($avg) ?></span></div>
    <?php if (empty($avg)): ?><p class="acp-muted">None</p><?php endif; ?>
    <?php foreach ($avg as $i => $c): ?>
      <div class="acp-rank">
        <div class="acp-rank-pos"><?= $i + 1 ?></div>
        <div><strong><?= esc($c['unit_name']) ?></strong><div class="acp-muted"><?= (int) $c['approved'] ?> approved</div></div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="acp-card">
    <div class="acp-card-head"><h2>Low</h2><span class="acp-badge low"><?= count($low) ?></span></div>
    <?php if (empty($low)): ?><p class="acp-muted">None</p><?php endif; ?>
    <?php foreach ($low as $i => $c): ?>
      <div class="acp-rank low">
        <div class="acp-rank-pos"><?= $i + 1 ?></div>
        <div><strong><?= esc($c['unit_name']) ?></strong><div class="acp-muted"><?= esc($c['notes'] ?? 'Below KPI') ?></div></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="acp-grid-2" style="margin-top:18px">
  <div class="acp-card">
    <div class="acp-card-head"><h2><i class="bi bi-list-check"></i> Weekly review board</h2></div>
    <ul class="acp-checklist">
      <li><i class="bi bi-inbox"></i><span><strong>Pending submissions</strong> — <?= (int) $pendingFaculty ?> faculty profiles awaiting review</span></li>
      <li><i class="bi bi-calendar-event"></i><span><strong>Upcoming events</strong> — <?= count($upcoming) ?> published ahead</span></li>
      <li><i class="bi bi-building-exclamation"></i><span><strong>Missing / low departments</strong> — <?= count($low) ?> units need follow-up</span></li>
      <li><i class="bi bi-graph-up"></i><span><strong>Website analytics</strong> — refresh in Analytics · GA</span></li>
      <li><i class="bi bi-lightbulb"></i><span><strong>Improvement suggestions</strong> — capture for quarterly Steering Committee</span></li>
    </ul>
    <?php if (!empty($upcoming)): ?>
      <table class="acp-table" style="margin-top:12px">
        <thead><tr><th>Upcoming event</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach ($upcoming as $ev): ?>
            <tr><td><?= esc($ev['title'] ?? '') ?></td><td><?= esc($ev['event_date'] ?? '') ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <div class="acp-card">
    <div class="acp-card-head"><h2><i class="bi bi-award"></i> Recognition shortlist</h2></div>
    <p class="acp-muted" style="margin-top:0">Per MoM incentive system — recommend Best Institution / Unit, Best Hospital, Best activity team, and Best individual contributor to the Steering Committee.</p>
    <div class="acp-kpi-row">
      <strong>Best Unit / Institution</strong>
      <div class="acp-muted"><?= !empty($top[0]['unit_name']) ? esc($top[0]['unit_name']) : '—' ?> (leading approved volume)</div>
    </div>
    <div class="acp-kpi-row">
      <strong>Best Hospital</strong>
      <?php
        $bestHosp = null;
        foreach ($rows as $r) {
            if (($r['unit_type'] ?? '') === 'hospital' && (int) ($r['approved'] ?? 0) > 0) {
                $bestHosp = $r;
                break;
            }
        }
      ?>
      <div class="acp-muted"><?= $bestHosp ? esc($bestHosp['unit_name']) : 'No hospital met threshold this month' ?></div>
    </div>
    <div class="acp-kpi-row">
      <strong>Best activity team</strong>
      <div class="acp-muted">Website Cell + Social Media Unit (coordination KPI)</div>
    </div>
    <div class="acp-kpi-row">
      <strong>Accountability note</strong>
      <div class="acp-muted">Units failing KPI for 3 consecutive months escalate to Steering Committee &amp; institutional heads.</div>
    </div>
  </div>
</div>

<div class="acp-card" style="margin-top:18px">
  <div class="acp-card-head">
    <h2>Contribution ledger · <?= esc($monthKey) ?></h2>
  </div>
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No contribution rows for this month. Add units below.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead>
        <tr>
          <th>Unit</th>
          <th>Type</th>
          <th>Subs</th>
          <th>Approved</th>
          <th>On time</th>
          <th>Quality</th>
          <th>Band</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><strong><?= esc($r['unit_name'] ?? '') ?></strong></td>
            <td><?= esc($r['unit_type'] ?? '') ?></td>
            <td><?= (int) ($r['submissions'] ?? 0) ?></td>
            <td><?= (int) ($r['approved'] ?? 0) ?></td>
            <td><?= (int) ($r['on_time'] ?? 0) ?></td>
            <td><?= esc((string) ($r['quality_score'] ?? '0')) ?></td>
            <td><span class="acp-badge <?= esc($r['rank_band'] === 'top' ? 'top' : ($r['rank_band'] === 'low' ? 'low' : 'avg')) ?>"><?= esc($r['rank_band'] ?? '') ?></span></td>
            <td class="acp-muted"><?= esc($r['notes'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<div class="acp-card" style="margin-top:18px">
  <div class="acp-card-head"><h2><i class="bi bi-plus-circle"></i> Add / record contribution</h2></div>
  <form class="acp-form" method="post" action="<?= site_url('acp/reports/contributions') ?>">
    <?= csrf_field() ?>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>Month<input type="month" name="month_key" value="<?= esc($monthKey) ?>"></label>
      <label>Unit name<input type="text" name="unit_name" required placeholder="e.g. ORIC"></label>
      <label>Unit type
        <select name="unit_type">
          <option value="unit">unit</option>
          <option value="institution">institution</option>
          <option value="hospital">hospital</option>
          <option value="society">society</option>
          <option value="team">team</option>
          <option value="individual">individual</option>
        </select>
      </label>
      <label>Submissions<input type="number" name="submissions" value="0"></label>
      <label>Approved<input type="number" name="approved" value="0"></label>
      <label>On time<input type="number" name="on_time" value="0"></label>
      <label>Quality score<input type="number" step="0.1" name="quality_score" value="7.0"></label>
      <label>Rank band
        <select name="rank_band">
          <option value="top">top</option>
          <option value="average" selected>average</option>
          <option value="low">low</option>
        </select>
      </label>
      <label>Notes<input type="text" name="notes" placeholder="Optional"></label>
    </div>
    <div class="acp-actions">
      <button class="acp-btn acp-btn-primary" type="submit"><i class="bi bi-save2"></i> Save contribution</button>
    </div>
  </form>
</div>

<div class="acp-card" style="margin-top:18px">
  <div class="acp-card-head"><h2><i class="bi bi-diagram-3"></i> SOP reminder</h2></div>
  <ol class="acp-list">
    <li>Conduct the activity and capture quality photos/videos.</li>
    <li>Complete the standard submission form within the prescribed timeline.</li>
    <li>Obtain required approval (target: within 2 days).</li>
    <li>Submit electronically to the Website Manager.</li>
    <li>Website Cell reviews, publishes within SLA, archives, and shares to social media where appropriate.</li>
  </ol>
</div>
<?= $this->endSection() ?>
