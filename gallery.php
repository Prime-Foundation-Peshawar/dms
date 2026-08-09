<style>
    /* ══ FILTER TABS ══ */
    .gallery-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 40px;
      justify-content: center;
    }
    .gf-btn {
      font-family: var(--font-head);
      font-size: .78rem;
      font-weight: 700;
      padding: 9px 22px;
      border-radius: 100px;
      border: 2px solid var(--border);
      background: white;
      color: var(--gray-dark);
      cursor: pointer;
      transition: all .2s;
      letter-spacing: .02em;
    }
    .gf-btn:hover  { border-color: var(--teal); color: var(--teal); }
    .gf-btn.active { background: var(--teal); border-color: var(--teal); color: white; }

    /* ══ GALLERY GRID ══ */
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
    }
    .gallery-item {
      position: relative;
      border-radius: var(--r-md);
      overflow: hidden;
      aspect-ratio: 1;
      cursor: pointer;
      background: var(--gray-light);
      transition: transform .3s, box-shadow .3s;
    }
    .gallery-item:hover { transform: scale(1.02); box-shadow: var(--shadow-lg); }

    /* Span variants */
    .gallery-item.span-2     { grid-column: span 2; aspect-ratio: 2/1; }
    .gallery-item.span-2-row { grid-row: span 2; aspect-ratio: 1/2; }

    .gallery-img {
      width: 100%; height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .4s;
    }
    .gallery-item:hover .gallery-img { transform: scale(1.06); }

    /* Placeholder (when no image) */
    .gallery-placeholder {
      width: 100%; height: 100%;
      background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 10px;
      transition: filter .3s;
    }
    .gallery-placeholder i    { font-size: 2rem; color: rgba(255,255,255,.5); }
    .gallery-placeholder span { font-family: var(--font-head); font-size: .75rem; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .08em; }

    /* Overlay */
    .gallery-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(10,22,40,.85) 0%, transparent 55%);
      opacity: 0;
      transition: opacity .25s;
      display: flex; align-items: flex-end;
      padding: 18px;
    }
    .gallery-item:hover .gallery-overlay { opacity: 1; }
    .gallery-overlay-text {
      font-family: var(--font-head);
      font-size: .82rem;
      font-weight: 700;
      color: white;
      line-height: 1.3;
    }
    .gallery-overlay-cat {
      font-family: var(--font-body);
      font-size: .68rem;
      font-weight: 700;
      color: var(--teal-light);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 3px;
    }
    /* Zoom icon badge */
    .gallery-zoom {
      position: absolute;
      top: 12px; right: 12px;
      width: 32px; height: 32px;
      background: rgba(255,255,255,.15);
      backdrop-filter: blur(8px);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: white; font-size: .9rem;
      opacity: 0; transition: opacity .25s;
    }
    .gallery-item:hover .gallery-zoom { opacity: 1; }

    /* ══ LIGHTBOX ══ */
    .pmc-lightbox {
      position: fixed; inset: 0;
      background: rgba(6,14,26,.96);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
    }
    .pmc-lightbox.open { display: flex; animation: lbFade .25s ease; }
    @keyframes lbFade { from{opacity:0} to{opacity:1} }

    .lb-inner {
      position: relative;
      max-width: 90vw;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .lb-img-wrap {
      position: relative;
      border-radius: var(--r-md);
      overflow: hidden;
      box-shadow: 0 24px 80px rgba(0,0,0,.6);
    }
    .lb-img {
      max-width: 88vw;
      max-height: 76vh;
      object-fit: contain;
      display: block;
    }
    /* Placeholder in lightbox */
    .lb-placeholder {
      width: 600px; max-width: 88vw;
      height: 400px; max-height: 76vh;
      background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 14px;
    }
    .lb-placeholder i    { font-size: 3rem; color: rgba(255,255,255,.4); }
    .lb-placeholder span { font-family:var(--font-head); font-size:.8rem; color:rgba(255,255,255,.35); }

    .lb-caption {
      margin-top: 16px;
      text-align: center;
    }
    .lb-caption-title {
      font-family: var(--font-head);
      font-size: .95rem;
      font-weight: 700;
      color: white;
      margin-bottom: 3px;
    }
    .lb-caption-cat {
      font-family: var(--font-body);
      font-size: .75rem;
      color: var(--teal-light);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .07em;
    }
    .lb-counter {
      font-family: var(--font-body);
      font-size: .75rem;
      color: rgba(255,255,255,.35);
      margin-top: 6px;
    }

    /* Controls */
    .lb-close {
      position: absolute;
      top: -48px; right: 0;
      width: 40px; height: 40px;
      background: rgba(255,255,255,.1);
      border: none; border-radius: 8px;
      color: white; font-size: 1.1rem;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: background .2s;
    }
    .lb-close:hover { background: rgba(255,255,255,.2); }

    .lb-prev, .lb-next {
      position: fixed;
      top: 50%; transform: translateY(-50%);
      width: 48px; height: 48px;
      background: rgba(255,255,255,.1);
      border: 1px solid rgba(255,255,255,.15);
      border-radius: 50%;
      color: white; font-size: 1.2rem;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: background .2s;
    }
    .lb-prev { left: 20px; }
    .lb-next { right: 20px; }
    .lb-prev:hover, .lb-next:hover { background: rgba(0,168,150,.5); }

    /* ══ ALBUM SECTION HEADING ══ */
    .album-heading {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 22px;
      padding-bottom: 14px;
      border-bottom: 2px solid var(--border);
    }
    .album-heading-icon {
      width: 44px; height: 44px;
      background: var(--navy);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: var(--gold); font-size: 1.2rem;
      flex-shrink: 0;
    }
    .album-title {
      font-family: var(--font-head);
      font-size: 1.1rem;
      font-weight: 800;
      color: var(--navy);
    }
    .album-count {
      font-family: var(--font-body);
      font-size: .75rem;
      font-weight: 700;
      color: white;
      background: var(--teal);
      padding: 2px 10px;
      border-radius: 100px;
    }

    /* ══ EMPTY STATE ══ */
    .gallery-empty {
      text-align: center;
      padding: 70px 0;
    }
    .gallery-empty i { font-size: 3rem; color: var(--gray-light); margin-bottom: 14px; display: block; }

    /* ══ BACK TO TOP ══ */
    #backToTop {
      position: fixed; bottom: 28px; right: 28px;
      width: 44px; height: 44px; background: var(--teal);
      color: white; border: none; border-radius: 10px; font-size: 1.1rem;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; z-index: 999; opacity: 0; transform: translateY(10px);
      transition: opacity .25s, transform .25s, background .2s;
      box-shadow: 0 4px 18px rgba(0,168,150,.35);
    }
    #backToTop.visible { opacity: 1; transform: translateY(0); }
    #backToTop:hover   { background: var(--navy); }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 991.98px) {
      .gallery-grid { grid-template-columns: repeat(3,1fr); }
      .gallery-item.span-2 { grid-column: span 2; }
      .gallery-item.span-2-row { grid-row: span 1; aspect-ratio: 1; }
    }
    @media (max-width: 767.98px) {
      .gallery-grid { grid-template-columns: repeat(2,1fr); }
      .gallery-item.span-2     { grid-column: span 2; }
      .gallery-item.span-2-row { grid-row: span 1; aspect-ratio: 1; }
      .lb-prev, .lb-next { display: none; }
    }
    @media (max-width: 480px) {
      .gallery-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
    }
  </style>
