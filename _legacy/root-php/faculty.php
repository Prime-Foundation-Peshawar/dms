<?php include('includes/header.php'); ?>

<link href="<?= dms_asset('assets/css/faculty.css') ?>" rel="stylesheet"/>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department of Medical Sciences</span>
    <h1>Our Faculty</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="about.php">About Us</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Faculty</span>
    </div>
  </div>
</div>

<!-- FACULTY STATS STRIP -->
<div class="fac-stats">
  <div class="container">
    <div class="row g-0">
      <div class="col-6 col-md-3">
        <div class="fac-stat-cell">
          <span class="fac-stat-num" id="statTotal">—</span>
          <span class="fac-stat-lbl">Teachers</span>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="fac-stat-cell">
          <span class="fac-stat-num" id="statProfessors">—</span>
          <span class="fac-stat-lbl">Professors</span>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="fac-stat-cell">
          <span class="fac-stat-num" id="statDepts">—</span>
          <span class="fac-stat-lbl">Departments</span>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="fac-stat-cell">
          <span class="fac-stat-num" id="statPMDC">PM&amp;DC</span>
          <span class="fac-stat-lbl">Registered</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN -->
<section class="pmc-section bg-off">
  <div class="container">

    <div class="row mb-4 align-items-end fu">
      <div class="col-lg-8">
        <span class="sec-eyebrow">Meet the faculty</span>
        <h2 class="sec-title">Teachers and doctors</h2>
        <p class="sec-desc mb-0">PM&amp;DC-registered professors, lecturers, and clinicians who teach the MBBS programme.</p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="departments.php" class="btn-pmc btn-pmc-outline"><i class="bi bi-diagram-3"></i> Academic Departments</a>
      </div>
    </div>

    <div class="filter-bar fu">
      <div class="row g-3 align-items-end">
        <div class="col-lg-3 col-md-6">
          <label class="filter-label" for="searchInput">Search</label>
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Name, department, reg. no…"/>
          </div>
        </div>
        <div class="col-lg-2 col-md-6">
          <label class="filter-label" for="deptFilter">Department</label>
          <select id="deptFilter" class="filter-select">
            <option value="">All Departments</option>
          </select>
        </div>
        <div class="col-lg-2 col-md-6">
          <label class="filter-label" for="desigFilter">Post</label>
          <select id="desigFilter" class="filter-select">
            <option value="">All posts</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <label class="filter-label" for="qualFilter">Degree</label>
          <select id="qualFilter" class="filter-select">
            <option value="">All degrees</option>
          </select>
        </div>
        <div class="col-lg-2 col-md-6">
          <button type="button" class="filter-clear-btn" id="clearFilters" title="Clear filters">
            <i class="bi bi-x-lg"></i> Clear
          </button>
        </div>
      </div>
      <div class="filter-meta">
        <span class="results-info">Showing <span id="resultCount">—</span> of <span id="totalCount">—</span> teachers</span>
      </div>
    </div>

    <div id="loadingState">
      <div class="spinner-pmc"></div>
      <p>Loading faculty…</p>
    </div>

    <div id="emptyState">
      <div class="empty-icon"><i class="bi bi-search"></i></div>
      <h5>No matching teachers</h5>
      <p>Try another name, post, or degree.</p>
      <button type="button" onclick="clearAllFilters()" class="btn-pmc btn-pmc-outline"><i class="bi bi-x-circle"></i> Clear filters</button>
</div>

    <div id="facultyContent"></div>

    <?php if (isset($_GET['debug'])): ?>
    <div class="mt-5 p-4 bg-light border rounded" id="debugPanel">
      <h5>DEBUG: Raw data of first faculty member</h5>
      <pre id="debugData" style="max-height:400px; overflow-y:auto;"></pre>
    </div>
    <?php endif; ?>

  </div>
</section>

<?php include('includes/footer.php'); ?>

<?php
require_once __DIR__ . '/includes/faculty-lib.php';
$faculty_photos = faculty_photo_src_map();
?>
<script>
// Shared server directory (same source as departments.php faculty counts).
const API_URL = 'faculty-proxy';
const DEBUG = new URLSearchParams(window.location.search).has('debug');
const FACULTY_PHOTOS = <?= json_encode((object) $faculty_photos, JSON_UNESCAPED_SLASHES) ?>;

