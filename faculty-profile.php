<?php
require_once __DIR__ . '/includes/faculty-lib.php';

$slug = faculty_slug($_GET['n'] ?? '');
if ($slug === '') {
  header('Location: faculty.php', true, 302);
  exit;
}

$extra = faculty_profile_lookup_cv($slug);
if (!$extra) {
  header('Location: faculty.php', true, 302);
  exit;
}
$display_name = $extra['name'] ?? '';
$page_title = ($display_name !== '' ? $display_name . ' — Faculty' : 'Faculty') . ' | Department of Medical Sciences';
$page_description = $display_name !== ''
  ? $display_name . ' teaches at Peshawar Medical College, Riphah Peshawar Campus.'
  : 'Faculty at the Department of Medical Sciences, Peshawar Medical College.';

include __DIR__ . '/includes/header.php';

$photo = '';
if (!empty($extra['photo'])) {
  $photo_fs = __DIR__ . '/' . ltrim($extra['photo'], '/');
  if (is_file($photo_fs)) {
    $photo = $extra['photo'];
  }
}
$desig = faculty_normalize_designation((string) ($extra['designation'] ?? ''));
$dept = (string) ($extra['department'] ?? '');
$is_hod = !empty($extra['hod']);
$quals = faculty_normalize_qualifications($extra['qualifications'] ?? []);
$skills = faculty_normalize_skills($extra['skills'] ?? []);
$research = faculty_research_preferences($extra);
$academic_roles = faculty_plain_list($extra['academic_roles'] ?? []);
$memberships = faculty_plain_list($extra['memberships'] ?? []);
$courses = faculty_plain_list($extra['courses'] ?? []);
$books = faculty_plain_list($extra['books'] ?? []);
$chapters = faculty_plain_list($extra['book_chapters'] ?? []);
$registrations = faculty_plain_list($extra['registrations'] ?? []);
$pubs = faculty_explode_publications($extra['publications'] ?? []);
$initials = 'F';
if ($display_name !== '') {
  $parts = preg_split('/\s+/', $display_name);
  $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1] ?? '', 0, 1));
}
?>

<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Noto+Naskh+Arabic:wght@400;600&display=swap" rel="stylesheet"/>
<link href="<?= dms_asset('assets/css/faculty.css') ?>" rel="stylesheet"/>

