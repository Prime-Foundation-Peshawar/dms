<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>
<?php
  $badge = match ($sub['status'] ?? '') {
      'approved' => 'acp-badge-ok',
      'rejected' => 'acp-badge-muted',
      default => 'acp-badge-warn',
  };
  $photos = is_array($sub['photos'] ?? null) ? $sub['photos'] : [];
  $pending = ($sub['status'] ?? '') === 'pending';
?>
<div class="acp-page-head">
  <div>
    <p class="mb-0">
      <a href="<?= site_url('acp/activities/submissions') ?>"><i class="bi bi-arrow-left"></i> Back to queue</a>
      <span class="acp-badge <?= esc($badge) ?> ms-2"><?= esc($sub['status'] ?? '') ?></span>
    </p>
  </div>
</div>

<div class="acp-review">
  <div class="acp-review-main">
    <div class="acp-card acp-review-card">
      <p class="acp-review-eyebrow"><?= esc($sub['department_name'] ?? '') ?></p>
      <h2 class="acp-review-title"><?= esc($sub['title'] ?? '') ?></h2>

      <dl class="acp-review-meta">
        <?php if (! empty($sub['date_label'])): ?>
          <div>
            <dt>Date / period</dt>
            <dd><?= esc($sub['date_label']) ?></dd>
          </div>
        <?php endif; ?>
        <div>
          <dt>Submitted</dt>
          <dd><?= esc($sub['submitted_at'] ?? '') ?></dd>
        </div>
      </dl>

      <div class="acp-review-body">
        <h3>Description</h3>
        <?php if (trim((string) ($sub['body'] ?? '')) === ''): ?>
          <p class="text-muted mb-0">None</p>
        <?php else: ?>
          <p class="mb-0"><?= nl2br(esc((string) $sub['body'])) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="acp-review-side">
    <div class="acp-card acp-review-card">
      <h3 class="acp-review-side-title">Images</h3>
      <?php if (! $photos): ?>
        <p class="acp-review-empty mb-0">No images attached</p>
      <?php else: ?>
        <div class="acp-review-thumbs">
          <?php foreach ($photos as $i => $p): ?>
            <a class="acp-review-thumb" href="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/file/' . (int) $i) ?>" target="_blank" rel="noopener">
              <img src="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/file/' . (int) $i) ?>"
                   alt="Submission image <?= (int) $i + 1 ?>">
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <p class="acp-review-hint">Approve publishes the title, date, description, and images on the department activity page.</p>
    </div>

    <?php if ($pending): ?>
      <div class="acp-card acp-review-card">
        <h3 class="acp-review-side-title">Decision</h3>
        <form method="post" id="activityReviewForm">
          <?= csrf_field() ?>
          <label class="form-label" for="reviewNote">Note <span class="text-muted fw-normal">(optional)</span></label>
          <textarea class="form-control" id="reviewNote" name="review_note" rows="3" placeholder="Internal note or reason"></textarea>
          <div class="acp-review-actions">
            <button class="btn-acp btn-acp-primary" type="submit"
              formaction="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/approve') ?>">
              Approve and publish
            </button>
            <button class="btn-acp btn-acp-danger" type="submit"
              formaction="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/reject') ?>">
              Reject
            </button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <div class="acp-card acp-review-card">
        <h3 class="acp-review-side-title">Review result</h3>
        <p class="mb-1"><strong>Reviewed</strong> <?= esc($sub['reviewed_at'] ?? '') ?></p>
        <?php if (! empty($sub['review_note'])): ?>
          <p class="mb-0"><?= esc($sub['review_note']) ?></p>
        <?php else: ?>
          <p class="text-muted mb-0">No note recorded.</p>
        <?php endif; ?>
        <?php if (($sub['status'] ?? '') === 'approved' && ! empty($photos)): ?>
          <form class="mt-3" method="post" action="<?= site_url('acp/activities/submissions/' . rawurlencode($key) . '/republish-images') ?>">
            <?= csrf_field() ?>
            <button class="btn-acp btn-acp-outline" type="submit">Publish images to activity page</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?= $this->endSection() ?>
