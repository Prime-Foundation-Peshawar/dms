<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php $id = (int) ($row['id'] ?? 0); $action = $id ? site_url('acp/pages/' . $id) : site_url('acp/pages'); ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/pages') ?>">Back to list</a>
  <?php if ($id && ($row['status'] ?? '') === 'published' && !empty($row['slug'])): ?>
    <a class="acp-btn acp-btn-ghost" href="<?= site_url((string) $row['slug']) ?>" target="_blank" rel="noopener">View page</a>
  <?php endif; ?>
</div>
<div class="acp-card">
  <form class="acp-form" method="post" action="<?= $action ?>">
    <?= csrf_field() ?>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
      <label>Title<input type="text" name="title" value="<?= esc($row['title'] ?? '') ?>" required></label>
      <label>Slug<input type="text" name="slug" value="<?= esc($row['slug'] ?? '') ?>" placeholder="auto from title"></label>
      <label>Hero title<input type="text" name="hero_title" value="<?= esc($row['hero_title'] ?? '') ?>"></label>
      <label>Breadcrumb label<input type="text" name="breadcrumb_label" value="<?= esc($row['breadcrumb_label'] ?? '') ?>"></label>
      <label>Status
        <select name="status">
          <option value="draft" <?= ($row['status'] ?? '') === 'draft' ? 'selected' : '' ?>>draft</option>
          <option value="published" <?= ($row['status'] ?? '') === 'published' ? 'selected' : '' ?>>published</option>
        </select>
      </label>
      <label>Sort order<input type="number" name="sort_order" value="<?= esc((string) ($row['sort_order'] ?? 10)) ?>"></label>
    </div>
    <label>Meta title<input type="text" name="meta_title" value="<?= esc($row['meta_title'] ?? '') ?>"></label>
    <label>Meta description<textarea name="meta_description" style="min-height:60px"><?= esc($row['meta_description'] ?? '') ?></textarea></label>
    <label>Body HTML <span class="acp-muted">(HTML allowed — inner page content)</span>
      <textarea name="body_html" style="min-height:280px"><?= esc($row['body_html'] ?? '') ?></textarea>
    </label>
    <label><input type="checkbox" name="show_sidebar" value="1" <?= !empty($row['show_sidebar']) ? 'checked' : '' ?>> Show sidebar</label>
    <div class="acp-actions"><button class="acp-btn acp-btn-primary" type="submit">Save</button></div>
  </form>
</div>
<?= $this->endSection() ?>
