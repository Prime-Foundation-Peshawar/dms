<?php include("includes/header.php"); ?>

<style>
/* ══ PROFILE HERO CARD ══ */
.profile-hero {
  background: linear-gradient(135deg, var(--navy) 0%, #0d3060 60%, #00695C 100%);
  border-radius: var(--r-lg); padding: 44px 48px;
  display: flex; gap: 36px; align-items: flex-start;
  margin-bottom: 36px; position: relative; overflow: hidden;
}
.profile-hero::before {
  content: ''; position: absolute; right: -80px; top: -80px;
  width: 400px; height: 400px; border-radius: 50%;
  border: 60px solid rgba(255,255,255,.03); pointer-events: none;
}
.profile-avatar-wrap { flex-shrink: 0; }
.profile-avatar {
  width: 130px; height: 130px; border-radius: 50%;
  border: 4px solid rgba(255,255,255,.2);
  box-shadow: 0 8px 32px rgba(0,0,0,.3);
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-head); font-size: 2.6rem; font-weight: 900; color: white;
  background: linear-gradient(135deg, var(--teal), var(--navy-mid));
  overflow: hidden; position: relative;
}
.profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
.profile-pmdc-badge {
  position: absolute; bottom: -4px; right: -4px;
  background: var(--teal); border: 3px solid white;
  border-radius: 50%; width: 32px; height: 32px;
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: .9rem;
}
.profile-body { flex: 1; min-width: 0; }
.profile-dept-link {
  display: inline-flex; align-items: center; gap: 6px;
  font-family: var(--font-head); font-size: .65rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: .12em; color: var(--teal-light);
  background: rgba(0,168,150,.15); border: 1px solid rgba(0,168,150,.3);
  padding: 4px 12px; border-radius: 100px; margin-bottom: 12px;
  text-decoration: none; transition: background .2s;
}
.profile-dept-link:hover { background: rgba(0,168,150,.25); }
.profile-name {
  font-family: var(--font-head); font-size: clamp(1.4rem, 3vw, 2rem);
  font-weight: 900; color: white; line-height: 1.15; margin-bottom: 6px;
}
.profile-desig {
  font-family: var(--font-body); font-size: .92rem; color: rgba(255,255,255,.6);
  font-weight: 600; margin-bottom: 18px;
}
.profile-qual {
  font-family: var(--font-body); font-size: .84rem; color: rgba(255,255,255,.5);
  font-weight: 500; line-height: 1.6; margin-bottom: 22px;
}
.profile-stats {
  display: flex; gap: 28px; flex-wrap: wrap;
}
.profile-stat { text-align: center; }
.ps-num { font-family: var(--font-head); font-size: 1.6rem; font-weight: 900; color: var(--gold-light); display: block; line-height: 1; }
.ps-lbl { font-family: var(--font-body); font-size: .7rem; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; display: block; }

.profile-contact {
  display: flex; gap: 10px; flex-wrap: wrap; margin-top: 22px;
}
.pc-btn {
  display: flex; align-items: center; gap: 7px;
  font-family: var(--font-head); font-size: .74rem; font-weight: 700;
  padding: 8px 16px; border-radius: var(--r-sm);
  background: rgba(255,255,255,.1); border: 1.5px solid rgba(255,255,255,.2);
  color: white; text-decoration: none; transition: all .2s;
}
.pc-btn:hover { background: rgba(0,168,150,.35); border-color: var(--teal-light); color: white; }

/* ══ TAB NAV ══ */
.profile-tabs {
  display: flex; gap: 0; border-bottom: 2px solid var(--border);
  margin-bottom: 32px; overflow-x: auto;
}
.profile-tab {
  font-family: var(--font-head); font-size: .8rem; font-weight: 700;
  padding: 12px 22px; color: var(--gray-mid); border: none; background: none;
  cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px;
  white-space: nowrap; transition: all .2s; display: flex; align-items: center; gap: 7px;
}
.profile-tab:hover  { color: var(--navy); }
.profile-tab.active { color: var(--teal); border-bottom-color: var(--teal); }
.profile-tab i { font-size: .9rem; }

/* ══ TAB CONTENT ══ */
.tab-pane { display: none; }
.tab-pane.active { display: block; animation: tabFade .25s ease; }
@keyframes tabFade { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }

