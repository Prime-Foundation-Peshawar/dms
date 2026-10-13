<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
helper('faculty');
$profileSvc = new \App\Services\FacultyProfileService();
?>
<div class="page-hero dept-page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow"><?= esc($dept['group'] ?? 'Department') ?></span>
    <h1><?= esc($dept['name'] ?? '') ?></h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('departments') ?>">Departments</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= esc($dept['name'] ?? '') ?></span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <section class="dept-panel fu" id="introduction">
      <div class="dept-panel-head">
        <span class="dept-panel-ico"><i class="bi <?= esc($dept['icon'] ?? 'bi-building') ?>"></i></span>
        <div>
          <h2>Introduction</h2>
          <?php if (!empty($dept['hod'])): ?>
            <p>Head of Department: <?= esc($dept['hod']) ?></p>
          <?php endif; ?>
        </div>
      </div>
      <div class="dept-panel-body">
        <?php foreach ($dept['intro'] ?? [] as $para): ?>
          <p><?= esc($para) ?></p>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="dept-panel fu" id="faculty">
      <div class="dept-panel-head">
        <span class="dept-panel-ico"><i class="bi bi-people"></i></span>
        <div>
          <h2>Faculty</h2>
          <p><?= count($faculty) ?> members listed from the live directory</p>
        </div>
      </div>
      <div class="dept-panel-body">
        <?php if ($faculty): ?>
          <div class="dept-faculty-grid">
            <?php foreach ($faculty as $member):
              $lookup = trim((string) ($member['empName'] ?? $member['name'] ?? ''));
              $profile = $profileSvc->lookup($lookup);
              $isHod = !empty($dept['hod']) && faculty_slugs_match(faculty_slug($lookup), faculty_slug((string) $dept['hod']));
              $photo = $profile['photo'] ?? ($member['photo'] ?? '');
              $parts = preg_split('/\s+/', (string) ($member['name'] ?? '')) ?: [];
              $initials = '';
              foreach ($parts as $part) {
                if ($part === '' || in_array(strtolower($part), ['dr.', 'dr', 'prof.', 'professor', 'associate', 'assistant'], true)) continue;
                $initials .= strtoupper(substr($part, 0, 1));
                if (strlen($initials) >= 2) break;
              }
              if ($initials === '') $initials = 'D';
            ?>
              <article class="dept-faculty-card<?= $isHod ? ' is-hod' : '' ?>">
                <?php if ($profile): ?><a class="dept-faculty-link" href="<?= site_url('faculty-profile?n=' . rawurlencode($profile['slug'] ?? faculty_slug($lookup))) ?>"><?php endif; ?>
                <div class="dept-faculty-avatar<?= $photo ? ' has-photo' : '' ?>">
                  <?php if ($photo): ?><img src="<?= esc(base_url(ltrim($photo, '/'))) ?>" alt=""><?php else: ?><?= esc($initials) ?><?php endif; ?>
                </div>
                <div class="dept-faculty-info">
                  <div class="dept-faculty-name-row">
                    <h3><?= esc($member['name']) ?></h3>
                    <?php if ($isHod): ?><span class="dept-hod-badge"><i class="bi bi-award-fill"></i> Head of Department</span><?php endif; ?>
                  </div>
                  <p><?= esc($member['qualification'] ?? '') ?></p>
                  <?php if (!empty($member['reg'])): ?><span class="reg-number">PM&amp;DC <?= esc($member['reg']) ?></span><?php endif; ?>
                </div>
                <?php if ($profile): ?></a><?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
          <div class="dept-panel-actions">
            <a href="<?= site_url('faculty') ?>" class="btn-pmc btn-pmc-outline">All faculty <i class="bi bi-arrow-right"></i></a>
          </div>
        <?php else: ?>
          <div class="dept-empty">
            <p>Faculty details for this department will be published here.</p>
            <a href="<?= site_url('faculty') ?>" class="btn-pmc btn-pmc-primary">View faculty</a>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="dept-panel fu" id="publications">
      <div class="dept-panel-head">
        <span class="dept-panel-ico"><i class="bi bi-journal-richtext"></i></span>
        <div><h2>Publications</h2><p>Research on the ORIC portal</p></div>
      </div>
      <div class="dept-panel-body">
        <a class="btn-pmc btn-pmc-primary" href="<?= esc($pubsUrl) ?>" target="_blank" rel="noopener">
          <i class="bi bi-box-arrow-up-right"></i> Publications
        </a>
      </div>
    </section>

    <section class="dept-panel fu" id="activities">
      <div class="dept-panel-head">
        <span class="dept-panel-ico"><i class="bi bi-calendar-event"></i></span>
        <div><h2>Activities</h2></div>
      </div>
      <div class="dept-panel-body">
        <?php if (!empty($dept['activities'])): ?>
          <div class="dept-timeline">
            <?php foreach ($dept['activities'] as $i => $item): ?>
              <a class="dept-timeline-item" href="<?= site_url('department-activity?slug=' . rawurlencode($slug) . '&i=' . $i) ?>">
                <div class="dept-timeline-date"><?= esc($item['date'] ?? 'TBA') ?></div>
                <div class="dept-timeline-body">
                  <h3><?= esc($item['title'] ?? '') ?></h3>
                  <p><?= esc($item['text'] ?? '') ?></p>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p>No activities published yet.</p>
        <?php endif; ?>
      </div>
    </section>
  </div>
</section>
<?= $this->endSection() ?>
