<?php include('includes/header.php'); ?>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* ══ ROOT VARIABLES (from your global styles – define colours & fonts) ══ */
    :root {
      --navy: #0A1628;
      --navy-mid: #122040;
      --teal: #00A896;
      --teal-pale: #E6F7F4;
      --gold: #C9A84C;
      --gold-light: #e0c068;
      --gray-dark: #2D3748;
      --gray-mid: #718096;
      --gray-light: #F7FAFC;
      --off-white: #FAFBFC;
      --border: #E2E8F0;
      --font-head: 'Inter', system-ui, -apple-system, sans-serif;
      --font-body: 'Inter', system-ui, -apple-system, sans-serif;
      --shadow-lg: 0 8px 30px rgba(0,0,0,0.08);
      --r-lg: 16px;
      --r-md: 12px;
      --r-sm: 8px;
    }

    /* ══ BASIC RESET ══ */
    body {
      font-family: var(--font-body);
      color: var(--gray-dark);
      background: #fff;
      margin: 0;
    }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

    /* ══ PAGE HERO ══ */
    .page-hero {
      background: linear-gradient(135deg, var(--navy), #00695C);
      padding: 60px 0;
      position: relative;
      overflow: hidden;
      color: white;
    }
    .page-hero-grid {
      position: absolute; inset: 0;
      background: repeating-linear-gradient(45deg, rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 2px, transparent 2px, transparent 12px);
    }
    .page-hero-content h1 {
      font-family: var(--font-head);
      font-size: 2.2rem;
      font-weight: 900;
      margin-bottom: 10px;
    }
    .breadcrumb-pmc {
      font-size: 0.85rem;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      align-items: center;
    }
    .breadcrumb-pmc a {
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      font-weight: 600;
    }
    .breadcrumb-pmc .sep {
      color: rgba(255,255,255,0.5);
    }
    .breadcrumb-pmc .current {
      color: #fff;
      font-weight: 700;
    }

    /* ══ MAIN SECTION ══ */
    .pmc-section {
      padding: 60px 0;
    }
    .bg-off {
      background: var(--off-white);
    }

    /* ══ BUTTONS (simplified) ══ */
    .btn-pmc {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-family: var(--font-head);
      font-weight: 700;
      font-size: 0.82rem;
      padding: 11px 20px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: 0.2s;
    }
    .btn-pmc-primary {
      background: var(--teal);
      color: white;
    }
    .btn-pmc-primary:hover {
      background: #008f7a;
    }
    .btn-pmc-outline {
      border: 1.5px solid var(--border);
      background: white;
      color: var(--navy);
    }
    .btn-pmc-outline:hover {
      border-color: var(--teal);
      color: var(--teal);
    }
    .btn-pmc-navy {
      background: var(--navy);
      color: white;
    }
    .btn-pmc-navy:hover {
      background: #0d1f3c;
    }

    /* ══ HERO IMAGE ══ */
    .event-hero-img {
      width: 100%;
      height: 460px;
      background: linear-gradient(135deg, var(--navy) 0%, #00695C 100%);
      background-image: var(--bg-img, none);
      background-size: cover;
      background-position: center;
      position: relative;
      border-radius: var(--r-lg);
      overflow: hidden;
      margin-bottom: 36px;
    }
    .event-hero-img::after {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(to bottom, rgba(10,22,40,.15) 0%, rgba(10,22,40,.6) 100%);
    }
    .event-hero-placeholder {
      width: 100%; height: 460px;
      background: linear-gradient(135deg, var(--navy), #00695C);
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 16px; border-radius: var(--r-lg);
      margin-bottom: 36px;
    }
    .event-hero-placeholder i    { font-size: 4rem; color: rgba(255,255,255,.3); }
    .event-hero-placeholder span { font-family: var(--font-head); font-size: .85rem; color: rgba(255,255,255,.3); text-transform: uppercase; letter-spacing: .1em; }

    .photo-credit {
      position: absolute; bottom: 12px; right: 14px;
      font-family: var(--font-body); font-size: .68rem;
      color: rgba(255,255,255,.5); z-index: 2;
    }

    /* ══ EVENT META BAR ══ */
    .event-meta-bar {
      display: flex;
      align-items: center;
      gap: 0;
      background: white;
      border: 1px solid var(--border);
      border-radius: var(--r-md);
      overflow: hidden;
      margin-bottom: 32px;
      flex-wrap: wrap;
    }
    .emb-item {
      flex: 1 1 160px;
      padding: 18px 22px;
      border-right: 1px solid var(--border);
      display: flex; align-items: flex-start; gap: 12px;
    }
    .emb-item:last-child { border-right: none; }
    .emb-icon {
      width: 38px; height: 38px; flex-shrink: 0;
      background: var(--teal-pale);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      color: var(--teal); font-size: 1rem;
    }
    .emb-label {
      font-family: var(--font-head); font-size: .65rem; font-weight: 800;
      text-transform: uppercase; letter-spacing: .1em; color: var(--gray-mid);
      display: block; margin-bottom: 3px;
    }
    .emb-value {
      font-family: var(--font-body); font-size: .87rem; font-weight: 600;
      color: var(--navy);
    }

    /* ══ ARTICLE CONTENT ══ */
    .article-header { margin-bottom: 28px; }
    .article-cat-badge {
      display: inline-block;
      font-family: var(--font-head); font-size: .68rem; font-weight: 800;
      text-transform: uppercase; letter-spacing: .1em;
      color: white; background: var(--teal);
      padding: 4px 12px; border-radius: 100px;
      margin-bottom: 14px;
    }
    .article-title {
      font-family: var(--font-head);
      font-size: clamp(1.6rem, 3.5vw, 2.3rem);
      font-weight: 900; color: var(--navy);
      line-height: 1.18; margin-bottom: 18px;
    }
    .article-byline {
      display: flex; align-items: center; gap: 18px;
      flex-wrap: wrap; margin-bottom: 0;
    }
    .byline-item {
      display: flex; align-items: center; gap: 6px;
      font-family: var(--font-body); font-size: .82rem; font-weight: 600;
      color: var(--gray-mid);
    }
    .byline-item i { color: var(--teal); font-size: .9rem; }
    .byline-divider { width: 4px; height: 4px; border-radius: 50%; background: var(--border); }

    .share-strip {
      display: flex; align-items: center; gap: 12px;
      padding: 14px 0;
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      margin: 24px 0 32px;
      flex-wrap: wrap;
    }
    .share-label {
      font-family: var(--font-head); font-size: .72rem; font-weight: 800;
      text-transform: uppercase; letter-spacing: .1em; color: var(--gray-mid);
      margin-right: 4px;
    }
    .share-btn {
      width: 36px; height: 36px;
      border-radius: 8px; border: 1.5px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      color: var(--gray-dark); font-size: .9rem; text-decoration: none;
      transition: all .2s;
    }
    .share-btn:hover { color: white; border-color: transparent; }
    .share-btn.fb:hover  { background: #1877F2; border-color: #1877F2; }
    .share-btn.tw:hover  { background: #000; border-color: #000; }
    .share-btn.wa:hover  { background: #25D366; border-color: #25D366; }
    .share-btn.li:hover  { background: #0A66C2; border-color: #0A66C2; }
    .share-btn.cp:hover  { background: var(--teal); border-color: var(--teal); }

    .article-body {
      font-family: var(--font-body);
      font-size: 1rem;
      color: var(--gray-dark);
      line-height: 1.88;
      font-weight: 500;
    }
    .article-body p { margin-bottom: 20px; }
    .article-body h3 {
      font-family: var(--font-head); font-size: 1.25rem; font-weight: 800;
      color: var(--navy); margin: 36px 0 14px;
    }
    .article-body h4 {
      font-family: var(--font-head); font-size: 1.05rem; font-weight: 700;
      color: var(--navy); margin: 28px 0 10px;
    }
    .article-body ul, .article-body ol {
      padding-left: 0; list-style: none; margin-bottom: 20px;
    }
    .article-body ul li, .article-body ol li {
      padding-left: 22px; position: relative; margin-bottom: 9px;
    }
    .article-body ul li::before {
      content: '›'; position: absolute; left: 0;
      color: var(--teal); font-weight: 700; font-size: 1.1rem;
    }
    .article-body ol { counter-reset: ol-counter; }
    .article-body ol li { counter-increment: ol-counter; }
    .article-body ol li::before {
      content: counter(ol-counter) '.';
      position: absolute; left: 0;
      color: var(--teal); font-weight: 800; font-size: .85rem;
    }
    .article-body blockquote {
      border-left: 4px solid var(--teal);
      background: var(--teal-pale);
      padding: 20px 24px;
      border-radius: 0 var(--r-sm) var(--r-sm) 0;
      margin: 28px 0;
    }
    .article-body blockquote p {
      font-style: italic; color: var(--navy);
      font-size: 1.05rem; font-weight: 600; margin: 0;
    }
    .article-body blockquote cite {
      display: block; margin-top: 8px;
      font-family: var(--font-head); font-size: .75rem; font-weight: 700;
      color: var(--teal); text-transform: uppercase; letter-spacing: .07em;
      font-style: normal;
    }

    .inline-gallery {
      display: grid;
      grid-template-columns: repeat(3,1fr);
      gap: 10px;
      margin: 28px 0;
      border-radius: var(--r-md);
      overflow: hidden;
    }
    .ig-item {
      aspect-ratio: 4/3;
      background: var(--gray-light);
      border-radius: var(--r-sm);
      overflow: hidden; cursor: pointer;
      position: relative;
    }
    .ig-item img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform .35s;
    }
    .ig-item:hover img { transform: scale(1.06); }
    .ig-item-placeholder {
      width: 100%; height: 100%;
      background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; align-items: center; justify-content: center;
      flex-direction: column; gap: 8px;
    }
    .ig-item-placeholder i    { font-size: 1.5rem; color: rgba(255,255,255,.35); }
    .ig-item-placeholder span { font-family: var(--font-head); font-size: .62rem; color: rgba(255,255,255,.25); }
    .ig-zoom {
      position: absolute; inset: 0;
      display: flex; align-items: center; justify-content: center;
      background: rgba(0,168,150,.5);
      opacity: 0; transition: opacity .25s; color: white; font-size: 1.2rem;
    }
    .ig-item:hover .ig-zoom { opacity: 1; }

    .info-box {
      background: var(--teal-pale);
      border: 1px solid rgba(0,168,150,.22);
      border-radius: var(--r-md);
      padding: 22px 24px;
      margin: 28px 0;
    }
    .info-box-title {
      font-family: var(--font-head); font-size: .82rem; font-weight: 800;
      color: var(--teal); text-transform: uppercase; letter-spacing: .08em;
      margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    }
    .info-box-title i { font-size: 1rem; }

    .article-tags {
      display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
      padding: 20px 0; border-top: 1px solid var(--border); margin-top: 36px;
    }
    .tag-label {
      font-family: var(--font-head); font-size: .7rem; font-weight: 800;
      text-transform: uppercase; letter-spacing: .1em; color: var(--gray-mid);
      margin-right: 4px;
    }
    .article-tag {
      font-family: var(--font-body); font-size: .75rem; font-weight: 700;
      background: var(--gray-light); color: var(--gray-dark);
      padding: 4px 12px; border-radius: 100px; text-decoration: none;
      transition: all .2s;
    }
    .article-tag:hover { background: var(--teal-pale); color: var(--teal); }

    .author-box {
      background: var(--off-white);
      border: 1px solid var(--border);
      border-radius: var(--r-lg);
      padding: 28px;
      display: flex; gap: 20px; align-items: flex-start;
      margin-top: 36px;
    }
    .author-avatar {
      width: 68px; height: 68px; flex-shrink: 0;
      background: var(--navy);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      color: var(--gold); font-size: 1.6rem;
    }
    .author-name  { font-family: var(--font-head); font-size: .95rem; font-weight: 800; color: var(--navy); margin-bottom: 2px; }
    .author-role  { font-family: var(--font-body); font-size: .75rem; font-weight: 600; color: var(--teal); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 8px; }
    .author-bio   { font-family: var(--font-body); font-size: .85rem; color: var(--gray-mid); line-height: 1.6; }

    .related-card {
      background: white; border: 1px solid var(--border);
      border-radius: var(--r-md); overflow: hidden;
      text-decoration: none; color: inherit;
      display: flex; flex-direction: column; height: 100%;
      transition: transform .25s, box-shadow .25s, border-color .25s;
    }
    .related-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: var(--teal); color: inherit; }
    .rc-img {
      height: 150px;
      background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.4); font-size: 2rem; flex-shrink: 0;
    }
    .rc-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
    .rc-cat  { font-family: var(--font-head); font-size: .62rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--teal); margin-bottom: 6px; }
    .rc-title { font-family: var(--font-head); font-size: .86rem; font-weight: 700; color: var(--navy); line-height: 1.38; flex: 1; }
    .rc-date  { font-family: var(--font-body); font-size: .72rem; color: var(--gray-mid); margin-top: 10px; display: flex; align-items: center; gap: 5px; }
    .rc-date i { color: var(--teal); font-size: .78rem; }

    .toc-widget {
      background: white; border: 1px solid var(--border);
      border-radius: var(--r-md); overflow: hidden; margin-bottom: 24px;
    }
    .toc-head {
      background: var(--navy); padding: 13px 18px;
      font-family: var(--font-head); font-size: .72rem; font-weight: 800;
      color: white; text-transform: uppercase; letter-spacing: .08em;
      display: flex; align-items: center; gap: 8px;
    }
    .toc-head i { color: var(--gold); }
    .toc-body { padding: 10px 0; }
    .toc-link {
      display: flex; align-items: center; gap: 9px;
      padding: 9px 18px; font-family: var(--font-body);
      font-size: .84rem; font-weight: 600; color: var(--navy);
      text-decoration: none; border-left: 3px solid transparent;
      transition: all .2s;
    }
    .toc-link:hover, .toc-link.active {
      background: var(--teal-pale); color: var(--teal);
      border-left-color: var(--teal);
    }
    .toc-link i { color: var(--teal); font-size: .78rem; flex-shrink: 0; }
    .toc-num {
      font-family: var(--font-head); font-size: .65rem; font-weight: 800;
      color: rgba(255,255,255,.7); background: var(--teal);
      width: 20px; height: 20px; border-radius: 5px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0; margin-left: auto;
    }

    .sidebar-widget {
      background: white; border: 1px solid var(--border);
      border-radius: var(--r-md); overflow: hidden;
    }
    .sw-head {
      background: var(--navy); padding: 12px 18px;
      font-family: var(--font-head); font-size: .75rem; font-weight: 800;
      color: white; text-transform: uppercase; letter-spacing: .08em;
      display: flex; align-items: center; gap: 8px;
    }
    .sw-body { padding: 16px; }
    .foot-contact {
      display: flex; align-items: center; gap: 8px;
    }

    .recog-strip {
      background: var(--off-white);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 20px 0;
    }
    .recog-grid {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
    }
    .recog-cell {
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: var(--font-head);
      font-size: 0.85rem;
      font-weight: 700;
      color: var(--gray-mid);
      text-transform: uppercase;
    }
    .recog-ico {
      font-size: 1.2rem;
      color: var(--teal);
    }

    .reading-progress {
      position: fixed; top: 0; left: 0; right: 0;
      height: 3px; z-index: 9999;
      background: var(--border);
    }
    .reading-progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--teal), var(--gold));
      width: 0%; transition: width .1s linear;
    }

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

    @media (max-width: 991.98px) {
      .event-meta-bar { flex-direction: column; }
      .emb-item { border-right: none; border-bottom: 1px solid var(--border); }
      .emb-item:last-child { border-bottom: none; }
    }
    @media (max-width: 767.98px) {
      .event-hero-img, .event-hero-placeholder { height: 260px; }
      .inline-gallery { grid-template-columns: 1fr 1fr; }
      .author-box { flex-direction: column; }
    }
    @media (max-width: 480px) {
      .inline-gallery { grid-template-columns: 1fr; }
    }
  </style>

