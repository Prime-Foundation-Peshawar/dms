<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn acp-btn-primary" href="<?= site_url('acp/events/new') ?>">Add event</a>
</div>
<div class="acp-card">
  <?php if (empty($rows)): ?>
    <p class="acp-muted">No events yet.</p>
  <?php else: ?>
    <table class="acp-table">
      <thead><tr><th>Title</th><th>Category</th><th>Date</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><strong><?= esc($row['title'] ?? '') ?></strong><br><span class="acp-muted"><?= esc($row['slug'] ?? '') ?></span></td>
            <td><?= esc($row['category'] ?? '') ?></td>
            <td><?= esc($row['event_date'] ?? '—') ?></td>
            <td><span class="acp-badge <?= ($row['status'] ?? '') === 'published' ? 'live' : 'soon' ?>"><?= esc($row['status'] ?? '') ?></span></td>
            <td class="acp-actions">
              <a class="acp-btn" href="<?= site_url('acp/events/' . (int) $row['id']) ?>">Edit</a>
              <a class="acp-btn acp-btn-ghost" href="<?= site_url('event-single?slug=' . rawurlencode((string) ($row['slug'] ?? ''))) ?>" target="_blank" rel="noopener">View</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>
