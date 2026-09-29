<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn acp-btn-primary" href="<?= site_url('acp/newsletters/new') ?>">Add newsletter</a>
  <a class="acp-btn acp-btn-ghost" href="<?= site_url('newsletter') ?>" target="_blank" rel="noopener">View page</a>
</div>
<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No newsletters yet.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Order</th><th>Title</th><th>Date</th><th>Active</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= (int) ($row['sort_order'] ?? 0) ?></td>
            <td><strong><?= esc($row['title'] ?? '') ?></strong></td>
            <td><?= esc($row['date_label'] ?? $row['published_at'] ?? '—') ?></td>
            <td><?= !empty($row['is_active']) ? 'Yes' : 'No' ?></td>
            <td class="acp-actions">
              <a class="acp-btn" href="<?= site_url('acp/newsletters/' . (int) $row['id']) ?>">Edit</a>
              <?php if (!empty($row['pdf_url'])): ?>
                <a class="acp-btn acp-btn-ghost" href="<?= esc($row['pdf_url']) ?>" target="_blank" rel="noopener">PDF</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
