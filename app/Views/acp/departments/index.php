<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>

<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>

<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No departments in the database yet. Deploy seed will populate them from the legacy static file.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Group</th>
          <th>HoD</th>
          <th>Updated</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td>
              <strong><?= esc($row['name'] ?? '') ?></strong><br>
              <span class="acp-muted"><?= esc($row['slug'] ?? '') ?></span>
            </td>
            <td><?= esc($row['dept_group'] ?? '—') ?></td>
            <td><?= esc($row['hod_name'] ?? '—') ?></td>
            <td><?= esc($row['updated_on'] ?? '—') ?></td>
            <td class="acp-actions">
              <a class="acp-btn" href="<?= site_url('acp/departments/' . (int) $row['id']) ?>">Edit</a>
              <a class="acp-btn acp-btn-ghost" href="<?= site_url('department?slug=' . rawurlencode((string) ($row['slug'] ?? ''))) ?>" target="_blank" rel="noopener">View</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