<div class="page-hero fp-page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="faculty.php">Faculty</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current" id="fpCrumb"><?= $display_name !== '' ? htmlspecialchars($display_name) : 'Profile' ?></span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off fp-section">
  <div class="container">
    <div id="fpLoading" class="fp-loading"<?= $extra ? ' style="display:none"' : '' ?>>
      <div class="spinner-pmc"></div>
      <p>Loading profile…</p>
    </div>

    <div id="fpMissing" class="fac-error" style="display:none">
      <div class="fac-error-icon"><i class="bi bi-person-x"></i></div>
      <h5>Profile not found</h5>
      <p>This person could not be found in the faculty list.</p>
      <a href="faculty.php" class="btn-pmc btn-pmc-primary"><i class="bi bi-people"></i> All faculty</a>
    </div>

    <article id="fpCard" class="fp-layout"<?= $extra ? '' : ' hidden' ?>>
      <aside class="fp-side">
        <div class="fp-portrait">
          <?php if ($photo): ?>
            <img id="fpPhoto" src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($display_name) ?>">
          <?php else: ?>
            <div id="fpAvatar" class="fp-avatar des-professor"><?= htmlspecialchars($initials) ?></div>
          <?php endif; ?>
          <?php if ($is_hod): ?>
            <span class="fp-hod"><i class="bi bi-award-fill"></i> Head of Department</span>
          <?php else: ?>
            <span id="fpHod" class="fp-hod" hidden><i class="bi bi-award-fill"></i> Head of Department</span>
          <?php endif; ?>
        </div>
        <div class="fp-side-meta">
          <p class="fp-kicker">Department of Medical Sciences</p>
          <p class="fp-dept" id="fpDept"><?= htmlspecialchars($dept !== '' ? $dept : 'Peshawar Medical College') ?></p>
          <div class="fp-chips" id="fpRegChips">
            <?php foreach ($registrations as $reg): ?>
              <span class="reg-chip"><i class="bi bi-card-text"></i> <?= htmlspecialchars($reg) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="fp-side-block" id="fpQualBlock"<?= $quals ? '' : ' hidden' ?>>
          <h2>Education</h2>
          <ul class="fp-qual-list" id="fpQuals">
            <?php foreach ($quals as $q): ?>
              <li><i class="bi bi-mortarboard-fill"></i><span><?= htmlspecialchars(faculty_soft_space((string) $q)) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="fp-side-block" id="fpSkillBlock"<?= $skills ? '' : ' hidden' ?>>
          <h2>College duties</h2>
          <ul class="fp-duty-list" id="fpSkills">
            <?php foreach ($skills as $s): ?>
              <li><?= htmlspecialchars(faculty_soft_space((string) $s)) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <a href="faculty.php" class="btn-pmc btn-pmc-outline w-100 justify-content-center fp-back"><i class="bi bi-arrow-left"></i> All faculty</a>
      </aside>

      <div class="fp-main">
        <header class="fp-identity">
          <p class="fp-desig-label">Current post</p>
          <p class="fp-desig" id="fpDesig"><?= htmlspecialchars($desig) ?></p>
          <h1 class="fp-name" id="fpName"><?= htmlspecialchars($display_name !== '' ? $display_name : 'Faculty member') ?></h1>
          <p class="fp-college">Peshawar Medical College · Riphah International University, Peshawar Campus</p>
          <div class="fp-stats" id="fpStats">
            <div class="fp-stat"><strong id="fpStatResearch"><?= count($research) ?: '—' ?></strong><span>Research areas</span></div>
            <div class="fp-stat"><strong id="fpStatPub"><?= count($pubs) ?: '—' ?></strong><span>Papers</span></div>
            <div class="fp-stat"><strong id="fpStatQual"><?= count($quals) ?: '—' ?></strong><span>Degrees</span></div>
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
            if (!$items) continue;
        ?>
        <section class="fp-panel" id="fp<?= htmlspecialchars($id) ?>Panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi <?= htmlspecialchars($ico) ?>"></i></span>
            <div>
              <h3><?= htmlspecialchars($title) ?></h3>
              <p><?= htmlspecialchars($sub) ?></p>
            </div>
          </div>
          <ul class="fp-cv-list">
            <?php foreach ($items as $item):
              $rtl = faculty_has_arabic($item);
            ?>
              <li<?= $rtl ? ' class="fp-rtl" dir="rtl" lang="ur"' : '' ?>><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
        <?php endforeach; ?>

        <section class="fp-panel" id="fpResearchPanel"<?= $research ? '' : ' hidden' ?>>
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-lightbulb"></i></span>
            <div>
              <h3>Research interests</h3>
              <p>Areas of academic and clinical research interest</p>
            </div>
          </div>
          <ul class="fp-research-list" id="fpResearch">
            <?php foreach ($research as $item): ?>
              <li><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </section>

        <?php
          $pub_sections = [
            ['fpPubPanel', 'Research papers', 'Main journal and conference papers from the college CV', $main_pubs ?: $pubs, true],
            ['fpOtherPubPanel', 'Islamic and ethical articles', 'Writings listed after the main research papers', $other_pubs, false],
          ];
          foreach ($pub_sections as [$panel_id, $title, $sub, $items, $collapse]):
            if (!$items) continue;
        ?>
        <section class="fp-panel" id="<?= htmlspecialchars($panel_id) ?>">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-journal-richtext"></i></span>
            <div>
              <h3><?= htmlspecialchars($title) ?></h3>
              <p><?= count($items) ?> listed from college records. <?= htmlspecialchars($sub) ?></p>
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
                  <p<?= $rtl ? ' dir="rtl" lang="ur"' : '' ?>><?= htmlspecialchars($pub) ?></p>
                  <div class="fp-pub-meta">
                    <?php if ($year !== ''): ?>
                      <span class="fp-pub-year"><?= htmlspecialchars($year) ?></span>
                    <?php endif; ?>
                    <?php if ($url !== ''): ?>
                      <a class="fp-pub-link" href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener">Open paper <i class="bi bi-box-arrow-up-right"></i></a>
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
            if (!$items) continue;
        ?>
        <section class="fp-panel" id="fp<?= htmlspecialchars($id) ?>Panel">
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi <?= htmlspecialchars($ico) ?>"></i></span>
            <div>
              <h3><?= htmlspecialchars($title) ?></h3>
              <p><?= htmlspecialchars($sub) ?></p>
            </div>
          </div>
          <ul class="fp-cv-list">
            <?php foreach ($items as $item):
              $rtl = faculty_has_arabic($item);
            ?>
              <li<?= $rtl ? ' class="fp-rtl" dir="rtl" lang="ur"' : '' ?>><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </section>
        <?php endforeach; ?>

        <section class="fp-panel" id="fpPending"<?= $extra ? ' hidden' : '' ?>>
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-hourglass-split"></i></span>
            <div>
              <h3>More details coming</h3>
              <p>The department is still adding this CV.</p>
            </div>
          </div>
          <p class="fp-pending-copy">Name, current post, subject, and PM&amp;DC numbers come from the college staff record. Research interests, college duties, and papers will appear when the department provides them.</p>
        </section>
      </div>
    </article>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
