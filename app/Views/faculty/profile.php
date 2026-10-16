<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
helper('faculty');
$EXTRA = $extra ?? null;
$DIRECTORY = $directory ?? null;
$slug = $slug ?? '';

$display_name = trim((string) ($EXTRA['name'] ?? $DIRECTORY['empName'] ?? 'Faculty Member'));
$photoRel = trim((string) ($EXTRA['photo'] ?? ''));
$photo = '';
if ($photoRel !== '') {
    if (preg_match('#^https?://#i', $photoRel)) {
        $photo = $photoRel;
    } else {
        $photo = base_url(ltrim($photoRel, '/'));
    }
}
$desig = faculty_public_current_post($DIRECTORY, $EXTRA);
$dept = (string) ($DIRECTORY['depName'] ?? $EXTRA['department'] ?? $EXTRA['department_name'] ?? '');
$is_hod = ! empty($EXTRA['hod']) || ! empty($EXTRA['is_hod']);

$dirQuals = [];
if (! $EXTRA && ! empty($DIRECTORY['qualifications'])) {
    $dirQuals = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', (string) $DIRECTORY['qualifications']) ?: [])));
}
$quals = faculty_normalize_qualifications($EXTRA['qualifications'] ?? $dirQuals);
$skills = faculty_normalize_skills($EXTRA['skills'] ?? []);
$research = $EXTRA ? faculty_research_preferences($EXTRA) : [];
$academic_roles = faculty_plain_list($EXTRA['academic_roles'] ?? []);
$memberships = faculty_plain_list($EXTRA['memberships'] ?? []);
$courses = faculty_plain_list($EXTRA['courses'] ?? []);
$books = faculty_plain_list($EXTRA['books'] ?? []);
$chapters = faculty_plain_list($EXTRA['book_chapters'] ?? []);
$registrations = faculty_public_registrations($DIRECTORY, $EXTRA);
$pubs = faculty_explode_publications($EXTRA['publications'] ?? []);
$initials = 'F';
if ($display_name !== '') {
    $parts = preg_split('/\s+/', $display_name) ?: [];
    $initials = strtoupper(substr($parts[0] ?? 'F', 0, 1) . substr($parts[count($parts) - 1] ?? '', 0, 1));
}
$has_cv = (bool) $EXTRA;
$avatarDes = faculty_normalize_designation((string) ($DIRECTORY['desTitle'] ?? $desig));
$avatarClass = match (true) {
    str_starts_with($avatarDes, 'Professor') && ! str_contains($avatarDes, 'Associate') && ! str_contains($avatarDes, 'Assistant') => 'des-professor',
    str_starts_with($avatarDes, 'Associate') => 'des-associate',
    str_starts_with($avatarDes, 'Assistant') => 'des-assistant',
    str_starts_with($avatarDes, 'Senior Lecturer') => 'des-senior-lec',
    str_starts_with($avatarDes, 'Lecturer') => 'des-lecturer',
    str_contains($avatarDes, 'Registrar') => 'des-registrar',
    default => 'des-other',
};
?>

<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Noto+Naskh+Arabic:wght@400;600&display=swap" rel="stylesheet"/>
<link href="<?= dms_asset('assets/css/faculty.css') ?>" rel="stylesheet"/>