/* ══ BIO SECTION ══ */
.bio-text {
  font-family: var(--font-body); font-size: .97rem;
  color: var(--gray-dark); line-height: 1.88; font-weight: 500;
}
.bio-text p { margin-bottom: 18px; }

/* Spec tags */
.spec-tag {
  font-family: var(--font-body); font-size: .78rem; font-weight: 700;
  background: var(--teal-pale); color: var(--teal);
  padding: 5px 14px; border-radius: 100px; display: inline-block;
  margin: 4px 4px 4px 0;
}

/* ══ PUBLICATIONS LIST ══ */
.pub-item-row {
  background: white; border: 1px solid var(--border); border-radius: var(--r-md);
  padding: 18px 20px; margin-bottom: 12px; display: flex; gap: 16px;
  align-items: flex-start; transition: border-color .2s, transform .2s;
}
.pub-item-row:hover { border-color: var(--teal); transform: translateX(3px); }
.pub-num {
  width: 30px; height: 30px; flex-shrink: 0;
  background: var(--teal); border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-head); font-size: .78rem; font-weight: 800; color: white;
}
.pub-item-title { font-family: var(--font-head); font-size: .88rem; font-weight: 700; color: var(--navy); line-height: 1.38; margin-bottom: 5px; }
.pub-item-meta  { font-family: var(--font-body); font-size: .76rem; color: var(--gray-mid); font-weight: 600; display: flex; gap: 14px; flex-wrap: wrap; }
.pub-item-meta i { color: var(--teal); margin-right: 3px; }

/* ══ ACHIEVEMENTS ══ */
.ach-item {
  display: flex; gap: 12px; align-items: flex-start;
  padding: 13px 16px; background: white; border: 1px solid var(--border);
  border-radius: var(--r-md); margin-bottom: 10px; transition: border-color .2s;
}
.ach-item:hover { border-color: var(--gold); }
.ach-icon { width: 34px; height: 34px; flex-shrink: 0; background: var(--gold-pale); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: .9rem; }
.ach-text { font-family: var(--font-body); font-size: .88rem; font-weight: 600; color: var(--navy); line-height: 1.45; }

/* ══ TEACHING LIST ══ */
.teach-item {
  display: flex; gap: 12px; align-items: center;
  padding: 12px 16px; background: white; border: 1px solid var(--border);
  border-radius: var(--r-md); margin-bottom: 10px; transition: border-color .2s;
}
.teach-item:hover { border-color: var(--teal); }
.teach-icon { width: 32px; height: 32px; flex-shrink: 0; background: var(--teal-pale); border-radius: 7px; display: flex; align-items: center; justify-content: center; color: var(--teal); font-size: .88rem; }
.teach-text { font-family: var(--font-body); font-size: .88rem; font-weight: 600; color: var(--navy); }

