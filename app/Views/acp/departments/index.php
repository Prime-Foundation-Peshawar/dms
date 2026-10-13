<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div><p>Edit department copy, HRMS faculty, and activities.</p></div>
  <div class="acp-search">
    <i class="bi bi-search"></i>
    <input class="form-control form-control-sm" type="search" placeholder="Filter…" data-acp-filter="#deptsTable">
  </div>
</div>
<div class="acp-card acp-fu">
  <div class="acp-table-wrap">
    <table class="acp-table" id="deptsTable" style="table-layout:auto">
      <thead>
        <tr>
          <th>Name</th>
          <th style="width:16%">Group</th>
          <th style="width:22%">HOD</th>
          <th class="col-status">Hidden</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><?= esc($row['name']) ?><div class="small text-muted"><?= esc($row['slug']) ?></div></td>
          <td><?= esc($row['group_name']) ?></td>
          <td><?= esc($row['hod'] ?? '') ?></td>
          <td><?= ! empty($row['hidden']) ? '<span class="acp-badge acp-badge-warn">Yes</span>' : '<span class="acp-badge acp-badge-muted">No</span>' ?></td>
          <td class="col-actions"><a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/departments/edit/' . $row['id']) ?>">Edit</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>
