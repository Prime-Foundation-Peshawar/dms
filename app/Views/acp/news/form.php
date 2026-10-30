<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php $isEdit = ! empty($row); ?>
<form method="post" enctype="multipart/form-data" action="<?= site_url($isEdit ? 'acp/news/update/' . $row['id'] : 'acp/news/store') ?>" class="acp-card acp-fu">
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
          <input class="form-control" name="slug" value="<?= esc($row['slug'] ?? '') ?>" placeholder="auto from title">
        </div>
        <div class="acp-field">
          <label class="form-label">Category</label>
          <?= acp_select('category', config(\Config\AcpLists::class)->newsCategories(), $row['category'] ?? '') ?>
        </div>
        <div class="acp-field">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <?php $st = $row['status'] ?? 'draft'; ?>
            <option value="draft" <?= $st === 'draft' ? 'selected' : '' ?>>draft</option>
            <option value="published" <?= $st === 'published' ? 'selected' : '' ?>>published</option>
          </select>
        </div>
        <div class="acp-field">
          <label class="form-label">Published at</label>
          <input class="form-control" type="datetime-local" name="published_at" value="<?= esc(isset($row['published_at']) ? date('Y-m-d\TH:i', strtotime($row['published_at'])) : date('Y-m-d\TH:i')) ?>">
        </div>
        <div class="acp-field">
          <label class="form-label">Expires at</label>
          <input class="form-control" type="datetime-local" name="expires_at" value="<?= esc(! empty($row['expires_at']) ? date('Y-m-d\TH:i', strtotime($row['expires_at'])) : '') ?>">
          <p class="form-text mb-0">Hidden from the site after this. For Admissions it shows as "Apply by".</p>
        </div>
        <div class="acp-field">
          <label class="form-label">Button link <span class="text-muted fw-normal">(optional)</span></label>
          <input class="form-control" name="link_url" value="<?= esc($row['link_url'] ?? '') ?>" placeholder="https://… or a site page, e.g. admissions">
          <p class="form-text mb-0">Homepage card button. Leave blank to link to this item's own page.</p>
        </div>
        <div class="acp-field">
          <label class="form-label">Button label</label>
          <input class="form-control" name="link_label" maxlength="60" value="<?= esc($row['link_label'] ?? '') ?>" placeholder="Read more">
          <p class="form-text mb-0">e.g. Apply Now, Register Now.</p>
        </div>
        <div class="acp-field span-full">
          <label class="form-label">Excerpt</label>
          <textarea class="form-control" name="excerpt" rows="2"><?= esc($row['excerpt'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Content</h2>
      <?= view('acp/partials/dual_editor', [
          'nameBase' => 'body',
          'label' => 'Article body',
          'text' => $row['body_text'] ?? '',
          'html' => $row['body_html'] ?? '',
      ]) ?>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Media</h2>
      <div class="acp-fields">
        <div class="acp-field">
          <label class="form-label">Cover image</label>
          <div class="acp-drop">
            <strong><i class="bi bi-image"></i> Cover</strong>
            <span data-drop-label>Drop image or click to browse</span>
            <input type="file" name="cover_image" accept="image/*">
          </div>
          <p class="form-text mb-0">Shown as the "Advertisement" button on the homepage card.</p>
          <?php if (! empty($row['cover_image'])): ?>
            <img src="<?= base_url($row['cover_image']) ?>" alt="" class="img-thumbnail mt-2" style="max-height:120px">
          <?php endif; ?>
        </div>
        <div class="acp-field">
          <label class="form-label">Gallery</label>
          <div class="acp-drop">
            <strong><i class="bi bi-images"></i> Extra media</strong>
            <span data-drop-label>Drop files or click to browse</span>
            <input type="file" name="media[]" multiple accept="image/*,.pdf">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="acp-card-foot">
    <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save</button>
    <a class="btn-acp btn-acp-outline" href="<?= site_url('acp/news') ?>">Cancel</a>
  </div>
</form>

<?php if (! empty($media)): ?>
<div class="acp-card acp-fu">
  <div class="acp-card-body">
    <h2 class="acp-form-section-title">Attached media <span class="text-muted fw-normal">(drag to reorder)</span></h2>
    <div class="acp-media-grid acp-sortable" data-acp-sortable data-reorder-url="<?= site_url('acp/news/media-reorder') ?>">
      <?php foreach ($media as $m): ?>
        <div class="acp-media-tile acp-sort-item" data-id="<?= (int) $m['id'] ?>">
          <?php if (preg_match('/\.(jpe?g|png|gif|webp)$/i', (string) $m['path'])): ?>
            <img src="<?= base_url($m['path']) ?>" alt="">
          <?php else: ?>
            <div class="p-3 text-center small"><i class="bi bi-file-earmark"></i> File</div>
          <?php endif; ?>
          <div class="acp-media-meta">
            <i class="bi bi-grip-vertical acp-sort-handle"></i>
            <form method="post" action="<?= site_url('acp/news/media-delete/' . $m['id']) ?>">
              <?= csrf_field() ?>
              <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Remove</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>