/* ══ DEPT FACULTY CARDS ══ */
.dept-fac-card {
  background: white; border: 1px solid var(--border); border-radius: var(--r-md);
  padding: 18px; text-align: center; height: 100%; display: flex; flex-direction: column;
  align-items: center; transition: transform .25s, box-shadow .25s, border-color .25s;
}
.dept-fac-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: var(--teal); }
.dfc-avatar {
  width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 12px;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-head); font-size: 1.2rem; font-weight: 800; color: white;
  border: 3px solid white; box-shadow: 0 3px 12px rgba(0,0,0,.12);
}
.dfc-name  { font-family: var(--font-head); font-size: .84rem; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
.dfc-desig { font-family: var(--font-body); font-size: .72rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 8px; }
.dfc-pmdc  { font-family: var(--font-body); font-size: .7rem; font-weight: 600; color: var(--gray-mid); display: flex; align-items: center; justify-content: center; gap: 4px; }
.dfc-pmdc i { color: var(--teal); }

@media(max-width:767.98px) {
  .profile-hero { flex-direction: column; padding: 28px 22px; }
  .profile-stats { gap: 18px; }
  .profile-avatar { width: 100px; height: 100px; font-size: 2rem; }
}
</style>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Faculty Profile</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="faculty.php">Faculty</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Prof. Dr. Ahmad Zubair Khan</span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">

    <!-- ── PROFILE HERO CARD ──────────────────────────────────── -->
    <div class="profile-hero fu">
      <div class="profile-avatar-wrap">
        <div class="profile-avatar">
          <!-- No image, so initials -->
          AK
          <div class="profile-pmdc-badge" title="PM&DC Registered">
            <i class="bi bi-patch-check-fill"></i>
          </div>
        </div>
      </div>

      <div class="profile-body">
        <a href="departments.php" class="profile-dept-link">
          <i class="bi bi-building"></i> Department of Anatomy
        </a>
        <h1 class="profile-name">Prof. Dr. Ahmad Zubair Khan</h1>
        <div class="profile-desig">Professor & Head of Department</div>
        <div class="profile-qual">MBBS (KMU), M.Phil Anatomy (CPSP), PhD (University of Leeds, UK)</div>

        <div class="profile-stats">
          <div class="profile-stat">
            <span class="ps-num">22+</span>
            <span class="ps-lbl">Publications</span>
          </div>
          <div class="profile-stat">
            <span class="ps-num">18+</span>
            <span class="ps-lbl">Years Exp.</span>
          </div>
          <div class="profile-stat">
            <span class="ps-num"><i class="bi bi-patch-check-fill" style="font-size:1.2rem;color:var(--teal-light);"></i></span>
            <span class="ps-lbl">PM&DC Reg.</span>
          </div>
        </div>

        <div class="profile-contact">
          <a href="mailto:anatomy@prime.edu.pk" class="pc-btn">
            <i class="bi bi-envelope-fill"></i> anatomy@prime.edu.pk
          </a>
          <a href="faculty-all.php" class="pc-btn">
            <i class="bi bi-people-fill"></i> All Faculty
          </a>
        </div>
      </div>
    </div><!-- /.profile-hero -->

    <div class="row g-5">

      <!-- ── LEFT: Profile Content ──────────────────────────── -->
      <div class="col-lg-8">

        <!-- Tab Navigation -->
        <div class="profile-tabs fu">
          <button class="profile-tab active" data-tab="bio" onclick="switchTab('bio')">    <i class="bi bi-person"></i> Biography</button>
          <button class="profile-tab"         data-tab="pubs" onclick="switchTab('pubs')">   <i class="bi bi-journal-richtext"></i> Publications <span class="pmc-tag" style="font-size:.65rem;">5</span></button>
          <button class="profile-tab"         data-tab="teach" onclick="switchTab('teach')">  <i class="bi bi-mortarboard"></i> Teaching</button>
          <button class="profile-tab"         data-tab="ach" onclick="switchTab('ach')">    <i class="bi bi-award"></i> Achievements</button>
        </div>

        <!-- Tab: Biography -->
        <div class="tab-pane active fu" id="tab-bio">
          <div class="bio-text">
            <p>Prof. Dr. Ahmad Zubair Khan joined PMC in 2008 and has served as Head of the Anatomy Department since 2015. With a distinguished academic career spanning 18+ years, he has mentored hundreds of MBBS students, supervised numerous M.Phil and PhD dissertations, and authored over 22 peer-reviewed research publications in national and international journals.</p>
            <p>He received his MBBS from Khyber Medical University (KMU) and completed his M.Phil in Anatomy through the College of Physicians & Surgeons Pakistan (CPSP) training programme. His PhD from the University of Leeds (UK) focused on neuroanatomical correlates of neurodegenerative conditions — a research interest he continues to pursue actively at PMC.</p>
            <p>Prof. Khan is a strong advocate for simulation-based anatomy teaching and was instrumental in the establishment of PMC's Clinical Skills Laboratory. He currently serves on PMC's Academic Committee and Curriculum Review Board, contributing to the ongoing enhancement of the MBBS curriculum in line with PM&DC guidelines and international best practices.</p>
          </div>

          <h5 style="font-family:var(--font-head);font-size:.88rem;font-weight:800;color:var(--navy);text-transform:uppercase;letter-spacing:.08em;margin:28px 0 12px;">Areas of Specialisation</h5>
          <div>
            <span class="spec-tag">Neuroanatomy</span>
            <span class="spec-tag">Embryology</span>
            <span class="spec-tag">Clinical Anatomy</span>
          </div>

          <div class="pmc-card mt-4" style="border-radius:12px;background:var(--teal-pale);border-color:rgba(0,168,150,.2);">
            <div style="display:flex;gap:14px;align-items:flex-start;">
              <div style="font-size:1.8rem;color:var(--teal);flex-shrink:0;"><i class="bi bi-patch-check-fill"></i></div>
              <div>
                <div style="font-family:var(--font-head);font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--teal);margin-bottom:5px;">PM&DC Registration</div>
                <div style="font-family:var(--font-body);font-size:.9rem;font-weight:600;color:var(--navy);">PM&DC/12345-XXXXX</div>
                <div style="font-family:var(--font-body);font-size:.8rem;color:var(--gray-mid);margin-top:3px;">Registered Medical Practitioner — Pakistan Medical &amp; Dental Council</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Publications -->
        <div class="tab-pane fu" id="tab-pubs">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 style="font-family:var(--font-head);font-size:1rem;font-weight:800;color:var(--navy);margin:0;">
              Research Publications <span class="pmc-tag">5</span>
            </h3>
            <span style="font-family:var(--font-body);font-size:.8rem;color:var(--gray-mid);">Showing 5 of 22+ total</span>
          </div>
          <!-- Pub 1 -->
          <div class="pub-item-row">
            <div class="pub-num">1</div>
            <div style="flex:1;">
              <div class="pub-item-title">Morphometric Analysis of the Foramen Magnum in Dry Skulls from KP Pakistan</div>
              <div class="pub-item-meta">
                <span><i class="bi bi-journal-richtext"></i>Pakistan Journal of Medical Sciences</span>
                <span><i class="bi bi-calendar3"></i>2024</span>
                <a href="#" target="_blank" style="color:var(--teal);font-weight:700;text-decoration:none;">
                  <i class="bi bi-arrow-up-right-square"></i> View
                </a>
              </div>
            </div>
          </div>
          <!-- Pub 2 -->
          <div class="pub-item-row">
            <div class="pub-num">2</div>
            <div style="flex:1;">
              <div class="pub-item-title">Anatomical Variations of the Circle of Willis: A Cadaveric Study</div>
              <div class="pub-item-meta">
                <span><i class="bi bi-journal-richtext"></i>Journal of Ayub Medical College</span>
                <span><i class="bi bi-calendar3"></i>2023</span>
                <a href="#" target="_blank" style="color:var(--teal);font-weight:700;text-decoration:none;">
                  <i class="bi bi-arrow-up-right-square"></i> View
                </a>
              </div>
            </div>
          </div>
          <!-- Pub 3 -->
          <div class="pub-item-row">
            <div class="pub-num">3</div>
            <div style="flex:1;">
              <div class="pub-item-title">Effectiveness of Near-Peer Teaching in Gross Anatomy Laboratory Sessions</div>
              <div class="pub-item-meta">
                <span><i class="bi bi-journal-richtext"></i>Journal of CPSP</span>
                <span><i class="bi bi-calendar3"></i>2023</span>
                <a href="#" target="_blank" style="color:var(--teal);font-weight:700;text-decoration:none;">
                  <i class="bi bi-arrow-up-right-square"></i> View
                </a>
              </div>
            </div>
          </div>
          <!-- Pub 4 -->
          <div class="pub-item-row">
            <div class="pub-num">4</div>
            <div style="flex:1;">
              <div class="pub-item-title">Surface Anatomy Landmarks for Safe Internal Jugular Vein Cannulation</div>
              <div class="pub-item-meta">
                <span><i class="bi bi-journal-richtext"></i>Pakistan Journal of Medical Sciences</span>
                <span><i class="bi bi-calendar3"></i>2022</span>
                <a href="#" target="_blank" style="color:var(--teal);font-weight:700;text-decoration:none;">
                  <i class="bi bi-arrow-up-right-square"></i> View
                </a>
              </div>
            </div>
          </div>
          <!-- Pub 5 -->
          <div class="pub-item-row">
            <div class="pub-num">5</div>
            <div style="flex:1;">
              <div class="pub-item-title">Integrated Basic Sciences Teaching: A PMC Pilot Study</div>
              <div class="pub-item-meta">
                <span><i class="bi bi-journal-richtext"></i>South East Asian Journal of Medical Education</span>
                <span><i class="bi bi-calendar3"></i>2022</span>
                <a href="#" target="_blank" style="color:var(--teal);font-weight:700;text-decoration:none;">
                  <i class="bi bi-arrow-up-right-square"></i> View
                </a>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <a href="faculty-research.php" class="btn-pmc btn-pmc-outline" style="font-size:.82rem;padding:10px 20px;">
              <i class="bi bi-journal-bookmark"></i> View All Faculty Research
            </a>
          </div>
        </div>

        <!-- Tab: Teaching -->
        <div class="tab-pane fu" id="tab-teach">
          <h3 style="font-family:var(--font-head);font-size:1rem;font-weight:800;color:var(--navy);margin-bottom:20px;">Subjects Taught</h3>
          <div class="teach-item"><div class="teach-icon"><i class="bi bi-book"></i></div><span class="teach-text">Gross Anatomy — 1st Year MBBS</span></div>
          <div class="teach-item"><div class="teach-icon"><i class="bi bi-book"></i></div><span class="teach-text">Embryology & Histology — 1st Year MBBS</span></div>
          <div class="teach-item"><div class="teach-icon"><i class="bi bi-book"></i></div><span class="teach-text">Neuroanatomy — 2nd Year MBBS</span></div>
          <div class="teach-item"><div class="teach-icon"><i class="bi bi-book"></i></div><span class="teach-text">Regional Applied Anatomy — 4th & 5th Year MBBS</span></div>
          <div class="teach-item"><div class="teach-icon"><i class="bi bi-book"></i></div><span class="teach-text">Research Methodology — UMR Society</span></div>
        </div>

        <!-- Tab: Achievements -->
        <div class="tab-pane fu" id="tab-ach">
          <h3 style="font-family:var(--font-head);font-size:1rem;font-weight:800;color:var(--navy);margin-bottom:20px;">Awards &amp; Achievements</h3>
          <div class="ach-item"><div class="ach-icon"><i class="bi bi-award-fill"></i></div><span class="ach-text">Best Teacher Award — PMC Annual Convocation 2023</span></div>
          <div class="ach-item"><div class="ach-icon"><i class="bi bi-award-fill"></i></div><span class="ach-text">PM&DC Recognised Supervisor for M.Phil Anatomy Programs</span></div>
          <div class="ach-item"><div class="ach-icon"><i class="bi bi-award-fill"></i></div><span class="ach-text">PhD Fellowship — University of Leeds, UK (2010–2013)</span></div>
          <div class="ach-item"><div class="ach-icon"><i class="bi bi-award-fill"></i></div><span class="ach-text">Published 22+ papers in national & international peer-reviewed journals</span></div>
          <div class="ach-item"><div class="ach-icon"><i class="bi bi-award-fill"></i></div><span class="ach-text">Served on PM&DC Curriculum Committee for Anatomy (2020–2022)</span></div>
        </div>

      </div><!-- /.col-lg-8 -->

      <!-- ── SIDEBAR ────────────────────────────────────────── -->
      <div class="col-lg-4">
        <div style="position:sticky;top:90px;">

          <!-- Quick Info -->
          <div class="sidebar-widget fu">
            <div class="sw-head"><i class="bi bi-person-badge-fill"></i> Faculty Details</div>
            <div style="padding:0;">
              <div class="info-row" style="display:flex;align-items:flex-start;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                <div style="width:32px;height:32px;flex-shrink:0;background:var(--teal-pale);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.88rem;margin-top:2px;"><i class="bi bi-building"></i></div>
                <div>
                  <div style="font-family:var(--font-head);font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-mid);">Department</div>
                  <div style="font-family:var(--font-body);font-size:.84rem;font-weight:600;color:var(--navy);margin-top:2px;">Department of Anatomy</div>
                </div>
              </div>
              <div class="info-row" style="display:flex;align-items:flex-start;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                <div style="width:32px;height:32px;flex-shrink:0;background:var(--teal-pale);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.88rem;margin-top:2px;"><i class="bi bi-briefcase"></i></div>
                <div>
                  <div style="font-family:var(--font-head);font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-mid);">Designation</div>
                  <div style="font-family:var(--font-body);font-size:.84rem;font-weight:600;color:var(--navy);margin-top:2px;">Professor & Head of Department</div>
                </div>
              </div>
              <div class="info-row" style="display:flex;align-items:flex-start;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                <div style="width:32px;height:32px;flex-shrink:0;background:var(--teal-pale);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.88rem;margin-top:2px;"><i class="bi bi-patch-check-fill"></i></div>
                <div>
                  <div style="font-family:var(--font-head);font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-mid);">PM&DC No.</div>
                  <div style="font-family:var(--font-body);font-size:.84rem;font-weight:600;color:var(--navy);margin-top:2px;">PM&DC/12345-XXXXX</div>
                </div>
              </div>
              <div class="info-row" style="display:flex;align-items:flex-start;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                <div style="width:32px;height:32px;flex-shrink:0;background:var(--teal-pale);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.88rem;margin-top:2px;"><i class="bi bi-journal-richtext"></i></div>
                <div>
                  <div style="font-family:var(--font-head);font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-mid);">Publications</div>
                  <div style="font-family:var(--font-body);font-size:.84rem;font-weight:600;color:var(--navy);margin-top:2px;">22+ peer-reviewed papers</div>
                </div>
              </div>
              <div class="info-row" style="display:flex;align-items:flex-start;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                <div style="width:32px;height:32px;flex-shrink:0;background:var(--teal-pale);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.88rem;margin-top:2px;"><i class="bi bi-calendar3"></i></div>
                <div>
                  <div style="font-family:var(--font-head);font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-mid);">Experience</div>
                  <div style="font-family:var(--font-body);font-size:.84rem;font-weight:600;color:var(--navy);margin-top:2px;">18+ years</div>
                </div>
              </div>
              <div class="info-row" style="display:flex;align-items:flex-start;gap:12px;padding:12px 18px;">
                <div style="width:32px;height:32px;flex-shrink:0;background:var(--teal-pale);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.88rem;margin-top:2px;"><i class="bi bi-envelope-fill"></i></div>
                <div>
                  <div style="font-family:var(--font-head);font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-mid);">Email</div>
                  <a href="mailto:anatomy@prime.edu.pk" style="font-family:var(--font-body);font-size:.84rem;font-weight:600;color:var(--teal);text-decoration:none;display:block;margin-top:2px;">anatomy@prime.edu.pk</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Other faculty from same dept -->
          <div class="sidebar-widget fu mt-3">
            <div class="sw-head"><i class="bi bi-people-fill"></i> Department of Anatomy</div>
            <div class="sw-body" style="padding:16px;">
              <div class="row g-2">
                <div class="col-6">
                  <div class="dept-fac-card">
                    <div class="dfc-avatar" style="background:linear-gradient(135deg,#1565C0,#1976D2);">NR</div>
                    <div class="dfc-name">Assoc. Prof. Dr. Nadia Rashid</div>
                    <div class="dfc-desig">Associate Professor</div>
                    <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/23456</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="dept-fac-card">
                    <div class="dfc-avatar" style="background:linear-gradient(135deg,#00695C,#00897B);">BT</div>
                    <div class="dfc-name">Asst. Prof. Dr. Bilal Tariq</div>
                    <div class="dfc-desig">Assistant Professor</div>
                    <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/34567</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="dept-fac-card">
                    <div class="dfc-avatar" style="background:linear-gradient(135deg,#6A1B9A,#7B1FA2);">SG</div>
                    <div class="dfc-name">Dr. Sana Gul</div>
                    <div class="dfc-desig">Senior Lecturer</div>
                    <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/45678</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="dept-fac-card">
                    <div class="dfc-avatar" style="background:linear-gradient(135deg,#E65100,#F57C00);">HY</div>
                    <div class="dfc-name">Dr. Hamza Yousuf</div>
                    <div class="dfc-desig">Lecturer</div>
                    <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/56789</div>
                  </div>
                </div>
              </div>
              <a href="faculty-all.php" class="btn-pmc btn-pmc-outline w-100 justify-content-center mt-3" style="font-size:.8rem;padding:9px;">
                <i class="bi bi-people"></i> View All Faculty
              </a>
            </div>
          </div>

          <!-- Quick links -->
          <div class="sidebar-widget fu mt-3">
            <div class="sw-head"><i class="bi bi-link-45deg"></i> Quick Links</div>
            <div class="sw-body">
              <a class="sw-link" href="admissions.php"><i class="bi bi-mortarboard" style="color:var(--teal);"></i>Apply for MBBS</a>
              <a class="sw-link" href="faculty-all.php"><i class="bi bi-people" style="color:var(--teal);"></i>Faculty Directory</a>
              <a class="sw-link" href="departments.php"><i class="bi bi-building" style="color:var(--teal);"></i>Departments</a>
              <a class="sw-link" href="education-literature.php"><i class="bi bi-journal-bookmark" style="color:var(--teal);"></i>Education Literature</a>
              <a class="sw-link" href="contact.php"><i class="bi bi-envelope" style="color:var(--teal);"></i>Contact PMC</a>
            </div>
          </div>

        </div>
      </div><!-- /.sidebar -->

    </div><!-- /.row -->

    <!-- ── OTHER FACULTY (wider) ─────────────────────────────────── -->
    <div class="mt-5 pt-4 fu" style="border-top:2px solid var(--border);">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 style="font-family:var(--font-head);font-size:1.1rem;font-weight:800;color:var(--navy);margin:0;">
          <i class="bi bi-people-fill me-2" style="color:var(--teal);"></i>
          More Faculty from Department of Anatomy
        </h3>
        <a href="faculty-all.php" class="btn-pmc btn-pmc-outline" style="font-size:.78rem;padding:8px 16px;">
          View All <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <div class="dept-fac-card" style="padding:22px 16px;">
            <div class="dfc-avatar" style="background:linear-gradient(135deg,#1565C0,#1976D2);width:72px;height:72px;font-size:1.4rem;">NR</div>
            <div class="dfc-name" style="font-size:.88rem;">Assoc. Prof. Dr. Nadia Rashid</div>
            <div class="dfc-desig">Associate Professor</div>
            <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/23456</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="dept-fac-card" style="padding:22px 16px;">
            <div class="dfc-avatar" style="background:linear-gradient(135deg,#00695C,#00897B);width:72px;height:72px;font-size:1.4rem;">BT</div>
            <div class="dfc-name" style="font-size:.88rem;">Asst. Prof. Dr. Bilal Tariq</div>
            <div class="dfc-desig">Assistant Professor</div>
            <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/34567</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="dept-fac-card" style="padding:22px 16px;">
            <div class="dfc-avatar" style="background:linear-gradient(135deg,#6A1B9A,#7B1FA2);width:72px;height:72px;font-size:1.4rem;">SG</div>
            <div class="dfc-name" style="font-size:.88rem;">Dr. Sana Gul</div>
            <div class="dfc-desig">Senior Lecturer</div>
            <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/45678</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="dept-fac-card" style="padding:22px 16px;">
            <div class="dfc-avatar" style="background:linear-gradient(135deg,#E65100,#F57C00);width:72px;height:72px;font-size:1.4rem;">HY</div>
            <div class="dfc-name" style="font-size:.88rem;">Dr. Hamza Yousuf</div>
            <div class="dfc-desig">Lecturer</div>
            <div class="dfc-pmdc"><i class="bi bi-patch-check-fill"></i>PM&DC/56789</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Recognition strip (static) -->
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

<?php include("includes/footer.php"); ?>

<script>
/* Tab switching */
function switchTab(name) {
  document.querySelectorAll('.profile-tab').forEach(t => {
    t.classList.toggle('active', t.dataset.tab === name);
  });
  document.querySelectorAll('.tab-pane').forEach(p => {
    p.classList.toggle('active', p.id === 'tab-' + name);
  });
}

/* Fade-up */
const obs = new IntersectionObserver(e => e.forEach(x => { if(x.isIntersecting){x.target.classList.add('vis');obs.unobserve(x.target);} }), {threshold:.08});
document.querySelectorAll('.fu').forEach(el => obs.observe(el));

/* Navbar + back-to-top */
const nav = document.getElementById('mainNav');
const btt = document.getElementById('backToTop');
window.addEventListener('scroll', () => {
  nav.classList.toggle('scrolled', scrollY > 40);
  btt.classList.toggle('visible', scrollY > 500);
}, { passive: true });
btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>