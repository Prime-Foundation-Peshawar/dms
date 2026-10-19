<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="<?= dms_asset('assets/css/faculty-update.css') ?>" rel="stylesheet"/>
<link href="<?= dms_asset('assets/css/activity-update.css') ?>" rel="stylesheet"/>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Departments</span>
    <h1>Activity Submission</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('departments') ?>">Departments</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Activity Submission</span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">
    <div class="fu-wrap">

      <?php if (! empty($thanks)): ?>
        <div class="fu-card fu-thanks">
          <div class="fu-check" aria-hidden="true"><i class="bi bi-check-lg"></i></div>
          <h2>Submission Received</h2>
          <p>Your activity has been submitted for review. It will appear on the website after administrative approval.</p>
          <div class="fu-actions" style="justify-content:center;margin-top:18px">
            <a class="btn-pmc btn-pmc-outline" href="<?= site_url('update-activity' . (! empty($selectedSlug) ? ('?dept=' . rawurlencode($selectedSlug)) : '')) ?>"><i class="bi bi-plus-lg"></i> Submit another</a>
            <a class="btn-pmc btn-pmc-primary" href="<?= site_url('departments') ?>"><i class="bi bi-building"></i> Departments</a>
          </div>
        </div>
      <?php else: ?>

        <div class="fu-intro">
          <h2>Department Activity Form</h2>
          <p>Please select your department and enter the activity details. All submissions are reviewed before they appear on the website.</p>
        </div>

        <?php if (! empty($draftSaved)): ?>
          <div class="fu-alert fu-alert-ok" role="status">Draft saved for this department. You can continue later from the same link.</div>
        <?php endif; ?>

        <?php if (! empty($errorMsg)): ?>
          <div class="fu-alert fu-alert-err" role="alert"><?= esc($errorMsg) ?></div>
        <?php endif; ?>

        <?php
          $draftTitle = (string) ($draft['title'] ?? '');
          $draftDate  = (string) ($draft['date_label'] ?? '');
          $draftBody  = (string) ($draft['body'] ?? '');
          $draftPhotos = is_array($draft['photos'] ?? null) ? $draft['photos'] : [];
          $draftSavedAt = (string) ($draft['saved_at'] ?? '');
        ?>

        <?php if (! empty($draft) && empty($draftSaved)): ?>
          <div class="fu-alert fu-alert-ok" role="status">
            Saved draft loaded for this department<?= $draftSavedAt !== '' ? (' (last saved ' . esc($draftSavedAt) . ')') : '' ?>.
          </div>
        <?php endif; ?>

        <form class="fu-form" method="post" action="<?= site_url('update-activity-submit') ?>" enctype="multipart/form-data" novalidate>
          <?= csrf_field() ?>
          <input type="text" name="website" class="fu-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

          <div class="fu-card">
            <h3>1. Department</h3>
            <label class="fu-label" for="departmentSlug">Select department <span class="req">*</span></label>
            <select class="au-select" id="departmentSlug" name="department_slug" required>
              <option value="">— Select department —</option>
              <?php foreach ($departments as $d): ?>
                <option value="<?= esc($d['slug'] ?? '') ?>" <?= (($selectedSlug ?? '') === ($d['slug'] ?? '')) ? 'selected' : '' ?>>
                  <?= esc($d['name'] ?? '') ?>
                </option>
              <?php endforeach; ?>
            </select>
            <p class="fu-note">Each department keeps one temporary draft. Select the department to restore it.</p>
          </div>

          <div class="fu-card">
            <h3>2. Activity Title</h3>
            <label class="fu-label" for="title">Title <span class="req">*</span></label>
            <input class="fu-input" type="text" id="title" name="title" maxlength="190" required
              value="<?= esc($draftTitle) ?>"
              placeholder="e.g. Workshop on oral hygiene">
          </div>

          <div class="fu-card">
            <h3>3. Date / Period</h3>
            <label class="fu-label" for="dateLabel">Date or academic period <span class="fu-optional">(optional)</span></label>
            <input class="fu-input" type="text" id="dateLabel" name="date_label" maxlength="120"
              value="<?= esc($draftDate) ?>"
              placeholder="e.g. March 2026  or  Ongoing">
            <p class="fu-note">Examples: March 2026, Term 2026, Ongoing. Leave blank if not applicable.</p>
          </div>

          <div class="fu-card">
            <h3>4. Description</h3>
            <label class="fu-label" for="body">Brief description</label>
            <textarea class="fu-textarea" id="body" name="body" maxlength="4000" required
              placeholder="Summary of the activity, participants, and key outcomes"><?= esc($draftBody) ?></textarea>
          </div>

          <div class="fu-card">
            <h3>5. Images <span class="fu-optional">(optional)</span></h3>
            <label class="fu-label" for="photos">Attach images</label>
            <input class="au-file-hidden" type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
            <div class="au-drop" id="photoDrop" tabindex="0" role="button" aria-label="Attach images by browsing or dropping files">
              <div class="au-drop-inner" id="photoDropInner">
                <i class="bi bi-images" aria-hidden="true"></i>
                <strong>Drop images here</strong>
                <span>or click to browse from your device</span>
                <em>JPG or PNG · up to 6 images · max 4 MB each</em>
              </div>
            </div>
            <div class="au-previews" id="photoPreviews" hidden></div>
            <p class="fu-note" id="photoCount">No images selected. This section is optional.</p>
            <?php if ($draftPhotos !== []): ?>
              <div class="au-draft-photos">
                <p class="fu-note"><strong><?= count($draftPhotos) ?></strong> image<?= count($draftPhotos) === 1 ? '' : 's' ?> already saved in this draft. New uploads will replace them.</p>
                <?php foreach ($draftPhotos as $p): ?>
                  <input type="hidden" name="draft_photos[]" value="<?= esc($p) ?>">
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

          <div class="fu-card">
            <div class="fu-actions au-actions">
              <button type="submit" class="btn-pmc btn-pmc-outline" id="draftBtn"
                formaction="<?= site_url('update-activity-draft') ?>" formnovalidate>
                <i class="bi bi-save"></i> Save draft
              </button>
              <button type="submit" class="btn-pmc btn-pmc-primary" id="submitBtn">
                <i class="bi bi-send"></i> Submit for review
              </button>
            </div>
            <p class="fu-note" style="text-align:center;margin-top:12px">Use Save draft to keep work temporarily. Submit for review when ready. Content is published only after approval.</p>
          </div>
        </form>
      <?php endif; ?>

      <div class="au-recent">
        <h2>Recently Published Activities</h2>
        <p><?= ! empty($selectedSlug) ? 'Approved activities for the selected department.' : 'Latest approved activities across departments.' ?></p>
        <?php if (! empty($recent)): ?>
          <ul class="au-list">
            <?php foreach ($recent as $item): ?>
              <li>
                <a class="au-item" href="<?= esc($item['href']) ?>">
                  <span class="au-item-dept"><?= esc($item['dept']) ?></span>
                  <p class="au-item-title"><?= esc($item['title']) ?></p>
                  <?php if (($item['date'] ?? '') !== ''): ?>
                    <p class="au-item-date"><i class="bi bi-calendar3"></i> <?= esc($item['date']) ?></p>
                  <?php endif; ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="au-empty">No published activities yet.</div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<script>
