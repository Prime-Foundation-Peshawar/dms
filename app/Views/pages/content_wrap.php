<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('pages/' . $viewKey) ?>
<?= $this->endSection() ?>
