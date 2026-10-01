<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<link href="<?= dms_asset('assets/css/faculty.css') ?>" rel="stylesheet"/>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department of Medical Sciences</span>
    <h1>Our Faculty</h1>
    <div class="breadcrumb-pmc">
      <a href="./">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="about">About Us</a>
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
        <a href="departments" class="btn-pmc btn-pmc-outline"><i class="bi bi-diagram-3"></i> Academic Departments</a>
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

<?= $this->endSection() ?>
