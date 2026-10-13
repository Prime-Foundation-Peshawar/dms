<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div>
    <p><a href="<?= site_url('acp/gallery') ?>">← Albums</a></p>
    <p class="mb-0">Upload and reorder photos for this album.</p>
  </div>
  <a class="btn-acp btn-acp-outline" href="<?= site_url('gallery') ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Public gallery</a>
</div>

<div class="acp-card acp-fu mb-3">
  <div class="acp-card-body">
    <h2 class="acp-form-section-title">Album details</h2>
    <form method="post" action="<?= site_url('acp/gallery/album-update/' . $album['id']) ?>" class="acp-fields">
      <?= csrf_field() ?>
      <div class="acp-field"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= esc($album['title']) ?>" required></div>
      <div class="acp-field"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= esc($album['slug']) ?>" required></div>
      <div class="acp-field"><label class="form-label">Icon</label><?= acp_select('icon', config(\Config\AcpLists::class)->galleryIcons(), $album['icon'] ?? 'bi-images', '') ?></div>
      <div class="acp-field d-flex align-items-end"><button class="btn-acp btn-acp-primary" type="submit">Save album</button></div>
    </form>
  </div>
</div>

<div class="acp-card acp-fu mb-3">
  <div class="acp-card-body">
    <h2 class="acp-form-section-title">Add photos</h2>
    <form method="post" action="<?= site_url('acp/gallery/items-store/' . $album['id']) ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="acp-drop mb-3">
        <input type="file" name="images[]" accept="image/*" multiple>
        <div data-drop-label>Drop images here or click to browse</div>
      </div>
      <div class="acp-fields mb-2">
        <div class="acp-field"><label class="form-label">Or asset path</label><input class="form-control" name="image_path" placeholder="assets/images/campus/example.jpg"></div>
        <div class="acp-field"><label class="form-label">Title</label><input class="form-control" name="title"></div>
        <div class="acp-field"><label class="form-label">Caption</label><input class="form-control" name="caption"></div>
      </div>
      <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-upload"></i> Add photos</button>
    </form>
  </div>
</div>

<div class="acp-card acp-fu">
  <div class="acp-card-body">
    <h2 class="acp-form-section-title">Photos (<?= count($items) ?>)</h2>
    <div class="row g-3" data-acp-sortable data-reorder-url="<?= site_url('acp/gallery/items-reorder') ?>">
      <?php foreach ($items as $item): ?>
        <div class="col-md-6 col-xl-4" data-id="<?= (int) $item['id'] ?>">
          <div class="border rounded p-2 h-100">
            <div class="d-flex gap-2 mb-2">
              <span class="acp-sort-handle btn-acp btn-acp-sm btn-acp-outline" style="cursor:grab"><i class="bi bi-grip-vertical"></i></span>
              <img src="<?= esc(base_url($item['image_path'])) ?>" alt="" style="width:72px;height:72px;object-fit:cover;border-radius:8px;">
              <div class="small text-muted text-truncate"><?= esc($item['image_path']) ?></div>
            </div>
            <form method="post" action="<?= site_url('acp/gallery/item-update/' . $item['id']) ?>" enctype="multipart/form-data">
              <?= csrf_field() ?>
              <div class="mb-2"><input class="form-control form-control-sm" name="title" value="<?= esc($item['title'] ?? '') ?>" placeholder="Title"></div>
              <div class="mb-2"><input class="form-control form-control-sm" name="caption" value="<?= esc($item['caption'] ?? '') ?>" placeholder="Caption"></div>
              <div class="mb-2"><input class="form-control form-control-sm" name="image_path" value="<?= esc($item['image_path'] ?? '') ?>" placeholder="Path"></div>
              <div class="mb-2"><input class="form-control form-control-sm" type="file" name="image" accept="image/*"></div>
              <div class="d-flex gap-2">
                <button class="btn-acp btn-acp-sm btn-acp-primary" type="submit">Save</button>
              </div>
            </form>
            <form class="mt-2" method="post" action="<?= site_url('acp/gallery/item-delete/' . $item['id']) ?>" onsubmit="return confirm('Delete photo?')">
              <?= csrf_field() ?>
              <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Delete</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (! $items): ?>
        <div class="col-12 acp-empty">No photos in this album yet.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