const DEPT_CONFIG = {
  'Anatomy':                  { icon: 'bi-body-text',        order: 1  },
  'Physiology':               { icon: 'bi-activity',          order: 2  },
  'Biochemistry':             { icon: 'bi-moisture',          order: 3  },
  'Pathology':                { icon: 'bi-eyedropper',        order: 4  },
  'Pharmacology':             { icon: 'bi-capsule-pill',      order: 5  },
  'Forensic Medicine':        { icon: 'bi-shield-check',      order: 6  },
  'CHS':                      { icon: 'bi-people-fill',       order: 7  },
  'DHPE & R':                 { icon: 'bi-mortarboard-fill',  order: 8  },
  'Medicine':                 { icon: 'bi-heart-pulse-fill',  order: 9  },
  'Surgery':                  { icon: 'bi-scissors',          order: 10 },
  'Gynae and Obstetrics':     { icon: 'bi-gender-female',     order: 11 },
  'Paediatrics':              { icon: 'bi-person-fill',       order: 12 },
  'Paeds Cardiology':         { icon: 'bi-heart',             order: 13 },
  'ENT':                      { icon: 'bi-ear-fill',          order: 14 },
  'Ophthalmology':            { icon: 'bi-eye-fill',          order: 15 },
  'Orthopedics':              { icon: 'bi-bandaid-fill',      order: 16 },
  'Dermatology':              { icon: 'bi-droplet-half',      order: 17 },
  'Radiology':                { icon: 'bi-radioactive',       order: 18 },
  'Anaesthesia':              { icon: 'bi-lungs-fill',        order: 19 },
  'Psychiatry':               { icon: 'bi-hypnotize',         order: 20 },
  'Cardiology':               { icon: 'bi-heart-pulse',       order: 21 },
  'Cardiac Surgery':          { icon: 'bi-heart-pulse',       order: 22 },
  'Neurosurgery':             { icon: 'bi-bandaid',           order: 23 },
  'Neurology':                { icon: 'bi-lightning',         order: 24 },
  'Pulmonology':              { icon: 'bi-lungs',             order: 25 },
  'Gastroenterology':         { icon: 'bi-clipboard2-pulse',  order: 26 },
  'Nephrology':               { icon: 'bi-droplet',           order: 27 },
  'Urology':                  { icon: 'bi-water',             order: 28 },
  'Endocrinology':            { icon: 'bi-clipboard2-pulse',  order: 29 },
  'Oncology':                 { icon: 'bi-virus',             order: 30 },
  'Medical Oncology':         { icon: 'bi-heart',             order: 31 },
  'Family Medicine':          { icon: 'bi-house-heart',       order: 32 },
  'Critical Care':            { icon: 'bi-activity',          order: 33 },
  'Accident and Emergency':   { icon: 'bi-ambulance',         order: 34 },
  'Administration':           { icon: 'bi-building-fill',     order: 99 },
  'IT & MI':                  { icon: 'bi-display',           order: 100 },
};

/** Map legacy / catalog labels onto HRMS department names. */
const DEPT_ALIASES = {
  'community health sciences': 'CHS',
  'dhpe & research': 'DHPE & R',
  'gynaecology & obstetrics': 'Gynae and Obstetrics',
  'gynaecology': 'Gynae and Obstetrics',
  'obstetrics + gynae': 'Gynae and Obstetrics',
  'obstetrics and gynae': 'Gynae and Obstetrics',
  'orthopaedics': 'Orthopedics',
};

const DESIG_RANK = {
  'Professor': 1, 'Professor & HOD': 1, 'Associate Professor': 2, 'Assistant Professor': 3,
  'Senior Registrar': 4, 'Senior Lecturer': 5, 'Lecturer': 6,
  'Registrar': 7, 'CEO': 8, 'Director IT': 9, 'Other': 10
};