<!-- Reading progress bar (now exists) -->
<div class="reading-progress">
  <div class="reading-progress-fill" id="readingBar"></div>
</div>

<!-- ═══ PAGE HERO ═══ -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Events &amp; News</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="events.html">Events</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">UMR Annual Research Conference 2025</span>
    </div>
  </div>
</div>

<!-- ═══ ARTICLE ═══ -->
<section class="pmc-section bg-off">
  <div class="container">
    <div class="row g-5">

      <!-- ── ARTICLE MAIN ─────────────────────────────────────── -->
      <div class="col-lg-8">

        <!-- Article Header -->
        <div class="article-header fu">
          <div class="article-cat-badge">Research</div>
          <h1 class="article-title">
            UMR Society Annual Medical Research Conference 2025 — Celebrating Student Innovation at PMC
          </h1>
          <div class="article-byline">
            <span class="byline-item"><i class="bi bi-person-circle"></i> PMC Communications</span>
            <div class="byline-divider"></div>
            <span class="byline-item"><i class="bi bi-calendar3"></i> April 22, 2025</span>
            <div class="byline-divider"></div>
            <span class="byline-item"><i class="bi bi-clock"></i> 5 min read</span>
            <div class="byline-divider"></div>
            <span class="byline-item"><i class="bi bi-eye"></i> 1,240 views</span>
          </div>
        </div>

        <!-- Share Strip -->
        <div class="share-strip fu">
          <span class="share-label">Share</span>
          <a href="#" class="share-btn fb" title="Share on Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="share-btn tw" title="Share on X/Twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="share-btn wa" title="Share on WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a href="#" class="share-btn li" title="Share on LinkedIn"><i class="bi bi-linkedin"></i></a>
          <button class="share-btn cp" title="Copy Link" onclick="copyLink(this)"><i class="bi bi-link-45deg"></i></button>
          <div class="ms-auto">
            <span class="byline-item" style="font-size:.78rem;"><i class="bi bi-bookmark-plus" style="color:var(--teal);"></i> Save</span>
          </div>
        </div>

        <!-- Hero Image Placeholder -->
        <div class="event-hero-placeholder fu">
          <i class="bi bi-flask-fill"></i>
          <span>UMR Conference 2025 — PMC Auditorium</span>
        </div>

        <!-- Event Meta Bar -->
        <div class="event-meta-bar fu">
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-calendar-event"></i></div>
            <div>
              <span class="emb-label">Date</span>
              <span class="emb-value">April 22, 2025 — Tuesday</span>
            </div>
          </div>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-clock"></i></div>
            <div>
              <span class="emb-label">Time</span>
              <span class="emb-value">9:00 AM – 4:00 PM</span>
            </div>
          </div>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-geo-alt"></i></div>
            <div>
              <span class="emb-label">Venue</span>
              <span class="emb-value">PMC Main Auditorium, Warsak Road</span>
            </div>
          </div>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-people"></i></div>
            <div>
              <span class="emb-label">Attendance</span>
              <span class="emb-value">350+ Students &amp; Faculty</span>
            </div>
          </div>
        </div>

        <!-- Article Body -->
        <div class="article-body fu" id="articleBody">

          <p>Peshawar Medical College's <strong>Undergraduate Medical Research (UMR) Society</strong> successfully organised its Annual Medical Research Conference 2025 on April 22 at the PMC Main Auditorium — a landmark event that once again demonstrated the vibrant research culture nurtured within the college since its establishment in 2005.</p>

          <p>The conference brought together over <strong>350 students and faculty members</strong>, with research presentations spanning all five years of the MBBS program. From anatomy correlates to clinical case studies, the quality and depth of student research at PMC continues to set the benchmark for private medical colleges across Khyber Pakhtunkhwa.</p>

          <h3 id="keynote">Keynote Address</h3>

          <blockquote>
            <p>"Research is the backbone of evidence-based medicine. PMC has always believed that medical students who learn to think critically, question, and investigate will become better doctors — not just better researchers."</p>
            <cite>— Principal, Peshawar Medical College</cite>
          </blockquote>

          <p>The Principal of PMC delivered the opening keynote, emphasising the college's longstanding commitment to research as a core graduate attribute. Students at PMC are expected to produce <strong>at least five research papers during their five-year MBBS program</strong> — an expectation that has produced measurable results at both national and international conferences.</p>

          <h3 id="presentations">Research Presentations</h3>

          <p>This year's conference featured presentations across five tracks:</p>

          <ul>
            <li><strong>Basic Sciences Track</strong> — Anatomy, Physiology, Biochemistry correlates and laboratory findings</li>
            <li><strong>Clinical Sciences Track</strong> — Case reports, clinical audits, and outcomes research</li>
            <li><strong>Community Medicine Track</strong> — Public health, epidemiology, and preventive medicine studies</li>
            <li><strong>Surgical Sciences Track</strong> — Surgical techniques, outcomes, and perioperative care</li>
            <li><strong>Medical Education Track</strong> — Innovations in teaching, curriculum design, and assessment</li>
          </ul>

          <!-- Inline Gallery -->
          <div class="inline-gallery">
            <div class="ig-item">
              <div class="ig-item-placeholder">
                <i class="bi bi-mic-fill"></i>
                <span>Opening Ceremony</span>
              </div>
              <div class="ig-zoom"><i class="bi bi-zoom-in"></i></div>
            </div>
            <div class="ig-item">
              <div class="ig-item-placeholder">
                <i class="bi bi-people-fill"></i>
                <span>Student Presentations</span>
              </div>
              <div class="ig-zoom"><i class="bi bi-zoom-in"></i></div>
            </div>
            <div class="ig-item">
              <div class="ig-item-placeholder">
                <i class="bi bi-award-fill"></i>
                <span>Award Ceremony</span>
              </div>
              <div class="ig-zoom"><i class="bi bi-zoom-in"></i></div>
            </div>
          </div>
          <p style="font-family:var(--font-body);font-size:.78rem;color:var(--gray-mid);text-align:center;margin-top:-14px;margin-bottom:28px;">
            <i class="bi bi-camera me-1"></i> UMR Conference 2025 — PMC Auditorium, Warsak Road, Peshawar
          </p>

          <h3 id="winners">Award Winners</h3>

          <p>Following a rigorous judging process by a panel of senior faculty and external experts, the following students were recognised for exceptional research:</p>

          <div class="info-box">
            <div class="info-box-title"><i class="bi bi-trophy-fill"></i> Best Research Awards 2025</div>
            <ol style="font-family:var(--font-body);font-size:.9rem;color:var(--gray-dark);line-height:1.8;padding-left:0;list-style:none;margin:0;">
              <li style="padding-left:22px;position:relative;margin-bottom:10px;">
                <span style="position:absolute;left:0;color:var(--gold);font-weight:900;font-family:var(--font-head);">1st</span>
                <strong>Best Paper — Clinical Sciences:</strong> "Outcomes of Early Mobilisation Post-Orthopaedic Surgery at Prime Teaching Hospital" — 4th Year Student Team
              </li>
              <li style="padding-left:22px;position:relative;margin-bottom:10px;">
                <span style="position:absolute;left:0;color:var(--gray-mid);font-weight:900;font-family:var(--font-head);">2nd</span>
                <strong>Best Paper — Community Medicine:</strong> "Prevalence of Hypertension in Semi-Urban Peshawar" — 3rd Year Student
              </li>
              <li style="padding-left:22px;position:relative;margin-bottom:10px;">
                <span style="position:absolute;left:0;color:var(--teal);font-weight:900;font-family:var(--font-head);">3rd</span>
                <strong>Best Paper — Basic Sciences:</strong> "Anatomical Variations of Celiac Artery: A Cadaveric Study" — 2nd Year Student Pair
              </li>
              <li style="padding-left:22px;position:relative;">
                <span style="position:absolute;left:0;color:var(--teal);font-weight:700;font-family:var(--font-head);">★</span>
                <strong>Best Poster Award:</strong> "Knowledge, Attitude &amp; Practice of Blood Donation Among MBBS Students" — 1st Year Team
              </li>
            </ol>
          </div>

          <h3 id="sponsorship">College Sponsorship</h3>

          <p>In keeping with its long-standing policy, <strong>PMC sponsored all expenses</strong> for student researchers whose papers were accepted for presentation at this conference, including registration fees, travel allowances for external delegates, and printing of conference proceedings.</p>

          <p>Students whose research is accepted at national or international conferences outside PMC are also eligible for full expense coverage — a policy that has resulted in PMC students presenting at medical forums across Pakistan, including CPSP Annual Conferences, PMRC Scientific Events, and medical student symposia in Islamabad, Lahore, and Karachi.</p>

          <h4 id="looking-ahead">Looking Ahead</h4>

          <p>The UMR Society has announced that the <strong>UMR Annual Conference 2026</strong> will be expanded to include inter-collegiate participation, inviting students from medical colleges across KP to present their research at PMC — a further step in establishing PMC as the hub of undergraduate medical research in the province.</p>

          <p>For more information about the UMR Society, research mentorship, or submitting your abstract for next year's conference, please visit the <a href="umr.php" style="color:var(--teal);font-weight:700;">UMR Society page</a> or contact the UMR Coordinator at the college.</p>

          <!-- Tags -->
          <div class="article-tags">
            <span class="tag-label">Tags:</span>
            <a class="article-tag" href="events.html">Research</a>
            <a class="article-tag" href="events.html">UMR Society</a>
            <a class="article-tag" href="events.html">Conference</a>
            <a class="article-tag" href="events.html">MBBS Students</a>
            <a class="article-tag" href="events.html">PMC Events</a>
          </div>

        </div><!-- /.article-body -->

        <!-- Author Box -->
        <div class="author-box fu">
          <div class="author-avatar"><i class="bi bi-building"></i></div>
          <div>
            <div class="author-name">PMC Communications Office</div>
            <div class="author-role">Peshawar Medical College · Official</div>
            <div class="author-bio">Official communications and news from the administration of Peshawar Medical College, Warsak Road, Peshawar. For queries, contact <a href="mailto:info@prime.edu.pk" style="color:var(--teal);">info@prime.edu.pk</a></div>
          </div>
        </div>

        <!-- Related Events -->
        <div class="mt-5 fu">
          <h3 style="font-family:var(--font-head);font-size:1.15rem;font-weight:800;color:var(--navy);margin-bottom:22px;padding-bottom:12px;border-bottom:2px solid var(--border);">
            <i class="bi bi-grid-3x3-gap me-2" style="color:var(--teal);"></i> Related Events
          </h3>
          <div class="row g-3">

            <div class="col-md-4">
              <a class="related-card" href="event-single.html">
                <div class="rc-img" style="background:linear-gradient(135deg,#0A1628,#122040);">
                  <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="rc-body">
                  <div class="rc-cat">Admissions</div>
                  <div class="rc-title">MBBS Admissions Open for Session 2026-27</div>
                  <div class="rc-date"><i class="bi bi-calendar3"></i> June 15, 2025</div>
                </div>
              </a>
            </div>

            <div class="col-md-4">
              <a class="related-card" href="event-single.html">
                <div class="rc-img" style="background:linear-gradient(135deg,#C9A84C,#e0c068);">
                  <i class="bi bi-award-fill"></i>
                </div>
                <div class="rc-body">
                  <div class="rc-cat">Achievement</div>
                  <div class="rc-title">PMC Retains #1 Ranking Among Private Medical Colleges in KP</div>
                  <div class="rc-date"><i class="bi bi-calendar3"></i> May 15, 2025</div>
                </div>
              </a>
            </div>

            <div class="col-md-4">
              <a class="related-card" href="event-single.html">
                <div class="rc-img" style="background:linear-gradient(135deg,#6A1B9A,#7B1FA2);">
                  <i class="bi bi-trophy-fill"></i>
                </div>
                <div class="rc-body">
                  <div class="rc-cat">Society</div>
                  <div class="rc-title">Annual Sports Gala 2025 — PMC Sports Society</div>
                  <div class="rc-date"><i class="bi bi-calendar3"></i> March 10, 2025</div>
                </div>
              </a>
            </div>

          </div>
        </div>

        <!-- Prev / Next Navigation -->
        <div class="d-flex justify-content-between gap-3 mt-5 fu">
          <a href="events.html" class="btn-pmc btn-pmc-outline" style="font-size:.82rem;padding:10px 18px;">
            <i class="bi bi-chevron-left"></i> Back to Events
          </a>
          <a href="event-single.html" class="btn-pmc btn-pmc-primary" style="font-size:.82rem;padding:10px 18px;">
            Next Event <i class="bi bi-chevron-right"></i>
          </a>
        </div>

      </div><!-- /.col-lg-8 -->

      <!-- ── SIDEBAR ────────────────────────────────────────────── -->
      <div class="col-lg-4">

        <!-- Table of Contents -->
        <div class="toc-widget fu" style="position:sticky;top:90px;">
          <div class="toc-head"><i class="bi bi-list-ul"></i> In This Article</div>
          <div class="toc-body">
            <a class="toc-link active" href="#keynote"><i class="bi bi-chevron-right"></i>Keynote Address<span class="toc-num">1</span></a>
            <a class="toc-link" href="#presentations"><i class="bi bi-chevron-right"></i>Research Presentations<span class="toc-num">2</span></a>
            <a class="toc-link" href="#winners"><i class="bi bi-chevron-right"></i>Award Winners<span class="toc-num">3</span></a>
            <a class="toc-link" href="#sponsorship"><i class="bi bi-chevron-right"></i>College Sponsorship<span class="toc-num">4</span></a>
            <a class="toc-link" href="#looking-ahead"><i class="bi bi-chevron-right"></i>Looking Ahead<span class="toc-num">5</span></a>
          </div>
        </div>

        <!-- Event Details Widget -->
        <div class="sidebar-widget mt-4 fu">
          <div class="sw-head"><i class="bi bi-calendar-check-fill"></i> Event Details</div>
          <div class="sw-body" style="padding:16px 20px;">
            <div class="foot-contact" style="margin-bottom:12px;"><i class="bi bi-calendar3" style="color:var(--teal);"></i><span style="font-family:var(--font-body);font-size:.84rem;color:var(--gray-dark);">April 22, 2025 (Tuesday)</span></div>
            <div class="foot-contact" style="margin-bottom:12px;"><i class="bi bi-clock" style="color:var(--teal);"></i><span style="font-family:var(--font-body);font-size:.84rem;color:var(--gray-dark);">9:00 AM – 4:00 PM</span></div>
            <div class="foot-contact" style="margin-bottom:12px;"><i class="bi bi-geo-alt-fill" style="color:var(--teal);"></i><span style="font-family:var(--font-body);font-size:.84rem;color:var(--gray-dark);">PMC Main Auditorium, Warsak Road, Peshawar</span></div>
            <div class="foot-contact" style="margin-bottom:12px;"><i class="bi bi-people-fill" style="color:var(--teal);"></i><span style="font-family:var(--font-body);font-size:.84rem;color:var(--gray-dark);">350+ Attendees</span></div>
            <div class="foot-contact"><i class="bi bi-tag-fill" style="color:var(--teal);"></i><span style="font-family:var(--font-body);font-size:.84rem;color:var(--gray-dark);">Research · UMR Society · Annual Event</span></div>
          </div>
        </div>

        <!-- Upcoming Events Widget -->
        <div class="sidebar-widget mt-4 fu">
          <div class="sw-head"><i class="bi bi-calendar-event"></i> Upcoming Events</div>
          <div class="sw-body" style="padding:14px 16px;">

            <a class="upcoming-card" href="event-single.html" style="display:flex;gap:0;border:1px solid var(--border);border-radius:10px;overflow:hidden;text-decoration:none;color:inherit;margin-bottom:10px;transition:border-color .2s;">
              <div style="background:var(--navy);padding:12px 10px;text-align:center;min-width:52px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <span style="font-family:var(--font-head);font-size:1.3rem;font-weight:900;color:var(--gold-light);line-height:1;">25</span>
                <span style="font-family:var(--font-body);font-size:.58rem;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.06em;">Jul</span>
              </div>
              <div style="padding:10px 13px;flex:1;">
                <div style="font-family:var(--font-head);font-size:.8rem;font-weight:700;color:var(--navy);line-height:1.3;">MDCAT Registration Deadline</div>
                <div style="font-family:var(--font-body);font-size:.72rem;color:var(--gray-mid);margin-top:3px;"><i class="bi bi-geo-alt-fill" style="color:var(--teal);"></i> PMC Admissions</div>
              </div>
            </a>

            <a class="upcoming-card" href="event-single.html" style="display:flex;gap:0;border:1px solid var(--border);border-radius:10px;overflow:hidden;text-decoration:none;color:inherit;margin-bottom:10px;transition:border-color .2s;">
              <div style="background:var(--navy);padding:12px 10px;text-align:center;min-width:52px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <span style="font-family:var(--font-head);font-size:1.3rem;font-weight:900;color:var(--gold-light);line-height:1;">10</span>
                <span style="font-family:var(--font-body);font-size:.58rem;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.06em;">Aug</span>
              </div>
              <div style="padding:10px 13px;flex:1;">
                <div style="font-family:var(--font-head);font-size:.8rem;font-weight:700;color:var(--navy);line-height:1.3;">New Student Orientation 2026-27</div>
                <div style="font-family:var(--font-body);font-size:.72rem;color:var(--gray-mid);margin-top:3px;"><i class="bi bi-geo-alt-fill" style="color:var(--teal);"></i> PMC Auditorium</div>
              </div>
            </a>

            <a class="upcoming-card" href="event-single.html" style="display:flex;gap:0;border:1px solid var(--border);border-radius:10px;overflow:hidden;text-decoration:none;color:inherit;transition:border-color .2s;">
              <div style="background:var(--navy);padding:12px 10px;text-align:center;min-width:52px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <span style="font-family:var(--font-head);font-size:1.3rem;font-weight:900;color:var(--gold-light);line-height:1;">02</span>
                <span style="font-family:var(--font-body);font-size:.58rem;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.06em;">Sep</span>
              </div>
              <div style="padding:10px 13px;flex:1;">
                <div style="font-family:var(--font-head);font-size:.8rem;font-weight:700;color:var(--navy);line-height:1.3;">SWS Free Medical Camp</div>
                <div style="font-family:var(--font-body);font-size:.72rem;color:var(--gray-mid);margin-top:3px;"><i class="bi bi-geo-alt-fill" style="color:var(--teal);"></i> Warsak Colony</div>
              </div>
            </a>

            <a href="events.html" class="btn-pmc btn-pmc-outline w-100 justify-content-center mt-3" style="font-size:.8rem;padding:9px;">
              <i class="bi bi-calendar3"></i> View All Events
            </a>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="sidebar-widget mt-4 fu">
          <div class="sw-head" style="background:var(--teal);"><i class="bi bi-mortarboard-fill"></i> Quick Actions</div>
          <div class="sw-body" style="padding:16px 20px;">
            <a href="admissions.php" class="btn-pmc btn-pmc-primary w-100 justify-content-center mb-2" style="font-size:.82rem;padding:11px;">Apply for MBBS 2026-27</a>
            <a href="vacant-seats.php" class="btn-pmc btn-pmc-outline w-100 justify-content-center mb-2" style="font-size:.82rem;padding:11px;">Check Vacant Seats</a>
            <a href="gallery.html" class="btn-pmc btn-pmc-navy w-100 justify-content-center" style="font-size:.82rem;padding:11px;"><i class="bi bi-images me-1"></i>Photo Gallery</a>
          </div>
        </div>

      </div><!-- /.col-lg-4 -->

    </div><!-- /.row -->
  </div>
