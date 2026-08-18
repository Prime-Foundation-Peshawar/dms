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
$page_title = ($display_name !== '' ? $display_name . ' — Faculty Profile' : 'Faculty Profile') . ' | Department of Medical Sciences';
$page_description = $display_name !== ''
  ? $display_name . ' — faculty profile at Peshawar Medical College, Riphah Peshawar Campus.'
  : 'Faculty profile — Department of Medical Sciences, Peshawar Medical College.';

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
$experience = faculty_normalize_experience($extra['experience'] ?? [], $desig);
$skills = faculty_normalize_skills($extra['skills'] ?? []);
$pubs = faculty_explode_publications($extra['publications'] ?? []);
if ($desig === '') {
  foreach ($experience as $row) {
    if (($row['kind'] ?? '') === 'role' && !empty($row['title'])) {
      $desig = trim((string) preg_replace('/,.*/', '', $row['title']));
      break;
    }
  }
}
$expRoles = count(array_filter($experience, fn($row) => ($row['kind'] ?? '') === 'role'));
$initials = 'F';
if ($display_name !== '') {
  $parts = preg_split('/\s+/', $display_name);
  $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1] ?? '', 0, 1));
}
?>

<link href="<?= dms_asset('assets/css/faculty.css') ?>" rel="stylesheet"/>

