<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div><p>Publish news with optional expiry dates.</p></div>
  <div class="d-flex gap-2 flex-wrap">
    <div class="acp-search">
      <i class="bi bi-search"></i>
      <input class="form-control form-control-sm" type="search" placeholder="Filter…" data-acp-filter="#newsTable">
    </div>
    <a class="btn-acp btn-acp-primary" href="<?= site_url('acp/news/create') ?>"><i class="bi bi-plus-lg"></i> New news</a>
  </div>
</div>
<div class="acp-card acp-fu">
  <div class="acp-table-wrap">
    <table class="acp-table" id="newsTable" style="table-layout:auto">
      <thead>
        <tr>
          <th>Title</th>
          <th class="col-narrow">Category</th>
          <th style="width:9rem">Published</th>
          <th style="width:9rem">Expires</th>
          <th class="col-status">Status</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $row):
        $eff = \App\Models\NewsPostModel::effectiveStatus($row);
        $badge = match ($eff) {
            'published' => 'acp-badge-ok',
            'expired'   => 'acp-badge-warn',
            default     => 'acp-badge-muted',
        };
      ?>
        <tr>
          <td><?= esc($row['title']) ?></td>
          <td><?= esc($row['category'] ?? '') ?></td>
          <td class="small"><?= esc($row['published_at'] ?? '') ?></td>
          <td class="small"><?= esc($row['expires_at'] ?? '—') ?></td>
          <td><span class="acp-badge <?= $badge ?>"><?= esc($eff) ?></span></td>
          <td class="col-actions">
            <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/news/edit/' . $row['id']) ?>">Edit</a>
            <form class="d-inline" method="post" action="<?= site_url('acp/news/delete/' . $row['id']) ?>" onsubmit="return confirm('Delete?')">
              <?= csrf_field() ?>
              <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (! $rows): ?>
        <tr class="acp-empty-row"><td colspan="6" class="acp-empty">No news yet.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>
