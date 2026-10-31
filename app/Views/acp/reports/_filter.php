<?php
/**
 * Period selector shared by the Reports and Website Analytics pages.
 *
 * @var array  $period
 * @var array  $options
 * @var string $action path the form submits to
 */
$typeLabels = ['month' => 'Monthly', 'quarter' => 'Quarterly', 'year' => 'Annual', 'range' => 'Custom Dates'];
[$rangeFrom, $rangeTo] = $period['type'] === 'range'
    ? explode('_', $period['ref'], 2)
    : [date('Y-m-01'), date('Y-m-d')];
?>
<form class="acp-card acp-report-filter acp-no-print mb-3" method="get" action="<?= site_url($action) ?>" id="reportFilter">
  <div class="acp-seg" role="tablist">
    <?php foreach ($typeLabels as $t => $lbl): ?>
      <label class="acp-seg-item<?= $period['type'] === $t ? ' active' : '' ?>">
        <input type="radio" name="period" value="<?= $t ?>" <?= $period['type'] === $t ? 'checked' : '' ?>> <?= $lbl ?>
      </label>
    <?php endforeach; ?>
  </div>
  <?php foreach ($options as $t => $opts): ?>
    <select class="form-select form-select-sm acp-report-ref" name="ref" data-period="<?= $t ?>" <?= $period['type'] === $t ? '' : 'hidden disabled' ?>>
      <?php foreach ($opts as $val => $lbl): ?>
        <option value="<?= esc($val) ?>" <?= $period['type'] === $t && $period['ref'] === (string) $val ? 'selected' : '' ?>><?= esc($lbl) ?></option>
      <?php endforeach; ?>
    </select>
  <?php endforeach; ?>
  <div class="acp-report-range" data-period="range" <?= $period['type'] === 'range' ? '' : 'hidden' ?>>
    <label class="small text-muted" for="rangeFrom">From</label>
    <input class="form-control form-control-sm" type="date" name="from" id="rangeFrom" value="<?= esc($rangeFrom) ?>" max="<?= date('Y-m-d') ?>" <?= $period['type'] === 'range' ? '' : 'disabled' ?>>
    <label class="small text-muted" for="rangeTo">To</label>
    <input class="form-control form-control-sm" type="date" name="to" id="rangeTo" value="<?= esc($rangeTo) ?>" max="<?= date('Y-m-d') ?>" <?= $period['type'] === 'range' ? '' : 'disabled' ?>>
    <button class="btn-acp btn-acp-sm btn-acp-primary" type="submit">Apply</button>
  </div>
</form>
<script>
(function () {
  const form = document.getElementById('reportFilter');
  const showType = function (type) {
    form.querySelectorAll('[data-period]').forEach(function (el) {
      const on = el.dataset.period === type;
      el.hidden = !on;
      if (el.tagName === 'SELECT') el.disabled = !on;
      el.querySelectorAll('input').forEach(function (i) { i.disabled = !on; });
    });
    form.querySelectorAll('.acp-seg-item').forEach(function (lbl) {
      lbl.classList.toggle('active', lbl.querySelector('input').value === type);
    });
  };
  form.querySelectorAll('input[name="period"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
      showType(radio.value);
      // Custom dates wait for Apply; the fixed periods load straight away.
      if (radio.value !== 'range') form.submit();
    });
  });
  form.querySelectorAll('.acp-report-ref').forEach(function (sel) {
    sel.addEventListener('change', function () { form.submit(); });
  });
})();
</script>
