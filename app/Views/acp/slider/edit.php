<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php $id = (int) ($row['id'] ?? 0); $action = $id ? site_url('acp/slider/' . $id) : site_url('acp/slider'); ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/slider') ?>">Back to list</a>
</div>
<div class="acp-card">
  <form class="acp-form" method="post" action="<?= $action ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
      <label>Sort order<input type="number" name="sort_order" value="<?= esc((string) ($row['sort_order'] ?? 10)) ?>"></label>
      <label>Media type
        <select name="media_type" id="mediaType">
          <option value="image" <?= ($row['media_type'] ?? '') === 'image' ? 'selected' : '' ?>>image</option>
          <option value="video" <?= ($row['media_type'] ?? '') === 'video' ? 'selected' : '' ?>>video</option>
        </select>
      </label>
    </div>
    <label><input type="checkbox" name="is_active" value="1" <?= !empty($row['is_active']) ? 'checked' : '' ?>> Active</label>

    <div id="imageFields">
      <h3 style="margin:12px 0 0">Image</h3>
      <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
        <label>Image path<input type="text" name="image_path" value="<?= esc($row['image_path'] ?? '') ?>"></label>
        <label>Upload image<input type="file" name="image_file" accept="image/jpeg,image/png,image/webp"></label>
        <label>WebP path<input type="text" name="image_webp_path" value="<?= esc($row['image_webp_path'] ?? '') ?>"></label>
      </div>
    </div>

    <div id="videoFields">
      <h3 style="margin:12px 0 0">Video</h3>
      <p class="acp-muted">Large MP4s may need manual upload to <code>public/assets/videos/</code>; paste paths here if so.</p>
      <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
        <label>Poster path<input type="text" name="poster_path" value="<?= esc($row['poster_path'] ?? '') ?>"></label>
        <label>Upload poster<input type="file" name="poster_file" accept="image/jpeg,image/png,image/webp"></label>
        <label>Poster webp<input type="text" name="poster_webp_path" value="<?= esc($row['poster_webp_path'] ?? '') ?>"></label>
        <label>Video 720 path<input type="text" name="video_720_path" value="<?= esc($row['video_720_path'] ?? '') ?>"></label>
        <label>Upload 720<input type="file" name="video_720_file" accept="video/mp4"></label>
        <label>Video 480 path<input type="text" name="video_480_path" value="<?= esc($row['video_480_path'] ?? '') ?>"></label>
        <label>Upload 480<input type="file" name="video_480_file" accept="video/mp4"></label>
      </div>
    </div>

    <h3 style="margin:12px 0 0">Copy</h3>
    <label>Brand<input type="text" name="brand" value="<?= esc($row['brand'] ?? '') ?>"></label>
    <label>Title HTML <span class="acp-muted">(e.g. Your &lt;span class="hl-teal"&gt;MBBS&lt;/span&gt; Journey)</span>
      <input type="text" name="title_html" value="<?= esc($row['title_html'] ?? '') ?>">
    </label>
    <label>Body<textarea name="body" style="min-height:80px"><?= esc($row['body'] ?? '') ?></textarea></label>
    <label>Body sub<textarea name="body_sub" style="min-height:60px"><?= esc($row['body_sub'] ?? '') ?></textarea></label>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>CTA 1 label<input type="text" name="cta1_label" value="<?= esc($row['cta1_label'] ?? '') ?>"></label>
      <label>CTA 1 URL<input type="text" name="cta1_url" value="<?= esc($row['cta1_url'] ?? '') ?>"></label>
      <label>CTA 1 style
        <select name="cta1_style">
          <option value="primary" <?= ($row['cta1_style'] ?? '') === 'primary' ? 'selected' : '' ?>>primary</option>
          <option value="outline-white" <?= ($row['cta1_style'] ?? '') === 'outline-white' ? 'selected' : '' ?>>outline-white</option>
        </select>
      </label>
      <label>CTA 2 label<input type="text" name="cta2_label" value="<?= esc($row['cta2_label'] ?? '') ?>"></label>
      <label>CTA 2 URL<input type="text" name="cta2_url" value="<?= esc($row['cta2_url'] ?? '') ?>"></label>
      <label>CTA 2 style
        <select name="cta2_style">
          <option value="outline-white" <?= ($row['cta2_style'] ?? '') === 'outline-white' ? 'selected' : '' ?>>outline-white</option>
          <option value="primary" <?= ($row['cta2_style'] ?? '') === 'primary' ? 'selected' : '' ?>>primary</option>
        </select>
      </label>
    </div>
    <label>Aria label<input type="text" name="aria_label" value="<?= esc($row['aria_label'] ?? '') ?>"></label>
    <div class="acp-actions"><button class="acp-btn acp-btn-primary" type="submit">Save</button></div>
  </form>
</div>
<script>
(function () {
  const sel = document.getElementById('mediaType');
  const img = document.getElementById('imageFields');
  const vid = document.getElementById('videoFields');
  function sync() {
    const v = sel && sel.value === 'video';
    if (img) img.style.display = v ? 'none' : '';
    if (vid) vid.style.display = v ? '' : 'none';
  }
  sel && sel.addEventListener('change', sync);
  sync();
})();
</script>
<?= $this->endSection() ?>