<?php include("includes/header.php"); ?>



<!-- ═══ PAGE HERO ═══ -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Photo Gallery</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Gallery</span>
    </div>
  </div>
</div>

<!-- ═══ GALLERY CONTENT ═══ -->
<section class="pmc-section bg-off">
  <div class="container">

    <!-- Intro -->
    <div class="row mb-5">
      <div class="col-lg-7">
        <span class="sec-eyebrow">Visual Journey</span>
        <h2 class="sec-title">Life at Peshawar Medical College</h2>
        <p class="sec-desc">Explore our campus, events, clinical training, research activities, student societies, and memorable moments captured across our 20+ year journey.</p>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="gallery-filters">
      <button class="gf-btn active" data-filter="all">
        <i class="bi bi-grid-3x3-gap me-1"></i> All Albums
      </button>
      <button class="gf-btn" data-filter="campus">
        <i class="bi bi-buildings me-1"></i> Campus
      </button>
      <button class="gf-btn" data-filter="events">
        <i class="bi bi-calendar-event me-1"></i> Events
      </button>
      <button class="gf-btn" data-filter="clinical">
        <i class="bi bi-hospital me-1"></i> Clinical Training
      </button>
      <button class="gf-btn" data-filter="research">
        <i class="bi bi-flask me-1"></i> Research
      </button>
      <button class="gf-btn" data-filter="societies">
        <i class="bi bi-people me-1"></i> Societies
      </button>
      <button class="gf-btn" data-filter="convocation">
        <i class="bi bi-mortarboard me-1"></i> Convocation
      </button>
    </div>

    <!--
    ═══════════════════════════════════════════════════════
    ALBUM 1 — CAMPUS
    PHP conversion:  foreach ($albums as $album) { ... }
    ═══════════════════════════════════════════════════════
    -->
    <div class="album-section mb-5" data-category="campus">
      <div class="album-heading">
        <div class="album-heading-icon"><i class="bi bi-buildings"></i></div>
        <div>
          <div class="album-title">PMC Campus &amp; Facilities</div>
          <span class="album-count">9 Photos</span>
        </div>
        <div class="ms-auto">
          <button class="gf-btn" style="font-size:.72rem;padding:6px 14px;"
                  onclick="openAlbumLightbox('campus')">
            <i class="bi bi-play-fill me-1"></i> View Slideshow
          </button>
        </div>
      </div>

      <div class="gallery-grid" id="grid-campus">
        <!-- Item 1 — large -->
        <div class="gallery-item span-2 fu"
             data-category="campus"
             data-title="Main Campus — Warsak Road"
             data-caption="25-Kanal campus on Warsak Road, Peshawar"
             data-img="assets/images/campus/pmc.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/pmc.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Main Campus','bi-buildings')"
               class="gallery-img" alt="PMC Main Campus"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div>
              <div class="gallery-overlay-cat">Campus</div>
              <div class="gallery-overlay-text">Main Campus — Warsak Road</div>
            </div>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="gallery-item fu" data-category="campus"
             data-title="Library & Learning Resource Centre"
             data-caption="Modern library with thousands of medical books and journals"
             data-img="assets/images/campus/library.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/library.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Library','bi-book-half')"
               class="gallery-img" alt="Library"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Campus</div><div class="gallery-overlay-text">Library &amp; LRC</div></div>
          </div>
        </div>

        <!-- Item 3 -->
        <div class="gallery-item fu" data-category="campus"
             data-title="Sports & Gymnasium"
             data-caption="State-of-the-art sports and gym facilities"
             data-img="assets/images/campus/gym.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/gym.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Sports & Gym','bi-trophy')"
               class="gallery-img" alt="Sports Gym"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Campus</div><div class="gallery-overlay-text">Sports &amp; Gymnasium</div></div>
          </div>
        </div>

        <!-- Item 4 -->
        <div class="gallery-item fu" data-category="campus"
             data-title="Cafeteria"
             data-caption="Well-equipped cafeteria serving students and staff"
             data-img="assets/images/campus/cafe.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/cafe.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Cafeteria','bi-cup-hot')"
               class="gallery-img" alt="Cafeteria"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Campus</div><div class="gallery-overlay-text">Cafeteria</div></div>
          </div>
        </div>

        <!-- Item 5 -->
        <div class="gallery-item fu" data-category="campus"
             data-title="Girls Hostel"
             data-caption="On-campus accommodation for female students"
             data-img="assets/images/campus/hostel.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/hostel.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Girls Hostel','bi-house-heart')"
               class="gallery-img" alt="Girls Hostel"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Campus</div><div class="gallery-overlay-text">Girls Hostel</div></div>
          </div>
        </div>

        <!-- Item 6 -->
        <div class="gallery-item fu" data-category="campus"
             data-title="College Masjid"
             data-caption="The beautiful mosque at the heart of our campus"
             data-img="assets/images/campus/mosque.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/mosque.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Masjid','bi-moon-stars')"
               class="gallery-img" alt="Masjid"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Campus</div><div class="gallery-overlay-text">College Masjid</div></div>
          </div>
        </div>

        <!-- Item 7 -->
        <div class="gallery-item fu" data-category="campus"
             data-title="Faculty Block"
             data-caption="Administrative and faculty offices"
             data-img="assets/images/campus/pmc-faculty.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/pmc-faculty.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Faculty Block','bi-building')"
               class="gallery-img" alt="Faculty Block"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Campus</div><div class="gallery-overlay-text">Faculty Block</div></div>
          </div>
        </div>
      </div>
    </div>

    <!--
    ALBUM 2 — EVENTS
    -->
    <div class="album-section mb-5" data-category="events">
      <div class="album-heading">
        <div class="album-heading-icon"><i class="bi bi-calendar-event"></i></div>
        <div>
          <div class="album-title">Events &amp; Ceremonies</div>
          <span class="album-count">6 Photos</span>
        </div>
        <div class="ms-auto">
          <a href="events.php" class="gf-btn" style="font-size:.72rem;padding:6px 14px;text-decoration:none;">
            <i class="bi bi-calendar3 me-1"></i> View All Events
          </a>
        </div>
      </div>

      <div class="gallery-grid" id="grid-events">
        <div class="gallery-item span-2 fu" data-category="events"
             data-title="Annual Convocation 2024"
             data-caption="Graduating class of 2024 receiving their degrees"
             data-img="assets/images/news/news1.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/news/news1.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Convocation 2024','bi-mortarboard')"
               class="gallery-img" alt="Convocation"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Events</div><div class="gallery-overlay-text">Annual Convocation 2024</div></div>
          </div>
        </div>

        <div class="gallery-item fu" data-category="events"
             data-title="UMR Annual Research Conference 2025"
             data-caption="Students presenting research papers at the annual UMR conference"
             data-img="assets/images/news/news3.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/news/news3.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('UMR Conference','bi-flask')"
               class="gallery-img" alt="UMR Conference"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Events</div><div class="gallery-overlay-text">UMR Conference 2025</div></div>
          </div>
        </div>

        <div class="gallery-item fu" data-category="events"
             data-title="White Coat Ceremony"
             data-caption="1st year students receiving their white coats"
             data-img="assets/images/news/pmc.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/news/pmc.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('White Coat Ceremony','bi-heart-pulse')"
               class="gallery-img" alt="White Coat Ceremony"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Events</div><div class="gallery-overlay-text">White Coat Ceremony</div></div>
          </div>
        </div>

        <div class="gallery-item fu" data-category="events"
             data-title="Annual Sports Gala 2025"
             data-caption="Students competing in the annual sports festival"
             data-img="assets/images/campus/pmc.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/pmc.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Sports Gala','bi-trophy')"
               class="gallery-img" alt="Sports Gala"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Events</div><div class="gallery-overlay-text">Annual Sports Gala 2025</div></div>
          </div>
        </div>

        <div class="gallery-item span-2 fu" data-category="events"
             data-title="Blood Donation Drive — SWS"
             data-caption="Social Welfare Society blood donation campaign"
             data-img="assets/images/campus/library.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/library.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Blood Donation Drive','bi-droplet-half')"
               class="gallery-img" alt="Blood Donation"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Events</div><div class="gallery-overlay-text">Blood Donation Drive — SWS</div></div>
          </div>
        </div>
      </div>
    </div>

    <!--
    ALBUM 3 — CLINICAL TRAINING
    -->
    <div class="album-section mb-5" data-category="clinical">
      <div class="album-heading">
        <div class="album-heading-icon"><i class="bi bi-hospital"></i></div>
        <div>
          <div class="album-title">Clinical Training</div>
          <span class="album-count">4 Photos</span>
        </div>
      </div>

      <div class="gallery-grid" id="grid-clinical">
        <div class="gallery-item span-2-row fu" data-category="clinical"
             data-title="Kuwait Teaching Hospital"
             data-caption="Students on clinical rotation at Kuwait Teaching Hospital"
             data-img="assets/images/campus/pmc.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/pmc.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Kuwait Hospital','bi-hospital-fill')"
               class="gallery-img" alt="Kuwait Hospital"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Clinical</div><div class="gallery-overlay-text">Kuwait Teaching Hospital</div></div>
          </div>
        </div>

        <div class="gallery-item fu" data-category="clinical"
             data-title="Clinical Skill Labs"
             data-caption="Students practicing in the simulation labs"
             data-img="assets/images/campus/gym.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/gym.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Skill Labs','bi-activity')"
               class="gallery-img" alt="Skill Labs"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Clinical</div><div class="gallery-overlay-text">Clinical Skill Labs</div></div>
          </div>
        </div>

        <div class="gallery-item fu" data-category="clinical"
             data-title="OPD Rotations"
             data-caption="Students observing outpatient department procedures"
             data-img="assets/images/campus/cafe.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/cafe.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('OPD Rotation','bi-clipboard-pulse')"
               class="gallery-img" alt="OPD Rotations"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Clinical</div><div class="gallery-overlay-text">OPD Rotations</div></div>
          </div>
        </div>

        <div class="gallery-item fu" data-category="clinical"
             data-title="Surgery Ward Rounds"
             data-caption="4th year students on surgery ward rounds"
             data-img="assets/images/campus/hostel.jpg"
             onclick="openLightbox(this)">
          <img src="assets/images/campus/hostel.jpg"
               onerror="this.parentElement.innerHTML=galleryPlaceholder('Surgery Rounds','bi-scissors')"
               class="gallery-img" alt="Surgery Rounds"/>
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div><div class="gallery-overlay-cat">Clinical</div><div class="gallery-overlay-text">Surgery Ward Rounds</div></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Load More -->
    <div class="text-center mt-4" id="loadMoreWrap">
      <button class="btn-pmc btn-pmc-outline" id="loadMoreBtn" onclick="loadMore()">
        <i class="bi bi-arrow-down-circle"></i> Load More Photos
      </button>
    </div>

  </div>
