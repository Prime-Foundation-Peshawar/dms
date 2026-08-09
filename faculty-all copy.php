<?php include('includes/header.php'); ?>

<link href="assets/css/faculty.css" rel="stylesheet"/>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Our Distinguished Faculty</h1>
    <div class="breadcrumb-pmc">
      <a href="index.html">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="about.html">About Us</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Faculty</span>
    </div>
  </div>
</div>

<!-- FACULTY STATS STRIP -->
<div class="fac-stats">
  <div class="container-fluid px-0">
    <div class="row g-0">
      <div class="col-6 col-md-3">
        <div class="fac-stat-cell">
          <span class="fac-stat-num" id="statTotal">—</span>
          <span class="fac-stat-lbl">Total Faculty Members</span>
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
          <span class="fac-stat-num" id="statPMDC">PM&DC</span>
          <span class="fac-stat-lbl">Verified &amp; Registered</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN -->
<section class="pmc-section bg-off">
  <div class="container">

    <!-- INTRO -->
    <div class="row mb-5 fu">
      <div class="col-lg-8">
        <span class="sec-eyebrow">About Our Faculty</span>
        <h2 class="sec-title">Expert Clinicians &amp; Academic Professionals</h2>
        <p class="sec-desc">Peshawar Medical College is proud to have a highly qualified, experienced, and dedicated team of professors, associate professors, assistant professors, senior lecturers, and lecturers — all PM&DC registered — spanning every department of the MBBS curriculum. Their collective expertise drives academic excellence and clinical competence in every PMC graduate.</p>
      </div>
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="filter-bar fu">
      <div class="row g-3 align-items-end">
        <div class="col-lg-4 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">Search Faculty</label>
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Search by name, designation, department…"/>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">Filter by Department</label>
          <select id="deptFilter" class="filter-select">
            <option value="">All Departments</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">Filter by Designation</label>
          <select id="desigFilter" class="filter-select">
            <option value="">All Designations</option>
          </select>
        </div>
        <div class="col-lg-2 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">View</label>
          <div class="d-flex align-items-center gap-2">
            <div class="view-toggle">
              <button class="vt-btn active" id="gridViewBtn" title="Grid View"><i class="bi bi-grid-3x3-gap-fill"></i></button>
              <button class="vt-btn" id="listViewBtn" title="List View"><i class="bi bi-list-ul"></i></button>
            </div>
            <button class="vt-btn" id="clearFilters" title="Clear Filters" style="background:var(--off-white);border-color:var(--border);">
              <i class="bi bi-x-lg" style="font-size:.8rem;"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- Results info -->
      <div class="mt-3 pt-2" style="border-top:1px solid var(--border);">
        <span class="results-info">Showing <span id="resultCount">—</span> of <span id="totalCount">—</span> faculty members</span>
      </div>
    </div>

    <!-- LOADING STATE -->
    <div id="loadingState">
      <div class="spinner-pmc"></div>
      <p style="font-family:var(--font-body);color:var(--gray-mid);font-size:.9rem;">Loading faculty data from server…</p>
    </div>

    <!-- EMPTY STATE -->
    <div id="emptyState">
      <div class="empty-icon"><i class="bi bi-search"></i></div>
      <h5 style="font-family:var(--font-head);color:var(--navy);font-size:1.1rem;margin-bottom:8px;">No Results Found</h5>
      <p style="font-family:var(--font-body);color:var(--gray-mid);font-size:.9rem;">Try adjusting your search or filter criteria.</p>
      <button onclick="clearAllFilters()" class="btn-pmc btn-pmc-outline" style="font-size:.85rem;padding:9px 20px;margin-top:8px;"><i class="bi bi-x-circle"></i> Clear All Filters</button>
    </div>

    <!-- FACULTY CONTENT -->
    <div id="facultyContent"></div>

  </div>
</section>