<div class="page-hero fp-page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Faculty Profile</span>
    <h1 id="fpHeroName"><?= $display_name !== '' ? htmlspecialchars($display_name) : 'Faculty Profile' ?></h1>
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
      <p>This faculty member could not be matched in the directory.</p>
      <a href="faculty.php" class="btn-pmc btn-pmc-primary"><i class="bi bi-people"></i> Back to Faculty</a>
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
          <div class="fp-chips" id="fpRegChips"></div>
        </div>
        <div class="fp-side-block" id="fpQualBlock"<?= $quals ? '' : ' hidden' ?>>
          <h2>Qualifications</h2>
          <ul class="fp-qual-list" id="fpQuals">
            <?php foreach ($quals as $q): ?>
              <li><i class="bi bi-mortarboard-fill"></i><span><?= htmlspecialchars(faculty_soft_space((string) $q)) ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="fp-side-block" id="fpSkillBlock"<?= $skills ? '' : ' hidden' ?>>
          <h2>Expertise</h2>
          <div class="fp-skill-wrap" id="fpSkills">
            <?php foreach ($skills as $s): ?>
              <span class="fp-skill"><?= htmlspecialchars(faculty_soft_space((string) $s)) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <a href="faculty.php" class="btn-pmc btn-pmc-outline w-100 justify-content-center fp-back"><i class="bi bi-arrow-left"></i> Faculty Directory</a>
      </aside>

      <div class="fp-main">
        <header class="fp-identity">
          <p class="fp-desig" id="fpDesig"><?= htmlspecialchars($desig) ?></p>
          <h2 class="fp-name" id="fpName"><?= htmlspecialchars($display_name !== '' ? $display_name : 'Faculty Member') ?></h2>
          <p class="fp-college">Peshawar Medical College · Riphah International University — Peshawar Campus</p>
          <div class="fp-stats" id="fpStats">
            <div class="fp-stat"><strong id="fpStatExp"><?= $expRoles ?: '—' ?></strong><span>Roles</span></div>
            <div class="fp-stat"><strong id="fpStatPub"><?= count($pubs) ?: '—' ?></strong><span>Publications</span></div>
            <div class="fp-stat"><strong id="fpStatQual"><?= count($quals) ?: '—' ?></strong><span>Qualifications</span></div>
          </div>
        </header>

        <section class="fp-panel" id="fpExpPanel"<?= $experience ? '' : ' hidden' ?>>
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-briefcase"></i></span>
            <div>
              <h3>Professional experience</h3>
              <p>Teaching, clinical, and academic posts</p>
            </div>
          </div>
          <ol class="fp-timeline" id="fpExp">
            <?php foreach ($experience as $item):
              if (($item['kind'] ?? '') === 'heading'): ?>
              <li class="fp-exp-heading"><?= htmlspecialchars($item['text'] ?? '') ?></li>
            <?php else: ?>
              <li class="fp-exp-role">
                <div class="fp-exp-card">
                  <div class="fp-exp-top">
                    <strong><?= htmlspecialchars($item['title'] ?? '') ?></strong>
                    <?php if (!empty($item['dates'])): ?>
                      <span class="fp-exp-dates"><?= htmlspecialchars($item['dates']) ?></span>
                    <?php endif; ?>
                  </div>
                  <?php if (!empty($item['detail'])): ?>
                    <p><?= htmlspecialchars($item['detail']) ?></p>
                  <?php endif; ?>
                </div>
              </li>
            <?php endif; endforeach; ?>
          </ol>
        </section>

        <section class="fp-panel" id="fpPubPanel"<?= $pubs ? '' : ' hidden' ?>>
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-journal-richtext"></i></span>
            <div>
              <h3>Selected publications</h3>
              <p id="fpPubCount"><?= count($pubs) ?> listed from departmental records</p>
            </div>
          </div>
          <ol class="fp-pubs<?= count($pubs) > 8 ? ' is-collapsed' : '' ?>" id="fpPubs">
            <?php foreach ($pubs as $i => $pub):
              $year = faculty_pub_year($pub);
              $url = faculty_pub_url($pub);
            ?>
              <li class="fp-pub">
                <span class="fp-pub-num"><?= (int) $i + 1 ?></span>
                <div class="fp-pub-body">
                  <p><?= htmlspecialchars($pub) ?></p>
                  <div class="fp-pub-meta">
                    <?php if ($year !== ''): ?>
                      <span class="fp-pub-year"><?= htmlspecialchars($year) ?></span>
                    <?php endif; ?>
                    <?php if ($url !== ''): ?>
                      <a class="fp-pub-link" href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener">View <i class="bi bi-box-arrow-up-right"></i></a>
                    <?php endif; ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ol>
          <?php if (count($pubs) > 8): ?>
            <button type="button" class="fp-pubs-more" id="fpPubsMore" data-total="<?= count($pubs) ?>">Show all <?= count($pubs) ?> publications</button>
          <?php endif; ?>
        </section>

        <section class="fp-panel" id="fpPending"<?= $extra ? ' hidden' : '' ?>>
          <div class="fp-panel-head">
            <span class="fp-panel-ico"><i class="bi bi-hourglass-split"></i></span>
            <div>
              <h3>Biography</h3>
              <p>Extended CV will appear here as departments submit profile data.</p>
            </div>
          </div>
          <p class="fp-pending-copy">Directory details (designation, department, and PM&amp;DC registration) are shown from the live faculty record. Full experience, expertise, and publication lists are being added from departmental templates.</p>
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
  const titles = /^(associate professor|assistant professor|professor|prof\.?|dr\.?)\s+/i;
  while (titles.test(n)) n = n.replace(titles, '');
  return n.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
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
        hrms = data.find(f => facultySlug(f.empName) === SLUG)
          || data.find(f => EXTRA && facultySlug(f.empName) === facultySlug(EXTRA.name));
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
    const desig = hrms.desTitle || (EXTRA && EXTRA.designation) || '';
    const dept = hrms.depName || (EXTRA && EXTRA.department) || '';
    document.getElementById('fpHeroName').textContent = name;
    document.getElementById('fpCrumb').textContent = name;
    document.getElementById('fpName').textContent = name;
    document.title = name + ' — Faculty Profile | Department of Medical Sciences';
    if (desig) document.getElementById('fpDesig').textContent = desig;
    if (dept) document.getElementById('fpDept').textContent = dept;
    const chips = [];
    if (hrms.facPMDCNo) chips.push('<span class="reg-chip"><i class="bi bi-shield-check"></i> PM&amp;DC ' + escapeHtml(hrms.facPMDCNo) + '</span>');
    if (hrms.facFacRegNo) chips.push('<span class="reg-chip"><i class="bi bi-card-text"></i> Faculty ' + escapeHtml(hrms.facFacRegNo) + '</span>');
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
