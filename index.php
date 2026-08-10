<?php
$preload_images = ['assets/images/slider/hero-slide-1.webp'];
include('includes/header.php');
?>

<!-- ═══ HERO SLIDER ═══ -->
<div id="heroSlider" aria-roledescription="carousel" aria-label="Department of Medical Sciences highlights">

  <!-- SLIDE 1 — MBBS / Peshawar Medical College -->
  <div class="hero-slide active" role="group" aria-roledescription="slide" aria-label="1 of 2">
    <div class="slide-media slide-bg-3"></div>
    <div class="slide-overlay"></div>
    <div class="container slide-inner">
      <div class="slide-content">
        <p class="slide-brand">Department of Medical Sciences</p>
        <h1 class="slide-title">Your <span class="hl-teal">MBBS</span> Journey Starts Here</h1>
        <p class="slide-body">Peshawar Medical College offers a PM&amp;DC-recognized five-year MBBS — rigorous basic sciences, early clinical exposure, and mentors who teach medicine with integrity.</p>
        <p class="slide-body slide-body-sub">Study at Riphah International University – Peshawar Campus and build the competence to serve communities across KP and beyond.</p>
        <div class="slide-actions">
          <a href="admissions.php" class="btn-pmc btn-pmc-primary"><i class="bi bi-mortarboard"></i> Admissions 2025–26</a>
          <a href="pmc.php" class="btn-pmc btn-pmc-outline-white">About PMC</a>
        </div>
      </div>
    </div>
  </div>

  <!-- SLIDE 2 — Clinical training & departments -->
  <div class="hero-slide" role="group" aria-roledescription="slide" aria-label="2 of 2" data-lazy-bg>
    <div class="slide-media slide-bg-4"></div>
    <div class="slide-overlay"></div>
    <div class="container slide-inner">
      <div class="row align-items-center g-4 g-xl-5">
        <div class="col-lg-6">
          <div class="slide-content">
            <p class="slide-brand">Clinical Training</p>
            <h1 class="slide-title">Learn Where <span class="hl">Care</span> Happens</h1>
            <p class="slide-body">From basic sciences to clinical specialties — train across affiliated teaching hospitals and academic departments with real patients, supervised practice, and research-minded mentors.</p>
            <div class="slide-actions">
              <a href="departments.php" class="btn-pmc btn-pmc-primary">Academic Departments</a>
              <a href="about.php#campus" class="btn-pmc btn-pmc-outline-white"><i class="bi bi-hospital"></i> Teaching Hospitals</a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-none d-lg-block">
          <div class="slide-facilities" aria-label="Clinical training strengths">
            <div class="sf-title">Where You Train</div>
            <div class="sf-grid">
              <div class="sf-item"><i class="bi bi-hospital"></i><span>Kuwait Teaching Hospital</span></div>
              <div class="sf-item"><i class="bi bi-heart-pulse"></i><span>Mercy Teaching Hospital</span></div>
              <div class="sf-item"><i class="bi bi-building"></i><span>Prime Teaching Hospital</span></div>
              <div class="sf-item"><i class="bi bi-diagram-3"></i><span>Academic Departments</span></div>
              <div class="sf-item"><i class="bi bi-clipboard2-pulse"></i><span>Skills Laboratories</span></div>
              <div class="sf-item"><i class="bi bi-people"></i><span>Expert Faculty</span></div>
              <div class="sf-item"><i class="bi bi-journal-medical"></i><span>PG Medical Education</span></div>
              <div class="sf-item"><i class="bi bi-search"></i><span>Research Culture</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Controls -->
  <div class="slider-chrome">
    <button class="slider-prev" type="button" aria-label="Previous slide"><i class="bi bi-chevron-left"></i></button>
    <div class="slider-indicators" role="tablist" aria-label="Slides">
      <button class="slider-dot active" type="button" data-slide="0" role="tab" aria-label="Peshawar Medical College" aria-selected="true">
        <span class="slider-dot-track"><span class="slider-dot-bar"></span></span>
        <span class="slider-dot-label">MBBS</span>
      </button>
      <button class="slider-dot" type="button" data-slide="1" role="tab" aria-label="Clinical Training" aria-selected="false">
        <span class="slider-dot-track"><span class="slider-dot-bar"></span></span>
        <span class="slider-dot-label">Clinical</span>
      </button>
    </div>
    <button class="slider-next" type="button" aria-label="Next slide"><i class="bi bi-chevron-right"></i></button>
  </div>
</div>