<!-- Recognition Strip -->
<section class="pmc-section-sm recog-strip">
  <div class="container">
    <div class="recog-grid">
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-patch-check-fill"></i></div><div class="recog-name">PM&DC</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-building-fill"></i></div><div class="recog-name">Riphah International University</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-award-fill"></i></div><div class="recog-name">CPSP</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-hospital-fill"></i></div><div class="recog-name">Ministry of Health</div></div>
      <div class="recog-cell" style="border-right:none;"><div class="recog-ico"><i class="bi bi-globe-americas"></i></div><div class="recog-name">WHO</div></div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>

<script>
/* ═══════════════════════════════════════════════
   PMC FACULTY PAGE — LIVE DATA FROM API
   API: https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php
═══════════════════════════════════════════════ */

// const API_URL = 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php';

const API_URL = 'faculty-proxy.php';

// Department config: icon + display order
const DEPT_CONFIG = {
  'Anatomy':           { icon: 'bi-body-text',        order: 1  },
  'Physiology':        { icon: 'bi-activity',          order: 2  },
  'Biochemistry':      { icon: 'bi-flask',             order: 3  },
  'Pathology':         { icon: 'bi-eyedropper',        order: 4  },
  'Pharmacology':      { icon: 'bi-capsule-pill',      order: 5  },
  'Forensic Medicine': { icon: 'bi-shield-check',      order: 6  },
  'CHS':               { icon: 'bi-people-fill',       order: 7  },
  'DHPE & R':          { icon: 'bi-mortarboard-fill',  order: 8  },
  'Psychiatry':        { icon: 'bi-brain',             order: 9  },
  'Medicine':          { icon: 'bi-heart-pulse-fill',  order: 10 },
  'Surgery':           { icon: 'bi-scissors',          order: 11 },
  'Gynaecology':       { icon: 'bi-gender-female',     order: 12 },
  'Paediatrics':       { icon: 'bi-person-fill',       order: 13 },
  'ENT':               { icon: 'bi-ear-fill',          order: 14 },
  'Ophthalmology':     { icon: 'bi-eye-fill',          order: 15 },
  'Orthopaedics':      { icon: 'bi-bandaid-fill',      order: 16 },
  'Dermatology':       { icon: 'bi-droplet-half',      order: 17 },
  'Radiology':         { icon: 'bi-radioactive',       order: 18 },
  'Anaesthesia':       { icon: 'bi-lungs-fill',        order: 19 },
  'Administration':    { icon: 'bi-building-fill',     order: 99 },
};

// Designation rank (for sorting within dept)
const DESIG_RANK = {
  'Professor': 1, 'Associate Professor': 2, 'Assistant Professor': 3,
  'Senior Lecturer': 4, 'Lecturer': 5, 'Senior Registrar': 6,
  'Registrar': 7, 'CEO': 8, 'Other': 9
};

// Avatar gradient per designation
const DESIG_CLASS = {
  'Professor':           'des-professor',
  'Associate Professor': 'des-associate',
  'Assistant Professor': 'des-assistant',
  'Senior Lecturer':     'des-senior-lec',
  'Lecturer':            'des-lecturer',
  'Senior Registrar':    'des-registrar',
  'Registrar':           'des-registrar',
  'CEO':                 'des-other',
};

let allFaculty  = [];
let currentView = 'grid'; // 'grid' | 'list'

// ─── Utility ────────────────────────────────────────
function getInitials(name) {
  return name.trim().split(/\s+/)
    .filter((_, i) => i < 2)
    .map(w => w[0].toUpperCase())
    .join('');
}

function getDeptIcon(dept) {
  return (DEPT_CONFIG[dept] || { icon: 'bi-person-badge' }).icon;
}

function getDeptOrder(dept) {
  return (DEPT_CONFIG[dept] || { order: 50 }).order;
}

function getDesigClass(desig) {
  return DESIG_CLASS[desig] || 'des-other';
}

function getDesigRank(desig) {
  return DESIG_RANK[desig] || 9;
}

// ─── Fetch Data ──────────────────────────────────────
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

