<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
$isEdit = ! empty($row['id']);
$returnTo = $return_to ?? '';
$lists = $lists ?? ['qualifications' => '', 'experience' => '', 'skills' => '', 'publications' => ''];
?>
<form method="post" enctype="multipart/form-data" action="<?= site_url($isEdit ? 'acp/faculty/update/' . $row['id'] : 'acp/faculty/store') ?>" class="acp-card acp-fu">
  <?= csrf_field() ?>
  <?php if ($returnTo !== ''): ?>
    <input type="hidden" name="return_to" value="<?= esc($returnTo) ?>">
  <?php endif; ?>
  <div class="acp-card-body">
    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Basics</h2>
      <p class="small text-muted mb-3">Link to HRMS with empId when available. Leave empId blank for CV-only profiles not in HRMS. Use override when they should appear under a different department.</p>
      <div class="acp-fields">
        <div class="acp-field">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" value="<?= esc($row['name'] ?? '') ?>" required>
        </div>
        <div class="acp-field">
          <label class="form-label">HRMS empId</label>
          <input class="form-control" name="emp_id" value="<?= esc($row['emp_id'] ?? '') ?>" placeholder="Leave blank if not in HRMS">
          <?php if (trim((string) ($row['emp_id'] ?? '')) === ''): ?>
            <div class="form-text">CV-only — not linked to an HRMS employee.</div>
          <?php endif; ?>
        </div>
        <div class="acp-field">
          <label class="form-label">Slug</label>
          <input class="form-control" name="slug" value="<?= esc($row['slug'] ?? '') ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Designation</label>
          <?= acp_select('designation', config(\Config\AcpLists::class)->facultyDesignations(), $row['designation'] ?? '') ?>
        </div>
        <div class="acp-field span-full">
          <label class="form-label">Photo</label>
          <div class="acp-drop">
            <strong><i class="bi bi-person-bounding-box"></i> Profile photo</strong>
            <span data-drop-label>Drop image or click</span>
            <input type="file" name="photo" accept="image/*">
          </div>
          <?php if (! empty($row['photo'])): ?>
            <img src="<?= base_url($row['photo']) ?>" alt="" class="img-thumbnail mt-2" style="max-height:120px">
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Department override</h2>
      <div class="acp-fields">
        <div class="acp-field">
          <label class="form-label">Department</label>
          <?php
            $deptNames = array_values(array_filter(array_map(
                static fn ($d) => trim((string) ($d['name'] ?? '')),
                is_array($departments ?? null) ? $departments : []
            )));
            sort($deptNames, SORT_NATURAL | SORT_FLAG_CASE);
          ?>
          <?= acp_select('department_name', $deptNames, $row['department_name'] ?? '', '— Select department —') ?>
        </div>
        <div class="acp-field">
          <label class="form-label">Flags</label>
          <div class="acp-field-flags">
            <div class="form-check m-0">
              <input class="form-check-input" type="checkbox" name="dept_override" value="1" id="dept_override" <?= ! empty($row['dept_override']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="dept_override">Override HRMS dept</label>
            </div>
            <div class="form-check m-0">
              <input class="form-check-input" type="checkbox" name="is_public" value="1" id="is_public" <?= ! isset($row['is_public']) || ! empty($row['is_public']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="is_public">Public</label>
            </div>
            <div class="form-check m-0">
              <input class="form-check-input" type="checkbox" name="is_hod" value="1" id="is_hod" <?= ! empty($row['is_hod']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="is_hod">HOD</label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">CV lists</h2>
      <div class="acp-fields">
        <?php
        $textAreas = [
            'qualifications' => 'Qualifications (one per line)',
            'experience' => 'Experience (one per line)',
            'skills' => 'Skills / duties (one per line)',
            'publications' => 'Publications (one per line)',
        ];
        foreach ($textAreas as $field => $label):
        ?>
        <div class="acp-field">
          <label class="form-label"><?= esc($label) ?></label>
          <textarea class="form-control" name="<?= esc($field) ?>" rows="5"><?= esc($lists[$field] ?? '') ?></textarea>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="acp-card-foot">
    <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save</button>
    <a class="btn-acp btn-acp-outline" href="<?= esc($returnTo !== '' ? $returnTo : site_url('acp/departments')) ?>">Back</a>
  </div>
</form>
<?= $this->endSection() ?>
