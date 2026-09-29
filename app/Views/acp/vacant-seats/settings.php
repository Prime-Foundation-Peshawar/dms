<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>
<?php
$inst = $row['instructions_json'] ?? [];
if (!is_array($inst)) { $inst = []; }
$instText = implode("\n", $inst);
?>
<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/vacant-seats') ?>">Back to seats</a>
  <a class="acp-btn acp-btn-ghost" href="<?= site_url('vacant-seats') ?>" target="_blank" rel="noopener">View page</a>
</div>
<div class="acp-card">
  <form class="acp-form" method="post" action="<?= site_url('acp/vacant-seats/settings') ?>">
    <?= csrf_field() ?>
    <label>Alert HTML<textarea name="alert_html" style="min-height:80px"><?= esc($row['alert_html'] ?? '') ?></textarea></label>
    <label>Intro HTML<textarea name="intro_html" style="min-height:80px"><?= esc($row['intro_html'] ?? '') ?></textarea></label>
    <label>Instructions <span class="acp-muted">(one per line)</span>
      <textarea name="instructions" style="min-height:160px"><?= esc($instText) ?></textarea>
    </label>
    <div style="display:grid;gap:14px;grid-template-columns:1fr 1fr">
      <label>Apply deadline<input type="text" name="apply_deadline" value="<?= esc($row['apply_deadline'] ?? '') ?>"></label>
      <label>Deadline note<input type="text" name="apply_deadline_note" value="<?= esc($row['apply_deadline_note'] ?? '') ?>"></label>
      <label>Merit date<input type="text" name="merit_date" value="<?= esc($row['merit_date'] ?? '') ?>"></label>
      <label>Merit note<input type="text" name="merit_date_note" value="<?= esc($row['merit_date_note'] ?? '') ?>"></label>
    </div>
    <label>Apply URL<input type="url" name="apply_url" value="<?= esc($row['apply_url'] ?? '') ?>"></label>
    <div class="acp-actions"><button class="acp-btn acp-btn-primary" type="submit">Save settings</button></div>
  </form>
</div>
<?= $this->endSection() ?>
