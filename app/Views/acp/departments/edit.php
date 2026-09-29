<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>

<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>

<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/departments') ?>">Back to list</a>
  <a class="acp-btn acp-btn-ghost" href="<?= site_url('department?slug=' . rawurlencode((string) ($pack['slug'] ?? ''))) ?>" target="_blank" rel="noopener">View public page</a>
</div>

<div class="acp-card">
  <form class="acp-form" method="post" action="<?= site_url('acp/departments/' . (int) $row['id']) ?>" id="deptEditForm">
    <?= csrf_field() ?>

    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
      <label>Name
        <input type="text" name="name" value="<?= esc($pack['name'] ?? '') ?>" required>
      </label>
      <label>Group
        <input type="text" name="dept_group" value="<?= esc($pack['group'] ?? '') ?>" placeholder="Basic / Clinical">
      </label>
      <label>Icon class
        <input type="text" name="icon" value="<?= esc($pack['icon'] ?? '') ?>" placeholder="bi-body-text">
      </label>
      <label>Updated on
        <input type="date" name="updated_on" value="<?= esc($pack['updated'] ?? '') ?>">
      </label>
    </div>

    <label>Head of department
      <input type="text" name="hod_name" value="<?= esc($pack['hod'] ?? '') ?>">
    </label>

    <label>Introduction <span class="acp-muted">(blank line between paragraphs)</span>
      <textarea name="intro" style="min-height:160px"><?= esc(implode("\n\n", $pack['intro'] ?? [])) ?></textarea>
    </label>

    <h3 style="margin:8px 0 0">Activities</h3>
    <p class="acp-muted">Faculty roster still comes from HRMS on the public page. Edit activities here.</p>

    <div id="actList" style="display:grid;gap:12px">
      <?php
        $acts = $pack['activities'] ?? [];
        if ($acts === []) {
            $acts = [['title' => '', 'date' => '', 'text' => '']];
        }
        foreach ($acts as $i => $act):
      ?>
        <div class="acp-card" style="box-shadow:none;padding:14px">
          <label>Title
            <input type="text" name="act_title[]" value="<?= esc($act['title'] ?? '') ?>">
          </label>
          <label>Date label
            <input type="text" name="act_date[]" value="<?= esc($act['date'] ?? '') ?>" placeholder="2025–26">
          </label>
          <label>Description
            <textarea name="act_text[]" style="min-height:80px"><?= esc($act['text'] ?? '') ?></textarea>
          </label>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="acp-actions">
      <button type="button" class="acp-btn" id="addActBtn">Add activity</button>
      <button type="submit" class="acp-btn acp-btn-primary">Save department</button>
    </div>
  </form>
</div>

<template id="actTpl">
  <div class="acp-card" style="box-shadow:none;padding:14px">
    <label>Title
      <input type="text" name="act_title[]" value="">
    </label>
    <label>Date label
      <input type="text" name="act_date[]" value="" placeholder="2025–26">
    </label>
    <label>Description
      <textarea name="act_text[]" style="min-height:80px"></textarea>
    </label>
  </div>
</template>
<script>
document.getElementById('addActBtn')?.addEventListener('click', function () {
  const tpl = document.getElementById('actTpl');
  const list = document.getElementById('actList');
  if (!tpl || !list) return;
  list.appendChild(tpl.content.cloneNode(true));
});
</script>
<?= $this->endSection() ?>
