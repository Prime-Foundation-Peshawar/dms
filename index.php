<?php
$preload_images = ['assets/images/slider/hero-slide-1.webp'];
include('includes/header.php');
?>

<!-- ═══ HERO SLIDER ═══ -->
<div id="heroSlider" aria-roledescription="carousel" aria-label="Department of Medical Sciences highlights">

  <!-- SLIDE 1 — Medical Sciences -->
  <div class="hero-slide active" role="group" aria-roledescription="slide" aria-label="1 of 2">
    <div class="slide-media slide-bg-3"></div>
    <div class="slide-overlay"></div>
    <div class="container slide-inner">
      <div class="slide-content">
        <p class="slide-brand">Department of Medical Sciences</p>
        <h1 class="slide-title">Shaping <span class="hl-teal">Compassionate</span> Physicians</h1>
        <p class="slide-body">Peshawar Medical College and postgraduate medical programmes — outcome-based curricula, clinical skill laboratories, teaching hospitals, and research-led education.</p>
        <p class="slide-body slide-body-sub">At Riphah International University – Peshawar Campus on Warsak Road, students train with professional competence, integrity, and a commitment to community health needs across KP and Pakistan.</p>
        <div class="slide-actions">
          <a href="medical-education.php" class="btn-pmc btn-pmc-primary"><i class="bi bi-journal-medical"></i> Medical Education</a>
          <a href="pmc.php" class="btn-pmc btn-pmc-outline-white">Peshawar Medical College</a>
        </div>
      </div>
    </div>
  </div>

  <!-- SLIDE 2 — Campus Life -->
  <div class="hero-slide" role="group" aria-roledescription="slide" aria-label="2 of 2" data-lazy-bg>
    <div class="slide-media slide-bg-4"></div>
    <div class="slide-overlay"></div>
    <div class="container slide-inner">
      <div class="row align-items-center g-4 g-xl-5">
        <div class="col-lg-6">
          <div class="slide-content">
            <p class="slide-brand">Campus Life</p>
            <h1 class="slide-title">A Vibrant <span class="hl">25-Kanal</span> Campus</h1>
            <p class="slide-body">On Warsak Road with green surroundings — library, hostel, masjid, cafeteria, gym, sports, day care, counselling, and student support for a complete medical campus experience.</p>
            <div class="slide-actions">
              <a href="about.php#campus" class="btn-pmc btn-pmc-primary">Explore Campus</a>
              <a href="contact.php" class="btn-pmc btn-pmc-outline-white"><i class="bi bi-map"></i> Get Directions</a>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="slide-facilities" aria-label="Campus facilities">
            <div class="sf-title">Campus Facilities</div>
            <div class="sf-grid">
              <div class="sf-item"><i class="bi bi-book-half"></i><span>Library &amp; LRC</span></div>
              <div class="sf-item"><i class="bi bi-house-heart"></i><span>Girls Hostel</span></div>
              <div class="sf-item"><i class="bi bi-trophy"></i><span>Sports &amp; Gym</span></div>
              <div class="sf-item"><i class="bi bi-brightness-high"></i><span>Masjid</span></div>
              <div class="sf-item"><i class="bi bi-cup-hot"></i><span>Cafeteria</span></div>
              <div class="sf-item"><i class="bi bi-bus-front"></i><span>Transportation</span></div>
              <div class="sf-item"><i class="bi bi-emoji-smile"></i><span>Day Care</span></div>
              <div class="sf-item"><i class="bi bi-chat-heart"></i><span>Counseling &amp; Aid</span></div>
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
      <button class="slider-dot active" type="button" data-slide="0" role="tab" aria-label="Medical Sciences" aria-selected="true">
        <span class="slider-dot-track"><span class="slider-dot-bar"></span></span>
        <span class="slider-dot-label">DMS</span>
      </button>
      <button class="slider-dot" type="button" data-slide="1" role="tab" aria-label="Campus Life" aria-selected="false">
        <span class="slider-dot-track"><span class="slider-dot-bar"></span></span>
        <span class="slider-dot-label">Life</span>
      </button>
    </div>
    <button class="slider-next" type="button" aria-label="Next slide"><i class="bi bi-chevron-right"></i></button>
  </div>
