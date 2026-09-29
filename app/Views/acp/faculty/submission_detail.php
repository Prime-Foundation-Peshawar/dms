<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>

<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>

<div class="acp-actions" style="margin-bottom:14px">
  <a class="acp-btn" href="<?= site_url('acp/faculty/submissions') ?>">Back to queue</a>
  <span class="acp-badge <?= esc($sub['status'] ?? '') ?>"><?= esc($sub['status'] ?? '') ?></span>
</div>

<div class="acp-detail-grid">
  <div class="acp-card">
    <h2 style="margin-top:0"><?= esc($sub['emp_name'] ?? '') ?></h2>
    <p class="acp-muted"><?= esc($sub['des_title'] ?? '') ?> · <?= esc($sub['dep_name'] ?? '') ?></p>
    <p><strong>Slug:</strong> <?= esc($sub['slug'] ?? '') ?></p>
    <p><strong>Submitted:</strong> <?= esc($sub['submitted_at'] ?? '') ?></p>
    <?php if (!empty($sub['contact_phone'])): ?>
      <p><strong>Phone (office):</strong> <?= esc($sub['contact_phone']) ?></p>
    <?php endif; ?>

    <h3>Research topics</h3>
    <?php if (empty($sub['research_preferences'])): ?>
      <p class="acp-muted">None</p>
    <?php else: ?>
      <ul class="acp-list">
        <?php foreach ($sub['research_preferences'] as $item): ?>
          <li><?= esc((string) $item) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <h3>Education</h3>
    <?php if (empty($sub['qualifications'])): ?>
      <p class="acp-muted">None</p>
    <?php else: ?>
      <ul class="acp-list">
        <?php foreach ($sub['qualifications'] as $item): ?>
          <li><?= esc((string) $item) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <h3>Duties</h3>
    <?php if (empty($sub['skills'])): ?>
      <p class="acp-muted">None</p>
    <?php else: ?>
      <ul class="acp-list">
        <?php foreach ($sub['skills'] as $item): ?>
          <li><?= esc((string) $item) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <h3>Publications</h3>
    <?php if (!empty($sub['publications_url'])): ?>
      <p><a href="<?= esc($sub['publications_url']) ?>" target="_blank" rel="noopener"><?= esc($sub['publications_url']) ?></a></p>
    <?php endif; ?>
    <?php if (!empty($sub['publications_file'])): ?>
      <p><a class="acp-btn" href="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/file/publications') ?>">Download publications file</a></p>
    <?php else: ?>
      <p class="acp-muted">No publications file</p>
    <?php endif; ?>
  </div>

  <div>
    <div class="acp-card">
      <h3 style="margin-top:0">Attachments</h3>
      <?php if (!empty($sub['photo'])): ?>
        <p><a class="acp-btn" href="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/file/photo') ?>">Download photo</a></p>
      <?php else: ?>
        <p class="acp-muted">No photo uploaded</p>
      <?php endif; ?>
      <?php if (!empty($existing)): ?>
        <p class="acp-muted">Live profile exists — approve will update it.</p>
      <?php else: ?>
        <p class="acp-muted">No live profile yet — approve will create one.</p>
      <?php endif; ?>
    </div>

    <?php if (($sub['status'] ?? '') === 'pending'): ?>
      <div class="acp-card" style="margin-top:16px">
        <form class="acp-form" method="post" action="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/approve') ?>">
          <?= csrf_field() ?>
          <label>Review note (optional)
            <textarea name="review_note" placeholder="Internal note"></textarea>
          </label>
          <button class="acp-btn acp-btn-teal" type="submit">Approve &amp; publish</button>
        </form>
        <form class="acp-form" method="post" action="<?= site_url('acp/faculty/submissions/' . (int) $sub['id'] . '/reject') ?>" style="margin-top:12px">
          <?= csrf_field() ?>
          <label>Reject note (optional)
            <textarea name="review_note" placeholder="Reason"></textarea>
          </label>
          <button class="acp-btn acp-btn-danger" type="submit">Reject</button>
        </form>
      </div>
    <?php else: ?>
      <div class="acp-card" style="margin-top:16px">
        <p class="acp-muted">Reviewed at <?= esc($sub['reviewed_at'] ?? '—') ?></p>
        <?php if (!empty($sub['review_note'])): ?>
          <p><?= esc($sub['review_note']) ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?= $this->endSection() ?>
