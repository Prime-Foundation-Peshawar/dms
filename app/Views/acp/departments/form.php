<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
$introLines = $introLines ?? [];
$hrmsFaculty = $hrmsFaculty ?? [];
$activities = $activities ?? [];
$returnTo = site_url('acp/departments/edit/' . $row['id']);
?>
<form method="post" action="<?= site_url('acp/departments/update/' . $row['id']) ?>" class="acp-card acp-fu">
  <?= csrf_field() ?>
  <div class="acp-card-body">
    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Basics</h2>
      <div class="acp-fields">
        <div class="acp-field">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" value="<?= esc($row['name']) ?>" required>
        </div>
        <div class="acp-field">
          <label class="form-label">Slug</label>
          <input class="form-control" value="<?= esc($row['slug']) ?>" disabled>
        </div>
        <div class="acp-field">
          <label class="form-label">Group</label>
          <?= acp_select('group_name', config(\Config\AcpLists::class)->departmentGroups(), $row['group_name'] ?? '', '') ?>
        </div>
        <div class="acp-field">
          <label class="form-label">HOD</label>
          <input class="form-control" name="hod" value="<?= esc($row['hod'] ?? '') ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Icon</label>
          <?= acp_select('icon', config(\Config\AcpLists::class)->departmentIcons(), $row['icon'] ?? '', '— Select icon —') ?>
        </div>
        <div class="acp-field">
          <label class="form-label">ORIC ID</label>
          <input class="form-control" name="oric_id" value="<?= esc($row['oric_id'] ?? '') ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Updated</label>
          <input class="form-control" type="date" name="content_updated_at" value="<?= esc($row['content_updated_at'] ?? '') ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Visibility</label>
          <div class="acp-field-flags">
            <div class="form-check m-0">
              <input class="form-check-input" type="checkbox" name="hidden" value="1" id="hidden" <?= ! empty($row['hidden']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="hidden">Hidden from public directory</label>
            </div>
          </div>
        </div>
        <div class="acp-field span-full">
          <label class="form-label">Intro paragraphs</label>
          <textarea class="form-control" name="intro_text" rows="5" placeholder="One paragraph per line"><?= esc(implode("\n", $introLines)) ?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="acp-card-foot">
    <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save department</button>
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/departments') ?>">Back</a>
  </div>
</form>

<div class="acp-card acp-fu">
  <div class="acp-card-body">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h2 class="acp-form-section-title mb-0">Faculty from HRMS</h2>
      <span class="acp-badge acp-badge-info"><?= count($hrmsFaculty) ?> listed</span>
    </div>
    <p class="small text-muted mb-3">Use <strong>Default</strong> / <strong>Overridden</strong> to switch department source. Rows marked <strong>CV-only</strong> are seed/CV profiles with no HRMS empId.</p>
  </div>
  <div class="acp-table-wrap">
    <table class="acp-table" style="table-layout:auto">
      <thead>
        <tr>
          <th>Name</th>
          <th>Designation</th>
          <th>Qualification</th>
          <th class="col-narrow">HRMS dept</th>
          <th style="width:10rem">Dept source</th>
          <th class="col-actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($hrmsFaculty as $f): ?>
        <?php
          $hrmsDept = (string) ($f['hrmsDepName'] ?? '');
          $shownDept = (string) ($f['depName'] ?? '');
          $isOverride = ! empty($f['dept_override']);
          $empId = trim((string) ($f['empId'] ?? ''));
          $isCvOnly = $empId === '';
          $createUrl = site_url('acp/faculty/create') . '?' . http_build_query([
              'name' => $f['empName'] ?? $f['name'] ?? '',
              'emp_id' => $empId,
              'department_name' => $row['name'],
              'department_slug' => $row['slug'],
              'designation' => $f['desTitle'] ?? '',
              'return_to' => $returnTo,
          ]);
        ?>
        <tr>
          <td>
            <?= esc($f['name'] ?? $f['empName'] ?? '') ?>
            <?php if ($isCvOnly): ?>
              <div class="small text-muted">empId: — <span class="acp-badge acp-badge-muted">CV-only</span></div>
            <?php else: ?>
              <div class="small text-muted">empId: <?= esc($empId) ?></div>
            <?php endif; ?>
            <?php if ($isOverride && $shownDept !== '' && strcasecmp($hrmsDept, $shownDept) !== 0): ?>
              <div class="small" style="color:var(--gold)">Showing as <?= esc($shownDept) ?></div>
            <?php endif; ?>
          </td>
          <td><?= esc($f['desTitle'] ?? '') ?></td>
          <td class="small"><?= esc($f['qualification'] ?? '') ?></td>
          <td class="small"><?= esc($hrmsDept !== '' ? $hrmsDept : '—') ?></td>
          <td>
            <?php if ($isCvOnly): ?>
              <span class="small text-muted">Profile only</span>
            <?php else: ?>
            <form method="post" action="<?= site_url('acp/faculty/set-dept-source') ?>">
              <?= csrf_field() ?>
              <input type="hidden" name="return_to" value="<?= esc($returnTo) ?>">
              <input type="hidden" name="emp_id" value="<?= esc($empId) ?>">
              <input type="hidden" name="profile_id" value="<?= esc((string) ($f['profile_id'] ?? '')) ?>">
              <input type="hidden" name="name" value="<?= esc($f['empName'] ?? $f['name'] ?? '') ?>">
              <input type="hidden" name="designation" value="<?= esc($f['desTitle'] ?? '') ?>">
              <input type="hidden" name="department_name" value="<?= esc($row['name']) ?>">
              <input type="hidden" name="department_slug" value="<?= esc($row['slug']) ?>">
              <div class="acp-seg">
                <button type="submit" name="mode" value="default" class="<?= $isOverride ? '' : 'is-on-default' ?>">Default</button>
                <button type="submit" name="mode" value="override" class="<?= $isOverride ? 'is-on-override' : '' ?>">Overridden</button>
              </div>
            </form>
            <?php endif; ?>
          </td>
          <td class="col-actions">
            <?php if (! empty($f['profile_id'])): ?>
              <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/faculty/edit/' . $f['profile_id'] . '?return_to=' . rawurlencode($returnTo)) ?>">Edit</a>
            <?php else: ?>
              <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= esc($createUrl) ?>">Create</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (! $hrmsFaculty): ?>
        <tr class="acp-empty-row"><td colspan="6" class="acp-empty">No HRMS faculty matched this department.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="acp-card acp-fu">
  <div class="acp-card-body">
    <h2 class="acp-form-section-title">Activities <span class="text-muted fw-normal">(drag to reorder)</span></h2>
    <div class="acp-sortable mb-3" data-acp-sortable data-reorder-url="<?= site_url('acp/departments/activities-reorder') ?>">
      <?php foreach ($activities as $act): ?>
        <div class="acp-sort-item" data-id="<?= (int) $act['id'] ?>">
          <i class="bi bi-grip-vertical acp-sort-handle"></i>
          <div class="flex-grow-1">
            <strong><?= esc($act['title']) ?></strong>
            <div class="small text-muted"><?= esc($act['date_label'] ?? '') ?></div>
            <div class="small"><?= esc($act['body'] ?? '') ?></div>
          </div>
          <form method="post" action="<?= site_url('acp/departments/activity-delete/' . $act['id']) ?>">
            <?= csrf_field() ?>
            <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Remove</button>
          </form>
        </div>
      <?php endforeach; ?>
      <?php if (! $activities): ?>
        <div class="acp-empty">No activities yet.</div>
      <?php endif; ?>
    </div>
    <form method="post" action="<?= site_url('acp/departments/activity-store/' . $row['id']) ?>">
      <?= csrf_field() ?>
      <div class="acp-fields">
        <div class="acp-field">
          <label class="form-label">Title</label>
          <input class="form-control" name="title" placeholder="Title" required>
        </div>
        <div class="acp-field">
          <label class="form-label">Date label</label>
          <input class="form-control" name="date_label" placeholder="e.g. Ongoing 2026">
        </div>
        <div class="acp-field span-full">
          <label class="form-label">Body</label>
          <textarea class="form-control" name="body" rows="2" placeholder="Body"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn-acp btn-acp-primary btn-acp-sm" type="submit"><i class="bi bi-plus"></i> Add activity</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
