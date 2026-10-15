<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div>
    <p>Manage photo albums shown on the public gallery page.</p>
    <?php if (! empty($seo['id'])): ?>
      <p class="small text-muted mb-0">Page SEO:
        <a href="<?= site_url('acp/pages/edit/' . $seo['id']) ?>">edit gallery page title/description</a>
        · <a href="<?= site_url('gallery') ?>" target="_blank">view public</a>
      </p>
    <?php endif; ?>
  </div>
</div>

<div class="acp-card acp-fu mb-3">
  <div class="acp-card-body">
    <h2 class="acp-form-section-title">New album</h2>
    <form method="post" action="<?= site_url('acp/gallery/album-store') ?>" class="acp-fields">
      <?= csrf_field() ?>
      <div class="acp-field"><label class="form-label">Title</label><input class="form-control" name="title" required></div>
      <div class="acp-field"><label class="form-label">Slug</label><input class="form-control" name="slug" placeholder="auto from title"></div>
      <div class="acp-field"><label class="form-label">Icon</label><?= acp_select('icon', config(\Config\AcpLists::class)->galleryIcons(), 'bi-images', '') ?></div>
      <div class="acp-field d-flex align-items-end"><button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-plus-lg"></i> Add album</button></div>
    </form>
  </div>
</div>

<div class="acp-card acp-fu">
  <div class="acp-card-body">
    <div data-acp-sortable data-reorder-url="<?= site_url('acp/gallery/albums-reorder') ?>">
      <?php foreach ($albums as $album): ?>
        <div class="d-flex align-items-center gap-3 py-3 border-bottom" data-id="<?= (int) $album['id'] ?>">
          <span class="acp-sort-handle btn-acp btn-acp-sm btn-acp-outline" style="cursor:grab"><i class="bi bi-grip-vertical"></i></span>
          <div class="flex-grow-1">
            <div class="fw-semibold"><?= esc($album['title']) ?></div>
            <div class="small text-muted"><?= esc($album['slug']) ?> · <?= count($album['items'] ?? []) ?> photos · <i class="bi <?= esc($album['icon'] ?? 'bi-images') ?>"></i></div>
          </div>
          <a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/gallery/album/' . $album['id']) ?>">Manage</a>
          <form method="post" action="<?= site_url('acp/gallery/album-delete/' . $album['id']) ?>" onsubmit="return confirm('Delete album and all photos?')">
            <?= csrf_field() ?>
            <button class="btn-acp btn-acp-sm btn-acp-danger" type="submit">Delete</button>
          </form>
        </div>
      <?php endforeach; ?>
      <?php if (! $albums): ?>
        <div class="acp-empty">No albums yet. Create one above or run <code>php spark db:seed GallerySeeder</code>.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
