<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php $isEdit = ! empty($row); ?>
<form method="post" action="<?= site_url($isEdit ? 'acp/users/update/' . $row['id'] : 'acp/users/store') ?>" class="acp-card acp-fu">
  <?= csrf_field() ?>
  <div class="acp-card-body">
    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Account</h2>
      <div class="acp-fields">
        <div class="acp-field">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" value="<?= esc($row['name'] ?? '') ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="email" value="<?= esc($row['email'] ?? '') ?>" required>
        </div>
        <div class="acp-field">
          <label class="form-label">Password<?= $isEdit ? ' (leave blank to keep)' : '' ?></label>
          <input class="form-control" type="password" name="password" <?= $isEdit ? '' : 'required' ?>>
        </div>
        <div class="acp-field">
          <label class="form-label">Role</label>
          <?= acp_select('role', config(\Config\AcpLists::class)->userRoles(), $row['role'] ?? 'admin', '') ?>
          <p class="form-text mb-0">committee: Patron / Committee members. They can view the Dashboard and Reports only.</p>
        </div>
        <div class="acp-field span-full">
          <label class="form-label">Status</label>
          <div class="acp-field-flags">
            <div class="form-check m-0">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?= ! isset($row['is_active']) || ! empty($row['is_active']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="acp-card-foot">
    <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save</button>
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/users') ?>">Cancel</a>
  </div>
</form>
<?= $this->endSection() ?>
