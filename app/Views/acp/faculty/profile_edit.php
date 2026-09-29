<?= $this->extend('acp/layouts/main') ?>
<?= $this->section('content') ?>

<?php if (!empty($flashOk)): ?><div class="acp-alert acp-alert-ok"><?= esc($flashOk) ?></div><?php endif; ?>
<?php if (!empty($flashError)): ?><div class="acp-alert acp-alert-err"><?= esc($flashError) ?></div><?php endif; ?>

<div class="acp-card">
  <?php
    $photoRel = trim((string) ($row['photo'] ?? ''));
    $photoUrl = $photoRel !== '' ? base_url(ltrim($photoRel, '/')) : '';
  ?>
  <?php if ($photoUrl !== ''): ?>
    <p class="acp-muted">Current photo</p>
    <p><img src="<?= esc($photoUrl) ?>" alt="" style="width:96px;height:96px;object-fit:cover;border-radius:50%;border:1px solid #d7e0ea"></p>
  <?php endif; ?>
  <form class="acp-form" method="post" action="<?= site_url('acp/faculty/profiles/' . (int) $row['id']) ?>">
    <?= csrf_field() ?>
    <label>Name
      <input type="text" name="name" value="<?= esc($row['name'] ?? '') ?>" required>
    </label>
    <label>Department
      <input type="text" name="department" value="<?= esc($row['department'] ?? '') ?>">
    </label>
    <label>Designation
      <input type="text" name="designation" value="<?= esc($row['designation'] ?? '') ?>">
    </label>
    <label>Publications URL
      <input type="url" name="publications_url" value="<?= esc($row['publications_url'] ?? '') ?>">
    </label>
    <label>Research topics (one per line)
      <textarea name="research_preferences"><?= esc(implode("\n", $row['research_preferences'] ?? [])) ?></textarea>
    </label>
    <label>Education (one per line)
      <textarea name="qualifications"><?= esc(implode("\n", $row['qualifications'] ?? [])) ?></textarea>
    </label>
    <label>Duties (one per line)
      <textarea name="skills"><?= esc(implode("\n", $row['skills'] ?? [])) ?></textarea>
    </label>
    <div class="acp-actions">
      <button class="acp-btn acp-btn-primary" type="submit">Save</button>
      <a class="acp-btn" href="<?= site_url('acp/faculty/profiles') ?>">Back</a>
      <a class="acp-btn acp-btn-ghost" href="<?= site_url('faculty-profile?n=' . rawurlencode((string) ($row['slug'] ?? ''))) ?>" target="_blank" rel="noopener">View public</a>
    </div>
  </form>
</div>
<?= $this->endSection() ?>
