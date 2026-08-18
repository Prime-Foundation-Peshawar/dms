<?php
require_once __DIR__ . '/includes/departments-data.php';
require_once __DIR__ . '/includes/faculty-lib.php';

$slug = isset($_GET['slug']) ? strtolower(trim($_GET['slug'])) : '';
$dept = $slug !== '' ? get_academic_department($slug) : null;

if (!$dept) {
  header('Location: departments.php');
  exit;
}

$faculty = $dept['faculty'] ?? [];
$activities = $dept['activities'] ?? [];
$intro = $dept['intro'] ?? [];
$facultyCount = count($faculty);
$activityCount = count($activities);

include('includes/header.php');
?>

<div class="page-hero dept-page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow"><?= htmlspecialchars($dept['group'] ?? 'Academic Department') ?></span>
    <div class="dept-hero-row">
      <div class="dept-hero-ico" aria-hidden="true"><i class="bi <?= htmlspecialchars($dept['icon']) ?>"></i></div>
      <div>
        <h1><?= htmlspecialchars($dept['name']) ?></h1>
        <p class="dept-hero-sub">Department of Medical Sciences · Peshawar Medical College</p>
      </div>
    </div>
    <div class="breadcrumb-pmc mt-3">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="departments.php">Departments</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= htmlspecialchars($dept['name']) ?></span>
    </div>
  </div>
</div>

