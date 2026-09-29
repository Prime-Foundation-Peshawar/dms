<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php
$q = static fn (string $t) => site_url('acp/reports?month=' . rawurlencode($monthKey) . '&tab=' . $t);
$compliance = $expectedTotal > 0 ? (int) round(($meetingKpi / $expectedTotal) * 100) : 0;
?>

<div class="acp-hero-banner">
  <h2><i class="bi bi-radar"></i> RCP protocol command report</h2>
  <p>Implements the Website Management MoM (6 Aug 2026): expected unit roster, <strong>missing departments</strong> with zero input, Top/Average/Low bands, 3-month escalation, content reception display, daily/weekly integrity checks, KPI ≥2 approved updates/month, 2-day approval SLA, awards shortlist, and improvement suggestions for Steering Committee.</p>
  <form method="get" action="<?= site_url('acp/reports') ?>" style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <input type="hidden" name="tab" value="<?= esc($tab) ?>">
    <label class="acp-chip">Month <input type="month" name="month" value="<?= esc($monthKey) ?>" style="border:0;border-radius:8px;padding:3px 8px;margin-left:6px;font:inherit;color:#111"></label>
    <button class="acp-btn acp-btn-gold" type="submit"><i class="bi bi-funnel"></i> Apply</button>
    <span class="acp-chip"><i class="bi bi-exclamation-octagon"></i> Missing <?= count($missing) ?></span>
    <span class="acp-chip"><i class="bi bi-lightning"></i> Escalations <?= count($escalations) ?></span>
  </form>
</div>

<div class="acp-tabs">
  <?php foreach ([
    'overview' => 'Overview',
    'missing' => 'Missing units',
    'escalations' => '3-month escalations',
    'reception' => 'Content reception',
    'integrity' => 'Integrity checks',
    'awards' => 'Awards & recognition',
    'suggestions' => 'Improvements',
    'governance' => 'Governance & SOP',
    'ledger' => 'Contribution ledger',
  ] as $key => $label): ?>
    <a href="<?= $q($key) ?>" class="<?= $tab === $key ? 'is-active' : '' ?>"><?= esc($label) ?></a>
  <?php endforeach; ?>
</div>

<?php if ($tab === 'overview'): ?>
<div class="acp-grid">
  <div class="acp-card acp-stat"><div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-buildings"></i></div><em>Roster</em></div><div><strong><?= (int) $expectedTotal ?></strong><span>Expected units</span></div></div>
  <div class="acp-card acp-stat"><div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-exclamation-triangle"></i></div><em>Gap</em></div><div><strong><?= count($missing) ?></strong><span>Missing (zero input)</span></div></div>
  <div class="acp-card acp-stat"><div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-patch-check"></i></div><em>KPI</em></div><div><strong><?= (int) $meetingKpi ?>/<?= (int) $expectedTotal ?></strong><span>≥<?= (int) $kpiTarget ?> approved / month</span></div></div>
  <div class="acp-card acp-stat"><div class="acp-stat-top"><div class="acp-stat-ico"><i class="bi bi-hourglass-split"></i></div><em>SLA</em></div><div><strong><?= (int) $slaBreaches ?></strong><span>Over <?= (int) $slaDays ?>-day turnaround</span></div></div>
</div>

<div class="acp-grid-2" style="margin-top:16px">
  <div class="acp-card">
    <div class="acp-card-head"><h2>Monthly KPI compliance</h2><span class="acp-badge <?= $compliance >= 70 ? 'live' : 'low' ?>"><?= $compliance ?>%</span></div>
    <p class="acp-muted">Units meeting “at least two approved website updates per month”.</p>
    <div class="acp-meter" style="--w:<?= $compliance ?>%"><span></span></div>
    <div class="acp-grid" style="grid-template-columns:repeat(3,1fr);margin-top:14px;gap:10px">
      <div><strong style="font-family:var(--font-display);font-size:1.4rem"><?= count($top) ?></strong><div class="acp-muted">Top</div></div>
      <div><strong style="font-family:var(--font-display);font-size:1.4rem"><?= count($avg) ?></strong><div class="acp-muted">Average</div></div>
      <div><strong style="font-family:var(--font-display);font-size:1.4rem"><?= count($low) + count($missing) ?></strong><div class="acp-muted">Low + Missing</div></div>
    </div>
  </div>
  <div class="acp-card">
    <div class="acp-card-head"><h2>Weekly review board</h2></div>
    <ul class="acp-checklist">
      <li><i class="bi bi-inbox"></i><span><strong>Pending submissions</strong> — <?= (int) $pendingFaculty ?> faculty profiles</span></li>
      <li><i class="bi bi-calendar-event"></i><span><strong>Upcoming events</strong> — <?= count($upcoming) ?> scheduled</span></li>
      <li><i class="bi bi-building-exclamation"></i><span><strong>Missing departments</strong> — <?= count($missing) ?> with zero input <a href="<?= $q('missing') ?>">view</a></span></li>
      <li><i class="bi bi-graph-up"></i><span><strong>Website analytics</strong> — <a href="<?= site_url('acp/analytics') ?>">GA workspace</a></span></li>
      <li><i class="bi bi-lightbulb"></i><span><strong>Improvement suggestions</strong> — <?= count($suggestions) ?> open <a href="<?= $q('suggestions') ?>">view</a></span></li>
    </ul>
  </div>