// ─── Populate Filters ────────────────────────────────
function populateFilters(faculty) {
  const depts  = [...new Set(faculty.map(f => f.depName).filter(Boolean))].sort((a,b) => getDeptOrder(a) - getDeptOrder(b));
  const desigs = [...new Set(faculty.map(f => f.desTitle).filter(Boolean))].sort((a,b) => getDesigRank(a) - getDesigRank(b));

  const deptSel  = document.getElementById('deptFilter');
  const desigSel = document.getElementById('desigFilter');

  depts.forEach(d => {
    const o = document.createElement('option');
    o.value = d; o.textContent = d;
    deptSel.appendChild(o);
  });

  desigs.forEach(d => {
    const o = document.createElement('option');
    o.value = d; o.textContent = d;
    desigSel.appendChild(o);
  });
}

// ─── Update Stats ────────────────────────────────────
function updateStats(faculty) {
  const total    = faculty.length;
  const profs    = faculty.filter(f => f.desTitle === 'Professor').length;
  const depts    = new Set(faculty.map(f => f.depName).filter(Boolean)).size;

  document.getElementById('statTotal').textContent      = total;
  document.getElementById('statProfessors').textContent = profs;
  document.getElementById('statDepts').textContent      = depts;
}

// ─── Filter ──────────────────────────────────────────
function getFiltered() {
  const q     = document.getElementById('searchInput').value.trim().toLowerCase();
  const dept  = document.getElementById('deptFilter').value;
  const desig = document.getElementById('desigFilter').value;

  return allFaculty.filter(f => {
    const matchQ    = !q || [f.empName, f.desTitle, f.depName, f.facPMDCNo]
                             .filter(Boolean).some(v => v.toLowerCase().includes(q));
    const matchDept  = !dept  || f.depName  === dept;
    const matchDesig = !desig || f.desTitle === desig;
    return matchQ && matchDept && matchDesig;
  });
}

// ─── Build GRID Card ─────────────────────────────────
function buildGridCard(f) {
  const initials  = getInitials(f.empName || '?');
  const desigCls  = getDesigClass(f.desTitle);
  const pmdc      = f.facPMDCNo || 'N/A';
  return `
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
      <div class="fac-card">
        <div class="fac-avatar ${desigCls}">${initials}</div>
        <div class="fac-name">${f.empName || '—'}</div>
        <div class="fac-designation">${f.desTitle || '—'}</div>
        <div class="fac-dept">${f.depName || '—'}</div>
        <div class="fac-pmdc"><i class="bi bi-patch-check-fill"></i> PM&DC: ${pmdc}</div>
      </div>
    </div>`;
}

// ─── Build LIST Row ──────────────────────────────────
function buildListRow(f) {
  const initials = getInitials(f.empName || '?');
  const desigCls = getDesigClass(f.desTitle);
  const pmdc     = f.facPMDCNo || 'N/A';
  return `
    <div class="fac-list-row">
      <div class="fac-list-avatar ${desigCls}">${initials}</div>
      <div style="flex:1;min-width:0;">
        <div class="fac-list-name">${f.empName || '—'}</div>
        <div class="fac-list-desig">${f.desTitle || '—'}</div>
      </div>
      <div class="fac-list-dept">${f.depName || '—'}</div>
      <div class="fac-list-pmdc"><i class="bi bi-patch-check-fill"></i> ${pmdc}</div>
    </div>`;
}