(function () {
  const sel = document.getElementById('departmentSlug');
  if (sel) {
    sel.addEventListener('change', function () {
      const v = sel.value || '';
      const url = new URL(window.location.href);
      if (v) url.searchParams.set('dept', v); else url.searchParams.delete('dept');
      url.searchParams.delete('error');
      url.searchParams.delete('thanks');
      window.location.href = url.toString();
    });
  }

  const input = document.getElementById('photos');
  const drop = document.getElementById('photoDrop');
  const previews = document.getElementById('photoPreviews');
  const countEl = document.getElementById('photoCount');
  if (!input || !drop || !previews || !countEl) return;

  const MAX_FILES = 6;
  const MAX_BYTES = 4 * 1024 * 1024;
  let files = [];

  function syncInput() {
    const dt = new DataTransfer();
    files.forEach((f) => dt.items.add(f));
    input.files = dt.files;
  }

  function render() {
    previews.innerHTML = '';
    if (!files.length) {
      previews.hidden = true;
        countEl.textContent = 'No images selected. This section is optional.';
      syncInput();
      return;
    }
    previews.hidden = false;
    countEl.textContent = files.length + ' image' + (files.length === 1 ? '' : 's') + ' selected.';
    files.forEach((file, idx) => {
      const card = document.createElement('div');
      card.className = 'au-preview';
      const img = document.createElement('img');
      img.alt = file.name;
      img.src = URL.createObjectURL(file);
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'au-preview-remove';
      btn.setAttribute('aria-label', 'Remove image');
      btn.innerHTML = '&times;';
      btn.addEventListener('click', () => {
        files.splice(idx, 1);
        render();
      });
      card.appendChild(img);
      card.appendChild(btn);
      previews.appendChild(card);
    });
    syncInput();
  }

  function addFiles(list) {
    const incoming = Array.from(list || []);
    for (const file of incoming) {
      if (!/^image\/(jpeg|png|webp|gif)$/i.test(file.type)) {
        countEl.textContent = 'Please upload JPG, PNG, WEBP, or GIF images only.';
        continue;
      }
      if (file.size > MAX_BYTES) {
        countEl.textContent = 'Each image must be under 4 MB.';
        continue;
      }
      if (files.length >= MAX_FILES) {
        countEl.textContent = 'A maximum of ' + MAX_FILES + ' images is allowed.';
        break;
      }
      const dup = files.some((f) => f.name === file.name && f.size === file.size);
      if (!dup) files.push(file);
    }
    render();
  }

  drop.addEventListener('click', () => input.click());
  drop.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      input.click();
    }
  });
  input.addEventListener('change', () => addFiles(input.files));
  ;['dragenter', 'dragover'].forEach((ev) => {
    drop.addEventListener(ev, (e) => {
      e.preventDefault();
      e.stopPropagation();
      drop.classList.add('is-drag');
    });
  });
  ;['dragleave', 'drop'].forEach((ev) => {
    drop.addEventListener(ev, (e) => {
      e.preventDefault();
      e.stopPropagation();
      drop.classList.remove('is-drag');
    });
  });
  drop.addEventListener('drop', (e) => addFiles(e.dataTransfer && e.dataTransfer.files));
})();
</script>

<?= $this->endSection() ?>