</div>

<div class="acp-grid-3" style="margin-top:16px">
  <div class="acp-card"><div class="acp-card-head"><h2>Top</h2><span class="acp-badge top"><?= count($top) ?></span></div>
    <?php foreach (array_slice($top, 0, 5) as $i => $c): ?>
      <div class="acp-rank top"><div class="acp-rank-pos"><?= $i+1 ?></div><div><strong><?= esc($c['unit_name']) ?></strong><div class="acp-muted"><?= (int)$c['approved'] ?> approved</div></div></div>
    <?php endforeach; ?>
  </div>
  <div class="acp-card acp-danger-panel"><div class="acp-card-head"><h2>Missing / silent</h2><span class="acp-badge missing"><?= count($missing) ?></span></div>
    <?php if (empty($missing)): ?><p class="acp-muted">All expected units submitted.</p><?php endif; ?>
    <?php foreach (array_slice($missing, 0, 6) as $i => $c): ?>
      <div class="acp-rank missing"><div class="acp-rank-pos">!</div><div><strong><?= esc($c['unit_name']) ?></strong><div class="acp-muted"><?= esc($c['reason']) ?></div></div></div>
    <?php endforeach; ?>
    <a class="acp-btn" href="<?= $q('missing') ?>" style="margin-top:8px">Full missing report</a>
  </div>
  <div class="acp-card acp-warn-panel"><div class="acp-card-head"><h2>Escalations</h2><span class="acp-badge low"><?= count($escalations) ?></span></div>
    <p class="acp-muted">Failing KPI for 3 consecutive months → Steering Committee.</p>
    <?php if (empty($escalations)): ?><p class="acp-muted">None this window.</p><?php endif; ?>
    <?php foreach (array_slice($escalations, 0, 5) as $e): ?>
      <div class="acp-rank low"><div class="acp-rank-pos">3×</div><div><strong><?= esc($e['unit_name']) ?></strong><div class="acp-muted">Latest approved: <?= (int)$e['latest_approved'] ?></div></div></div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php if ($tab === 'missing'): ?>
