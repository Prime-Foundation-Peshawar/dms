<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div><p class="mb-0">Manage the slides at the top of the home page. Click a slide to edit it; drag slides to set their order.</p></div>
  <a class="btn-acp btn-acp-outline" href="<?= site_url('/') ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i> View home</a>
</div>
<form method="post" action="<?= site_url('acp/slider/save') ?>" enctype="multipart/form-data" class="acp-card acp-fu">
  <?= csrf_field() ?>
  <div class="acp-card-body">
    <div id="sliderSlides" class="row g-3">
      <?php foreach ($slides as $i => $slide): ?>
        <?= view('acp/slider/slide_fields', ['slide' => $slide, 'i' => $i]) ?>
      <?php endforeach; ?>
    </div>
    <template id="slideTemplate"><?= view('acp/slider/slide_fields', ['slide' => [], 'i' => '__INDEX__']) ?></template>
    <button class="btn-acp btn-acp-outline mt-3" type="button" id="addSlide"><i class="bi bi-plus-lg"></i> Add slide</button>
    <?php if (! $slides): ?><p class="small text-muted mt-3 mb-0">No slides yet. The campus film banner shows on the home page until you add one.</p><?php endif; ?>
  </div>
  <div class="acp-card-foot"><button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save slider</button></div>
</form>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
(() => {
  const list = document.getElementById('sliderSlides');
  const template = document.getElementById('slideTemplate');
  let next = list.children.length;

  document.getElementById('addSlide').addEventListener('click', () => {
    list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', next++));
  });
  list.addEventListener('click', e => {
    if (e.target.closest('.acp-sort-handle')) e.preventDefault();
    const btn = e.target.closest('[data-remove-slide]');
    if (btn) btn.closest('.slider-slide').remove();
  });
  // Open a collapsed slide if one of its required fields is empty on save.
  list.addEventListener('invalid', e => { e.target.closest('details').open = true; }, true);
  new Sortable(list, { animation: 150, handle: '.acp-sort-handle', draggable: '.slider-slide' });
})();
</script>
<?= $this->endSection() ?>
