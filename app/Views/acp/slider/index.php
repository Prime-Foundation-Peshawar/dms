<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn acp-btn-primary" href="<?= site_url('acp/slider/new') ?>">Add slide</a>
  <a class="acp-btn acp-btn-ghost" href="<?= site_url('/') ?>" target="_blank" rel="noopener">View homepage</a>
</div>
<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No slides yet.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Order</th><th>Type</th><th>Title</th><th>Active</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= (int) ($row['sort_order'] ?? 0) ?></td>
            <td><?= esc($row['media_type'] ?? '') ?></td>
            <td><?= esc(strip_tags((string) ($row['title_html'] ?? $row['brand'] ?? 'Slide'))) ?></td>
            <td><?= !empty($row['is_active']) ? 'Yes' : 'No' ?></td>
            <td class="acp-actions"><a class="acp-btn" href="<?= site_url('acp/slider/' . (int) $row['id']) ?>">Edit</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
