<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php $id = (int) ($row['id'] ?? 0); $action = $id ? site_url('acp/newsletters/' . $id) : site_url('acp/newsletters'); ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/newsletters') ?>">Back to list</a>
</div>
<div class="acp-card">
  <form class="acp-form" method="post" action="<?= $action ?>">
    <?= csrf_field() ?>
    <label>Title<input type="text" name="title" value="<?= esc($row['title'] ?? '') ?>" required></label>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>Date label<input type="text" name="date_label" value="<?= esc($row['date_label'] ?? '') ?>"></label>
      <label>Published at<input type="date" name="published_at" value="<?= esc(substr((string) ($row['published_at'] ?? ''), 0, 10)) ?>"></label>
      <label>Sort order<input type="number" name="sort_order" value="<?= esc((string) ($row['sort_order'] ?? 10)) ?>"></label>
    </div>
    <label>PDF URL<input type="url" name="pdf_url" value="<?= esc($row['pdf_url'] ?? '') ?>" required></label>
    <label>Cover image URL<input type="url" name="cover_url" value="<?= esc($row['cover_url'] ?? '') ?>"></label>
    <label><input type="checkbox" name="is_active" value="1" <?= !empty($row['is_active']) ? 'checked' : '' ?>> Active</label>
    <div class="acp-actions"><button class="acp-btn acp-btn-primary" type="submit">Save</button></div>
  </form>
</div>
<?= $this->endSection() ?>
