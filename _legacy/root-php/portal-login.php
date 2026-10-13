<?php include('includes/header.php'); ?>
<style>
/* ═══ Portal Tabs (prominent) ═══ */
.portal-tabs {
  display: flex; gap: 12px; margin-bottom: 34px;
  background: var(--off-white); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: 8px; box-shadow: var(--shadow-sm);
  overflow-x: auto; /* in case of many tabs on small screens */
}
.portal-tab {
  font-family: var(--font-head); font-size: .9rem; font-weight: 700;
  padding: 14px 28px; background: white; border: 2px solid transparent;
  border-radius: var(--r-md); color: var(--gray-dark);
  cursor: pointer; position: relative; transition: all .25s;
  flex: 1; text-align: center; white-space: nowrap;
  box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.portal-tab.active {
  background: var(--navy); border-color: var(--navy); color: white;
  box-shadow: 0 6px 14px rgba(10,22,40,.18);
  transform: scale(1.02);
}
.portal-tab:not(.active):hover {
  background: var(--teal-pale); border-color: var(--teal); color: var(--teal);
  transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,.06);
}
.portal-tab i { font-size: 1.1rem; margin-right: 8px; vertical-align: middle; }
/* Remove the underline after pseudo-element, use background change instead */
.portal-tab.active::after { display: none; }

