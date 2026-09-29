<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php $id = (int) ($row['id'] ?? 0); $action = $id ? site_url('acp/events/' . $id) : site_url('acp/events'); ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/events') ?>">Back to list</a>
</div>
<div class="acp-card">
  <form class="acp-form" method="post" action="<?= $action ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
      <label>Title<input type="text" name="title" value="<?= esc($row['title'] ?? '') ?>" required></label>
      <label>Slug<input type="text" name="slug" value="<?= esc($row['slug'] ?? '') ?>"></label>
      <label>Category
        <select name="category">
          <?php foreach ($categories as $cat): ?>
            <option value="<?= esc($cat) ?>" <?= ($row['category'] ?? '') === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Status
        <select name="status">
          <option value="draft" <?= ($row['status'] ?? '') === 'draft' ? 'selected' : '' ?>>draft</option>
          <option value="published" <?= ($row['status'] ?? '') === 'published' ? 'selected' : '' ?>>published</option>
        </select>
      </label>
      <label>Author<input type="text" name="author" value="<?= esc($row['author'] ?? '') ?>"></label>
      <label>Published at<input type="text" name="published_at" value="<?= esc($row['published_at'] ?? '') ?>"></label>
      <label>Event date<input type="date" name="event_date" value="<?= esc($row['event_date'] ?? '') ?>"></label>
      <label>Event end date<input type="date" name="event_end_date" value="<?= esc($row['event_end_date'] ?? '') ?>"></label>
      <label>Venue<input type="text" name="venue" value="<?= esc($row['venue'] ?? '') ?>"></label>
      <label>Read minutes<input type="number" name="read_minutes" value="<?= esc((string) ($row['read_minutes'] ?? '')) ?>"></label>
      <label>Sort order<input type="number" name="sort_order" value="<?= esc((string) ($row['sort_order'] ?? 0)) ?>"></label>
    </div>
    <label>Excerpt<textarea name="excerpt" style="min-height:80px"><?= esc($row['excerpt'] ?? '') ?></textarea></label>
    <label>Body HTML <span class="acp-muted">(HTML allowed)</span>
      <textarea name="body_html" style="min-height:220px"><?= esc($row['body_html'] ?? '') ?></textarea>
    </label>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
      <label>Cover image path<input type="text" name="cover_image" value="<?= esc($row['cover_image'] ?? '') ?>"></label>
      <label>Upload cover<input type="file" name="cover_file" accept="image/jpeg,image/png,image/webp"></label>
      <label>Card icon<input type="text" name="card_icon" value="<?= esc($row['card_icon'] ?? '') ?>"></label>
      <label>Card gradient<input type="text" name="card_gradient" value="<?= esc($row['card_gradient'] ?? '') ?>"></label>
      <label>External link URL<input type="text" name="link_url" value="<?= esc($row['link_url'] ?? '') ?>"></label>
      <label>Link label<input type="text" name="link_label" value="<?= esc($row['link_label'] ?? '') ?>"></label>
    </div>
    <label>Tags<textarea name="tags" style="min-height:60px"><?= esc(implode("\n", $row['tags'] ?? [])) ?></textarea></label>
    <label><input type="checkbox" name="is_featured" value="1" <?= !empty($row['is_featured']) ? 'checked' : '' ?>> Featured</label>
    <div class="acp-actions"><button class="acp-btn acp-btn-primary" type="submit">Save</button></div>
  </form>
</div>
<?= $this->endSection() ?>