</section>

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

<button id="backToTop" aria-label="Back to top"><i class="bi bi-chevron-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  /* ══════════════════════════════════════════════
     SINGLE EVENT PAGE SCRIPTS
  ══════════════════════════════════════════════ */

  // ── Reading progress bar ──────────────────────────────────────────
  function updateProgress() {
    const article  = document.getElementById('articleBody');
    if (!article) return;
    const scrollTop   = window.scrollY;
    const artTop      = article.offsetTop;
    const artHeight   = article.offsetHeight;
    const winHeight   = window.innerHeight;
    const progress    = Math.min(100, Math.max(0,
      ((scrollTop - artTop + winHeight * 0.5) / artHeight) * 100
    ));
    const bar = document.getElementById('readingBar');
    if (bar) bar.style.width = progress + '%';
  }
  window.addEventListener('scroll', updateProgress, { passive: true });

  // ── TOC active state on scroll ────────────────────────────────────
  const tocLinks   = document.querySelectorAll('.toc-link');
  const sections   = [...document.querySelectorAll('.article-body h3[id], .article-body h4[id]')];

  function updateTOC() {
    let current = '';
    sections.forEach(sec => {
      if (window.scrollY >= sec.offsetTop - 120) current = sec.id;
    });
    tocLinks.forEach(link => {
      const href = link.getAttribute('href');
      link.classList.toggle('active', href === '#' + current);
    });
  }
  window.addEventListener('scroll', updateTOC, { passive: true });

  // ── Copy link ────────────────────────────────────────────────────
  function copyLink(btn) {
    navigator.clipboard.writeText(window.location.href).then(() => {
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-check-lg"></i>';
      btn.style.background = 'var(--teal)';
      btn.style.borderColor = 'var(--teal)';
      btn.style.color = 'white';
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.style.background = '';
        btn.style.borderColor = '';
        btn.style.color = '';
      }, 2000);
    });
  }

  // ── Smooth scroll for TOC links ───────────────────────────────────
  tocLinks.forEach(link => {
    link.addEventListener('click', e => {
      const href = link.getAttribute('href');
      if (href.startsWith('#')) {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          window.scrollTo({ top: target.offsetTop - 100, behavior: 'smooth' });
        }
      }
    });
  });

  // ── Fade-up observer (adds class "vis" if you have CSS for it) ────
  const fuEls = document.querySelectorAll('.fu');
  const obs   = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
  }, { threshold: 0.08 });
  fuEls.forEach(el => obs.observe(el));

  // ── Back to top + navbar scroll (if mainNav exists) ─────────────────
  const btt = document.getElementById('backToTop');
  window.addEventListener('scroll', () => {
    btt.classList.toggle('visible', window.scrollY > 500);
  }, { passive: true });
  btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>
<?php include('includes/footer.php'); ?>