</section>

<!-- ═══ LIGHTBOX ═══ -->
<div class="pmc-lightbox" id="pmcLightbox" onclick="closeLightboxOnBackdrop(event)">
  <div class="lb-inner" id="lbInner">
    <button class="lb-close" onclick="closeLightbox()" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    <div class="lb-img-wrap" id="lbImgWrap">
      <!-- content injected by JS -->
    </div>
    <div class="lb-caption" id="lbCaption"></div>
    <div class="lb-counter" id="lbCounter"></div>
  </div>
  <button class="lb-prev" onclick="lbNav(-1)" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
  <button class="lb-next" onclick="lbNav(1)"  aria-label="Next"><i class="bi bi-chevron-right"></i></button>
</div>

<!-- Recognition Strip -->
<section class="pmc-section-sm recog-strip">
  <div class="container">
    <div class="recog-grid">
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-patch-check-fill"></i></div><div class="recog-name">PM&DC</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-building-fill"></i></div><div class="recog-name">Riphah University</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-award-fill"></i></div><div class="recog-name">CPSP</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-hospital-fill"></i></div><div class="recog-name">Ministry of Health</div></div>
      <div class="recog-cell" style="border-right:none;"><div class="recog-ico"><i class="bi bi-globe-americas"></i></div><div class="recog-name">WHO</div></div>
    </div>
  </div>