<!-- ═══ STATS BAR ═══ -->
<div class="pmc-stats home-stats">
  <div class="container">
    <div class="row g-0">
      <div class="col-6 col-md-3">
        <div class="stat-cell">
          <span class="stat-num" data-count="2005">2005</span>
          <span class="stat-lbl">Year Established</span>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-cell">
          <span class="stat-num" data-count="20" data-suffix="+">20+</span>
          <span class="stat-lbl">Years of Excellence</span>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-cell">
          <span class="stat-num" data-count="3">3</span>
          <span class="stat-lbl">Teaching Hospitals</span>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-cell">
          <span class="stat-num">#1</span>
          <span class="stat-lbl">Private in KP (PM&amp;DC 2024)</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ═══ PROGRAMS ═══ -->
<section class="pmc-section" id="programs">
  <div class="container">
    <div class="home-sec-head text-center fu">
      <span class="sec-eyebrow">Academic Programs</span>
      <h2 class="sec-title">Medical Education at International Standards</h2>
      <p class="sec-desc">A curriculum built to produce graduates with clinical acumen, research ability, ethical values, and community leadership.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-6 fu fu-delay-1">
        <div class="pmc-card home-prog-card">
          <div class="pmc-card-icon home-prog-logo">
            <img src="assets/images/logo/pmclogo.png" alt="Peshawar Medical College" />
          </div>
          <h4>Peshawar Medical College</h4>
          <p>MBBS education aligned with the community health needs of KP and Pakistan, with clinical training at 3 affiliated teaching hospitals.</p>
          <div class="home-prog-tags">
            <span class="pmc-tag">MBBS</span>
            <span class="pmc-tag">PM&amp;DC</span>
            <span class="pmc-tag">3 Hospitals</span>
          </div>
          <a href="pmc.php" class="btn-pmc btn-pmc-outline home-prog-btn">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-6 fu fu-delay-2">
        <div class="pmc-card home-prog-card">
          <div class="pmc-card-icon"><i class="bi bi-clipboard2-pulse"></i></div>
          <h4>Postgraduate Medical Education</h4>
          <p>FCPS and MCPS programmes recognized by CPSP since 2011, with plans to introduce MS and diploma pathways.</p>
          <div class="home-prog-tags">
            <span class="pmc-tag">FCPS</span>
            <span class="pmc-tag">MCPS</span>
            <span class="pmc-tag">CPSP</span>
          </div>
          <a href="pg-medical-education.php" class="btn-pmc btn-pmc-outline home-prog-btn">Explore <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ ABOUT ═══ -->
<section class="pmc-section bg-off" id="about">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5 fu">
        <div class="about-visual home-about-visual">
          <div class="about-badge">
            <span class="about-badge-num">2005</span>
            <span class="about-badge-lbl">Established</span>
          </div>
        </div>
      </div>
      <div class="col-lg-7 fu fu-delay-2">
        <span class="sec-eyebrow">About DMS</span>
        <h2 class="sec-title">Department of Medical Sciences</h2>
        <p class="sec-desc">A constituent department of Riphah International University – Peshawar Campus. It comprises <strong>Peshawar Medical College</strong> and postgraduate medical programmes, preparing physicians with competence, integrity, commitment, and research ability aligned to the health needs of KP and Pakistan.</p>
        <p class="sec-desc">Recognized by PM&amp;DC. In the 2024 inspection, we stood first among private hospitals of KP with more than 80% score.</p>
        <a href="about.php" class="btn-pmc btn-pmc-primary mt-2"><i class="bi bi-arrow-right-circle"></i> Read Full About</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHY DMS ═══ -->
<section class="pmc-section" id="why-dms">
  <div class="container">
    <div class="home-sec-head text-center fu">
      <span class="sec-eyebrow">Why DMS</span>
      <h2 class="sec-title">Why Choose Medical Sciences at Riphah Peshawar</h2>
      <p class="sec-desc">Clinical depth, ethical formation, and recognised standards — built around the health needs of KP and Pakistan.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3 fu fu-delay-1">
        <div class="why-card">
          <div class="why-ico"><i class="bi bi-award-fill"></i></div>
          <h4>PM&amp;DC Recognised</h4>
          <p>Top among private hospitals of KP in the 2024 PM&amp;DC inspection, with more than 80% score.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 fu fu-delay-2">
        <div class="why-card">
          <div class="why-ico"><i class="bi bi-hospital"></i></div>
          <h4>3 Teaching Hospitals</h4>
          <p>Clinical rotations across Kuwait, Mercy, and Prime Teaching Hospitals for broad bedside exposure.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 fu fu-delay-3">
        <div class="why-card">
          <div class="why-ico"><i class="bi bi-heart-pulse"></i></div>
          <h4>Ethics &amp; Competence</h4>
          <p>Outcome-based curricula that form physicians with integrity, research ability, and community focus.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 fu fu-delay-4">
        <div class="why-card">
          <div class="why-ico"><i class="bi bi-mortarboard-fill"></i></div>
          <h4>PG Pathways</h4>
          <p>FCPS and MCPS training recognised by CPSP, with expanding postgraduate opportunities since 2011.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ TEACHING HOSPITALS ═══ -->
