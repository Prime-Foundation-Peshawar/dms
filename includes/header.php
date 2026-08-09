<?php
if (!defined('base_url')) {
  $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
  if ($host === 'staging.riphahpsh.edu.pk' || $host === 'www.staging.riphahpsh.edu.pk') {
    define('base_url', 'https://staging.riphahpsh.edu.pk/dms/');
  } else {
    define('base_url', 'https://dms.riphahpsh.edu.pk/');
  }
}
if (!function_exists('dms_asset')) {
  /** Append filemtime query string so browsers pick up updated local assets. */
  function dms_asset(string $path): string {
    $full = dirname(__DIR__) . '/' . ltrim($path, '/');
    $v = is_file($full) ? (string) filemtime($full) : (string) time();
    return htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '?v=' . rawurlencode($v);
  }
}
$page_title = $page_title ?? 'Department of Medical Sciences | Peshawar Medical College — Riphah Peshawar Campus';
$page_description = $page_description ?? 'Department of Medical Sciences, Riphah International University – Peshawar Campus. Peshawar Medical College — PM&DC recognized MBBS and postgraduate programmes. Warsak Road, Peshawar.';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_description) ?>" />

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/images/logo/favicon-logo.jpg" sizes="32x32">
  <link rel="apple-touch-icon" href="assets/images/logo/favicon-logo.jpg">
  
  <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
  <meta property="og:image" content="<?= htmlspecialchars(base_url) ?>assets/images/logo/favicon-logo.jpg">
  <meta property="og:url" content="<?= htmlspecialchars(base_url) ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- PMC Global CSS -->
  <link href="<?= dms_asset('assets/css/pmc-global.css') ?>" rel="stylesheet" />
  <!-- PMC CSS -->
  <link href="<?= dms_asset('assets/css/style.css') ?>" rel="stylesheet" />
  <?php if (!empty($preload_images) && is_array($preload_images)): ?>
    <?php foreach ($preload_images as $preload_href): ?>
  <link rel="preload" as="image" href="<?= htmlspecialchars($preload_href) ?>" type="image/webp" fetchpriority="high" />
    <?php endforeach; ?>
  <?php endif; ?>

</head>