</section>

<!-- ═══ FOOTER ═══ -->
<?php include("includes/footer.php"); ?>

<button id="backToTop" aria-label="Back to top"><i class="bi bi-chevron-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/pmc-global.js"></script>
<script>
/* ══════════════════════════════════════════════
   GALLERY PAGE SCRIPTS
══════════════════════════════════════════════ */

// ── Placeholder HTML generator ──────────────────────────────────
function galleryPlaceholder(label, icon) {
  return `<div class="gallery-placeholder">
    <i class="bi ${icon || 'bi-image'}"></i>
    <span>${label}</span>
  </div>
  <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
  <div class="gallery-overlay">
    <div><div class="gallery-overlay-text">${label}</div></div>
  </div>`;
}

// ── Filter ───────────────────────────────────────────────────────
const filterBtns   = document.querySelectorAll('.gf-btn[data-filter]');
const albumSections = document.querySelectorAll('.album-section');

filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const filter = btn.dataset.filter;

    albumSections.forEach(section => {
      const cat = section.dataset.category;
      const show = filter === 'all' || cat === filter;
      section.style.display = show ? '' : 'none';
      if (show) section.style.animation = 'none';
    });

    // Scroll to first visible album
    const first = [...albumSections].find(s => s.style.display !== 'none');
    if (first && filter !== 'all') first.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});