const DESIG_PREFIX = {
  'Professor': 'Prof.',
  'Professor & HOD': 'Prof.',
  'Associate Professor': 'Assoc. Prof.',
  'Assistant Professor': 'Asst. Prof.',
  'Senior Lecturer': 'Sr. Lecturer',
  'Lecturer': 'Lecturer',
  'Senior Registrar': 'Sr. Registrar',
  'Registrar': 'Registrar',
  'CEO': 'CEO',
  'Director IT': 'Director',
  'Other': ''
};

let allFaculty = [];

function canonicalDept(name) {
  const raw = String(name || '').trim();
  if (!raw) return '';
  if (DEPT_CONFIG[raw]) return raw;
  const key = raw.toLowerCase().replace(/&/g, 'and').replace(/\s+/g, ' ').trim();
  if (DEPT_ALIASES[key]) return DEPT_ALIASES[key];
  if (DEPT_ALIASES[raw.toLowerCase()]) return DEPT_ALIASES[raw.toLowerCase()];
  return raw;
}

function getDeptIcon(dept) {
  return (DEPT_CONFIG[canonicalDept(dept)] || { icon: 'bi-person-badge' }).icon;
}

function getDeptOrder(dept) {
  return (DEPT_CONFIG[canonicalDept(dept)] || { order: 50 }).order;
}

function getDesigRank(desig) {
  return DESIG_RANK[desig] || 10;
}

function isFacultyRank(desig) {
  const d = String(desig || '').toLowerCase().replace(/&/g, ' and ').replace(/[^a-z0-9]+/g, ' ').trim();
  if (!d) return false;
  if (/\bsenior lecturer\b/.test(d)) return false;
  if (/\blecturer\b/.test(d) && !/\bsenior registrar\b/.test(d)) return false;
  if (/\bregistrar\b/.test(d) && !/\bsenior registrar\b/.test(d)) return false;
  if (/\bsenior registrar\b/.test(d) || /(^| )sr( |$)/.test(d)) return true;
  if (/\bassociate professor\b/.test(d) || /\bassoc prof\b/.test(d)) return true;
  if (/\bassistant professor\b/.test(d) || /\basst prof\b/.test(d) || /\bassist prof\b/.test(d) || /\bassistant prof\b/.test(d)) return true;
  if (/\bhod\b/.test(d) || /\bchairman\b/.test(d) || /\bhead of\b/.test(d) || /\bdean\b/.test(d)) return true;
  return /\bprofessor\b/.test(d) || /(^| )prof( |$)/.test(d);
}

function getDesignationPrefix(desTitle) {
  return DESIG_PREFIX[desTitle] || '';
}

function getDisplayName(m) {
  const prefix = getDesignationPrefix(m.desTitle);
  const name = m.empName || '';
  const medicalDesigs = [
    'Professor', 'Professor & HOD', 'Associate Professor', 'Assistant Professor',
    'Senior Registrar'
  ];
  if (medicalDesigs.includes(m.desTitle)) {
    return prefix ? (prefix + ' Dr. ' + name) : ('Dr. ' + name);
  }
  return prefix ? (prefix + ' ' + name) : name;
}

function getInitials(m) {
  const name = (m.empName || '').trim();
  if (!name) return 'D';
  const parts = name.split(/\s+/).filter(Boolean);
  let initials = '';
  for (const part of parts) {
    initials += part.charAt(0).toUpperCase();
    if (initials.length >= 2) break;
  }
  return initials || 'D';
}

