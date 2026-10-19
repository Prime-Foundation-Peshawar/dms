<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
  $badge = match ($sub['status'] ?? '') {
      'approved' => 'acp-badge-ok',
      'rejected' => 'acp-badge-muted',
      default => 'acp-badge-warn',
  };
  $photos = is_array($sub['photos'] ?? null) ? $sub['photos'] : [];
?>
<div class="acp-page-head">
  <div>
    <p>
      <a href="<?= site_url('acp/activities/submissions') ?>">Back to queue</a>
      · <span class="acp-badge <?= esc($badge) ?>"><?= esc($sub['status'] ?? '') ?></span>
    </p>
  </div>
</div>
<div class="row g-3">
  <div class="col-lg-7">
    <div class="acp-card">
      <h2 class="h5"><?= esc($sub['title'] ?? '') ?></h2>
      <p class="text-muted mb-1"><?= esc($sub['department_name'] ?? '') ?></p>
      <?php if (! empty($sub['date_label'])): ?>
        <p><strong>Date / period:</strong> <?= esc($sub['date_label']) ?></p>
      <?php endif; ?>
      <p><strong>Submitted:</strong> <?= esc($sub['submitted_at'] ?? '') ?></p>

      <h3 class="h6 mt-3">Description</h3>
      <?php if (trim((string) ($sub['body'] ?? '')) === ''): ?>
        <p class="text-muted">None</p>
      <?php else: ?>
        <div class="acp-prewrap"><?= nl2br(esc((string) $sub['body'])) ?></div>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="acp-card">
      <h3 class="h6">Images</h3>
      <?php if (! $photos): ?>
        <p class="text-muted">No images attached</p>
      <?php else: ?>
        <div class="d-flex flex-wrap gap-2">
          <?php foreach ($photos as $i => $p): ?>
            <a href="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/file/' . (int) $i) ?>" target="_blank" rel="noopener">
              <img src="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/file/' . (int) $i) ?>"
                   alt="Submission image <?= (int) $i + 1 ?>"
                   style="width:96px;height:96px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb">
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <p class="text-muted mt-3 mb-0">Approving publishes the title, date, and description on the department page. Images are kept with this submission for reference.</p>
    </div>
    <?php if (($sub['status'] ?? '') === 'pending'): ?>
      <div class="acp-card mt-3">
        <form method="post" action="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/approve') ?>">
          <?= csrf_field() ?>
          <label class="form-label">Review note (optional)</label>
          <textarea class="form-control mb-2" name="review_note" rows="3" placeholder="Internal note"></textarea>
          <button class="btn-acp btn-acp-primary" type="submit">Approve and publish</button>
        </form>
        <form class="mt-3" method="post" action="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/reject') ?>">
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
