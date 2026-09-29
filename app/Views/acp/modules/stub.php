<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-card">
  <h2 style="margin-top:0"><?= esc($label) ?></h2>
  <p class="acp-muted">This module is scaffolded in the ACP and will be connected to the database in the next phase. Faculty submissions and live profiles are available now.</p>
  <a class="acp-btn" href="<?= site_url('acp') ?>">Back to dashboard</a>
</div>
<?= $this->endSection() ?>