<section class="pmc-section bg-navy home-hospitals" id="hospitals">
  <div class="container">
    <div class="home-sec-head text-center fu">
      <span class="sec-eyebrow">Affiliated Teaching Hospitals</span>
      <h2 class="sec-title">Clinical Training at Its Finest</h2>
      <p class="sec-desc">Students rotate through three affiliated teaching hospitals for comprehensive, real-world clinical exposure throughout MBBS training.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 fu fu-delay-1">
        <div class="hosp-card">
          <div class="hosp-ico"><i class="bi bi-hospital-fill"></i></div>
          <div class="hosp-name">Kuwait Teaching Hospital</div>
          <p class="hosp-desc">Major tertiary care centre providing comprehensive clinical rotations across medical and surgical specialties.</p>
          <a href="https://kth.prime.edu.pk/" class="hosp-link" target="_blank" rel="noopener">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 fu fu-delay-2">
        <div class="hosp-card">
          <div class="hosp-ico"><i class="bi bi-heart-pulse-fill"></i></div>
          <div class="hosp-name">Mercy Teaching Hospital</div>
          <p class="hosp-desc">Focused on community healthcare with high patient volume — ideal for broad clinical experience.</p>
          <a href="https://mth.prime.edu.pk/" class="hosp-link" target="_blank" rel="noopener">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 fu fu-delay-3">
        <div class="hosp-card">
          <div class="hosp-ico"><i class="bi bi-building-fill"></i></div>
          <div class="hosp-name">Prime Teaching Hospital</div>
          <p class="hosp-desc">Modern diagnostic and surgical facilities for intensive clinical and surgical training rotations.</p>
          <a href="https://pth.prime.edu.pk/" class="hosp-link" target="_blank" rel="noopener">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CAMPUS LIFE ═══ -->
<section class="pmc-section bg-off" id="campus">
  <div class="container">
    <div class="row align-items-end mb-4 g-3">
      <div class="col-lg-8 fu">
        <span class="sec-eyebrow">Campus Life</span>
        <h2 class="sec-title">A Campus Built for Learning &amp; Wellbeing</h2>
        <p class="sec-desc mb-0">Spanning 25 kanals at Warsak Rd, Sher Ali Town, Peshawar — academics, recreation, spirituality, and community support in one place.</p>
      </div>
      <div class="col-lg-4 text-lg-end fu fu-delay-2">
        <a href="about.php#campus" class="btn-pmc btn-pmc-outline">View Campus Details <i class="bi bi-arrow-right"></i></a>
      </div>
    </div>
    <div class="campus-mosaic fu">
      <div class="campus-tile big">
        <div class="ct-inner ct-bg-1">
          <div class="ct-ico"><i class="bi bi-buildings"></i></div>
          <div class="ct-lbl">Department of Medical Sciences<br /><span class="ct-sub">25 Kanals · Sher Ali Town, Peshawar</span></div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-2">
          <div class="ct-ico"><i class="bi bi-book-half"></i></div>
          <div class="ct-lbl">Library &amp; LRC</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-3">
          <div class="ct-ico"><i class="bi bi-trophy"></i></div>
          <div class="ct-lbl">Sports &amp; Gym</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-4">
          <div class="ct-ico"><i class="bi bi-cup-hot"></i></div>
          <div class="ct-lbl">Cafeteria</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-5">
          <div class="ct-ico"><i class="bi bi-house-heart"></i></div>
          <div class="ct-lbl">Girls Hostel</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-6">
          <div class="ct-ico"><i class="bi bi-brightness-high"></i></div>
          <div class="ct-lbl">Masjid</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-7">
          <div class="ct-ico"><i class="bi bi-bus-front"></i></div>
          <div class="ct-lbl">Transportation</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-8">
          <div class="ct-ico"><i class="bi bi-emoji-smile"></i></div>
          <div class="ct-lbl">Day Care Center</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-9">
          <div class="ct-ico"><i class="bi bi-chat-heart"></i></div>
          <div class="ct-lbl">Counseling &amp; Aid</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ NEWS & EVENTS ═══ -->
