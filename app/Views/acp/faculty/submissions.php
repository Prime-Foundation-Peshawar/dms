<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head">
  <div><p>Faculty self-updates stay here until you approve them. Approving writes research topics, education, duties, photo, and the publications link onto the live profile.</p></div>
</div>
<div class="d-flex gap-2 flex-wrap mb-3">
  <?php foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label): ?>
    <a class="btn-acp btn-acp-sm <?= ($status === $key) ? 'btn-acp-primary' : 'btn-acp-outline' ?>" href="<?= site_url('acp/faculty/submissions?status=' . $key) ?>">
      <?= esc($label) ?> (<?= (int) ($counts[$key] ?? 0) ?>)
    </a>
  <?php endforeach; ?>
</div>
<div class="acp-card">
  <div class="acp-table-wrap">
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
        <?php
          $badge = match ($row['status'] ?? '') {
              'approved' => 'acp-badge-ok',
              'rejected' => 'acp-badge-muted',
              default => 'acp-badge-warn',
          };
        ?>
        <tr>
          <td>
            <strong><?= esc($row['emp_name'] ?? '') ?></strong>
            <div class="small text-muted"><?= esc($row['des_title'] ?? '') ?></div>
          </td>
          <td><?= esc($row['dep_name'] ?? '') ?></td>
          <td><?= esc($row['submitted_at'] ?? '') ?></td>
          <td><span class="acp-badge <?= esc($badge) ?>"><?= esc($row['status'] ?? '') ?></span></td>
          <td><a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/faculty/submissions/' . (int) $row['id']) ?>">Open</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (! $rows): ?>
        <tr class="acp-empty-row"><td colspan="5" class="acp-empty">No submissions in this view.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>
