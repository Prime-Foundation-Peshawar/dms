<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php $id = (int) ($row['id'] ?? 0); $action = $id ? site_url('acp/gallery/' . $id) : site_url('acp/gallery'); ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/gallery') ?>">Back to albums</a>
</div>
<div class="acp-card">
  <form class="acp-form" method="post" action="<?= $action ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <h3 style="margin:0 0 8px">Album</h3>
    <label>Title<input type="text" name="title" value="<?= esc($row['title'] ?? '') ?>" required></label>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>Slug<input type="text" name="slug" value="<?= esc($row['slug'] ?? '') ?>"></label>
      <label>Category filter key<input type="text" name="category" value="<?= esc($row['category'] ?? '') ?>" placeholder="campus"></label>
      <label>Icon class<input type="text" name="icon" value="<?= esc($row['icon'] ?? 'bi-images') ?>"></label>
    </div>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
      <label>Sort order<input type="number" name="sort_order" value="<?= esc((string) ($row['sort_order'] ?? 10)) ?>"></label>
      <label style="display:flex;align-items:flex-end;gap:8px;padding-bottom:8px"><input type="checkbox" name="is_active" value="1" <?= !empty($row['is_active']) ? 'checked' : '' ?>> Active</label>
    </div>

    <?php if ($id): ?>
      <h3 style="margin:18px 0 8px">Images</h3>
      <?php if (empty($images)): ?>
        <p class="acp-muted">No images yet — add one below.</p>
      <?php else: ?>
        <?php foreach ($images as $i => $img): ?>
          <div class="acp-card" style="margin-bottom:12px;padding:12px;background:#f8fafc">
            <input type="hidden" name="img_id[<?= $i ?>]" value="<?= (int) $img['id'] ?>">
            <label>Title<input type="text" name="img_title[<?= $i ?>]" value="<?= esc($img['title'] ?? '') ?>"></label>
            <label>Caption<input type="text" name="img_caption[<?= $i ?>]" value="<?= esc($img['caption'] ?? '') ?>"></label>
            <div style="display:grid;gap:14px;grid-template-columns:2fr 1fr 1fr">
              <label>Image path<input type="text" name="img_path[<?= $i ?>]" value="<?= esc($img['image_path'] ?? '') ?>"></label>
              <label>Span class
                <select name="img_span[<?= $i ?>]">
                  <option value="" <?= ($img['span_class'] ?? '') === '' ? 'selected' : '' ?>>(none)</option>
                  <option value="span-2" <?= ($img['span_class'] ?? '') === 'span-2' ? 'selected' : '' ?>>span-2</option>
                  <option value="span-2-row" <?= ($img['span_class'] ?? '') === 'span-2-row' ? 'selected' : '' ?>>span-2-row</option>
                </select>
              </label>
              <label>Sort<input type="number" name="img_sort[<?= $i ?>]" value="<?= esc((string) ($img['sort_order'] ?? 10)) ?>"></label>
            </div>
            <label>Upload replacement<input type="file" name="img_file_<?= $i ?>" accept="image/jpeg,image/png,image/webp"></label>
            <label><input type="checkbox" name="img_active[<?= $i ?>]" value="1" <?= !empty($img['is_active']) ? 'checked' : '' ?>> Active (clear title+path to remove)</label>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <h3 style="margin:18px 0 8px">Add image</h3>
      <label>Title<input type="text" name="new_img_title" value=""></label>
      <label>Caption<input type="text" name="new_img_caption" value=""></label>
      <div style="display:grid;gap:14px;grid-template-columns:2fr 1fr 1fr">
        <label>Image path<input type="text" name="new_img_path" value=""></label>
        <label>Span
          <select name="new_img_span">
            <option value="">(none)</option>
            <option value="span-2">span-2</option>
            <option value="span-2-row">span-2-row</option>
          </select>
        </label>
        <label>Sort<input type="number" name="new_img_sort" value="100"></label>
      </div>
      <label>Upload<input type="file" name="new_img_file" accept="image/jpeg,image/png,image/webp"></label>
    <?php else: ?>
      <p class="acp-muted">Save the album first, then add images.</p>
    <?php endif; ?>

    <div class="acp-actions"><button class="acp-btn acp-btn-primary" type="submit">Save</button></div>
  </form>
</div>
<?= $this->endSection() ?>