<section class="pmc-section bg-off" id="news">
  <div class="container">
    <div class="row align-items-end mb-5">
      <div class="col-lg-7 fu">
        <span class="sec-eyebrow">News &amp; Updates</span>
        <h2 class="sec-title">Latest from DMS</h2>
        <p class="sec-desc mb-0">Events, admissions, and campus notices — current items only.</p>
      </div>
      <div class="col-lg-5 text-lg-end fu fu-delay-2 mt-3 mt-lg-0">
        <a href="all-news.php" class="btn-pmc btn-pmc-outline"><i class="bi bi-newspaper"></i> All News</a>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 fu fu-delay-1">
        <article class="news-card news-card-text news-card--event">
          <div class="nc-body">
            <div class="nc-meta">
              <span class="nc-cat nc-cat-news">Event</span>
              <span class="nc-date"><i class="bi bi-calendar3"></i> Published: July 2026</span>
            </div>
            <h3 class="nc-title">Participate in Seert-un-Nabi Week</h3>
            <p class="nc-deadline"><i class="bi bi-clock"></i> Apply by <strong>8 Aug 2026</strong></p>
            <div class="nc-actions">
              <a target="_blank" href="assets/images/news/ad-seerat-ul-nabi.jpeg" class="nc-btn nc-btn-primary">Read more <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </article>
      </div>
      <div class="col-lg-4 col-md-6 fu fu-delay-2">
        <article class="news-card news-card-text news-card--admissions">
          <div class="nc-body">
            <div class="nc-meta">
              <span class="nc-cat nc-cat-admissions">Admissions</span>
              <span class="nc-date"><i class="bi bi-calendar3"></i> Published: Apr 2026</span>
            </div>
            <h3 class="nc-title">MPhil Basic Medical Sciences</h3>
            <p class="nc-deadline"><i class="bi bi-clock"></i> Apply by <strong>20 Aug 2026</strong></p>
            <div class="nc-actions nc-actions-split">
              <a target="_blank" href="assets/images/news/ad-medical.jpeg" class="nc-btn nc-btn-primary">Read more <i class="bi bi-arrow-right"></i></a>
              <a target="_blank" href="assets/images/news/PG-Admission-Form-Medical-Sciences.pdf" class="nc-btn nc-btn-form"><i class="bi bi-download"></i> Form</a>
            </div>
          </div>
        </article>
      </div>
      <div class="col-lg-4 col-md-6 fu fu-delay-3">
        <article class="news-card news-card-text news-card--admissions">
          <div class="nc-body">
            <div class="nc-meta">
              <span class="nc-cat nc-cat-admissions">Admissions</span>
              <span class="nc-date"><i class="bi bi-calendar3"></i> Published: Apr 2026</span>
            </div>
            <h3 class="nc-title">BS, MPhil &amp; PhD Islamiyat</h3>
            <p class="nc-deadline"><i class="bi bi-clock"></i> Apply by <strong>20 Aug 2026</strong></p>
            <div class="nc-actions nc-actions-split">
              <a target="_blank" href="assets/images/news/ad-islamiyat.jpeg" class="nc-btn nc-btn-primary">Read more <i class="bi bi-arrow-right"></i></a>
              <a target="_blank" href="assets/images/news/PG-Admission-Form-Islamiyat.pdf" class="nc-btn nc-btn-form"><i class="bi bi-download"></i> Form</a>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CTA BAND ═══ -->
<section class="cta-band home-cta">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7 fu">
        <h2 class="mb-2">Ready to Join Department of Medical Sciences?</h2>
        <p class="mb-0">Explore MBBS admissions, postgraduate pathways, or contact us for guidance.</p>
      </div>
      <div class="col-lg-5 text-lg-end fu fu-delay-2">
        <div class="home-cta-actions">
          <a href="admissions.php" class="btn-pmc btn-pmc-outline-white"><i class="bi bi-pencil-square"></i> Apply Now</a>
          <a href="contact.php" class="btn-pmc btn-pmc-outline-white"><i class="bi bi-telephone"></i> Contact Us</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ RECOGNITIONS ═══ -->
<section class="pmc-section recog-strip" id="recognitions">
  <div class="container">
    <div class="home-sec-head text-center fu">
      <span class="sec-eyebrow">Recognitions &amp; Accreditations</span>
      <h2 class="sec-title">Recognized by Leading Institutions</h2>
    </div>
    <div class="recog-grid fu">
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-patch-check-fill"></i></div>
        <div class="recog-name">Pakistan Medical &amp; Dental Council<br /><small>(PM&amp;DC)</small></div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-building-fill"></i></div>
        <div class="recog-name">Riphah International University</div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-award-fill"></i></div>
        <div class="recog-name">College of Physicians &amp; Surgeons Pakistan<br /><small>(CPSP)</small></div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-hospital-fill"></i></div>
        <div class="recog-name">Ministry of Health<br /><small>Pakistan</small></div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-globe2"></i></div>
        <div class="recog-name">World Health Organization<br /><small>(WHO)</small></div>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
