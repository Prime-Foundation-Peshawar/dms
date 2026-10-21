<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<div class="acp-page-head acp-fu">
  <div><p class="mb-0">Manage the image slides displayed at the top of the home page. Drag slides to set their order.</p></div>
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
    <?php if (! $slides): ?><p class="small text-muted mt-3 mb-0">No managed slides yet. The current cinematic home hero remains visible until you add an enabled slide.</p><?php endif; ?>
  </div>
  <div class="acp-card-foot"><button class="btn-acp btn-acp-primary" type="submit"><i class="bi bi-check-lg"></i> Save slider</button></div>
</form>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
(() => { const list=document.getElementById('sliderSlides'), template=document.getElementById('slideTemplate');
  document.getElementById('addSlide').addEventListener('click', () => { const n=list.children.length; list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', n)); });
  list.addEventListener('click', e => { const btn=e.target.closest('[data-remove-slide]'); if (btn) btn.closest('.slider-slide').remove(); });
  new Sortable(list, { animation: 150, handle: '.acp-sort-handle', draggable: '.slider-slide' });
})();
</script>
<?= $this->endSection() ?>
