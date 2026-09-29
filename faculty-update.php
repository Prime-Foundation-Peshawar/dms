<?php
require_once __DIR__ . '/includes/faculty-lib.php';

$page_title = 'Update Your Faculty Profile | Department of Medical Sciences';
$page_description = 'Simple form for PMC faculty to share research preferences and profile details for the college website.';
$robots = 'noindex, nofollow';
$thanks = isset($_GET['thanks']);
$errorMsg = trim((string) ($_GET['error'] ?? ''));

include __DIR__ . '/includes/header.php';
?>

<link href="<?= dms_asset('assets/css/faculty-update.css') ?>" rel="stylesheet"/>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Faculty</span>
    <h1>Update your profile</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="faculty.php">Faculty</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Update profile</span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">
    <div class="fu-wrap">

      <?php if ($thanks): ?>
        <div class="fu-card fu-thanks">
          <div class="fu-check" aria-hidden="true"><i class="bi bi-check-lg"></i></div>
          <h2>Thank you</h2>
          <p>Your details were received. The website team will review them before they appear on your public profile.</p>
          <p class="fu-note" style="margin-top:14px">You can close this page now.</p>
          <div class="fu-actions" style="justify-content:center;margin-top:18px">
            <a class="btn-pmc btn-pmc-outline" href="faculty-update"><i class="bi bi-arrow-repeat"></i> Submit another</a>
            <a class="btn-pmc btn-pmc-primary" href="faculty.php"><i class="bi bi-people"></i> Faculty list</a>
          </div>
        </div>
      <?php else: ?>

        <div class="fu-intro">
          <h2>Easy profile form for PMC faculty</h2>
          <p>Find your name, add research preferences, and optionally update education, college duties, or photo. No login needed. Changes are reviewed before going live.</p>
        </div>

        <?php if ($errorMsg !== ''): ?>
          <div class="fu-alert fu-alert-err" role="alert"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>

        <form class="fu-form" id="facultyUpdateForm" action="faculty-update-submit.php" method="post" enctype="multipart/form-data" novalidate>
          <input type="text" name="website" class="fu-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

          <div class="fu-card">
            <h3>1. Who are you?</h3>
            <p class="fu-help">Start typing your name, then tap your name in the list.</p>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="nameSearch">Your name <span class="req">*</span></label>
                <div class="fu-search-wrap">
                  <input class="fu-input" type="search" id="nameSearch" name="name_search" placeholder="Type your name…" autocomplete="off" required>
                  <div class="fu-suggest" id="nameSuggest" role="listbox" aria-label="Matching faculty"></div>
                </div>
                <p class="fu-status" id="loadStatus">Loading faculty list…</p>
              </div>
            </div>

            <input type="hidden" name="emp_name" id="empName" value="">
            <input type="hidden" name="slug" id="slug" value="">

            <div class="fu-row fu-row-2">
              <div>
                <label class="fu-label" for="desTitle">Current post</label>
                <input class="fu-input fu-readonly" type="text" id="desTitle" name="des_title" readonly placeholder="Select your name first">
              </div>
              <div>
                <label class="fu-label" for="depName">Department</label>
                <input class="fu-input fu-readonly" type="text" id="depName" name="dep_name" readonly placeholder="Select your name first">
              </div>
            </div>
          </div>

          <div class="fu-card">
            <h3>2. Research preferences <span class="req">*</span></h3>
            <p class="fu-help">Write 3–8 short topics students or visitors should know. One topic per line.</p>
            <label class="fu-label" for="research">What do you research?</label>
            <textarea class="fu-textarea" id="research" name="research_preferences" required placeholder="Example:&#10;Culturally adapted CBT&#10;Mental health policy&#10;Medical education equity"></textarea>
          </div>

          <div class="fu-card">
            <h3>3. Optional details</h3>
            <p class="fu-help">Fill only if you want to update these. Leave blank to keep existing website info.</p>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="qualifications">Education / degrees</label>
                <textarea class="fu-textarea" id="qualifications" name="qualifications" placeholder="One degree per line&#10;MBBS&#10;FCPS (Psychiatry)"></textarea>
              </div>
            </div>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="skills">College duties</label>
                <textarea class="fu-textarea" id="skills" name="skills" placeholder="One duty per line&#10;Research supervision&#10;Curriculum committee"></textarea>
              </div>
            </div>

            <div class="fu-row fu-row-2">
              <div>
                <label class="fu-label" for="photo">Photo (optional)</label>
                <input class="fu-file" type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
                <p class="fu-note">JPG or PNG, under 2.5 MB.</p>
              </div>
              <div>
                <label class="fu-label" for="phone">Phone / WhatsApp (for office only)</label>
                <input class="fu-input" type="tel" id="phone" name="contact_phone" placeholder="03xx-xxxxxxx" autocomplete="tel">
                <p class="fu-note">Not shown on the public website.</p>
              </div>
            </div>
          </div>

          <div class="fu-card">
            <div class="fu-actions">
              <button type="submit" class="btn-pmc btn-pmc-primary" id="submitBtn">
                <i class="bi bi-send"></i> Submit for review
              </button>
              <span class="fu-note">Takes about 2 minutes. You can close the page after submitting.</span>
            </div>
          </div>
        </form>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php if (!$thanks): ?>
