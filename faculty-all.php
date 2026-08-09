<?php include('includes/header.php'); ?>

<style>
  .dept-members table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--font-body);
  }
  .dept-members th {
    text-align: left;
    padding: 10px 12px;
    font-family: var(--font-head);
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748b;
    border-bottom: 2px solid #e2e8f0;
  }
  .dept-members td {
    padding: 10px 12px;
    border-bottom: 1px solid #f1f5f9;
    font-size: .85rem;
    color: #334155;
  }
  .dept-members tr:last-child td {
    border-bottom: none;
  }
  .reg-number {
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-size: 0.82rem;
    color: #475569;
    background: #f8fafc;
    padding: 2px 10px;
    border-radius: 4px;
  }
</style>

<link href="assets/css/faculty.css" rel="stylesheet"/>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Our Distinguished Faculty</h1>
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
          <span class="fac-stat-lbl">Verified & Registered</span>
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
        <h2 class="sec-title">Expert Clinicians & Academic Professionals</h2>
        <p class="sec-desc">Riphah International University - Peshawar Campus is proud to have a highly qualified, experienced, and dedicated team of professors, associate professors, assistant professors, senior lecturers, and lecturers — all PM&DC registered — spanning every department of the MBBS curriculum.</p>
      </div>
    </div>

    <!-- SEARCH & FILTER BAR (now includes qualification + designation filter) -->
    <div class="filter-bar fu">
      <div class="row g-3 align-items-end">
        <div class="col-lg-2 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">Search Faculty</label>
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Search by name, department, reg. no…"/>
          </div>
        </div>
        <div class="col-lg-2 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">Department</label>
          <select id="deptFilter" class="filter-select">
            <option value="">All Departments</option>
          </select>
        </div>
        <!-- NEW: Designation Filter -->
        <div class="col-lg-2 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">Designation</label>
          <select id="desigFilter" class="filter-select">
            <option value="">All Designations</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <label class="form-label" style="font-family:var(--font-head);font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;">Qualification</label>
          <select id="qualFilter" class="filter-select">
            <option value="">All Qualifications</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <button class="vt-btn" id="clearFilters" title="Clear Filters" style="background:var(--off-white);border:1px solid var(--border);padding:8px 16px;border-radius:8px; width:100%;">
            <i class="bi bi-x-lg" style="font-size:.8rem;"></i> Clear
          </button>
        </div>
      </div>
      <div class="mt-3 pt-2" style="border-top:1px solid var(--border);">
        <span class="results-info">Showing <span id="resultCount">—</span> of <span id="totalCount">—</span> faculty members</span>
      </div>
    </div>

    <!-- LOADING STATE -->
    <div id="loadingState">
      <div class="spinner-pmc"></div>
      <p style="font-family:var(--font-body);color:var(--gray-mid);font-size:.9rem;">Loading faculty data…</p>
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

    <!-- DEBUG SECTION (shown when ?debug=1) -->
    <?php if (isset($_GET['debug'])): ?>
    <div class="mt-5 p-4 bg-light border rounded" id="debugPanel">
      <h5>DEBUG: Raw data of first faculty member</h5>
      <pre id="debugData" style="max-height:400px; overflow-y:auto;"></pre>
    </div>
    <?php endif; ?>

  </div>
</section>

<?php include('includes/footer.php'); ?>

<script>
const API_URL = 'faculty-proxy.php';
const DEBUG = new URLSearchParams(window.location.search).has('debug');

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
  'IT & MI':           { icon: 'bi-display',           order: 100 },
};

const DESIG_RANK = {
  'Professor': 1, 'Associate Professor': 2, 'Assistant Professor': 3,
  'Senior Lecturer': 4, 'Lecturer': 5, 'Senior Registrar': 6,
  'Registrar': 7, 'CEO': 8, 'Director IT': 9, 'Other': 10
};