<section class="pmc-section dept-page">
  <div class="container">

    <div class="dept-stats fu">
      <div class="dept-stat">
        <strong><?= (int)$facultyCount ?></strong>
        <span>Faculty members</span>
      </div>
      <div class="dept-stat">
        <strong><?= (int)$activityCount ?></strong>
        <span>Listed activities</span>
      </div>
      <div class="dept-stat">
        <strong><?= htmlspecialchars($dept['group'] ?? '—') ?></strong>
        <span>Category</span>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-lg-8">

        <!-- Introduction -->
        <section class="dept-panel fu" id="intro">
          <div class="dept-panel-head">
            <span class="dept-panel-ico"><i class="bi bi-info-circle"></i></span>
            <div>
              <h2>Introduction</h2>
              <p>About the Department of <?= htmlspecialchars($dept['name']) ?></p>
            </div>
          </div>
          <div class="dept-panel-body">
            <?php foreach ($intro as $para): ?>
              <p><?= htmlspecialchars($para) ?></p>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Faculty -->
        <section class="dept-panel fu" id="faculty">
          <div class="dept-panel-head">
            <span class="dept-panel-ico"><i class="bi bi-people"></i></span>
            <div>
              <h2>Faculty</h2>
              <p><?= $facultyCount ?> teaching staff in <?= htmlspecialchars($dept['name']) ?></p>
            </div>
          </div>
          <div class="dept-panel-body">
            <?php if ($faculty): ?>
              <div class="dept-faculty-list">
                <?php foreach ($faculty as $member):
                  $isHod = !empty($dept['hod']) && strcasecmp(trim($member['name']), trim($dept['hod'])) === 0;
                  $parts = preg_split('/\s+/', trim($member['name']));
                  $initials = '';
                  foreach ($parts as $part) {
                    if ($part === '' || in_array(strtolower($part), ['dr.', 'dr', 'professor', 'associate', 'assistant', 'senior', 'registrar'], true)) {
                      continue;
                    }
                    $initials .= strtoupper(substr($part, 0, 1));
                    if (strlen($initials) >= 2) break;
                  }
                  if ($initials === '') $initials = 'D';
                ?>
                <?php
                  $profile = faculty_profile_lookup_cv($member['name']);
                ?>
                  <article class="dept-faculty-card<?= $isHod ? ' is-hod' : '' ?><?= $profile ? '' : ' is-static' ?>">
                    <?php if ($profile): ?>
                    <a class="dept-faculty-link" href="faculty-profile?n=<?= htmlspecialchars($profile['slug'] ?? faculty_slug($member['name'])) ?>">
                    <?php endif; ?>
                    <div class="dept-faculty-avatar"><?= htmlspecialchars($initials) ?></div>
                    <div class="dept-faculty-info">
                      <div class="dept-faculty-name-row">
                        <h3><?= htmlspecialchars($member['name']) ?></h3>
                        <?php if ($isHod): ?>
                          <span class="dept-hod-badge"><i class="bi bi-award-fill"></i> Head of Department</span>
                        <?php endif; ?>
                      </div>
                      <p><?= htmlspecialchars($member['qualification']) ?></p>
                      <span class="reg-number">PM&amp;DC <?= htmlspecialchars($member['reg']) ?></span>
                    </div>
                    <?php if ($profile): ?>
                    </a>
                    <?php endif; ?>
                  </article>
                <?php endforeach; ?>
              </div>
              <div class="dept-panel-actions">
                <a href="faculty.php" class="btn-pmc btn-pmc-outline">Full Faculty Directory <i class="bi bi-arrow-right"></i></a>
              </div>
            <?php else: ?>
              <div class="dept-empty">
                <i class="bi bi-people"></i>
                <p>Faculty details for this department will be published here. Meanwhile, see the full directory.</p>
                <a href="faculty.php" class="btn-pmc btn-pmc-primary">View Faculty Directory</a>
              </div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Activities -->
        <section class="dept-panel fu" id="activities">
          <div class="dept-panel-head">
            <span class="dept-panel-ico"><i class="bi bi-calendar-event"></i></span>
            <div>
              <h2>Activities</h2>
              <p>Teaching activities, academic events, and department initiatives</p>
            </div>
          </div>
          <div class="dept-panel-body">
            <?php if ($activities): ?>
              <div class="dept-timeline">
                <?php foreach ($activities as $activityIndex => $item):
                  $dateRaw = trim($item['date'] ?? '');
                  $dateMain = $dateRaw !== '' ? $dateRaw : 'TBA';
                  // Split multi-word dates for display (e.g. "Aug 2026" -> Aug / 2026)
                  $dateBits = preg_split('/\s+/', $dateMain);
                  $dateTop = $dateBits[0] ?? $dateMain;
                  $dateBottom = isset($dateBits[1]) ? implode(' ', array_slice($dateBits, 1)) : '';
                  $activityUrl = 'department-activity.php?slug=' . urlencode($slug) . '&id=' . (int)$activityIndex;
                ?>
                  <a class="dept-activity" href="<?= htmlspecialchars($activityUrl) ?>">
                    <div class="dept-activity-datebox" aria-label="Date: <?= htmlspecialchars($dateMain) ?>">
                      <span class="dept-activity-date-top"><?= htmlspecialchars($dateTop) ?></span>
                      <?php if ($dateBottom !== ''): ?>
                        <span class="dept-activity-date-bottom"><?= htmlspecialchars($dateBottom) ?></span>
                      <?php else: ?>
                        <span class="dept-activity-date-bottom"><i class="bi bi-calendar3"></i></span>
                      <?php endif; ?>
                    </div>
                    <div class="dept-activity-body">
                      <div class="dept-activity-meta">
                        <i class="bi bi-clock"></i>
                        <time datetime="<?= htmlspecialchars($dateMain) ?>"><?= htmlspecialchars($dateMain) ?></time>
                      </div>
                      <h3><?= htmlspecialchars($item['title']) ?></h3>
                      <p><?= htmlspecialchars($item['text']) ?></p>
                      <span class="dept-activity-more">View details <i class="bi bi-arrow-right"></i></span>
                    </div>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="dept-empty">
                <i class="bi bi-calendar-event"></i>
                <p>Activities and events for this department will appear here once provided by the department.</p>
              </div>
            <?php endif; ?>
          </div>
        </section>

      </div>

      <div class="col-lg-4">
        <aside class="dept-aside fu fu-delay-2">
          <div class="dept-aside-card">
            <div class="dept-aside-brand">
              <div class="dept-side-ico"><i class="bi <?= htmlspecialchars($dept['icon']) ?>"></i></div>
              <div>
                <strong><?= htmlspecialchars($dept['name']) ?></strong>
                <span><?= htmlspecialchars($dept['group'] ?? '') ?></span>
              </div>
            </div>
            <nav class="dept-aside-nav" aria-label="On this page">
              <a href="#intro"><i class="bi bi-info-circle"></i> Introduction</a>
              <a href="#faculty"><i class="bi bi-people"></i> Faculty <em><?= (int)$facultyCount ?></em></a>
              <a href="#activities"><i class="bi bi-calendar-event"></i> Activities <em><?= (int)$activityCount ?></em></a>
            </nav>
            <div class="dept-aside-links">
              <a href="departments.php" class="btn-pmc btn-pmc-outline w-100 justify-content-center mb-2"><i class="bi bi-grid"></i> All Departments</a>
              <a href="faculty.php" class="btn-pmc btn-pmc-primary w-100 justify-content-center"><i class="bi bi-person-vcard"></i> Faculty Directory</a>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
