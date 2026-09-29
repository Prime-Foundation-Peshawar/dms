<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>

<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>

<div class="acp-tabs">
  <?php foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label): ?>
    <a class="<?= ($status === $key) ? 'is-active' : '' ?>" href="<?= site_url('acp/faculty/submissions?status=' . $key) ?>">
      <?= esc($label) ?>
      <?php if (isset($counts[$key])): ?>(<?= (int) $counts[$key] ?>)<?php endif; ?>
    </a>
  <?php endforeach; ?>
</div>

<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No submissions in this view.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead>
        <tr>
          <th>Faculty</th>
          <th>Department</th>
          <th>Submitted</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td>
              <strong><?= esc($row['emp_name'] ?? '') ?></strong><br>
              <span class="acp-muted"><?= esc($row['des_title'] ?? '') ?></span>
            </td>
            <td><?= esc($row['dep_name'] ?? '—') ?></td>
            <td><?= esc($row['submitted_at'] ?? '') ?></td>
            <td><span class="acp-badge <?= esc($row['status'] ?? '') ?>"><?= esc($row['status'] ?? '') ?></span></td>
            <td><a class="acp-btn" href="<?= site_url('acp/faculty/submissions/' . (int) $row['id']) ?>">Open</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
