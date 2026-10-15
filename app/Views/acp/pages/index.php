<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div>
    <p>CMS pages render from the database. Template pages keep special layouts.</p>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <div class="acp-search">
      <i class="bi bi-search"></i>
      <input class="form-control form-control-sm" type="search" placeholder="Filter…" data-acp-filter="#pagesTable">
    </div>
    <a class="btn-acp btn-acp-primary" href="<?= site_url('acp/pages/create') ?>"><i class="bi bi-plus-lg"></i> New page</a>
  </div>
</div>
<div class="acp-card acp-fu">
  <div class="acp-table-wrap">
    <table class="acp-table" id="pagesTable" style="table-layout:auto">
      <thead>
        <tr>
          <th style="width:18%">Slug</th>
          <th>Title</th>
          <th class="col-status">Source</th>
          <th class="col-status">Status</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><code><?= esc($row['slug']) ?></code></td>
          <td><?= esc($row['title']) ?></td>
          <td>
            <?php if (! empty($row['use_cms'])): ?>
              <span class="acp-badge acp-badge-info">CMS</span>
            <?php else: ?>
              <span class="acp-badge acp-badge-muted">Template</span>
            <?php endif; ?>
          </td>
          <td><span class="acp-badge <?= $row['status'] === 'published' ? 'acp-badge-ok' : 'acp-badge-muted' ?>"><?= esc($row['status']) ?></span></td>
          <td class="col-actions">
            <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/pages/edit/' . $row['id']) ?>">Edit</a>
            <form class="d-inline" method="post" action="<?= site_url('acp/pages/delete/' . $row['id']) ?>" onsubmit="return confirm('Delete this page?')">
              <?= csrf_field() ?>
              <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (! $rows): ?>
        <tr class="acp-empty-row"><td colspan="5" class="acp-empty">No pages yet. <a href="<?= site_url('acp/pages/create') ?>">Create one</a>.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>
