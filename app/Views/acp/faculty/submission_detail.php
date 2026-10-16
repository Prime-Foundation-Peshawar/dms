<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
  $badge = match ($sub['status'] ?? '') {
      'approved' => 'acp-badge-ok',
      'rejected' => 'acp-badge-muted',
      default => 'acp-badge-warn',
  };
?>
<div class="acp-page-head">
  <div>
    <p>
      <a href="<?= site_url('acp/faculty/submissions') ?>">Back to queue</a>
      · <span class="acp-badge <?= esc($badge) ?>"><?= esc($sub['status'] ?? '') ?></span>
    </p>
  </div>
</div>
<div class="row g-3">
  <div class="col-lg-7">
    <div class="acp-card">
      <h2 class="h5"><?= esc($sub['emp_name'] ?? '') ?></h2>
      <p class="text-muted"><?= esc($sub['des_title'] ?? '') ?> · <?= esc($sub['dep_name'] ?? '') ?></p>
      <p><strong>Slug:</strong> <?= esc($sub['slug'] ?? '') ?></p>
      <p><strong>Submitted:</strong> <?= esc($sub['submitted_at'] ?? '') ?></p>
      <?php if (! empty($sub['contact_phone'])): ?>
        <p><strong>Phone (office, not published):</strong> <?= esc($sub['contact_phone']) ?></p>
      <?php endif; ?>

      <h3 class="h6 mt-3">Research topics</h3>
      <?php if (empty($sub['research_preferences'])): ?>
        <p class="text-muted">None</p>
      <?php else: ?>
        <ul><?php foreach ($sub['research_preferences'] as $item): ?><li><?= esc((string) $item) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>

      <h3 class="h6">Education</h3>
      <?php if (empty($sub['qualifications'])): ?>
        <p class="text-muted">None</p>
      <?php else: ?>
        <ul><?php foreach ($sub['qualifications'] as $item): ?><li><?= esc((string) $item) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>

      <h3 class="h6">Duties</h3>
      <?php if (empty($sub['skills'])): ?>
        <p class="text-muted">None</p>
      <?php else: ?>
        <ul><?php foreach ($sub['skills'] as $item): ?><li><?= esc((string) $item) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>

      <h3 class="h6">Publications</h3>
      <?php if (! empty($sub['publications_url'])): ?>
        <p><a href="<?= esc($sub['publications_url']) ?>" target="_blank" rel="noopener"><?= esc($sub['publications_url']) ?></a></p>
      <?php endif; ?>
      <?php if (! empty($sub['publications_file'])): ?>
        <p><a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/file/publications') ?>">Download publications file</a></p>
      <?php else: ?>
        <p class="text-muted">No publications file</p>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="acp-card">
      <h3 class="h6">Attachments</h3>
      <?php if (! empty($sub['photo'])): ?>
        <p><a class="btn-acp btn-acp-sm btn-acp-outline" href="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/file/photo') ?>">Download photo</a></p>
      <?php else: ?>
        <p class="text-muted">No photo uploaded</p>
      <?php endif; ?>
      <?php if (! empty($existing)): ?>
        <p class="text-muted">Live profile exists. Approve updates that profile and leaves the HRMS name and department switch as they are.</p>
      <?php else: ?>
        <p class="text-muted">No live profile yet. Approve will create one.</p>
      <?php endif; ?>
    </div>
    <?php if (($sub['status'] ?? '') === 'pending'): ?>
      <div class="acp-card mt-3">
        <form method="post" action="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/approve') ?>">
          <?= csrf_field() ?>
          <label class="form-label">Review note (optional)</label>
          <textarea class="form-control mb-2" name="review_note" rows="3" placeholder="Internal note"></textarea>
          <button class="btn-acp btn-acp-primary" type="submit">Approve and publish</button>
        </form>
        <form class="mt-3" method="post" action="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/reject') ?>">
          <?= csrf_field() ?>
          <label class="form-label">Reject note (optional)</label>
          <textarea class="form-control mb-2" name="review_note" rows="3" placeholder="Reason"></textarea>
          <button class="btn-acp btn-acp-danger" type="submit">Reject</button>
        </form>
      </div>
    <?php else: ?>
      <div class="acp-card mt-3">
        <p class="text-muted mb-1">Reviewed <?= esc($sub['reviewed_at'] ?? '') ?></p>
        <?php if (! empty($sub['review_note'])): ?>
          <p><?= esc($sub['review_note']) ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?= $this->endSection() ?>
