<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
$lists = config(\Config\AcpLists::class);
$yearOpts = $lists->vacantSeatYears();
$sessionOpts = $lists->vacantSeatSessions();
?>
<div class="acp-page-head acp-fu">
  <div><p>Edit vacant seats table, notices, and apply link for the public page.</p></div>
  <a class="btn-acp btn-acp-outline" href="<?= site_url('vacant-seats') ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Preview</a>
</div>

<form method="post" action="<?= site_url('acp/vacant-seats/save') ?>" class="acp-card acp-fu">
  <?= csrf_field() ?>
  <div class="acp-card-body">
    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Intro &amp; heading</h2>
      <div class="acp-fields">
        <div class="acp-field span-full"><label class="form-label">Alert / intro</label><textarea class="form-control" name="intro" rows="3"><?= esc($data['intro'] ?? '') ?></textarea></div>
        <div class="acp-field span-full"><label class="form-label">Table heading</label><input class="form-control" name="heading" value="<?= esc($data['heading'] ?? '') ?>"></div>
      </div>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Seat rows</h2>
      <div id="vsRows">
        <?php
        $rows = $data['rows'] ?? [];
        if (! $rows) {
            $rows = [['year' => '', 'session' => '', 'seats' => '', 'note' => '']];
        }
        foreach ($rows as $row):
        ?>
        <div class="acp-fields mb-2 vs-row">
          <div class="acp-field">
            <label class="form-label">Year</label>
            <?= acp_select('year[]', $yearOpts, $row['year'] ?? '', '— Year —') ?>
          </div>
          <div class="acp-field">
            <label class="form-label">Session</label>
            <?= acp_select('session[]', $sessionOpts, $row['session'] ?? '', '— Session —') ?>
          </div>
          <div class="acp-field">
            <label class="form-label">Seats</label>
            <input class="form-control" name="seats[]" value="<?= esc($row['seats'] ?? '') ?>" placeholder="Seats">
          </div>
          <div class="acp-field">
            <label class="form-label">Note</label>
            <input class="form-control" name="note[]" value="<?= esc($row['note'] ?? '') ?>" placeholder="Note (optional)">
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" class="btn-acp btn-acp-sm btn-acp-outline" id="vsAddRow"><i class="bi bi-plus"></i> Add row</button>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Notices (one per line)</h2>
      <textarea class="form-control" name="notices" rows="8"><?= esc(implode("\n", $data['notices'] ?? [])) ?></textarea>
    </div>

    <div class="acp-form-section">
      <h2 class="acp-form-section-title">Apply CTA</h2>
      <div class="acp-fields">
        <div class="acp-field"><label class="form-label">Last date</label><input class="form-control" name="last_date" value="<?= esc($data['last_date'] ?? '') ?>"></div>
        <div class="acp-field"><label class="form-label">Button label</label><input class="form-control" name="apply_label" value="<?= esc($data['apply_label'] ?? '') ?>"></div>
        <div class="acp-field span-full"><label class="form-label">Apply URL</label><input class="form-control" name="apply_url" value="<?= esc($data['apply_url'] ?? '') ?>"></div>
      </div>
    </div>
  </div>
  <div class="acp-card-foot">
    <button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save</button>
  </div>
</form>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
(function () {
  var btn = document.getElementById('vsAddRow');
  var wrap = document.getElementById('vsRows');
  if (!btn || !wrap) return;
  var yearOpts = <?= json_encode($yearOpts, JSON_UNESCAPED_UNICODE) ?>;
  var sessionOpts = <?= json_encode($sessionOpts, JSON_UNESCAPED_UNICODE) ?>;
  function optsHtml(name, options, emptyLabel) {
    var h = '<select class="form-select" name="' + name + '"><option value="">' + emptyLabel + '</option>';
    options.forEach(function (o) {
      h += '<option value="' + String(o).replace(/"/g, '&quot;') + '">' + o + '</option>';
    });
    return h + '</select>';
  }
  btn.addEventListener('click', function () {
    var div = document.createElement('div');
    div.className = 'acp-fields mb-2 vs-row';
    div.innerHTML = '<div class="acp-field"><label class="form-label">Year</label>' + optsHtml('year[]', yearOpts, '— Year —') + '</div>'
      + '<div class="acp-field"><label class="form-label">Session</label>' + optsHtml('session[]', sessionOpts, '— Session —') + '</div>'
      + '<div class="acp-field"><label class="form-label">Seats</label><input class="form-control" name="seats[]" placeholder="Seats"></div>'
      + '<div class="acp-field"><label class="form-label">Note</label><input class="form-control" name="note[]" placeholder="Note"></div>';
    wrap.appendChild(div);
  });
})();
</script>
<?= $this->endSection() ?>