<div class="page-hero fp-page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('faculty') ?>">Faculty</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= esc($display_name !== '' ? $display_name : 'Profile') ?></span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off fp-section">
  <div class="container">
    <article class="fp-layout">
      <aside class="fp-side">
        <div class="fp-portrait">
          <?php if ($photo): ?>
            <img src="<?= esc($photo) ?>" alt="<?= esc($display_name) ?>">
          <?php else: ?>
            <div class="fp-avatar <?= esc($avatarClass) ?>"><?= esc($initials) ?></div>
          <?php endif; ?>
          <?php if ($is_hod): ?>
            <span class="fp-hod"><i class="bi bi-award-fill"></i> Head of Department</span>
          <?php endif; ?>
        </div>
        <div class="fp-side-meta">
          <p class="fp-kicker">Department of Medical Sciences</p>
          <p class="fp-dept"><?= esc($dept !== '' ? $dept : 'Peshawar Medical College') ?></p>
          <div class="fp-chips">
            <?php foreach ($registrations as $reg): ?>
              <span class="reg-chip"><i class="bi bi-card-text"></i> <?= esc($reg) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="fp-side-block"<?= $quals ? '' : ' hidden' ?>>
          <h2>Education</h2>
          <ul class="fp-qual-list">
            <?php foreach ($quals as $q): ?>
              <li><i class="bi bi-mortarboard-fill"></i><span><?= esc(faculty_soft_space((string) $q)) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="fp-side-block"<?= $skills ? '' : ' hidden' ?>>
          <h2>College duties</h2>
          <ul class="fp-duty-list">
            <?php foreach ($skills as $s): ?>
              <li><?= esc(faculty_soft_space((string) $s)) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <a href="<?= site_url('faculty') ?>" class="btn-pmc btn-pmc-outline w-100 justify-content-center fp-back"><i class="bi bi-arrow-left"></i> All faculty</a>
      </aside>

      <div class="fp-main">
        <header class="fp-identity">
          <p class="fp-desig-label">Current post</p>
          <p class="fp-desig"><?= esc($desig) ?></p>
          <h1 class="fp-name"><?= esc($display_name !== '' ? $display_name : 'Faculty member') ?></h1>
          <p class="fp-college">Peshawar Medical College · Riphah International University, Peshawar Campus</p>
          <div class="fp-stats">
            <div class="fp-stat"><strong><?= count($research) ?: '—' ?></strong><span>Research areas</span></div>
            <div class="fp-stat"><strong><?= count($pubs) ?: '—' ?></strong><span>Papers</span></div>
            <div class="fp-stat"><strong><?= count($quals) ?: '—' ?></strong><span>Degrees</span></div>
          </div>
        </header>

        <?php
          $main_pubs = [];
          $other_pubs = [];
          foreach ($pubs as $pub) {
              if (faculty_has_arabic($pub)) {
                  $other_pubs[] = $pub;
              } else {
                  $main_pubs[] = $pub;
              }
          }
          $extra_panels = [
              ['academic_roles', 'Academic roles', 'Examiner, supervisor, and current academic appointments', $academic_roles, 'bi-mortarboard'],
          ];
          foreach ($extra_panels as [$id, $title, $sub, $items, $ico]):
              if (! $items) {
                  continue;
              }
        ?>
        <section class="fp-panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi <?= esc($ico) ?>"></i></span>
            <div>
              <h3><?= esc($title) ?></h3>
              <p><?= esc($sub) ?></p>
            </div>
          </div>
          <ul class="fp-cv-list">
            <?php foreach ($items as $item):
                $rtl = faculty_has_arabic($item);
            ?>
              <li<?= $rtl ? ' class="fp-rtl" dir="rtl" lang="ur"' : '' ?>><?= esc($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
        <?php endforeach; ?>

        <?php if ($research): ?>
        <section class="fp-panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-lightbulb"></i></span>
            <div>
              <h3>Research interests</h3>
              <p>Areas of academic and clinical research interest</p>
            </div>
          </div>
          <ul class="fp-research-list">
            <?php foreach ($research as $item): ?>
              <li><?= esc($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
        <?php endif; ?>

        <?php
          $pubsUrl = trim((string) ($EXTRA['publications_url'] ?? ''));
          if ($pubsUrl !== '' && preg_match('#^https?://#i', $pubsUrl)):
        ?>
        <section class="fp-panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-link-45deg"></i></span>
            <div>
              <h3>Publications list</h3>
              <p>Link submitted by the faculty member</p>
            </div>
          </div>
          <p><a href="<?= esc($pubsUrl) ?>" target="_blank" rel="noopener"><?= esc($pubsUrl) ?></a></p>
        </section>
        <?php endif; ?>

        <?php
          $pub_sections = [
              ['Research papers', 'Main journal and conference papers from the college CV', $main_pubs ?: $pubs, true],
              ['Islamic and ethical articles', 'Writings listed after the main research papers', $other_pubs, false],
          ];
          foreach ($pub_sections as [$title, $sub, $items, $collapse]):
              if (! $items) {
                  continue;
              }
        ?>
        <section class="fp-panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-journal-richtext"></i></span>
            <div>
              <h3><?= esc($title) ?></h3>
              <p><?= count($items) ?> listed from college records. <?= esc($sub) ?></p>
            </div>
          </div>
          <ol class="fp-pubs<?= $collapse && count($items) > 8 ? ' is-collapsed' : '' ?>"<?= $collapse ? ' id="fpPubs"' : '' ?>>
            <?php foreach ($items as $i => $pub):
                $year = faculty_pub_year($pub);
                $url = faculty_pub_url($pub);
                $rtl = faculty_has_arabic($pub);
            ?>
              <li class="fp-pub<?= $rtl ? ' fp-rtl' : '' ?>">
                <span class="fp-pub-num"><?= (int) $i + 1 ?></span>
                <div class="fp-pub-body">
                  <p<?= $rtl ? ' dir="rtl" lang="ur"' : '' ?>><?= esc($pub) ?></p>
                  <div class="fp-pub-meta">
                    <?php if ($year !== ''): ?>
                      <span class="fp-pub-year"><?= esc($year) ?></span>
                    <?php endif; ?>
                    <?php if ($url !== ''): ?>
                      <a class="fp-pub-link" href="<?= esc($url) ?>" target="_blank" rel="noopener">Open paper <i class="bi bi-box-arrow-up-right"></i></a>
                    <?php endif; ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ol>
          <?php if ($collapse && count($items) > 8): ?>
            <button type="button" class="fp-pubs-more" id="fpPubsMore" data-total="<?= count($items) ?>">Show all <?= count($items) ?> papers</button>
          <?php endif; ?>
        </section>
        <?php endforeach; ?>

        <?php
          $later_panels = [
              ['books', 'Published books', 'Books listed in the college CV', $books, 'bi-book'],
              ['chapters', 'Book chapters', 'Chapters listed in the college CV', $chapters, 'bi-bookmark'],
              ['memberships', 'Memberships', 'Academic and professional bodies', $memberships, 'bi-people'],
              ['courses', 'Courses attended', 'Medical education and professional courses', $courses, 'bi-journal-check'],
          ];
          foreach ($later_panels as [$id, $title, $sub, $items, $ico]):
              if (! $items) {
                  continue;
              }
        ?>
        <section class="fp-panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi <?= esc($ico) ?>"></i></span>
            <div>
              <h3><?= esc($title) ?></h3>
              <p><?= esc($sub) ?></p>
            </div>
          </div>
          <ul class="fp-cv-list">
            <?php foreach ($items as $item):
                $rtl = faculty_has_arabic($item);
            ?>
              <li<?= $rtl ? ' class="fp-rtl" dir="rtl" lang="ur"' : '' ?>><?= esc($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
        <?php endforeach; ?>

        <?php if (! $has_cv): ?>
        <section class="fp-panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-hourglass-split"></i></span>
            <div>
              <h3>More details coming</h3>
              <p>The department is still adding this CV.</p>
            </div>
          </div>
          <p class="fp-pending-copy">Name, current post, subject, and PM&amp;DC numbers come from the college staff record. Research interests, college duties, and papers will appear when the department provides them.</p>
        </section>
        <?php endif; ?>
      </div>
    </article>
  </div>
</section>

<script>
document.getElementById('fpPubsMore')?.addEventListener('click', function () {
  document.getElementById('fpPubs')?.classList.remove('is-collapsed');
  this.remove();
});
</script>
<?= $this->endSection() ?>
