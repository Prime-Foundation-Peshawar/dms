<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn acp-btn-primary" href="<?= site_url('acp/pages/new') ?>">Add page</a>
</div>
<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No pages yet. Seed will populate from legacy HTML on deploy.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Order</th><th>Title</th><th>Status</th><th>Sidebar</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= (int) ($row['sort_order'] ?? 0) ?></td>
            <td>
              <strong><?= esc($row['title'] ?? '') ?></strong><br>
              <span class="acp-muted"><?= esc($row['slug'] ?? '') ?></span>
            </td>
            <td><span class="acp-badge <?= ($row['status'] ?? '') === 'published' ? 'live' : 'soon' ?>"><?= esc($row['status'] ?? '') ?></span></td>
            <td><?= !empty($row['show_sidebar']) ? 'Yes' : 'No' ?></td>
            <td class="acp-actions">
              <a class="acp-btn" href="<?= site_url('acp/pages/' . (int) $row['id']) ?>">Edit</a>
              <?php if (($row['status'] ?? '') === 'published'): ?>
                <a class="acp-btn acp-btn-ghost" href="<?= site_url((string) ($row['slug'] ?? '')) ?>" target="_blank" rel="noopener">View</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