// Map full designation to a short prefix (to show before name)
const DESIG_PREFIX = {
  'Professor': 'Prof.',
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

function getDeptIcon(dept) {
  return (DEPT_CONFIG[dept] || { icon: 'bi-person-badge' }).icon;
}

function getDeptOrder(dept) {
  return (DEPT_CONFIG[dept] || { order: 50 }).order;
}

function getDesigRank(desig) {
  return DESIG_RANK[desig] || 10;
}

/**
 * Returns a short prefix for a designation, e.g., "Prof."
 */
function getDesignationPrefix(desTitle) {
  return DESIG_PREFIX[desTitle] || '';
}

/**
 * Construct the display name with appropriate prefix.
 * Medical designations get "Dr." added; non‑medical ones only get the role prefix.
 */
function getDisplayName(m) {
  const prefix = getDesignationPrefix(m.desTitle);
  const name = m.empName || '';
  // Designations that are clinical / academic medical posts
  const medicalDesigs = [
    'Professor', 'Associate Professor', 'Assistant Professor',
    'Senior Lecturer', 'Lecturer', 'Senior Registrar', 'Registrar'
  ];
  if (medicalDesigs.includes(m.desTitle)) {
    return prefix ? (prefix + ' Dr. ' + name) : ('Dr. ' + name);
  } else {
    // Non‑medical (e.g., Director IT) – use prefix only, no Dr.
    return prefix ? (prefix + ' ' + name) : name;
  }
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
  // Department filter
  const depts = [...new Set(faculty.map(f => f.depName).filter(Boolean))]
                 .sort((a,b) => getDeptOrder(a) - getDeptOrder(b));
  const deptSel = document.getElementById('deptFilter');
  while (deptSel.options.length > 1) deptSel.remove(1);
  depts.forEach(d => {
    const o = document.createElement('option');
    o.value = d; o.textContent = d;
    deptSel.appendChild(o);
  });

  // Designation filter (NEW)
  const desigs = [...new Set(faculty.map(f => f.desTitle).filter(Boolean))]
                   .sort((a,b) => (DESIG_RANK[a] || 99) - (DESIG_RANK[b] || 99));
  const desigSel = document.getElementById('desigFilter');
  while (desigSel.options.length > 1) desigSel.remove(1);
  desigs.forEach(d => {
    const o = document.createElement('option');
    o.value = d; o.textContent = d;
    desigSel.appendChild(o);
  });

  // Qualification filter
  const quals = [...new Set(
    faculty.map(f => getQualification(f)).filter(Boolean)
  )].sort();
  const qualSel = document.getElementById('qualFilter');
  while (qualSel.options.length > 1) qualSel.remove(1);
  quals.forEach(q => {
    const o = document.createElement('option');
    o.value = q; o.textContent = q;
    qualSel.appendChild(o);
  });
}

function updateStats(faculty) {
  document.getElementById('statTotal').textContent      = faculty.length;
  document.getElementById('statProfessors').textContent = faculty.filter(f => f.desTitle === 'Professor').length;
  document.getElementById('statDepts').textContent      = new Set(faculty.map(f => f.depName).filter(Boolean)).size;
}

function getFiltered() {
  const q     = document.getElementById('searchInput').value.trim().toLowerCase();
  const dept  = document.getElementById('deptFilter').value;
  const desig = document.getElementById('desigFilter').value;  // NEW
  const qual  = document.getElementById('qualFilter').value;

  return allFaculty.filter(f => {
    const displayName = getDisplayName(f);
    const matchQ = !q || [
      displayName, f.depName, f.facPMDCNo, f.facFacRegNo, getQualification(f)
    ].filter(Boolean).some(v => v.toLowerCase().includes(q));
    const matchDept  = !dept  || f.depName === dept;
    const matchDesig = !desig || f.desTitle === desig;           // NEW
    const matchQual  = !qual  || getQualification(f) === qual;
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

  // 1. Group by department
  const grouped = {};
  faculty.forEach(f => {
    const dept = f.depName || 'Other';
    if (!grouped[dept]) grouped[dept] = [];
    grouped[dept].push(f);
  });

  // 2. For each department, count PhD holders and sort members
  const deptStats = Object.keys(grouped).map(dept => {
    const members = grouped[dept];
    const phdCount = members.filter(m =>
      (getQualification(m) || '').toLowerCase().includes('phd')
    ).length;

    // HoD detection (fallback: highest rank)
    let hod = null;
    if (!hod) {
      const sortedByRank = [...members].sort((a, b) =>
        getDesigRank(a.desTitle) - getDesigRank(b.desTitle)
      );
      hod = sortedByRank[0];
    }

    return { dept, members, phdCount, hod };
  });

  // 3. Sort departments by PhD count (desc), then by original order
  deptStats.sort((a, b) => {
    if (b.phdCount !== a.phdCount) return b.phdCount - a.phdCount;
    return (DEPT_CONFIG[a.dept]?.order ?? 99) - (DEPT_CONFIG[b.dept]?.order ?? 99);
  });

  // 4. Build HTML
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
        <div class="dept-header" onclick="toggleDept(this)">
          <div class="dept-icon"><i class="bi ${icon}"></i></div>
          <div class="dept-name">Department of ${dept}</div>
          <span class="dept-count">${members.length} Member${members.length !== 1 ? 's' : ''}</span>
          <i class="bi bi-chevron-down dept-toggle-icon"></i>
        </div>
        <div class="dept-members">
          <table>
            <thead><tr><th>Name</th><th>Qualification</th><th>PMC Reg. No.</th><th>Faculty Reg. No.</th></tr></thead>
            <tbody>
              ${sortedMembers.map(m => `
                <tr>
                  <td>${getDisplayName(m)}</td>
                  <td>${getQualification(m) || '—'}</td>
                  <td><span class="reg-number">${m.facPMDCNo || '—'}</span></td>
                  <td><span class="reg-number">${m.facFacRegNo || '—'}</span></td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>`;
  });

  container.innerHTML = html;
}

function clearAllFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('deptFilter').value  = '';
  document.getElementById('desigFilter').value = '';   // NEW
  document.getElementById('qualFilter').value  = '';
  renderFaculty(allFaculty);
}

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

  if (DEBUG && data.length > 0) {
    document.getElementById('debugData').textContent = JSON.stringify(data[0], null, 2);
  }

  allFaculty = data.sort((a,b) => {
    const deptDiff = getDeptOrder(a.depName) - getDeptOrder(b.depName);
    if (deptDiff !== 0) return deptDiff;
    return getDesigRank(a.desTitle) - getDesigRank(b.desTitle);
  });

  // Manual IT & MI entry
  allFaculty.push({
    depName: 'IT & MI',
    empName: 'Muhammad Furqan',
    facPMDCNo: '',
    facFacRegNo: '',
    qualifications: 'MS Computer Science'
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
  document.getElementById('desigFilter').addEventListener('change', () => renderFaculty(getFiltered())); // NEW
  document.getElementById('qualFilter').addEventListener('change', () => renderFaculty(getFiltered()));
  document.getElementById('clearFilters').addEventListener('click', clearAllFilters);
})();
</script>