const SLUG = <?= json_encode($slug, JSON_UNESCAPED_UNICODE) ?>;
const EXTRA = <?= json_encode($extra, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
const API_URL = 'faculty-proxy';

function facultySlug(name) {
  let n = String(name || '').trim();
  const titles = /^(associate professor|assistant professor|senior registrar|sr\.?\s*registrar|professor|prof\.?|dr\.?)\s+/i;
  while (titles.test(n)) n = n.replace(titles, '');
  n = n.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
  return n.replace(/^(mohammad|muhammed)-/, 'muhammad-');
}

function slugsMatch(a, b) {
  if (!a || !b) return false;
  a = facultySlug(a);
  b = facultySlug(b);
  if (a === b) return true;
  const ta = a.split('-').filter(Boolean);
  const tb = b.split('-').filter(Boolean);
  if (!ta.length || !tb.length) return false;
  if (ta[0] === tb[0] && ta[1] && tb[1] && ta[1] === tb[1]) return true;
  const fa = ta[0], fb = tb[0], la = ta[ta.length - 1], lb = tb[tb.length - 1];
  return (fa === fb || (fa.length >= 4 && fb.length >= 4 && (fa.startsWith(fb.slice(0, 4)) || fb.startsWith(fa.slice(0, 4)))))
    && (la === lb || la.startsWith(lb) || lb.startsWith(la));
}

function escapeHtml(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function avatarClass(desTitle) {
  const map = {
    'Professor': 'des-professor',
    'Associate Professor': 'des-associate',
    'Assistant Professor': 'des-assistant',
    'Senior Lecturer': 'des-senior-lec',
    'Lecturer': 'des-lecturer',
    'Senior Registrar': 'des-registrar',
    'Registrar': 'des-registrar',
  };
  return map[desTitle] || 'des-other';
}

(async function init() {
  const loading = document.getElementById('fpLoading');
  const missing = document.getElementById('fpMissing');
  const card = document.getElementById('fpCard');
  let hrms = null;
  try {
    const res = await fetch(API_URL);
    if (res.ok) {
      const data = await res.json();
      if (Array.isArray(data)) {
        hrms = data.find(f => slugsMatch(f.empName, SLUG))
          || data.find(f => EXTRA && slugsMatch(f.empName, EXTRA.name || EXTRA.slug || ''));
      }
    }
  } catch (e) {
    console.error(e);
  }

  if (!EXTRA && !hrms) {
    loading.style.display = 'none';
    missing.style.display = 'block';
    return;
  }

  if (hrms) {
    const name = hrms.empName || (EXTRA && EXTRA.name) || '';
    const rawDesig = hrms.desTitle || (EXTRA && EXTRA.designation) || '';
    const ownerTitle = /owner|patron|founder|\bceo\b|chairman of (the )?prime/i.test(String(rawDesig));
    const desig = ownerTitle ? ((EXTRA && EXTRA.designation) || 'Professor') : rawDesig;
    const dept = hrms.depName || (EXTRA && EXTRA.department) || '';
    document.getElementById('fpCrumb').textContent = name;
    document.getElementById('fpName').textContent = name;
    document.title = name + ' — Faculty | Department of Medical Sciences';
    if (desig) document.getElementById('fpDesig').textContent = desig;
    if (dept) document.getElementById('fpDept').textContent = dept;
    const chips = [];
    if (hrms.facPMDCNo) chips.push('<span class="reg-chip"><i class="bi bi-shield-check"></i> PM&amp;DC No. ' + escapeHtml(hrms.facPMDCNo) + '</span>');
    if (hrms.facFacRegNo) chips.push('<span class="reg-chip"><i class="bi bi-card-text"></i> Faculty No. ' + escapeHtml(hrms.facFacRegNo) + '</span>');
    const extraRegs = Array.isArray(EXTRA && EXTRA.registrations) ? EXTRA.registrations : [];
    extraRegs.forEach(reg => {
      const text = String(reg || '').trim();
      if (!text) return;
      if (/pm\s*&?\s*dc/i.test(text) && hrms.facPMDCNo) return;
      chips.push('<span class="reg-chip"><i class="bi bi-card-text"></i> ' + escapeHtml(text) + '</span>');
    });
    document.getElementById('fpRegChips').innerHTML = chips.join('');
    const av = document.getElementById('fpAvatar');
    if (av) av.className = 'fp-avatar ' + avatarClass(hrms.desTitle);
    if (!EXTRA && hrms.qualifications) {
      const block = document.getElementById('fpQualBlock');
      const ul = document.getElementById('fpQuals');
      ul.innerHTML = hrms.qualifications.split(/[,;]+/).map(q => q.trim()).filter(Boolean)
        .map(q => '<li><i class="bi bi-mortarboard-fill"></i><span>' + escapeHtml(q) + '</span></li>').join('');
      block.hidden = ul.children.length === 0;
      document.getElementById('fpStatQual').textContent = ul.children.length || '—';
    }
  }

  if (!EXTRA) {
    document.getElementById('fpPending').hidden = false;
  }

  loading.style.display = 'none';
  card.hidden = false;

  const more = document.getElementById('fpPubsMore');
  if (more) {
    more.addEventListener('click', () => {
      const list = document.getElementById('fpPubs');
      if (list) list.classList.remove('is-collapsed');
      more.remove();
    });
  }
})();
</script>