<body>

  <!-- ═══ TOP BAR ═══ -->
  <div class="pmc-topbar d-none d-md-block">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-1">
          <i class="bi bi-geo-alt-fill me-1"></i> Warsak Road, Peshawar, KP, Pakistan
          <span class="sep">|</span>
          <i class="bi bi-telephone-fill me-1"></i>
          <a href="tel:+929152021914">+92-91-5202191–94</a>
          <span class="sep">|</span>
          <i class="bi bi-envelope-fill me-1"></i>
          <a href="mailto:info@riphahpsh.edu.pk">info@riphahpsh.edu.pk</a>
        </div>
        <div class="d-flex align-items-center gap-3">
          <a href="http://careers.prime.edu.pk" target="_blank"><i class="bi bi-briefcase me-1"></i>Career Portal</a>
          <a href="https://ses.prime.edu.pk" target="_blank"><i class="bi bi-laptop me-1"></i>LMS</a>
          <a href="vacant-seats.php" class="tb-cta"><i class="bi bi-door-open me-1"></i>Vacant Seats</a>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ NAVBAR ═══ -->
  <nav class="pmc-nav navbar navbar-expand-lg" id="mainNav">
    <div class="container">
      <a class="pmc-brand" href="index.php">
        <div class="">
          <img src="assets/images/logo/riphah-psh.png"
            alt="Department of Medical Sciences (DMS) — Riphah Peshawar Campus" width="200px;" />
        </div>
        <div class="pmc-brand-text d-none d-md-block" style="margin-left:10px;line-height:1.25;">
          <div style="font-size:.88rem;font-weight:700;color:var(--navy);">Department of Medical Sciences</div>
          <div style="font-size:.72rem;color:var(--teal);">Peshawar Medical College · Riphah Peshawar Campus</div>
        </div>
      </a>

      <button class="pmc-toggler navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <i class="bi bi-list" style="font-size:1.4rem;color:var(--navy)"></i>
      </button>

      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto align-items-lg-center position-static">

          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

          <!-- ABOUT mega -->
          <li class="nav-item mega-menu-wrapper position-static">
            <a class="nav-link dropdown-toggle" href="about.php">About</a>
            <div class="mega-menu">
              <div class="container">
                <div class="row g-4">
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-info-circle-fill"></i> About Us</div>
                    <a class="mega-link" href="about.php"><i class="bi bi-building"></i>About DMS</a>
                    <a class="mega-link" href="vision-mission.php"><i class="bi bi-eye"></i>Vision &amp; Mission</a>
                    <a class="mega-link" href="faculty.php"><i class="bi bi-people"></i>Faculty</a>
                  </div>
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-hospital-fill"></i> College &amp; Hospitals</div>
                    <a class="mega-link" href="pmc.php"><i class="bi bi-award"></i>Peshawar Medical College</a>
                    <a class="mega-link" href="https://kth.prime.edu.pk/" target="_blank"><i
                        class="bi bi-hospital"></i>Kuwait Teaching Hospital</a>
                    <a class="mega-link" href="https://mth.prime.edu.pk/" target="_blank"><i
                        class="bi bi-heart-pulse"></i>Mercy Teaching Hospital</a>
                    <a class="mega-link" href="https://pth.prime.edu.pk/" target="_blank"><i
                        class="bi bi-building"></i>Prime Teaching Hospital</a>
                  </div>
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-diagram-3"></i> Other Relevant Departments</div>
                    <a class="mega-link" href="https://riphahpsh.edu.pk/" target="_blank"><i class="bi bi-building"></i>Main Campus</a>
                    <a class="mega-link" href="https://dds.riphahpsh.edu.pk/" target="_blank"><i class="bi bi-clipboard2-pulse"></i>Department of Dental Sciences</a>
                    <a class="mega-link" href="https://riphahpsh.edu.pk/islamic-studies.php" target="_blank"><i class="bi bi-book"></i>Department of Islamic Studies &amp; Comparative Religion</a>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <li class="nav-item"><a class="nav-link" href="departments.php">Departments</a></li>

          <li class="nav-item mega-menu-wrapper">
            <a class="nav-link dropdown-toggle" href="#programs">Programs</a>
            <div class="dropdown-menu plain-dd" style="min-width:260px;">
              <a class="dropdown-item" href="pmc.php"><i class="bi bi-mortarboard"></i>Undergraduate Medical Education (PMC)</a>
              <a class="dropdown-item" href="pg-medical-education.php"><i class="bi bi-journal-medical"></i>Postgraduate Medical Education</a>
              <a class="dropdown-item" href="medical-education.php"><i class="bi bi-book"></i>Medical Education Overview</a>
            </div>
          </li>

          <!-- ADMISSIONS mega -->
          <li class="nav-item mega-menu-wrapper position-static">
            <a class="nav-link dropdown-toggle" href="admissions.php">Admissions</a>
            <div class="mega-menu">
              <div class="container">
                <div class="row g-4">
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-mortarboard-fill"></i> Apply</div>
                    <a class="mega-link" href="admissions.php"><i class="bi bi-pencil-square"></i>Admissions 2025–26</a>
                    <a class="mega-link" href="vacant-seats.php"><i class="bi bi-door-open"></i>Vacant Seats</a>
                    <a class="mega-link" href="https://pmc.prime.edu.pk/downloads/Medical Final Prospectus 2025-26.pdf"
                      target="_blank"><i class="bi bi-file-pdf"></i>Prospectus 2025–26</a>
                    <a class="mega-link" href="https://pmc.prime.edu.pk/downloads/PMC MBBS Prospectus 2024-25.pdf"
                      target="_blank"><i class="bi bi-file-pdf"></i>Prospectus 2024–25</a>
                  </div>
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-cash-stack"></i> Fees & Scholarships</div>
                    <a class="mega-link"
                      href="https://pmc.prime.edu.pk/downloads/MBBS_Fee_Session 2025-26_24-12-2025.htm"
                      target="_blank"><i class="bi bi-receipt"></i>Fee Structure 2025–26</a>
                    <a class="mega-link"
                      href="https://pmc.prime.edu.pk/downloads/MBBS_Fee_Session 2024-25-Final dated 23.07.2024.htm"
                      target="_blank"><i class="bi bi-receipt"></i>Fee Structure 2024–25</a>
                    <a class="mega-link" href="https://pmc.prime.edu.pk/downloads/Scholarship Policy.pdf"
                      target="_blank"><i class="bi bi-award"></i>Scholarship Policy</a>
                    <a class="mega-link" href="https://pmc.prime.edu.pk/downloads/Scholarship_Application_Form.pdf"
                      target="_blank"><i class="bi bi-file-earmark-text"></i>Scholarship Application</a>
                  </div>
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-info-circle"></i> Key Information</div>
                    <a class="mega-link" href="admissions.php#eligibility"><i class="bi bi-check-circle"></i>Eligibility
                      Criteria</a>
                    <a class="mega-link" href="admissions.php#process"><i class="bi bi-list-ol"></i>Admission
                      Process</a>
                    <a class="mega-link" href="https://pmc.prime.edu.pk/portal_login.php"><i class="bi bi-person-circle"></i>Student Portal</a>
                  </div>
                  <!-- <div class="col-lg-3">
                    <div class="mega-feature">
                      <div class="mega-feature-icon"><i class="bi bi-door-open-fill"></i></div>
                      <h5>Seats Availability</h5>
                      <p>Limited seats remaining for MBBS Session 2026–27. Apply before seats fill up — open to
                        students.</p>
                      <a class="mf-link" href="vacant-seats.php">Check Seats <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
          </li>

          <!-- EDUCATION & RESEARCH mega -->
          <li class="nav-item mega-menu-wrapper position-static">
            <a class="nav-link dropdown-toggle" href="medical-education.php">Education & Research</a>
            <div class="mega-menu">
              <div class="container">
                <div class="row g-4">
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-book-fill"></i> Education</div>
                    <a class="mega-link" href="medical-education.php"><i class="bi bi-journal-medical"></i>Medical
                      Education</a>
                    <a class="mega-link" href="pg-medical-education.php"><i
                        class="bi bi-journal-medical"></i>Postgraduate Medical Education</a>
                    <a class="mega-link" href="curriculum.php"><i class="bi bi-journal-text"></i>Curriculum</a>
                    <a class="mega-link" href="examinations.php"><i class="bi bi-clipboard-pulse"></i>Examinations &
                      Assessments</a>
                    <!-- <a class="mega-link" href="clinical-skill-labs.php"><i class="bi bi-activity"></i>Clinical Skill
                      Labs</a> -->
                  </div>
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-flask"></i> Research</div>
                    <a class="mega-link" href="https://umr.prime.edu.pk/" target="_blank"><i
                        class="bi bi-search"></i>Undergraduate Medical Research (UMR)</a>
                    <a class="mega-link" href="https://sws.prime.edu.pk/"><i class="bi bi-file-earmark-text"></i>Students
                      Research</a>
                    <a class="mega-link" href="faculty-research.php"><i class="bi bi-people"></i>Faculty Research</a>
                    <a class="mega-link" href="https://oric.prime.edu.pk/" target="_blank"><i
                        class="bi bi-lightbulb"></i>ORIC</a>
                    <a class="mega-link" href="https://riphahpsh.edu.pk/pubedu"><i
                        class="bi bi-journal-bookmark"></i>Educational Literature</a>
                  </div>
                  <div class="col-lg-4">
                    <div class="mega-col-head"><i class="bi bi-calendar-event"></i> Resources</div>
                    <a class="mega-link" href="student-guide.php"><i class="bi bi-book"></i>Student Guide</a>
                    <!-- <a class="mega-link" href="#"><i class="bi bi-calendar3"></i>Academic Calendar</a> -->
                  </div>
                  <!-- <div class="col-lg-3">
                    <div class="mega-feature">
                      <div class="mega-feature-icon"><i class="bi bi-flask-fill"></i></div>
                      <h5>Active Research Culture</h5>
                      <p>Department of Medical Sciences - Riphah International University (Peshawar Campus) students
                        publish research papers and present at national & international conferences —
                        supported by the college.</p>
                      <a class="mf-link" href="https://umr.prime.edu.pk/" target="_blank">Explore UMR <i
                          class="bi bi-arrow-right"></i></a>
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
          </li>

          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>