</div>

<!-- ═══ STATS BAR ═══ -->
<div class="pmc-stats">
  <div class="container-fluid px-0">
    <div class="row g-0">
      <div class="col-6 col-md-3">
        <div class="stat-cell"><span class="stat-num" data-count="2005" data-suffix="">2005</span><span
            class="stat-lbl">Year Established</span></div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-cell"><span class="stat-num" data-count="20" data-suffix="+">20+</span><span
            class="stat-lbl">Years of Excellence</span></div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-cell"><span class="stat-num" data-count="3" data-suffix="">3</span><span
            class="stat-lbl">Teaching Hospitals</span></div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-cell"><span class="stat-num">#1</span><span class="stat-lbl">Choice of Students</span></div>
      </div>
    </div>
  </div>
</div>

<!-- ═══ PROGRAMS ═══ -->
<section class="pmc-section" id="programs">
  <div class="container">
    <div class="text-center mb-5 fu">
      <span class="sec-eyebrow">Academic Programs</span>
      <h2 class="sec-title">Medical Education at International Standards</h2>
      <p class="sec-desc mx-auto" style="max-width:570px;">A curriculum built to produce graduates with clinical acumen,
        research ability, ethical values, and community leadership.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-6 col-md-6 fu fu-delay-2">
        <div class="pmc-card">
          <div class="pmc-card-icon">
              <!--<i class="bi bi-clipboard-pulse"></i>-->
              <img src="assets/images/logo/pmclogo.png"/>    
          </div>
          <h4>Peshawar Medical College</h4>
          <p>MBBS education aligned with the community health needs of KP and Pakistan, with clinical training at three affiliated teaching hospitals.</p>
          <div class="mt-3 mb-4">
            <span class="pmc-tag prog-tag">MBBS</span>
            <span class="pmc-tag prog-tag">PM&amp;DC</span>
            <span class="pmc-tag prog-tag">3 Hospitals</span>
            </div>
          <a href="pmc.php" class="btn-pmc btn-pmc-outline"
            style="font-size:.8rem;padding:9px 18px;">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 fu fu-delay-2">
        <div class="pmc-card">
          <div class="pmc-card-icon"><i class="bi bi-clipboard2-pulse"></i></div>
          <h4>Postgraduate Medical Education</h4>
          <p>The postgraduate training programme started in 2011, offering FCPS and MCPS programmes recognized by CPSP,
            with plans to introduce MS and diploma programmes.</p>
          <div class="mt-3 mb-4">
            <span class="pmc-tag prog-tag">Postgraduate</span>
            <!-- <span class="pmc-tag prog-tag">Programs</span> -->
            </div>
          <a href="pg-medical-education.php" class="btn-pmc btn-pmc-outline"
            style="font-size:.8rem;padding:9px 18px;">Explore <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ ABOUT ═══ -->
<section class="pmc-section bg-off" id="about">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-5 fu">
        <div class="about-visual" style="position:relative;">
          <div class="about-badge">
            <span class="about-badge-num">2005</span>
            <span class="about-badge-lbl">Established</span>
          </div>
        </div>
      </div>
      <div class="col-lg-7 fu fu-delay-2">
        <span class="sec-eyebrow">About DMS</span>
        <h2 class="sec-title">Department of Medical Sciences</h2>

        <p class="sec-desc">The Department of Medical Sciences is a constituent department of Riphah International University – Peshawar Campus. It comprises <strong>Peshawar Medical College</strong> and postgraduate medical programmes, preparing physicians with high standards of competence, integrity, commitment, and research in line with the community health needs of KP and Pakistan.</p>
        <p class="sec-desc">Recognized by the Pakistan Medical &amp; Dental Council (PM&amp;DC). In the 2024 PM&amp;DC inspection of public and private sector colleges, we stood first among private hospitals of KP with more than 80% score.</p>
        <a href="about.php" class="btn-pmc btn-pmc-primary mt-4"><i class="bi bi-arrow-right-circle"></i> Read Full
          About Department of Medical Sciences</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHY DMS ═══ -->
