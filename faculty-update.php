<?php
require_once __DIR__ . '/includes/faculty-lib.php';

$page_title = 'Faculty Profile Form | Department of Medical Sciences';
$page_description = 'Add or update your PMC faculty profile for the college website.';
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
    <h1>Faculty profile form</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="faculty.php">Faculty</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Faculty profile form</span>
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
          <p>Submitted for review. You can close this page.</p>
          <div class="fu-actions" style="justify-content:center;margin-top:18px">
            <a class="btn-pmc btn-pmc-outline" href="faculty-update"><i class="bi bi-arrow-repeat"></i> Submit another</a>
            <a class="btn-pmc btn-pmc-primary" href="faculty.php"><i class="bi bi-people"></i> Faculty list</a>
          </div>
        </div>
      <?php else: ?>

        <div class="fu-intro">
          <h2>Faculty profile form</h2>
          <p>Select your name, add research topics, and upload publications. Reviewed before going live.</p>
        </div>

        <?php if ($errorMsg !== ''): ?>
          <div class="fu-alert fu-alert-err" role="alert"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>

        <form class="fu-form" id="facultyUpdateForm" action="faculty-update-submit.php" method="post" enctype="multipart/form-data" novalidate>
          <input type="text" name="website" class="fu-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

          <div class="fu-card">
            <h3>1. Who are you?</h3>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="nameSearch">Your name <span class="req">*</span></label>
                <div class="fu-search-wrap">
                  <input class="fu-input" type="search" id="nameSearch" name="name_search" placeholder="Type your name…" autocomplete="off" required>
                  <div class="fu-suggest" id="nameSuggest" role="listbox" aria-label="Matching faculty"></div>
                </div>
                <p class="fu-status" id="loadStatus">Loading…</p>
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
            <h3>2. Research topics <span class="req">*</span></h3>
            <label class="fu-label" for="researchInput">Add 3–8 short topics</label>
            <div class="fu-tag-box" id="researchBox">
              <div class="fu-tags" id="researchTags" aria-live="polite"></div>
              <div class="fu-tag-add">
                <input class="fu-input" type="text" id="researchInput" maxlength="80" placeholder="e.g. Culturally adapted CBT" autocomplete="off">
                <button type="button" class="btn-pmc btn-pmc-outline" id="researchAddBtn">Add</button>
              </div>
            </div>
            <input type="hidden" name="research_preferences" id="researchHidden" value="">
            <p class="fu-note" id="researchCount">0 topics</p>
          </div>

          <div class="fu-card">
            <h3>3. Publications</h3>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="publicationsUrl">Link (optional)</label>
                <input class="fu-input" type="url" id="publicationsUrl" name="publications_url" placeholder="e.g. scholar.google.com / orcid.org">
              </div>
            </div>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="publicationsFile">Upload list <span class="req">*</span></label>
                <input class="fu-file" type="file" id="publicationsFile" name="publications_file" accept=".pdf,.doc,.docx,.txt,.rtf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain" required>
                <p class="fu-note">PDF, Word, or TXT — max 8 MB</p>
              </div>
            </div>
          </div>

          <div class="fu-card">
            <h3>4. Other details</h3>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="qualifications">Education / degrees</label>
                <textarea class="fu-textarea" id="qualifications" name="qualifications" placeholder="One per line"></textarea>
              </div>
            </div>

            <div class="fu-row">
              <div>
                <label class="fu-label" for="skills">College duties</label>
                <textarea class="fu-textarea" id="skills" name="skills" placeholder="One per line"></textarea>
              </div>
            </div>

            <div class="fu-row fu-row-2">
              <div>
                <label class="fu-label" for="photo">Photo</label>
                <input class="fu-file" type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
                <p class="fu-note">JPG/PNG — max 2.5 MB</p>
              </div>
              <div>
                <label class="fu-label" for="phone">Phone (office only)</label>
                <input class="fu-input" type="tel" id="phone" name="contact_phone" placeholder="03xx-xxxxxxx" autocomplete="tel">
              </div>
            </div>
          </div>

          <div class="fu-card">
            <div class="fu-actions">
              <button type="submit" class="btn-pmc btn-pmc-primary" id="submitBtn">
                <i class="bi bi-send"></i> Submit
              </button>
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
  const PROFILES_URL = 'faculty-profiles-api';
  const MAX_TAGS = 8;

  const search = document.getElementById('nameSearch');
  const suggest = document.getElementById('nameSuggest');
  const statusEl = document.getElementById('loadStatus');
  const empName = document.getElementById('empName');
  const slugEl = document.getElementById('slug');
  const desTitle = document.getElementById('desTitle');
  const depName = document.getElementById('depName');
  const researchInput = document.getElementById('researchInput');
  const researchTagsEl = document.getElementById('researchTags');
  const researchHidden = document.getElementById('researchHidden');
  const researchCount = document.getElementById('researchCount');
  const researchAddBtn = document.getElementById('researchAddBtn');
  const publicationsUrl = document.getElementById('publicationsUrl');
  const publicationsFile = document.getElementById('publicationsFile');
  const qualifications = document.getElementById('qualifications');
  const skills = document.getElementById('skills');
  const form = document.getElementById('facultyUpdateForm');
  const submitBtn = document.getElementById('submitBtn');

  let faculty = [];
  let profiles = {};
  let researchTags = [];

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

  function syncResearchHidden() {
    researchHidden.value = researchTags.join('\n');
    researchCount.textContent = researchTags.length
      ? (researchTags.length + ' topic' + (researchTags.length === 1 ? '' : 's'))
      : '0 topics';
  }

  function renderResearchTags() {
    researchTagsEl.innerHTML = researchTags.map((tag, i) => (
      '<span class="fu-tag">' + escapeHtml(tag) +
        '<button type="button" aria-label="Remove ' + escapeHtml(tag) + '" data-i="' + i + '">&times;</button>' +
      '</span>'
    )).join('');
    researchTagsEl.querySelectorAll('button[data-i]').forEach(btn => {
      btn.addEventListener('click', () => {
        researchTags.splice(Number(btn.getAttribute('data-i')), 1);
        renderResearchTags();
      });
    });
    syncResearchHidden();
  }

  function addResearchTag(raw) {
    const tag = String(raw || '').trim().replace(/\s+/g, ' ');
    if (!tag) return;
    if (researchTags.length >= MAX_TAGS) {
      researchCount.textContent = 'Maximum ' + MAX_TAGS + ' topics.';
      return;
    }
    const exists = researchTags.some(t => t.toLowerCase() === tag.toLowerCase());
    if (exists) return;
    researchTags.push(tag.slice(0, 80));
    researchInput.value = '';
    renderResearchTags();
  }

  function setResearchTags(list) {
    researchTags = [];
    (list || []).forEach(item => {
      const tag = String(item || '').trim();
      if (tag && researchTags.length < MAX_TAGS) researchTags.push(tag.slice(0, 80));
    });
    renderResearchTags();
  }

  function clearProfileFields() {
    setResearchTags([]);
    publicationsUrl.value = '';
    qualifications.value = '';
    skills.value = '';
  }

  function closeSuggest() {
    suggest.classList.remove('is-open');
    suggest.innerHTML = '';
  }

  function findExistingProfile(slug, name) {
    const pack = profiles || {};
    const map = pack.profiles || pack;
    const index = pack.index || {};
    if (!map || typeof map !== 'object') return null;

    const keys = [slug, index[slug], facultySlug(name)].filter(Boolean);
    for (const key of keys) {
      if (map[key]) return map[key];
    }
    for (const row of Object.values(map)) {
      if (!row || typeof row !== 'object') continue;
      const aliases = [row.slug, ...(row.aliases || [])].map(facultySlug);
      if (aliases.includes(slug) || aliases.includes(facultySlug(name))) {
        return row;
      }
    }
    return null;
  }

  function applyProfileExtras(rec) {
    if (!rec) return;
    if (Array.isArray(rec.research_preferences) && rec.research_preferences.length) {
      setResearchTags(rec.research_preferences);
    } else if (Array.isArray(rec.research_interests) && rec.research_interests.length) {
      setResearchTags(rec.research_interests);
    }
    if (rec.publications_url) {
      publicationsUrl.value = rec.publications_url;
    }
    if (Array.isArray(rec.qualifications)) {
      qualifications.value = linesFromList(rec.qualifications);
    }
    if (Array.isArray(rec.skills)) {
      skills.value = linesFromList(rec.skills);
    }
  }

  function selectFaculty(row) {
    empName.value = row.empName || '';
    slugEl.value = facultySlug(row.empName || '');
    desTitle.value = row.desTitle || '';
    depName.value = row.depName || '';
    search.value = row.empName || '';
    closeSuggest();

    clearProfileFields();
    const existing = findExistingProfile(slugEl.value, empName.value);
    if (existing) {
      applyProfileExtras(existing);
      statusEl.className = 'fu-status is-existing';
      statusEl.textContent = 'Selected — existing profile (pre-filled).';
    } else {
      statusEl.className = 'fu-status is-new';
      statusEl.textContent = 'Selected — new profile.';
    }
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
      const empId = String(f.empId || f.empID || f.EmpID || f.emp_id || f.empCode || '').toLowerCase();
      return name.includes(query) || dept.includes(query) || empId.includes(query);
    }).slice(0, 12);

    if (!hits.length) {
      suggest.innerHTML = '<button type="button" disabled>No match. Check spelling.</button>';
      suggest.classList.add('is-open');
      return;
    }

    suggest.innerHTML = hits.map((f, i) => {
      const empId = f.empId || f.empID || f.EmpID || f.emp_id || f.empCode || '';
      const slug = facultySlug(f.empName || '');
      const hasProfile = !!findExistingProfile(slug, f.empName || '');
      const idBits = [];
      if (empId) idBits.push('Emp ID: ' + empId);
      if (f.desTitle) idBits.push(f.desTitle);
      if (f.depName) idBits.push(f.depName);
      idBits.push(hasProfile ? 'Has profile' : 'New');
      return (
        '<button type="button" role="option" data-i="' + i + '">' +
          '<strong>' + escapeHtml(f.empName || '') + '</strong>' +
          '<span>' + escapeHtml(idBits.join(' · ')) + '</span>' +
        '</button>'
      );
    }).join('');
    suggest.classList.add('is-open');
    suggest.querySelectorAll('button[data-i]').forEach((btn, idx) => {
      btn.addEventListener('click', () => selectFaculty(hits[idx]));
    });
  }

  researchAddBtn.addEventListener('click', () => addResearchTag(researchInput.value));
  researchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      addResearchTag(researchInput.value.replace(/,/g, ''));
    }
  });

  search.addEventListener('input', () => {
    empName.value = '';
    slugEl.value = '';
    desTitle.value = '';
    depName.value = '';
    clearProfileFields();
    statusEl.className = 'fu-status';
    statusEl.textContent = faculty.length ? 'Type your name.' : 'List empty.';
    renderSuggest(search.value);
  });
  search.addEventListener('focus', () => {
    if (search.value.trim().length >= 2) renderSuggest(search.value);
  });
  document.addEventListener('click', (e) => {
    if (!suggest.contains(e.target) && e.target !== search) closeSuggest();
  });

  form.addEventListener('submit', (e) => {
    syncResearchHidden();
    if (!empName.value.trim() || !slugEl.value.trim()) {
      e.preventDefault();
      statusEl.textContent = 'Select your name from the list.';
      search.focus();
      return;
    }
    if (!researchTags.length) {
      e.preventDefault();
      researchCount.textContent = 'Add at least one topic.';
      researchInput.focus();
      return;
    }
    if (!publicationsFile.files || !publicationsFile.files.length) {
      e.preventDefault();
      publicationsFile.focus();
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
      const profileCount = profiles && profiles.profiles
        ? Object.keys(profiles.profiles).length
        : 0;
      statusEl.textContent = faculty.length
        ? (faculty.length + ' faculty loaded'
            + (profileCount ? (' · ' + profileCount + ' with profiles') : '')
            + '.')
        : 'List empty.';
    } catch (err) {
      console.error(err);
      statusEl.textContent = 'Could not load list. Refresh and try again.';
    }
  })();
})();
</script>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