<div class="acp-card acp-danger-panel">
  <div class="acp-card-head">
    <h2><i class="bi bi-building-exclamation"></i> Missing departments / units report</h2>
    <span class="acp-badge missing"><?= count($missing) ?> silent</span>
  </div>
  <p class="acp-muted">Weekly review item from MoM: departments/units that did <strong>not</strong> provide any activity or input to the Website Manager this month. Generated against the canonical expected roster (Finance, Student Affairs, HR, QEC, DHPE&amp;R, ORIC, UMR, societies, hospitals, institutions, excursions).</p>
  <?php if (empty($missing)): ?>
    <p class="acp-muted">No missing units for <?= esc($monthKey) ?>.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Unit</th><th>Type</th><th>Institution</th><th>Expected content</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ($missing as $m): ?>
          <tr>
            <td><strong><?= esc($m['unit_name']) ?></strong></td>
            <td><?= esc($m['unit_type']) ?></td>
            <td><?= esc($m['institution'] ?? '—') ?></td>
            <td class="acp-muted"><?= esc($m['content_examples'] ?? '') ?></td>
            <td><span class="acp-badge missing">No input</span><div class="acp-muted"><?= esc($m['reason']) ?></div></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<div class="acp-card" style="margin-top:16px">
  <div class="acp-card-head"><h2>Below KPI (&lt; <?= (int)$kpiTarget ?> approved)</h2></div>
  <table class="acp-table">
    <thead><tr><th>Unit</th><th>Subs</th><th>Approved</th><th>On time</th><th>Band</th><th>Notes</th></tr></thead>
    <tbody>
      <?php foreach ($belowKpi as $r): ?>
        <tr>
          <td><strong><?= esc($r['unit_name']) ?></strong></td>
          <td><?= (int)$r['submissions'] ?></td>
          <td><?= (int)$r['approved'] ?></td>
          <td><?= (int)$r['on_time'] ?></td>
          <td><span class="acp-badge <?= esc($r['rank_band'] === 'missing' ? 'missing' : 'low') ?>"><?= esc($r['rank_band']) ?></span></td>
          <td class="acp-muted"><?= esc($r['notes'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php if ($tab === 'escalations'): ?>
<div class="acp-card acp-warn-panel">
  <div class="acp-card-head"><h2>Three consecutive months — Steering Committee escalation</h2></div>
  <p class="acp-muted">MoM: any department failing prescribed KPIs for three consecutive months shall be reported to the Website Steering Committee and institutional / Unit Heads for corrective action.</p>
  <?php if (empty($escalations)): ?>
    <p class="acp-muted">No units currently in the 3-month failure window.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Unit</th><th>Failed months</th><th>Window</th><th>Latest approved</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach ($escalations as $e): ?>
          <tr>
            <td><strong><?= esc($e['unit_name']) ?></strong></td>
            <td><?= (int)$e['failed_months'] ?></td>
            <td class="acp-muted"><?= esc(implode(' → ', array_reverse($e['window']))) ?></td>
            <td><?= (int)$e['latest_approved'] ?></td>
            <td><span class="acp-badge low">Escalate</span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($tab === 'reception'): ?>
<div class="acp-card">
  <div class="acp-card-head"><h2>Website content reception display</h2><span class="acp-badge live"><?= count($activities) ?></span></div>
  <p class="acp-muted">MoM: monthly activity report visible to concerned personnel about activities they provided to the Website Manager — receipt, approval, publish, social share.</p>
  <?php if (empty($activities)): ?>
    <p class="acp-muted">No reception log rows this month.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Unit</th><th>Activity</th><th>Received</th><th>Published</th><th>Turnaround (h)</th><th>Social</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ($activities as $a): ?>
          <?php $late = (int)($a['turnaround_hours'] ?? 0) > ($slaDays * 24); ?>
          <tr>
            <td><strong><?= esc($a['unit_name']) ?></strong></td>
            <td><?= esc($a['activity_title']) ?></td>
            <td><?= esc($a['received_at'] ?? '—') ?></td>
            <td><?= esc($a['published_at'] ?? '—') ?></td>
            <td><?= (int)($a['turnaround_hours'] ?? 0) ?><?= $late ? ' <span class="acp-badge low">SLA</span>' : '' ?></td>
            <td><?= !empty($a['shared_social']) ? 'Yes' : 'No' ?></td>
            <td><span class="acp-badge live"><?= esc($a['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<div class="acp-card" style="margin-top:16px">
  <div class="acp-card-head"><h2>Log reception</h2></div>
  <form class="acp-form" method="post" action="<?= site_url('acp/reports/activities') ?>">
    <?= csrf_field() ?>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>Month<input type="month" name="month_key" value="<?= esc($monthKey) ?>"></label>
      <label>Unit name<input type="text" name="unit_name" required list="expectedUnits"></label>
      <label>Status
        <select name="status"><option value="received">received</option><option value="published" selected>published</option><option value="archived">archived</option></select>
      </label>
      <label style="grid-column:1/-1">Activity title<input type="text" name="activity_title" required></label>
      <label>Received at<input type="datetime-local" name="received_at"></label>
      <label>Approved at<input type="datetime-local" name="approved_at"></label>
      <label>Published at<input type="datetime-local" name="published_at"></label>
      <label>Turnaround hours<input type="number" name="turnaround_hours" value="0"></label>
      <label style="display:flex;align-items:flex-end;gap:8px;padding-bottom:8px"><input type="checkbox" name="shared_social" value="1"> Shared on social</label>
    </div>
    <button class="acp-btn acp-btn-teal" type="submit"><i class="bi bi-plus-lg"></i> Add to reception log</button>
  </form>
</div>
<?php endif; ?>

<?php if ($tab === 'integrity'): ?>
<div class="acp-card">
  <div class="acp-card-head"><h2>Website integrity monitoring</h2></div>
  <p class="acp-muted">MoM: Website Manager daily check for hacking / unwanted / unauthorized content; Director IT weekly check.</p>
  <table class="acp-table">
    <thead><tr><th>Date</th><th>Type</th><th>Status</th><th>By</th><th>Notes</th></tr></thead>
    <tbody>
      <?php foreach ($integrity as $c): ?>
        <tr>
          <td><?= esc($c['check_date']) ?></td>
          <td><?= esc($c['check_type']) ?></td>
          <td><span class="acp-badge <?= ($c['status'] ?? '') === 'clear' ? 'live' : 'low' ?>"><?= esc($c['status']) ?></span></td>
          <td><?= esc($c['checked_by'] ?? '') ?></td>
          <td class="acp-muted"><?= esc($c['notes'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="acp-card" style="margin-top:16px">
  <form class="acp-form" method="post" action="<?= site_url('acp/reports/integrity') ?>">
    <?= csrf_field() ?>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>Date<input type="date" name="check_date" value="<?= date('Y-m-d') ?>"></label>
      <label>Type<select name="check_type"><option value="daily">daily (Web Manager)</option><option value="weekly">weekly (Director IT)</option></select></label>
      <label>Status<select name="status"><option value="clear">clear</option><option value="issue">issue</option></select></label>
      <label>Checked by<input type="text" name="checked_by" value="<?= esc(session('cms_user_name') ?: 'Website Manager') ?>"></label>
      <label style="grid-column:span 2">Notes<input type="text" name="notes" placeholder="Findings"></label>
    </div>
    <button class="acp-btn acp-btn-primary" type="submit"><i class="bi bi-shield-check"></i> Record check</button>
  </form>
</div>
<?php endif; ?>

<?php if ($tab === 'awards'): ?>
<div class="acp-card">
  <div class="acp-card-head"><h2>Motivation &amp; incentive shortlist</h2></div>
  <p class="acp-muted">MoM: Website Cell recommends Best Institution/Unit, Best Hospital, Best activity team, Best individual — certificates + cash prize via Steering Committee.</p>
  <div class="acp-kpi-row"><strong>Best Institution / Unit</strong><div class="acp-muted"><?= !empty($top[0]['unit_name']) ? esc($top[0]['unit_name']) : '—' ?> (highest approved volume)</div></div>
  <div class="acp-kpi-row"><strong>Best Hospital</strong><div class="acp-muted"><?= $bestHospital ? esc($bestHospital['unit_name']) . ' · ' . (int)$bestHospital['approved'] . ' approved' : 'No hospital met threshold' ?></div></div>
  <div class="acp-kpi-row"><strong>Best society / activity team</strong><div class="acp-muted"><?= $bestSociety ? esc($bestSociety['unit_name']) : 'Website Cell + Social Media Unit (coordination)' ?></div></div>
  <div class="acp-kpi-row"><strong>Best individual contributor</strong><div class="acp-muted"><?= $bestIndividual ? esc($bestIndividual['unit_name']) : 'Add unit_type=individual rows to nominate' ?></div></div>
</div>
<?php endif; ?>

<?php if ($tab === 'suggestions'): ?>
<div class="acp-card">
  <div class="acp-card-head"><h2>Improvement suggestions</h2></div>
  <p class="acp-muted">Weekly review + monthly Steering Committee / higher-management feedback for continuous improvement.</p>
  <?php if (empty($suggestions)): ?><p class="acp-muted">None logged.</p><?php endif; ?>
  <ul class="acp-checklist">
    <?php foreach ($suggestions as $s): ?>
      <li><i class="bi bi-lightbulb"></i><span><strong><?= esc($s['source'] ?? 'Website Cell') ?>:</strong> <?= esc($s['suggestion']) ?> <span class="acp-badge soon"><?= esc($s['status']) ?></span></span></li>
    <?php endforeach; ?>
  </ul>
</div>
<div class="acp-card" style="margin-top:16px">
  <form class="acp-form" method="post" action="<?= site_url('acp/reports/suggestions') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="month_key" value="<?= esc($monthKey) ?>">
    <label>Source<input type="text" name="source" value="Website Cell weekly review"></label>
    <label>Suggestion<textarea name="suggestion" required></textarea></label>
    <button class="acp-btn acp-btn-teal" type="submit">Add suggestion</button>
  </form>
</div>
<?php endif; ?>

<?php if ($tab === 'governance'): ?>
<div class="acp-grid-2">
  <div class="acp-card">
    <div class="acp-card-head"><h2>A. Website Steering Committee</h2></div>
    <p class="acp-muted">Chair: Nominee of Director Campus. Members: ED, Vice Dean DHPE&amp;R, Deans of RCP departments, MS of each teaching hospital, Director IT, Website Manager (Secretary).</p>
    <ul class="acp-list">
      <li>Approve website policy</li>
      <li>Monitor institutional compliance</li>
      <li>Review quarterly performance</li>
      <li>Resolve intra/interdepartmental issues</li>
      <li>Approve annual website improvement plan</li>
      <li>Meet at least quarterly</li>
    </ul>
  </div>
  <div class="acp-card">
    <div class="acp-card-head"><h2>B. Website Operations Unit</h2></div>
    <p class="acp-muted">Website Manager, Web Developer, Social Media Officer, Content Editor, Graphic Designer, Photographer/Videographer.</p>
    <ul class="acp-list">
      <li>Edit/upload/publish received content (not generate news)</li>
      <li>Report non-compliance to Director IT</li>
      <li>Weekly 20–30 min review + monthly comprehensive review</li>
      <li>Prepare monthly / quarterly / annual reports</li>
      <li>Archive/delete per protocol; coordinate with Social Media Unit</li>
    </ul>
  </div>
</div>
<div class="acp-card" style="margin-top:16px">
  <div class="acp-card-head"><h2>SOP (8 steps)</h2></div>
  <ol class="acp-list">
    <li>Conduct the activity.</li>
    <li>Capture quality photographs/videos.</li>
    <li>Complete the standard submission form within the prescribed timeline.</li>
    <li>Obtain required approval (target: within 2 days).</li>
    <li>Submit electronically to the Website Manager.</li>
    <li>Website Manager reviews and requests technical changes if required.</li>
    <li>Publish on website within defined timeline.</li>
    <li>Share with originating department, archive, and disseminate via official social media where appropriate.</li>
  </ol>
</div>
<div class="acp-card" style="margin-top:16px">
  <div class="acp-card-head"><h2>Expected roster (<?= count($expected) ?>)</h2></div>
  <table class="acp-table">
    <thead><tr><th>Unit</th><th>Type</th><th>Institution</th><th>Content examples</th></tr></thead>
    <tbody>
      <?php foreach ($expected as $u): ?>
        <tr>
          <td><strong><?= esc($u['unit_name']) ?></strong></td>
          <td><?= esc($u['unit_type']) ?></td>
          <td><?= esc($u['institution'] ?? '') ?></td>
          <td class="acp-muted"><?= esc($u['content_examples'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php if ($tab === 'ledger'): ?>
<div class="acp-card">
  <div class="acp-card-head"><h2>Contribution ledger · <?= esc($monthKey) ?></h2></div>
  <table class="acp-table">
    <thead><tr><th>Unit</th><th>Type</th><th>Subs</th><th>Approved</th><th>On time</th><th>Quality</th><th>Band</th><th>Notes</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><strong><?= esc($r['unit_name']) ?></strong></td>
          <td><?= esc($r['unit_type']) ?></td>
          <td><?= (int)$r['submissions'] ?></td>
          <td><?= (int)$r['approved'] ?></td>
          <td><?= (int)$r['on_time'] ?></td>
          <td><?= esc((string)$r['quality_score']) ?></td>
          <td><span class="acp-badge <?= esc(in_array($r['rank_band'], ['top','missing','low'], true) ? $r['rank_band'] : 'avg') ?>"><?= esc($r['rank_band']) ?></span></td>
          <td class="acp-muted"><?= esc($r['notes'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="acp-card" style="margin-top:16px">
  <div class="acp-card-head"><h2>Record / update contribution</h2></div>
  <form class="acp-form" method="post" action="<?= site_url('acp/reports/contributions') ?>">
    <?= csrf_field() ?>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>Month<input type="month" name="month_key" value="<?= esc($monthKey) ?>"></label>
      <label>Unit name<input type="text" name="unit_name" required list="expectedUnits"></label>
      <label>Unit type
        <select name="unit_type">
          <?php foreach (['unit','institution','hospital','society','activity','team','individual'] as $t): ?>
            <option value="<?= $t ?>"><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Submissions<input type="number" name="submissions" value="0"></label>
      <label>Approved<input type="number" name="approved" value="0"></label>
      <label>On time<input type="number" name="on_time" value="0"></label>
      <label>Quality<input type="number" step="0.1" name="quality_score" value="7"></label>
      <label>Band
        <select name="rank_band">
          <option value="top">top</option>
          <option value="average" selected>average</option>
          <option value="low">low</option>
          <option value="missing">missing</option>
        </select>
      </label>
      <label>Notes<input type="text" name="notes"></label>
    </div>
    <button class="acp-btn acp-btn-primary" type="submit">Save contribution</button>
  </form>
</div>
<?php endif; ?>

<datalist id="expectedUnits">
  <?php foreach ($expected as $u): ?>
    <option value="<?= esc($u['unit_name']) ?>"></option>
  <?php endforeach; ?>
</datalist>
<?= $this->endSection() ?>