<section class="pmc-section" id="why-dms">
  <div class="container">
    <div class="text-center mb-5 fu">
      <span class="sec-eyebrow">Why DMS</span>
      <h2 class="sec-title">Why Choose Medical Sciences at Riphah Peshawar</h2>
      <p class="sec-desc mx-auto" style="max-width:560px;">Clinical depth, ethical formation, and recognised standards — built around the health needs of KP and Pakistan.</p>
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
          <h4>Three Teaching Hospitals</h4>
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
<section class="pmc-section bg-navy" id="hospitals">
  <div class="container">
    <div class="text-center mb-5 fu">
      <span class="sec-eyebrow" style="color:var(--gold-light);">Affiliated Teaching Hospitals</span>
      <h2 class="sec-title" style="color:white;">Clinical Training at Its Finest</h2>
      <p class="sec-desc mx-auto" style="max-width:570px;">Department of Medical Sciences students rotate through four
        affiliated institutions
        offering comprehensive, real-world clinical exposure throughout their MBBS training.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 fu fu-delay-1">
        <div class="hosp-card">
          <div class="hosp-ico"><i class="bi bi-hospital-fill"></i></div>
          <div class="hosp-name">Kuwait Teaching Hospital</div>
          <p class="hosp-desc">Major tertiary care centre providing comprehensive clinical rotations across all medical
            and surgical specialties.</p><a href="https://kth.prime.edu.pk/" class="hosp-link" target="_blank">Learn
            More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 fu fu-delay-2">
        <div class="hosp-card">
          <div class="hosp-ico"><i class="bi bi-heart-pulse-fill"></i></div>
          <div class="hosp-name">Mercy Teaching Hospital</div>
          <p class="hosp-desc">Focused on community healthcare with high patient volume — ideal for comprehensive, broad
            clinical experience.</p><a href="https://mth.prime.edu.pk/" class="hosp-link" target="_blank">Learn More <i
              class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 fu fu-delay-3">
        <div class="hosp-card">
          <div class="hosp-ico"><i class="bi bi-building-fill"></i></div>
          <div class="hosp-name">Prime Teaching Hospital</div>
          <p class="hosp-desc">Equipped with modern diagnostic and surgical facilities for intensive clinical and
            surgical training rotations.</p><a href="https://pth.prime.edu.pk/" class="hosp-link" target="_blank">Learn
            More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

<!-- ═══ CAMPUS LIFE ═══ -->
<section class="pmc-section bg-off" id="campus">
  <div class="container">
    <div class="row align-items-end mb-5">
      <div class="col-lg-7 fu">
        <span class="sec-eyebrow">Life at Department of Medical Sciences</span>
        <h2 class="sec-title">A Campus Built for Learning & Wellbeing</h2>
        <p class="sec-desc">Spanning 25 kanals on Warsak Road, Department of Medical Science's lush green campus
          provides everything a medical
          student needs — academics, recreation, spirituality, and community.</p>
      </div>
      <div class="col-lg-5 text-lg-end fu fu-delay-2">
      </div>
    </div>
    <div class="campus-mosaic fu">
      <div class="campus-tile big">
        <div class="ct-inner ct-bg-1">
          <div class="ct-ico"><i class="bi bi-buildings"></i></div>
          <div class="ct-lbl">Department of Medical Sciences<br /><span
              style="font-size:.78rem;opacity:.65;font-weight:500;">25 Kanals
              · Warsak Road, Peshawar</span></div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-2">
          <div class="ct-ico"><i class="bi bi-book-half"></i></div>
          <div class="ct-lbl">Library & LRC</div>
        </div>
      </div>
      <div class="campus-tile">
        <div class="ct-inner ct-bg-3">
          <div class="ct-ico"><i class="bi bi-trophy"></i></div>
          <div class="ct-lbl">Sports & Gym</div>
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
          <div class="ct-lbl">Counseling & Aid</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ NEWS & EVENTS ═══ -->