// ─── Render Faculty ──────────────────────────────────
function renderFaculty(faculty) {
  const container   = document.getElementById('facultyContent');
  const emptyState  = document.getElementById('emptyState');
  const resultCount = document.getElementById('resultCount');
  const totalCount  = document.getElementById('totalCount');

  resultCount.textContent = faculty.length;
  totalCount.textContent  = allFaculty.length;

  if (!faculty.length) {
    container.innerHTML = '';
    emptyState.style.display = 'block';
    return;
  }
  emptyState.style.display = 'none';

  // Group by department
  const grouped = {};
  faculty.forEach(f => {
    const dept = f.depName || 'Other';
    if (!grouped[dept]) grouped[dept] = [];
    grouped[dept].push(f);
  });

  // Sort departments by config order
  const sortedDepts = Object.keys(grouped).sort((a,b) => getDeptOrder(a) - getDeptOrder(b));

  let html = '';

  sortedDepts.forEach(dept => {
    const members = grouped[dept].sort((a,b) => getDesigRank(a.desTitle) - getDesigRank(b.desTitle));
    const icon    = getDeptIcon(dept);

    html += `
      <div class="dept-section">
        <div class="dept-header" onclick="toggleDept(this)">
          <div class="dept-icon"><i class="bi ${icon}"></i></div>
          <div class="dept-name">Department of ${dept}</div>
          <span class="dept-count">${members.length} Member${members.length !== 1 ? 's' : ''}</span>
          <i class="bi bi-chevron-down dept-toggle-icon"></i>
        </div>
        <div class="dept-members">`;

    if (currentView === 'grid') {
      html += `<div class="row g-0">`;
      members.forEach(f => { html += buildGridCard(f); });
      html += `</div>`;
    } else {
      members.forEach(f => { html += buildListRow(f); });
    }

    html += `</div></div>`;
  });

  container.innerHTML = html;
}

// ─── Toggle Dept Collapse ────────────────────────────
function toggleDept(header) {
  const section = header.closest('.dept-section');
  const members = section.querySelector('.dept-members');
  if (members.style.display === 'none') {
    members.style.display = '';
    section.classList.remove('collapsed');
  } else {
    members.style.display = 'none';
    section.classList.add('collapsed');
  }
}

// ─── Clear Filters ───────────────────────────────────
function clearAllFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('deptFilter').value  = '';
  document.getElementById('desigFilter').value = '';
  renderFaculty(allFaculty);
}

// ─── Init ────────────────────────────────────────────
(async function init() {
  const loading = document.getElementById('loadingState');
  loading.style.display = 'block';

  const data = await fetchFaculty();

  loading.style.display = 'none';

  if (!data) {
    document.getElementById('facultyContent').innerHTML = `
      <div class="text-center py-5">
        <div style="font-size:3rem;color:var(--gray-light);margin-bottom:16px;"><i class="bi bi-wifi-off"></i></div>
        <h5 style="font-family:var(--font-head);color:var(--navy);">Unable to Load Faculty Data</h5>
        <p style="font-family:var(--font-body);color:var(--gray-mid);font-size:.9rem;">Please check your connection or try refreshing the page.</p>
        <button onclick="location.reload()" class="btn-pmc btn-pmc-primary mt-3" style="font-size:.85rem;padding:10px 22px;">
          <i class="bi bi-arrow-clockwise"></i> Retry
        </button>
      </div>`;
    return;
  }

  // Sort: by dept order, then by designation rank
  allFaculty = data.sort((a,b) => {
    const deptDiff = getDeptOrder(a.depName) - getDeptOrder(b.depName);
    if (deptDiff !== 0) return deptDiff;
    return getDesigRank(a.desTitle) - getDesigRank(b.desTitle);
  });

  updateStats(allFaculty);
  populateFilters(allFaculty);
  renderFaculty(allFaculty);

  // Event listeners
  let searchTimer;
  document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => renderFaculty(getFiltered()), 250);
  });
  document.getElementById('deptFilter').addEventListener('change',  () => renderFaculty(getFiltered()));
  document.getElementById('desigFilter').addEventListener('change', () => renderFaculty(getFiltered()));
  document.getElementById('clearFilters').addEventListener('click', clearAllFilters);

  // View toggle
  document.getElementById('gridViewBtn').addEventListener('click', () => {
    currentView = 'grid';
    document.getElementById('gridViewBtn').classList.add('active');
    document.getElementById('listViewBtn').classList.remove('active');
    renderFaculty(getFiltered());
  });
  document.getElementById('listViewBtn').addEventListener('click', () => {
    currentView = 'list';
    document.getElementById('listViewBtn').classList.add('active');
    document.getElementById('gridViewBtn').classList.remove('active');
    renderFaculty(getFiltered());
  });
})();
</script>