function getAvatarClass(desTitle) {
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

function escapeHtml(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

async function fetchFaculty() {
  try {
    const res = await fetch(API_URL);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();
    return Array.isArray(data) ? data : [];
  } catch (err) {
    console.error('Faculty API error:', err);
    return null;
  }
}

function populateFilters(faculty) {
  const depts = [...new Set(faculty.map(f => f.depName).filter(Boolean))]
    .sort((a, b) => getDeptOrder(a) - getDeptOrder(b));
  const deptSel = document.getElementById('deptFilter');
  while (deptSel.options.length > 1) deptSel.remove(1);
  depts.forEach(d => {
    const o = document.createElement('option');
    o.value = d; o.textContent = d;
    deptSel.appendChild(o);
  });

  const desigs = [...new Set(faculty.map(f => f.desTitle).filter(Boolean))]
    .sort((a, b) => (DESIG_RANK[a] || 99) - (DESIG_RANK[b] || 99));
  const desigSel = document.getElementById('desigFilter');
  while (desigSel.options.length > 1) desigSel.remove(1);
  desigs.forEach(d => {
    const o = document.createElement('option');
    o.value = d; o.textContent = d;
    desigSel.appendChild(o);
  });

  const quals = [...new Set(faculty.map(f => getQualification(f)).filter(Boolean))].sort();
  const qualSel = document.getElementById('qualFilter');
  while (qualSel.options.length > 1) qualSel.remove(1);
  quals.forEach(q => {
    const o = document.createElement('option');
    o.value = q; o.textContent = q;
    qualSel.appendChild(o);
  });
}

function updateStats(faculty) {
  document.getElementById('statTotal').textContent = faculty.length;
  document.getElementById('statProfessors').textContent = faculty.filter(f => f.desTitle === 'Professor').length;
  document.getElementById('statDepts').textContent = new Set(faculty.map(f => f.depName).filter(Boolean)).size;
}

function getFiltered() {
  const q = document.getElementById('searchInput').value.trim().toLowerCase();
  const dept = document.getElementById('deptFilter').value;
  const desig = document.getElementById('desigFilter').value;
  const qual = document.getElementById('qualFilter').value;

  return allFaculty.filter(f => {
    const displayName = getDisplayName(f);
    const matchQ = !q || [
      displayName, f.depName, f.facPMDCNo, f.facFacRegNo, getQualification(f), f.desTitle
    ].filter(Boolean).some(v => String(v).toLowerCase().includes(q));
    const matchDept = !dept || f.depName === dept;
    const matchDesig = !desig || f.desTitle === desig;
    const matchQual = !qual || getQualification(f) === qual;
    return matchQ && matchDept && matchDesig && matchQual;
  });
}

function toggleDept(header) {
  const section = header.closest('.dept-section');
  const members = section.querySelector('.dept-members');
  members.style.display = members.style.display === 'none' ? '' : 'none';
  section.classList.toggle('collapsed');
}

function getQualification(m) {
  return m.qualifications || m.qualification || m.degree || m.education || m.qual || m.qualification_name || '';
}

let extraPack = { profiles: {}, index: {} };

function facultySlug(name) {
  let n = String(name || '').trim();
  const titles = /^(associate professor|assistant professor|senior registrar|senior lecturer|assoc\.?\s*prof\.?|asst\.?\s*prof\.?|sr\.?\s*registrar|sr\.?\s*lecturer|professor|prof\.?|dr\.?)\s+/i;
  while (titles.test(n)) n = n.replace(titles, '');
  n = n.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
  return n.replace(/^(mohammad|muhammed)-/, 'muhammad-');
}

function slugTokens(slug) {
  const parts = String(slug || '').split('-').filter(Boolean);
  return parts.map(p => (['m', 'md', 'mohammad', 'muhammed'].includes(p) ? 'muhammad' : p));
}

function tokensSubsequence(short, long) {
  if (!short.length) return true;
  if (short.length > long.length) return false;
  let i = 0;
  for (const token of long) {
    if (i < short.length && short[i] === token) i++;
  }
  return i === short.length;
}

function slugsMatch(a, b) {
  if (!a || !b) return false;
  a = facultySlug(a);
  b = facultySlug(b);
  if (a === b) return true;
  const ta = slugTokens(a);
  const tb = slugTokens(b);
  if (!ta.length || !tb.length) return false;
  if (ta.join('-') === tb.join('-')) return true;
  if (ta[0] !== tb[0]) return false;
  const la = ta[ta.length - 1], lb = tb[tb.length - 1];
  if (la !== lb && !la.startsWith(lb) && !lb.startsWith(la)) return false;
  const midA = ta.slice(1, -1);
  const midB = tb.slice(1, -1);
  // Both have middle names: require overlap (stops aman-khan matching bilal-khan).
  if (midA.length && midB.length) {
    return tokensSubsequence(midA, midB) || tokensSubsequence(midB, midA);
  }
  return true;
}

function extraFor(name) {
  const s = facultySlug(name);
  const tries = [s];
  if (extraPack.index && extraPack.index[s]) tries.push(extraPack.index[s]);
  for (const key of tries) {
    if (extraPack.profiles && extraPack.profiles[key]) return extraPack.profiles[key];
  }
  const rows = extraPack.profiles ? Object.values(extraPack.profiles) : [];
  for (const rec of rows) {
    const keys = [rec.slug, rec.name, ...(rec.aliases || [])].filter(Boolean);
    if (keys.some(k => slugsMatch(s, facultySlug(k)) || slugsMatch(s, k))) return rec;
  }
  return null;
}

function hasWordProfile(extra) {
  if (!extra) return false;
  if (!isFacultyRank(extra.designation)) return false;
  if (extra.photo) return true;
  return ['qualifications', 'experience', 'publications', 'skills']
    .some(key => Array.isArray(extra[key]) && extra[key].length > 0);
}

function listHasProfile(rec) {
  const keys = [rec.slug, ...(rec.aliases || [])].filter(Boolean);
  return allFaculty.some(f => {
    const s = facultySlug(f.empName);
    return keys.some(k => slugsMatch(s, k));
  });
}

function appendMissingCvFaculty() {
  const rows = extraPack.profiles ? Object.values(extraPack.profiles) : [];
  for (const rec of rows) {
    if (!rec || !rec.name || !hasWordProfile(rec)) continue;
    if (!isFacultyRank(rec.designation)) continue;
    if (listHasProfile(rec)) continue;
    allFaculty.push({
      empName: rec.name,
      desTitle: String(rec.designation || 'Faculty').split(/[\n;]/)[0].trim(),
      depName: rec.department || '',
      facPMDCNo: '',
      facFacRegNo: '',
      qualifications: Array.isArray(rec.qualifications)
        ? rec.qualifications.join(', ')
        : (rec.qualifications || ''),
    });
  }
}

function renderMemberRow(m) {
  const extra = extraFor(m.empName);
  const linked = hasWordProfile(extra);
  const slug = (extra && extra.slug) || facultySlug(m.empName);
  const name = escapeHtml(getDisplayName(m));
  const desig = escapeHtml(m.desTitle || 'Faculty');
  const qual = escapeHtml(getQualification(m) || '—');
  const pmdc = escapeHtml(m.facPMDCNo || '—');
  const facReg = escapeHtml(m.facFacRegNo || '—');
  const initials = escapeHtml(getInitials(m));
  const avatarClass = getAvatarClass(m.desTitle);
  const nameSlug = facultySlug(m.empName);
  const photo = FACULTY_PHOTOS[nameSlug]
    || (extra && extra.slug && FACULTY_PHOTOS[extra.slug])
    || (extra && extra.photo)
    || '';
  const avatar = photo
    ? `<img src="${escapeHtml(photo)}" alt="">`
    : initials;
  const inner = `
      <div class="fac-list-avatar ${avatarClass}">${avatar}</div>
      <div class="fac-list-main">
        <div class="fac-list-name">${name}</div>
        <div class="fac-list-desig">${desig}</div>
        <div class="fac-list-qual">${qual}</div>
        <div class="fac-list-regs">
          <span class="reg-chip"><i class="bi bi-shield-check"></i> PM&amp;DC No. ${pmdc}</span>
          <span class="reg-chip"><i class="bi bi-card-text"></i> Faculty No. ${facReg}</span>
        </div>
      </div>
      ${linked ? '<i class="bi bi-chevron-right fac-list-go" aria-hidden="true"></i>' : ''}`;

  if (linked) {
    return `<a class="fac-list-row" href="faculty-profile?n=${encodeURIComponent(slug)}">${inner}</a>`;
  }
  return `<article class="fac-list-row is-static">${inner}</article>`;
}

function renderFaculty(faculty) {
  const container = document.getElementById('facultyContent');
  const emptyState = document.getElementById('emptyState');
  const resultCount = document.getElementById('resultCount');
  const totalCount = document.getElementById('totalCount');

  resultCount.textContent = faculty.length;
  totalCount.textContent = allFaculty.length;

  if (!faculty.length) {
    container.innerHTML = '';
    emptyState.style.display = 'block';
    return;
  }
  emptyState.style.display = 'none';

  const grouped = {};
  faculty.forEach(f => {
    const dept = f.depName || 'Other';
    if (!grouped[dept]) grouped[dept] = [];
    grouped[dept].push(f);
  });

  const deptStats = Object.keys(grouped).map(dept => {
    const members = grouped[dept];
    const sortedByRank = [...members].sort((a, b) =>
      getDesigRank(a.desTitle) - getDesigRank(b.desTitle)
    );
    return { dept, members, hod: sortedByRank[0] || null };
  });

  deptStats.sort((a, b) => getDeptOrder(a.dept) - getDeptOrder(b.dept));

  let html = '';
  deptStats.forEach(({ dept, members, hod }) => {
    const sortedMembers = [...members].sort((a, b) => {
      if (a === hod) return -1;
      if (b === hod) return 1;
      return getDesigRank(a.desTitle) - getDesigRank(b.desTitle);
    });

    const icon = getDeptIcon(dept);
    html += `
      <div class="dept-section">
        <div class="dept-header" onclick="toggleDept(this)" role="button" tabindex="0">
          <div class="dept-icon"><i class="bi ${icon}"></i></div>
          <div class="dept-name">${escapeHtml(dept)}</div>
          <span class="dept-count">${members.length} Member${members.length !== 1 ? 's' : ''}</span>
          <i class="bi bi-chevron-down dept-toggle-icon"></i>
        </div>
        <div class="dept-members">
          ${sortedMembers.map(renderMemberRow).join('')}
        </div>
      </div>`;
  });

  container.innerHTML = html;
}

function clearAllFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('deptFilter').value = '';
  document.getElementById('desigFilter').value = '';
  document.getElementById('qualFilter').value = '';
  renderFaculty(allFaculty);
}

(async function init() {
  const loading = document.getElementById('loadingState');
  loading.style.display = 'block';

  const data = await fetchFaculty();
  try {
    const extraRes = await fetch('assets/data/faculty-profiles.json');
    if (extraRes.ok) {
      extraPack = await extraRes.json();
      const profiles = extraPack.profiles || {};
      const index = extraPack.index || {};
      for (const [slug, rec] of Object.entries(profiles)) {
        if (rec && isFacultyRank(rec.designation)) continue;
        delete profiles[slug];
        for (const [alias, target] of Object.entries(index)) {
          if (target === slug) delete index[alias];
        }
      }
    }
  } catch (e) { /* directory still works without extra CVs */ }
  loading.style.display = 'none';

  if (!data) {
    document.getElementById('facultyContent').innerHTML = `
      <div class="fac-error">
        <div class="fac-error-icon"><i class="bi bi-wifi-off"></i></div>
        <h5>Could not load the faculty list</h5>
        <p>Check your connection, then try again.</p>
        <button type="button" onclick="location.reload()" class="btn-pmc btn-pmc-primary">
          <i class="bi bi-arrow-clockwise"></i> Retry
        </button>
      </div>`;
    return;
  }

  if (DEBUG && data.length > 0) {
    const debugEl = document.getElementById('debugData');
    if (debugEl) debugEl.textContent = JSON.stringify(data[0], null, 2);
  }

  // Directory is already filtered/merged server-side (faculty_public_directory).
  allFaculty = data
    .map(f => {
      f.depName = canonicalDept(f.depName) || f.depName;
      return f;
    })
    .sort((a, b) => {
    const deptDiff = getDeptOrder(a.depName) - getDeptOrder(b.depName);
    if (deptDiff !== 0) return deptDiff;
    return getDesigRank(a.desTitle) - getDesigRank(b.desTitle);
  });

  updateStats(allFaculty);
  populateFilters(allFaculty);
  renderFaculty(allFaculty);

  let searchTimer;
  document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => renderFaculty(getFiltered()), 250);
  });
  document.getElementById('deptFilter').addEventListener('change', () => renderFaculty(getFiltered()));
  document.getElementById('desigFilter').addEventListener('change', () => renderFaculty(getFiltered()));
  document.getElementById('qualFilter').addEventListener('change', () => renderFaculty(getFiltered()));
  document.getElementById('clearFilters').addEventListener('click', clearAllFilters);
})();
</script>
