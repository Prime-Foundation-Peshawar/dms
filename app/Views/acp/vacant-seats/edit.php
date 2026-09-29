<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php $id = (int) ($row['id'] ?? 0); $action = $id ? site_url('acp/vacant-seats/' . $id) : site_url('acp/vacant-seats'); ?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/vacant-seats') ?>">Back to list</a>
</div>
<div class="acp-card">
  <form class="acp-form" method="post" action="<?= $action ?>">
    <?= csrf_field() ?>
    <label>Programme<input type="text" name="programme" value="<?= esc($row['programme'] ?? '') ?>" required></label>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr 1fr">
      <label>Session<input type="text" name="session_label" value="<?= esc($row['session_label'] ?? '') ?>"></label>
      <label>Vacant seats<input type="number" name="seats" value="<?= esc((string) ($row['seats'] ?? 0)) ?>"></label>
      <label>Sort order<input type="number" name="sort_order" value="<?= esc((string) ($row['sort_order'] ?? 10)) ?>"></label>
    </div>
    <label><input type="checkbox" name="is_active" value="1" <?= !empty($row['is_active']) ? 'checked' : '' ?>> Active</label>
    <div class="acp-actions"><button class="acp-btn acp-btn-primary" type="submit">Save</button></div>
  </form>
</div>
<?= $this->endSection() ?>
