<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<form method="post" action="<?= site_url('acp/settings/save') ?>" class="acp-card acp-fu">
  <?= csrf_field() ?>
  <div class="acp-card-body">
    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Key / value</h2>
      <?php foreach ($rows as $row): ?>
        <?php if (in_array($row['key'] ?? '', ['newsletters', 'vacant_seats'], true)) continue; ?>
        <div class="acp-fields mb-2">
          <div class="acp-field">
            <input class="form-control" name="key[]" value="<?= esc($row['key']) ?>" placeholder="key">
          </div>
          <div class="acp-field">
            <textarea class="form-control" name="value[]" rows="2"><?= esc($row['value'] ?? '') ?></textarea>
          </div>
        </div>
      <?php endforeach; ?>
      <div class="acp-fields">
        <div class="acp-field"><input class="form-control" name="key[]" placeholder="new.key"></div>
        <div class="acp-field"><textarea class="form-control" name="value[]" rows="2" placeholder="value"></textarea></div>
      </div>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Site modules</h2>
      <p class="small text-muted mb-0">
        Vacant seats are managed under
        <a href="<?= site_url('acp/vacant-seats') ?>">Vacant seats</a>.
        Newsletters are an external page
        (<a href="https://riphahpsh.edu.pk/news-letter.php" target="_blank" rel="noopener">riphahpsh.edu.pk</a>).
      </p>
    </div>
  </div>
  <div class="acp-card-foot">
    <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save settings</button>
  </div>
</form>
<?= $this->endSection() ?>
