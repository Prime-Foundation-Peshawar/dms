<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div><p>ACP login accounts.</p></div>
  <a class="btn-acp btn-acp-primary" href="<?= site_url('acp/users/create') ?>"><i class="bi bi-plus-lg"></i> New user</a>
</div>
<div class="acp-card acp-fu">
  <div class="acp-table-wrap">
    <table class="acp-table" style="table-layout:auto">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th class="col-narrow">Role</th>
          <th class="col-status">Active</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><?= esc($row['name'] ?? '') ?></td>
          <td><?= esc($row['email']) ?></td>
          <td><?= esc($row['role']) ?></td>
          <td><?= ! empty($row['is_active']) ? '<span class="acp-badge acp-badge-ok">Yes</span>' : '<span class="acp-badge acp-badge-muted">No</span>' ?></td>
          <td class="col-actions">
            <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/users/edit/' . $row['id']) ?>">Edit</a>
            <form class="d-inline" method="post" action="<?= site_url('acp/users/delete/' . $row['id']) ?>" onsubmit="return confirm('Delete user?')">
              <?= csrf_field() ?>
              <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>