<script>
(function () {
  const API_URL = 'faculty-proxy';
  const PROFILES_URL = 'assets/data/faculty-profiles.json';
  const search = document.getElementById('nameSearch');
  const suggest = document.getElementById('nameSuggest');
  const statusEl = document.getElementById('loadStatus');
  const empName = document.getElementById('empName');
  const slugEl = document.getElementById('slug');
  const desTitle = document.getElementById('desTitle');
  const depName = document.getElementById('depName');
  const research = document.getElementById('research');
  const qualifications = document.getElementById('qualifications');
  const skills = document.getElementById('skills');
  const form = document.getElementById('facultyUpdateForm');
  const submitBtn = document.getElementById('submitBtn');

  let faculty = [];
  let profiles = {};
  let selected = null;

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

  function linesFromList(list) {
    if (!Array.isArray(list) || !list.length) return '';
    return list.map(x => String(x || '').trim()).filter(Boolean).join('\n');
  }

  function closeSuggest() {
    suggest.classList.remove('is-open');
    suggest.innerHTML = '';
  }

  function applyProfileExtras(slug, name) {
    const pack = profiles || {};
    const map = pack.profiles || pack;
    const index = pack.index || {};
    let rec = null;
    const keys = [slug, index[slug], facultySlug(name)].filter(Boolean);
    for (const key of keys) {
      if (map[key]) { rec = map[key]; break; }
    }
    if (!rec && map && typeof map === 'object') {
      for (const row of Object.values(map)) {
        if (!row || typeof row !== 'object') continue;
        const aliases = [row.slug, ...(row.aliases || [])].map(facultySlug);
        if (aliases.includes(slug) || aliases.includes(facultySlug(name))) {
          rec = row;
          break;
        }
      }
    }
    if (!rec) return;
    if (!research.value.trim() && Array.isArray(rec.research_preferences) && rec.research_preferences.length) {
      research.value = linesFromList(rec.research_preferences);
    } else if (!research.value.trim() && Array.isArray(rec.research_interests) && rec.research_interests.length) {
      research.value = linesFromList(rec.research_interests);
    }
    if (!qualifications.value.trim() && Array.isArray(rec.qualifications)) {
      qualifications.value = linesFromList(rec.qualifications);
    }
    if (!skills.value.trim() && Array.isArray(rec.skills)) {
      skills.value = linesFromList(rec.skills);
    }
  }

  function selectFaculty(row) {
    selected = row;
    empName.value = row.empName || '';
    slugEl.value = facultySlug(row.empName || '');
    desTitle.value = row.desTitle || '';
    depName.value = row.depName || '';
    search.value = row.empName || '';
    closeSuggest();
    applyProfileExtras(slugEl.value, empName.value);
    statusEl.textContent = 'Selected. You can edit the fields below.';
  }

  function renderSuggest(q) {
    const query = String(q || '').trim().toLowerCase();
    if (query.length < 2) {
      closeSuggest();
      return;
    }
    const hits = faculty.filter(f => {
      const name = String(f.empName || '').toLowerCase();
      const dept = String(f.depName || '').toLowerCase();
      return name.includes(query) || dept.includes(query);
    }).slice(0, 12);

    if (!hits.length) {
      suggest.innerHTML = '<button type="button" disabled>No match. Check spelling.</button>';
      suggest.classList.add('is-open');
      return;
    }

    suggest.innerHTML = hits.map((f, i) => (
      '<button type="button" role="option" data-i="' + i + '">' +
        '<strong>' + escapeHtml(f.empName || '') + '</strong>' +
        '<span>' + escapeHtml((f.desTitle || '') + (f.depName ? ' · ' + f.depName : '')) + '</span>' +
      '</button>'
    )).join('');
    suggest.classList.add('is-open');
    suggest.querySelectorAll('button[data-i]').forEach((btn, idx) => {
      btn.addEventListener('click', () => selectFaculty(hits[idx]));
    });
  }

  search.addEventListener('input', () => {
    selected = null;
    empName.value = '';
    slugEl.value = '';
    desTitle.value = '';
    depName.value = '';
    renderSuggest(search.value);
  });

  search.addEventListener('focus', () => {
    if (search.value.trim().length >= 2) renderSuggest(search.value);
  });

  document.addEventListener('click', (e) => {
    if (!suggest.contains(e.target) && e.target !== search) closeSuggest();
  });

  form.addEventListener('submit', (e) => {
    if (!empName.value.trim() || !slugEl.value.trim()) {
      e.preventDefault();
      statusEl.textContent = 'Please select your name from the list.';
      search.focus();
      return;
    }
    if (!research.value.trim()) {
      e.preventDefault();
      research.focus();
      return;
    }
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending…';
  });

  (async function load() {
    try {
      const [facRes, profRes] = await Promise.all([
        fetch(API_URL),
        fetch(PROFILES_URL).catch(() => null)
      ]);
      if (!facRes.ok) throw new Error('Faculty list failed');
      const data = await facRes.json();
      faculty = Array.isArray(data) ? data.slice().sort((a, b) =>
        String(a.empName || '').localeCompare(String(b.empName || ''))
      ) : [];
      if (profRes && profRes.ok) {
        profiles = await profRes.json();
      }
      statusEl.textContent = faculty.length
        ? 'Faculty list ready. Type your name to begin.'
        : 'Faculty list is empty. Please try again later.';
    } catch (err) {
      console.error(err);
      statusEl.textContent = 'Could not load faculty list. Refresh the page and try again.';
    }
  })();
})();
</script>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
