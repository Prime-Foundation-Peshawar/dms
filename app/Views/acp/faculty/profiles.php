<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No live profiles yet.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Department</th>
          <th>Designation</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><strong><?= esc($row['name'] ?? '') ?></strong></td>
            <td><?= esc($row['department'] ?? '—') ?></td>
            <td><?= esc($row['designation'] ?? '—') ?></td>
            <td><a class="acp-btn" href="<?= site_url('acp/faculty/profiles/' . (int) $row['id']) ?>">Edit</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