<section class="pmc-section" id="news">
  <div class="container">
    <div class="row align-items-end mb-5">
      <div class="col-lg-7 fu">
        <span class="sec-eyebrow">News & Events</span>
        <h2 class="sec-title">Latest from the Campus</h2>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 fu fu-delay-1">
        <div class="news-card">
          <div class="nc-img nc-img-1" style="background-image:url('assets/images/news/ad-seerat-ul-nabi.jpeg')">
          </div>
          <div class="nc-body">
            <div class="nc-meta">
              <span class="nc-cat">News</span>
              <i class="bi bi-dot"></i>
              July 2026
            </div>
            <div class="nc-title">Participate in Seert-un-Nabi Week</div>
            <div class="nc-excerpt">Last date: 8th August 2026</div>
            <a target="_blank" href="assets/images/news/ad-seerat-ul-nabi.jpeg" class="nc-link">
              Read more
              <i class="bi bi-arrow-right"></i>
            </a>
            </a>
            </a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 fu fu-delay-2">
        <div class="news-card">
          <div class="nc-img nc-img-2" style="background-image:url('assets/images/news/ad-medical.jpeg')">
          </div>
          <div class="nc-body">
            <div class="nc-meta"><span class="nc-cat">Admissions</span><i class="bi bi-dot"></i>Apr 2026</div>
            <br>
            <div class="nc-title">MPhil Basic Medical Sciences</div>
            <div class="nc-excerpt">Last date: 20th Aug 2026</div>
            <a target="_blank" href="assets/images/news/ad-medical.jpeg" class="nc-link">
              Advertisement
              <i class="bi bi-arrow-right"></i>
            </a>
            <a target="_blank" href="assets/images/news/PG-Admission-Form-Medical-Sciences.pdf" class="nc-link">
              Form
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 fu fu-delay-2">
        <div class="news-card">
          <div class="nc-img nc-img-2" style="background-image:url('assets/images/news/ad-islamiyat.jpeg')">
          </div>
          <div class="nc-body">
            <div class="nc-meta"><span class="nc-cat">Admissions</span><i class="bi bi-dot"></i>Apr 2026</div>
            <div class="nc-title">BS, MPhil & PhD Islamiyat</div>
            <div class="nc-excerpt">Last date: 20th Aug 2026</div>
            <a target="_blank" href="assets/images/news/ad-islamiyat.jpeg" class="nc-link">
              Advertisement
              <i class="bi bi-arrow-right"></i>
            </a>
            <a target="_blank" href="assets/images/news/PG-Admission-Form-Islamiyat.pdf" class="nc-link">
              Form
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

<!-- ═══ CTA BAND ═══ -->
<section class="cta-band">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7 fu">
        <h2 class="mb-3">Ready to Join Department of Medical Sciences?</h2>
        <p class="mb-0">Explore MBBS admissions, postgraduate pathways, or contact the campus for guidance.</p>
      </div>
      <div class="col-lg-5 text-lg-end fu fu-delay-2">
        <a href="admissions.php" class="btn-pmc btn-pmc-outline-white me-2 mb-2"><i class="bi bi-pencil-square"></i> Apply Now</a>
        <a href="contact.php" class="btn-pmc btn-pmc-outline-white mb-2"><i class="bi bi-telephone"></i> Contact Us</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ RECOGNITIONS ═══ -->
<section class="pmc-section recog-strip" id="recognitions">
  <div class="container">
    <div class="text-center mb-5 fu">
      <span class="sec-eyebrow">Recognitions &amp; Accreditations</span>
      <h2 class="sec-title" style="color:white;">Recognized by Leading Institutions</h2>
    </div>
    <div class="recog-grid fu">
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-patch-check-fill"></i></div>
        <div class="recog-name">Pakistan Medical &amp; Dental Council<br /><small
            style="opacity:.5;font-size:.62rem;">(PM&amp;DC)</small></div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-building-fill"></i></div>
        <div class="recog-name">Riphah International University</div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-award-fill"></i></div>
        <div class="recog-name">College of Physicians &amp; Surgeons Pakistan<br /><small
            style="opacity:.5;font-size:.62rem;">(CPSP)</small></div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-hospital-fill"></i></div>
        <div class="recog-name">Ministry of Health<br /><small style="opacity:.5;font-size:.62rem;">Pakistan</small>
        </div>
      </div>
      <div class="recog-cell" style="border-right:none;">
        <div class="recog-ico"><i class="bi bi-globe2"></i></div>
        <div class="recog-name">World Health Organization<br /><small style="opacity:.5;font-size:.62rem;">(WHO)</small>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>