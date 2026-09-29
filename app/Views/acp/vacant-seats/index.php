<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn acp-btn-primary" href="<?= site_url('acp/vacant-seats/new') ?>">Add row</a>
  <a class="acp-btn" href="<?= site_url('acp/vacant-seats/settings') ?>">Page settings</a>
  <a class="acp-btn acp-btn-ghost" href="<?= site_url('vacant-seats') ?>" target="_blank" rel="noopener">View page</a>
</div>
<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No vacant seat rows yet.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Order</th><th>Programme</th><th>Session</th><th>Seats</th><th>Active</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= (int) ($row['sort_order'] ?? 0) ?></td>
            <td><strong><?= esc($row['programme'] ?? '') ?></strong></td>
            <td><?= esc($row['session_label'] ?? '—') ?></td>
            <td><?= (int) ($row['seats'] ?? 0) ?></td>
            <td><?= !empty($row['is_active']) ? 'Yes' : 'No' ?></td>
            <td class="acp-actions"><a class="acp-btn" href="<?= site_url('acp/vacant-seats/' . (int) $row['id']) ?>">Edit</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php if (!empty($settings['apply_deadline'])): ?>
  <p class="acp-muted" style="margin-top:12px">Apply deadline: <?= esc($settings['apply_deadline']) ?> · Merit: <?= esc($settings['merit_date'] ?? '—') ?></p>
<?php endif; ?>
<?= $this->endSection() ?>