// ── Lightbox ─────────────────────────────────────────────────────
let lbItems   = [];
let lbCurrent = 0;

function openLightbox(el) {
  // Collect all gallery items currently visible
  lbItems = [...document.querySelectorAll('.gallery-item[data-img]')].filter(e => {
    return e.closest('.album-section').style.display !== 'none';
  });
  lbCurrent = lbItems.indexOf(el);
  renderLightbox();
  document.getElementById('pmcLightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function openAlbumLightbox(category) {
  lbItems = [...document.querySelectorAll(`.gallery-item[data-category="${category}"][data-img]`)];
  lbCurrent = 0;
  renderLightbox();
  document.getElementById('pmcLightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  document.getElementById('pmcLightbox').classList.remove('open');
  document.body.style.overflow = '';
}

function closeLightboxOnBackdrop(e) {
  if (e.target === document.getElementById('pmcLightbox')) closeLightbox();
}

function lbNav(dir) {
  lbCurrent = ((lbCurrent + dir) + lbItems.length) % lbItems.length;
  renderLightbox();
}

function renderLightbox() {
  const el    = lbItems[lbCurrent];
  const img   = el.dataset.img   || '';
  const title = el.dataset.title || '';
  const cap   = el.dataset.caption || '';
  const cat   = el.dataset.category || '';

  const wrap = document.getElementById('lbImgWrap');
  const isFallback = !img || img.endsWith('/');

  if (img) {
    wrap.innerHTML = `<img src="${img}" class="lb-img" alt="${title}"
      onerror="this.parentElement.innerHTML='<div class=\\'lb-placeholder\\'><i class=\\'bi bi-image\\'></i><span>${title}</span></div>'"
    />`;
  } else {
    wrap.innerHTML = `<div class="lb-placeholder"><i class="bi bi-image"></i><span>${title}</span></div>`;
  }

  document.getElementById('lbCaption').innerHTML = `
    <div class="lb-caption-cat">${cat}</div>
    <div class="lb-caption-title">${title}</div>
    ${cap ? `<div style="font-family:var(--font-body);font-size:.8rem;color:rgba(255,255,255,.5);margin-top:3px;">${cap}</div>` : ''}
  `;
  document.getElementById('lbCounter').textContent = `${lbCurrent + 1} / ${lbItems.length}`;
}

// Keyboard navigation
document.addEventListener('keydown', e => {
  const lb = document.getElementById('pmcLightbox');
  if (!lb.classList.contains('open')) return;
  if (e.key === 'ArrowRight' || e.key === 'ArrowDown') lbNav(1);
  if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   lbNav(-1);
  if (e.key === 'Escape') closeLightbox();
});

// ── Fade-up observer ─────────────────────────────────────────────
const fuEls = document.querySelectorAll('.fu');
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
}, { threshold: 0.08 });
fuEls.forEach(el => obs.observe(el));

// ── Load More (placeholder) ─────────────────────────────────────
function loadMore() {
  document.getElementById('loadMoreBtn').innerHTML = '<i class="bi bi-check-circle"></i> All Photos Loaded';
  document.getElementById('loadMoreBtn').disabled = true;
}

// ── Back to top ──────────────────────────────────────────────────
const btt = document.getElementById('backToTop');
window.addEventListener('scroll', () => btt.classList.toggle('visible', scrollY > 500));
btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

// ── Navbar scroll ────────────────────────────────────────────────
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 40));
</script>