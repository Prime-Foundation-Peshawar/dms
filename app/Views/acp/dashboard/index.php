<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-grid">
  <div class="acp-card acp-stat">
    <strong><?= (int) $pending ?></strong>
    <span>Pending submissions</span>
  </div>
  <div class="acp-card acp-stat">
    <strong><?= (int) $live ?></strong>
    <span>Live faculty profiles</span>
  </div>
  <div class="acp-card acp-stat">
    <strong><?= (int) $approved ?></strong>
    <span>Approved</span>
  </div>
  <div class="acp-card acp-stat">
    <strong><?= (int) $rejected ?></strong>
    <span>Rejected</span>
  </div>
</div>

<div class="acp-card" style="margin-top:18px">
  <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:10px">
    <h2 style="margin:0;font-size:1.1rem">Pending review</h2>
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

<h2 style="margin:28px 0 8px;font-size:1.1rem">Modules</h2>
<div class="acp-modules">
  <?php foreach ($modules as $mod): ?>
    <a class="acp-module" href="<?= esc($mod['href']) ?>">
      <div>
        <strong><?= esc($mod['label']) ?></strong>
        <span class="acp-muted"><?= $mod['status'] === 'live' ? 'Available now' : 'Coming next' ?></span>
      </div>
      <span class="acp-badge <?= esc($mod['status']) ?>"><?= $mod['status'] === 'live' ? 'Live' : 'Soon' ?></span>
    </a>
  <?php endforeach; ?>
</div>
<?= $this->endSection() ?>
