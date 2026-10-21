<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
$row = is_array($row ?? null) ? $row : [];
$isEdit = ! empty($row['id']);
$returnTo = $return_to ?? '';
$lists = $lists ?? ['qualifications' => '', 'experience' => '', 'skills' => '', 'publications' => ''];
$photoUrl = trim((string) ($row['photo'] ?? ''));
if ($photoUrl !== '' && ! preg_match('#^https?://#i', $photoUrl)) {
    $photoUrl = base_url(ltrim($photoUrl, '/'));
}
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
          <div class="acp-photo-picker">
            <div class="acp-photo-preview<?= $photoUrl !== '' ? ' has-photo' : '' ?>" id="facultyPhotoPreview">
              <?php if ($photoUrl !== ''): ?>
                <img src="<?= esc($photoUrl) ?>" alt="Current photo" id="facultyPhotoImg">
              <?php else: ?>
                <span class="acp-photo-placeholder" id="facultyPhotoPlaceholder"><i class="bi bi-person"></i></span>
                <img src="" alt="" id="facultyPhotoImg" hidden>
              <?php endif; ?>
            </div>
            <div class="acp-photo-actions">
              <div class="acp-drop acp-photo-drop" id="facultyPhotoDrop">
                <strong><i class="bi bi-cloud-upload"></i> Upload photo</strong>
                <span data-drop-label>Drop an image here, or click to browse</span>
                <em>JPG, PNG or WEBP · square crop works best</em>
                <input type="file" name="photo" id="facultyPhotoInput" accept="image/jpeg,image/png,image/webp,image/gif">
              </div>
              <p class="form-text mb-0">A clear headshot shows best on the faculty directory and profile page.</p>
            </div>
          </div>
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
<script>
(function () {
  const input = document.getElementById('facultyPhotoInput');
  const preview = document.getElementById('facultyPhotoPreview');
  const img = document.getElementById('facultyPhotoImg');
  const placeholder = document.getElementById('facultyPhotoPlaceholder');
  const drop = document.getElementById('facultyPhotoDrop');
  if (!input || !preview || !img) return;

  function showFile(file) {
    if (!file || !/^image\//i.test(file.type)) return;
    const url = URL.createObjectURL(file);
    img.src = url;
    img.hidden = false;
    preview.classList.add('has-photo');
    if (placeholder) placeholder.hidden = true;
  }

  input.addEventListener('change', function () {
    showFile(input.files && input.files[0]);
  });
  ;['dragenter', 'dragover'].forEach(function (ev) {
    drop.addEventListener(ev, function (e) {
      e.preventDefault();
      drop.classList.add('is-dragover');
    });
  });
  ;['dragleave', 'drop'].forEach(function (ev) {
    drop.addEventListener(ev, function (e) {
      e.preventDefault();
      drop.classList.remove('is-dragover');
    });
  });
  drop.addEventListener('drop', function (e) {
    const file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    showFile(file);
  });
})();
</script>
<?= $this->endSection() ?>
