<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div><p>CV profiles linked to HRMS by empId. Prefer editing from a department page. Profiles with no empId are CV-only (not in HRMS).</p></div>
  <div class="d-flex gap-2 flex-wrap">
    <div class="acp-search">
      <i class="bi bi-search"></i>
      <input class="form-control form-control-sm" type="search" placeholder="Filter…" data-acp-filter="#facultyTable">
    </div>
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/faculty/submissions') ?>"><i class="bi bi-inbox"></i> Review updates</a>
    <a class="btn-acp btn-acp-primary" href="<?= site_url('acp/faculty/create') ?>"><i class="bi bi-plus-lg"></i> New profile</a>
  </div>
</div>
<div class="acp-card acp-fu">
  <div class="acp-table-wrap">
    <table class="acp-table" id="facultyTable" style="table-layout:auto">
      <thead>
        <tr>
          <th>Name</th>
          <th class="col-narrow">empId</th>
          <th style="width:20%">Department</th>
          <th style="width:18%">Designation</th>
          <th class="col-status">Public</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><?= esc($row['name']) ?><div class="small text-muted"><?= esc($row['slug']) ?></div></td>
          <td><?php
            $eid = trim((string) ($row['emp_id'] ?? ''));
            echo $eid !== '' ? '<code>' . esc($eid) . '</code>' : '<span class="small text-muted">CV-only</span>';
          ?></td>
          <td><?= esc($row['department_name'] ?? '') ?><?= ! empty($row['dept_override']) ? ' <span class="acp-badge acp-badge-warn">override</span>' : '' ?></td>
          <td><?= esc($row['designation'] ?? '') ?></td>
          <td><?= ! empty($row['is_public']) ? '<span class="acp-badge acp-badge-ok">Yes</span>' : '<span class="acp-badge acp-badge-muted">No</span>' ?></td>
          <td class="col-actions">
            <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/faculty/edit/' . $row['id']) ?>">Edit</a>
            <form class="d-inline" method="post" action="<?= site_url('acp/faculty/delete/' . $row['id']) ?>" onsubmit="return confirm('Delete profile?')">
              <?= csrf_field() ?>
              <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (! $rows): ?>
        <tr class="acp-empty-row"><td colspan="6" class="acp-empty">No faculty profiles yet.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>
