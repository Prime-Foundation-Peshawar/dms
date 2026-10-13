<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div><p>Campus and college events.</p></div>
  <div class="d-flex gap-2 flex-wrap">
    <div class="acp-search">
      <i class="bi bi-search"></i>
      <input class="form-control form-control-sm" type="search" placeholder="Filter…" data-acp-filter="#eventsTable">
    </div>
    <a class="btn-acp btn-acp-primary" href="<?= site_url('acp/events/create') ?>"><i class="bi bi-plus-lg"></i> New event</a>
  </div>
</div>
<div class="acp-card acp-fu">
  <div class="acp-table-wrap">
    <table class="acp-table" id="eventsTable" style="table-layout:auto">
      <thead>
        <tr>
          <th>Title</th>
          <th style="width:8rem">Date</th>
          <th style="width:22%">Location</th>
          <th class="col-status">Status</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><?= esc($row['title']) ?></td>
          <td class="small"><?= esc($row['event_date'] ?? '') ?></td>
          <td><?= esc($row['location'] ?? '') ?></td>
          <td><span class="acp-badge <?= ($row['status'] ?? '') === 'published' ? 'acp-badge-ok' : 'acp-badge-muted' ?>"><?= esc($row['status'] ?? '') ?></span></td>
          <td class="col-actions">
            <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/events/edit/' . $row['id']) ?>">Edit</a>
            <form class="d-inline" method="post" action="<?= site_url('acp/events/delete/' . $row['id']) ?>" onsubmit="return confirm('Delete?')">
              <?= csrf_field() ?>
              <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (! $rows): ?>
        <tr class="acp-empty-row"><td colspan="5" class="acp-empty">No events yet.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>
