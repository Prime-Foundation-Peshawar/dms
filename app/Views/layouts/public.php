<?php
/**
 * Public site layout — master header/nav + page section + footer.
 */
helper(['public', 'dms']);
public_boot();
echo $this->include('partials/header');
?>
<?= $this->renderSection('content') ?>
<?= $this->renderSection('scripts') ?>
<?= $this->include('partials/footer') ?>
