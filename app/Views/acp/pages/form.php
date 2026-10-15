<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php $isEdit = ! empty($row); ?>
<form method="post" action="<?= site_url($isEdit ? 'acp/pages/update/' . $row['id'] : 'acp/pages/store') ?>" class="acp-card acp-fu">
  <?= csrf_field() ?>
  <div class="acp-card-body">
    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Basics</h2>
      <div class="acp-fields">
        <div class="acp-field">
          <label class="form-label">Title</label>
          <input class="form-control" name="title" value="<?= esc($row['title'] ?? '') ?>" required>
        </div>
        <div class="acp-field">
          <label class="form-label">Slug</label>
          <input class="form-control" name="slug" value="<?= esc($row['slug'] ?? '') ?>" <?= $isEdit ? 'readonly' : '' ?> placeholder="auto from title">
        </div>
        <div class="acp-field">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <?php $st = $row['status'] ?? 'draft'; ?>
            <option value="published" <?= $st === 'published' ? 'selected' : '' ?>>published</option>
            <option value="draft" <?= $st === 'draft' ? 'selected' : '' ?>>draft</option>
          </select>
        </div>
        <div class="acp-field">
          <label class="form-label">Label (hero)</label>
          <input class="form-control" name="label" value="<?= esc($row['label'] ?? '') ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Section</label>
          <?= acp_select('section', config(\Config\AcpLists::class)->pageSections(), $row['section'] ?? '') ?>
        </div>
        <div class="acp-field">
          <label class="form-label">View key</label>
          <?= acp_select('view_key', config(\Config\AcpLists::class)->pageViewKeys(), $row['view_key'] ?? '', '— Select template —') ?>
        </div>
        <div class="acp-field">
          <label class="form-label">Priority</label>
          <input class="form-control" name="priority" type="number" step="0.1" value="<?= esc($row['priority'] ?? '0.5') ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Changefreq</label>
          <?= acp_select('changefreq', config(\Config\AcpLists::class)->pageChangefreqs(), $row['changefreq'] ?? 'monthly', '') ?>
        </div>
        <div class="acp-field span-full">
          <label class="form-label">Options</label>
          <div class="acp-field-flags">
            <div class="form-check m-0">
              <input class="form-check-input" type="checkbox" name="use_cms" value="1" id="use_cms" <?= ! isset($row['use_cms']) || ! empty($row['use_cms']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="use_cms">Render from CMS content</label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Content</h2>
      <div class="acp-fields" style="margin-bottom:1.1rem">
        <div class="acp-field span-full">
          <label class="form-label">SEO description</label>
          <textarea class="form-control" name="description" rows="2"><?= esc($row['description'] ?? '') ?></textarea>
        </div>
      </div>
      <?= view('acp/partials/dual_editor', [
          'nameBase' => 'body',
          'label' => 'Page content',
          'text' => $row['body_text'] ?? '',
          'html' => $row['body_html'] ?? '',
      ]) ?>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Advanced</h2>
      <div class="acp-fields">
        <div class="acp-field span-full">
          <label class="form-label">SEO JSON</label>
          <textarea class="form-control font-monospace" name="seo_json" rows="3"><?= esc($row['seo_json'] ?? '{}') ?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="acp-card-foot">
    <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save</button>
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/pages') ?>">Cancel</a>
    <?php if ($isEdit && ! empty($row['slug'])): ?>
      <a class="btn-acp btn-acp-outline" href="<?= site_url($row['slug']) ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i> View</a>
    <?php endif; ?>
  </div>
</form>
<?= $this->endSection() ?>