/* ═══ Login Card ═══ */
.login-card {
  background: white; border: 1px solid var(--border);
  border-radius: var(--r-lg); box-shadow: var(--shadow-sm);
  padding: 30px 28px; max-width: 480px; margin: 0 auto;
}
.login-card .lc-icon {
  width: 64px; height: 64px; background: var(--teal-pale);
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  margin: 0 auto 18px; color: var(--teal); font-size: 1.8rem;
}
.login-card h3 {
  font-family: var(--font-head); font-weight: 800; color: var(--navy);
  text-align: center; margin-bottom: 6px;
}
.login-card p.sub {
  font-family: var(--font-body); font-size: .85rem; color: var(--gray-mid);
  text-align: center; margin-bottom: 24px;
}
.login-card .btn-login {
  width: 100%; padding: 12px; border-radius: var(--r-sm);
  font-family: var(--font-head); font-size: .9rem; font-weight: 700;
  background: var(--teal); color: white; border: none; cursor: pointer;
  transition: background .2s; text-align: center; text-decoration: none;
  display: inline-block;
}
.login-card .btn-login:hover { background: #008f80; }

/* Responsive */
@media(max-width:575px) {
  .portal-tab { padding: 12px 16px; font-size: .82rem; }
  .portal-tabs { flex-wrap: nowrap; }
}
</style>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Portal Login</h1>
    <div class="breadcrumb-pmc"><a href="index.php">Home</a><span class="sep"><i class="bi bi-chevron-right"></i></span><span class="current">Portal Login</span></div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content fu">
          <p style="font-family:var(--font-body); font-size:.95rem; color:var(--gray-mid); margin-bottom: 0;">
            Select your role to access the PMC Portal. All users (students, teachers, and parents) use the same central login system.
          </p>

          <!-- Portal Tabs (now larger, card-like) -->
          <div class="portal-tabs fu">
            <button class="portal-tab active" data-portal="student" onclick="switchPortal('student')">
              <i class="bi bi-mortarboard-fill"></i> Student
            </button>
            <button class="portal-tab" data-portal="teacher" onclick="switchPortal('teacher')">
              <i class="bi bi-person-workspace"></i> Teacher
            </button>
            <button class="portal-tab" data-portal="parent" onclick="switchPortal('parent')">
              <i class="bi bi-people-fill"></i> Parent
            </button>
          </div>

          <!-- Student Login Card -->
          <div class="portal-pane fu" id="pane-student">
            <div class="login-card">
              <div class="lc-icon"><i class="bi bi-person-circle"></i></div>
              <h3>Student Portal</h3>
              <p class="sub">Access your academic dashboard, fee challan, exam schedule, and results.</p>
              <a href="https://pmc.prime.edu.pk/portal_login.php" target="_blank" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Login to Student Portal
              </a>
              <p style="font-size:.76rem; color:var(--gray-mid); text-align:center; margin:16px 0 0;">
                Having trouble? Contact IT at <a href="mailto:info@prime.edu.pk" style="color:var(--teal);">info@prime.edu.pk</a>
              </p>
            </div>
          </div>

          <!-- Teacher Login Card -->
          <div class="portal-pane fu" id="pane-teacher" style="display:none;">
            <div class="login-card">
              <div class="lc-icon" style="background:var(--gold-pale); color:var(--gold);"><i class="bi bi-person-badge-fill"></i></div>
              <h3>Teacher Portal</h3>
              <p class="sub">Manage your courses, upload grades, access timetables, and communicate with students.</p>
              <a href="https://pmc.prime.edu.pk/portal_login.php" target="_blank" class="btn-login" style="background:var(--navy);">
                <i class="bi bi-box-arrow-in-right"></i> Login to Teacher Portal
              </a>
              <p style="font-size:.76rem; color:var(--gray-mid); text-align:center; margin:16px 0 0;">
                Need help? <a href="mailto:faculty.support@prime.edu.pk" style="color:var(--teal);">faculty.support@prime.edu.pk</a>
              </p>
            </div>
          </div>

          <!-- Parent Login Card -->
          <div class="portal-pane fu" id="pane-parent" style="display:none;">
            <div class="login-card">
              <div class="lc-icon" style="background:var(--teal-pale); color:var(--navy);"><i class="bi bi-shield-lock-fill"></i></div>
              <h3>Parent Portal</h3>
              <p class="sub">Monitor your child's attendance, academic progress, fee status, and announcements.</p>
              <a href="https://pmc.prime.edu.pk/portal_login.php" target="_blank" class="btn-login" style="background:var(--gold);">
                <i class="bi bi-box-arrow-in-right"></i> Login to Parent Portal
              </a>
              <p style="font-size:.76rem; color:var(--gray-mid); text-align:center; margin:16px 0 0;">
                For assistance: <a href="mailto:parents@prime.edu.pk" style="color:var(--teal);">parents@prime.edu.pk</a>
              </p>
            </div>
          </div>

        </div>
      </div>

      <!-- Sidebar (unchanged) -->
      <div class="col-lg-4 fu fu-delay-2">
        <div class="sidebar-widget">
          <div class="sw-head"><i class="bi bi-grid-fill"></i> Quick Navigation</div>
          <div class="sw-body">
            <a class="sw-link" href="about.php"><i class="bi bi-building"></i>Introduction</a>
            <a class="sw-link" href="vision-mission.php"><i class="bi bi-eye"></i>Vision &amp; Mission</a>
            <a class="sw-link" href="faculty.php"><i class="bi bi-people"></i>Faculty</a>
            <a class="sw-link" href="admissions.php"><i class="bi bi-mortarboard"></i>Admissions</a>
            <a class="sw-link" href="vacant-seats.php"><i class="bi bi-door-open"></i>Vacant Seats</a>
            <a class="sw-link" href="medical-education.php"><i class="bi bi-book"></i>Medical Education</a>
            <a class="sw-link" href="curriculum.php"><i class="bi bi-journal-text"></i>Curriculum</a>
            <a class="sw-link" href="examinations.php"><i class="bi bi-clipboard-pulse"></i>Examinations</a>
            <a class="sw-link" href="umr.php"><i class="bi bi-flask"></i>UMR Research</a>
            <a class="sw-link" href="contact.php"><i class="bi bi-envelope"></i>Contact Us</a>
          </div>
        </div>
        <div class="sidebar-widget">
          <div class="sw-head"><i class="bi bi-hospital-fill"></i> Teaching Hospitals</div>
          <div class="sw-body">
            <a class="sw-link" href="https://prime.edu.pk/pf/idx_kth.php" target="_blank"><i class="bi bi-hospital"></i>Kuwait Teaching Hospital</a>
            <a class="sw-link" href="https://prime.edu.pk/pf/idx_mth.php" target="_blank"><i class="bi bi-heart-pulse"></i>Mercy Teaching Hospital</a>
            <a class="sw-link" href="https://prime.edu.pk/pf/idx_pth.php" target="_blank"><i class="bi bi-capsule-pill"></i>Prime Teaching Hospital</a>
            <a class="sw-link" href="https://prime.edu.pk/pf/community-development.php" target="_blank"><i class="bi bi-people-fill"></i>Community Dev. Center</a>
          </div>
        </div>
        <div class="sidebar-widget">
          <div class="sw-head" style="background:var(--teal);"><i class="bi bi-info-circle-fill"></i> Need Help?</div>
          <div class="sw-body" style="padding:18px 20px;">
            <p style="font-family:var(--font-body);font-size:.84rem;color:var(--gray-mid);margin-bottom:14px;">
              For login issues or portal assistance, please contact the relevant department.</p>
            <a href="contact.php" class="btn-pmc btn-pmc-primary w-100 justify-content-center" style="font-size:.82rem;padding:11px;">Contact IT Support</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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
function switchPortal(portal) {
  document.querySelectorAll('.portal-tab').forEach(tab => {
    tab.classList.toggle('active', tab.dataset.portal === portal);
  });
  document.getElementById('pane-student').style.display = portal === 'student' ? '' : 'none';
  document.getElementById('pane-teacher').style.display = portal === 'teacher' ? '' : 'none';
  document.getElementById('pane-parent').style.display = portal === 'parent' ? '' : 'none';
}
</script>