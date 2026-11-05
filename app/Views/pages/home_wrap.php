<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= esc(dms_asset('assets/css/career-slider.css'), 'attr') ?>">
<?= $this->include('pages/home') ?>
<script src="<?= esc(dms_asset('assets/js/career-slider.js'), 'attr') ?>" defer></script>
<?= $this->endSection() ?>
