<style>
  /* ══ FILTER BAR ══ */
  .news-filter-bar {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 22px 28px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 32px;
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    align-items: flex-end;
  }

  .nfb-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
    min-width: 180px;
  }

  .nfb-label {
    font-family: var(--font-head);
    font-size: .68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--gray-mid);
  }

  .nfb-search-wrap {
    position: relative;
  }

  .nfb-search-wrap i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-mid);
    font-size: .95rem;
    pointer-events: none;
  }

  .nfb-input {
    font-family: var(--font-body);
    font-size: .9rem;
    font-weight: 500;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: 10px 14px 10px 38px;
    width: 100%;
    background: var(--off-white);
    color: var(--text);
    transition: border-color .2s, box-shadow .2s;
  }

  .nfb-input:focus {
    outline: none;
    border-color: var(--teal);
    background: white;
    box-shadow: 0 0 0 3px rgba(0, 168, 150, .12);
  }

  .nfb-select {
    font-family: var(--font-body);
    font-size: .88rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: 10px 14px;
    width: 100%;
    background: var(--off-white);
    color: var(--text);
    transition: border-color .2s;
    cursor: pointer;
  }

  .nfb-select:focus {
    outline: none;
    border-color: var(--teal);
  }

  .nfb-btn {
    font-family: var(--font-head);
    font-size: .78rem;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: var(--r-sm);
    border: 1.5px solid var(--border);
    background: white;
    color: var(--gray-dark);
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
    align-self: flex-end;
  }

  .nfb-btn:hover {
    border-color: var(--teal);
    color: var(--teal);
  }

  .results-bar {
    font-family: var(--font-body);
    font-size: .84rem;
    font-weight: 600;
    color: var(--gray-mid);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .results-bar strong {
    color: var(--teal);
  }

  /* ══ CAT PILLS ══ */
  .cat-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 30px;
  }

  .cat-pill {
    font-family: var(--font-head);
    font-size: .72rem;
    font-weight: 700;
    padding: 6px 18px;
    border-radius: 100px;
    cursor: pointer;
    border: 2px solid var(--border);
    background: white;
    color: var(--gray-dark);
    transition: all .2s;
    text-decoration: none;
  }

  .cat-pill:hover {
    border-color: var(--teal);
    color: var(--teal);
  }

  .cat-pill.active {
    background: var(--navy);
    border-color: var(--navy);
    color: white;
  }

  /* ══ FEATURED CARD ══ */
  .featured-news-card {
    background: linear-gradient(135deg, var(--navy) 0%, #0d3060 100%);
    border-radius: var(--r-lg);
    overflow: hidden;
    margin-bottom: 36px;
    display: flex;
    min-height: 300px;
    position: relative;
  }

  .fnc-img {
    flex: 0 0 46%;
    background-image: var(--bg-img, none);
    background-size: cover;
    background-position: center;
    position: relative;
  }

  .fnc-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, transparent 55%, var(--navy) 100%);
  }

  .fnc-placeholder {
    flex: 0 0 46%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, .04);
  }

  .fnc-placeholder i {
    font-size: 4rem;
    color: rgba(255, 255, 255, .12);
  }

  .fnc-body {
    flex: 1;
    padding: 40px 36px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .fnc-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-head);
    font-size: .64rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--gold-light);
    background: rgba(201, 168, 76, .14);
    border: 1px solid rgba(201, 168, 76, .3);
    padding: 4px 12px;
    border-radius: 100px;
    margin-bottom: 14px;
    align-self: flex-start;
  }

  .fnc-title {
    font-family: var(--font-head);
    font-size: clamp(1.2rem, 2.5vw, 1.7rem);
    font-weight: 900;
    color: white;
    line-height: 1.2;
    margin-bottom: 12px;
  }

  .fnc-excerpt {
    font-family: var(--font-body);
    font-size: .88rem;
    color: rgba(255, 255, 255, .6);
    line-height: 1.75;
    font-weight: 500;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .fnc-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    font-family: var(--font-body);
    font-size: .78rem;
    font-weight: 600;
    color: rgba(255, 255, 255, .45);
    margin-bottom: 20px;
  }

  .fnc-meta i {
    color: var(--teal-light);
    margin-right: 4px;
  }

  /* ══ NEWS CARD ══ */
  .news-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform .25s, box-shadow .25s, border-color .25s;
    text-decoration: none;
    color: inherit;
  }

  .news-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--teal);
    color: inherit;
  }

  .nc-img-wrap {
    height: 200px;
    position: relative;
    flex-shrink: 0;
    background-image: var(--bg-img, none);
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .nc-img-wrap::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(10, 22, 40, .5) 100%);
  }

  .nc-img-placeholder {
    width: 100%;
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-shrink: 0;
    position: relative;
  }

  .nc-img-placeholder i {
    font-size: 2.2rem;
    color: rgba(255, 255, 255, .45);
    position: relative;
    z-index: 1;
  }

  .nc-img-placeholder span {
    font-family: var(--font-head);
    font-size: .68rem;
    font-weight: 700;
    color: rgba(255, 255, 255, .3);
    text-transform: uppercase;
    letter-spacing: .07em;
    position: relative;
    z-index: 1;
  }

  .nc-cat-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    font-family: var(--font-head);
    font-size: .62rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 3px 10px;
    border-radius: 100px;
    color: white;
  }

  .nc-date-pill {
    position: absolute;
    bottom: 10px;
    right: 10px;
    z-index: 2;
    background: white;
    border-radius: 8px;
    padding: 5px 9px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
    min-width: 42px;
  }

  .nc-date-pill .day {
    font-family: var(--font-head);
    font-size: 1rem;
    font-weight: 900;
    color: var(--navy);
    display: block;
    line-height: 1;
  }

  .nc-date-pill .month {
    font-family: var(--font-body);
    font-size: .56rem;
    font-weight: 700;
    color: var(--teal);
    text-transform: uppercase;
    display: block;
  }

  .nc-body {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .nc-title {
    font-family: var(--font-head);
    font-size: .92rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.4;
    margin-bottom: 9px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .nc-excerpt {
    font-family: var(--font-body);
    font-size: .82rem;
    color: var(--gray-mid);
    line-height: 1.65;
    font-weight: 500;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .nc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid var(--border);
  }

  .nc-author {
    font-family: var(--font-body);
    font-size: .74rem;
    font-weight: 600;
    color: var(--gray-mid);
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .nc-author i {
    color: var(--teal);
    font-size: .82rem;
  }

  .nc-read {
    font-family: var(--font-head);
    font-size: .74rem;
    font-weight: 700;
    color: var(--teal);
    display: flex;
    align-items: center;
    gap: 5px;
    transition: gap .2s, color .2s;
  }

  .news-card:hover .nc-read {
    gap: 9px;
    color: var(--navy);
  }

  /* ══ CATEGORY BADGE COLORS ══ */
  .cat-admissions {
    background: rgba(0, 168, 150, .85);
  }

  .cat-research {
    background: rgba(10, 22, 40, .85);
  }

  .cat-achievement {
    background: rgba(180, 130, 0, .9);
  }

  .cat-conference {
    background: rgba(21, 101, 192, .85);
  }

  .cat-society {
    background: rgba(106, 27, 154, .85);
  }

  .cat-general {
    background: rgba(55, 71, 79, .85);
  }

  /* ══ SIDEBAR ══ */
  .news-sidebar-widget {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .nsw-head {
    background: var(--navy);
    padding: 13px 18px;
    font-family: var(--font-head);
    font-size: .72rem;
    font-weight: 800;
    color: white;
    text-transform: uppercase;
    letter-spacing: .08em;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .nsw-head i {
    color: var(--gold);
  }

  .nsw-body {
    padding: 6px 0;
  }

  .nsw-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    font-family: var(--font-body);
    font-size: .85rem;
    font-weight: 600;
    color: var(--navy);
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all .2s;
  }

  .nsw-link:hover {
    background: var(--teal-pale);
    color: var(--teal);
    border-left-color: var(--teal);
  }

  .recent-item {
    display: flex;
    gap: 12px;
    padding: 12px 18px;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    color: inherit;
    transition: background .2s;
  }

  .recent-item:last-child {
    border-bottom: none;
  }

  .recent-item:hover {
    background: var(--off-white);
  }

  .ri-thumb {
    width: 60px;
    height: 60px;
    flex-shrink: 0;
    border-radius: 8px;
    overflow: hidden;
    background: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .ri-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .ri-thumb i {
    font-size: 1.3rem;
    color: rgba(255, 255, 255, .4);
  }

  .ri-title {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.35;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .ri-date {
    font-family: var(--font-body);
    font-size: .72rem;
    color: var(--gray-mid);
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .ri-date i {
    color: var(--teal);
    font-size: .78rem;
  }

  /* ══ EMPTY ══ */
  .news-empty {
    text-align: center;
    padding: 60px 0;
  }

  .news-empty i {
    font-size: 3rem;
    color: var(--gray-light);
    display: block;
    margin-bottom: 14px;
  }

  /* ══ PAGINATION ══ */
  .news-pagination {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-top: 44px;
    flex-wrap: wrap;
  }

  .pg-btn {
    min-width: 40px;
    height: 40px;
    padding: 0 10px;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-head);
    font-size: .82rem;
    font-weight: 700;
    color: var(--navy);
    background: white;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
  }

  .pg-btn:hover {
    border-color: var(--teal);
    color: var(--teal);
  }

  .pg-btn.active {
    background: var(--teal);
    border-color: var(--teal);
    color: white;
  }

  .pg-btn.disabled {
    opacity: .4;
    pointer-events: none;
  }

  @media (max-width: 767.98px) {
    .featured-news-card {
      flex-direction: column;
    }

    .fnc-img,
    .fnc-placeholder {
      flex: none;
      height: 200px;
    }

    .fnc-body {
      padding: 24px 20px;
    }

    .news-filter-bar {
      flex-direction: column;
    }
  }
</style>




<?php include("includes/header.php"); ?>

<!-- ═══ PAGE HERO ═══ -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>News &amp; Events</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">News &amp; Events</span>
    </div>
  </div>
</div>

<!-- ═══ MAIN CONTENT ═══ -->
<section class="pmc-section bg-off">
  <div class="container">
    <div class="row g-5">

      <!-- LEFT: News Listing -->
      <div class="col-lg-8">

        <!-- Filter Bar (static, non‑functional but keeps the look) -->
        <div class="news-filter-bar fu">
          <div class="nfb-group" style="flex:2;">
            <label class="nfb-label">Search</label>
            <div class="nfb-search-wrap">
              <i class="bi bi-search"></i>
              <input type="text" class="nfb-input" placeholder="Search news…" readonly>
            </div>
          </div>
          <div class="nfb-group">
            <label class="nfb-label">Category</label>
            <select class="nfb-select" disabled>
              <option>All Categories</option>
            </select>
          </div>
          <div class="nfb-group">
            <label class="nfb-label">Year</label>
            <select class="nfb-select" disabled>
              <option>All Years</option>
            </select>
          </div>
          <button class="nfb-btn" disabled><i class="bi bi-x-circle me-1"></i> Clear</button>
        </div>

        <!-- Category Pills (static) -->
        <div class="cat-pills fu">
          <span class="cat-pill active">All</span>
          <span class="cat-pill">Admissions</span>
          <span class="cat-pill">Achievement</span>
          <span class="cat-pill">Research</span>
          <span class="cat-pill">Conference</span>
          <span class="cat-pill">Society</span>
          <span class="cat-pill">General</span>
        </div>

        <!-- Results count -->
        <div class="results-bar fu">
          <i class="bi bi-newspaper" style="color:var(--teal);"></i>
          Showing <strong>9</strong> of <strong>9</strong> items
        </div>

        <!-- Featured Card -->
        <div class="featured-news-card fu" style="margin-bottom:36px;">
          <div class="fnc-placeholder" style="background:linear-gradient(135deg,#0A1628,#1a3a6b);">
            <i class="bi bi-mortarboard-fill" style="font-size:5rem;color:rgba(255,255,255,.15);"></i>
          </div>
          <div class="fnc-body">
            <div class="fnc-eyebrow"><i class="bi bi-star-fill"></i> Featured · Admissions</div>
            <h2 class="fnc-title">MBBS Admissions Open for Session 2025–26</h2>
            <p class="fnc-excerpt">PMC invites applications from eligible local, overseas Pakistani, and international
              students for MBBS Session 2025–26. Limited seats are available — apply early.</p>
            <div class="fnc-meta">
              <span><i class="bi bi-calendar3"></i> June 15, 2025</span>
              <span><i class="bi bi-person"></i> PMC Admin</span>
              <span><i class="bi bi-clock"></i> 2 min read</span>
            </div>
            <a href="single-news.php" class="btn-pmc btn-pmc-gold" style="align-self:flex-start;">
              <i class="bi bi-arrow-right-circle"></i> Read Full Article
            </a>
          </div>
        </div>

        <!-- News Grid (9 hard‑coded cards) -->
        <div class="row g-4" id="newsGrid">

          <!-- 1. Admissions -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#0A1628,#1a3a6b);">
                <i class="bi bi-mortarboard-fill"></i><span>Admissions</span>
                <div class="nc-date-pill"><span class="day">15</span><span class="month">Jun</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">MBBS Admissions Open for Session 2025–26</div>
                <div class="nc-excerpt">PMC invites applications from eligible local, overseas Pakistani, and
                  international students for MBBS Session 2025–26.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>PMC Admin</span>
                  <span class="nc-read">2 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 2. Achievement -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#C9A84C,#e0c068);">
                <i class="bi bi-award-fill"></i><span>Achievement</span>
                <div class="nc-date-pill"><span class="day">15</span><span class="month">May</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">PMC Retains #1 Ranking Among Private Medical Colleges in KP</div>
                <div class="nc-excerpt">Following the latest PM&DC inspection, PMC secured the highest score among all
                  private medical colleges in KP with over 80%.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>PMC Communications</span>
                  <span class="nc-read">3 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 3. Research -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#00695C,#00897B);">
                <i class="bi bi-flask-fill"></i><span>Research</span>
                <div class="nc-date-pill"><span class="day">22</span><span class="month">Apr</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">UMR Society Annual Medical Research Conference 2025</div>
                <div class="nc-excerpt">The PMC Undergraduate Medical Research Society organised its annual conference
                  showcasing student projects.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>UMR Society</span>
                  <span class="nc-read">5 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 4. Conference -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#1565C0,#1976D2);">
                <i class="bi bi-mic-fill"></i><span>Conference</span>
                <div class="nc-date-pill"><span class="day">18</span><span class="month">Feb</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">International Medical Education Symposium 2025 — PMC Hosts Delegates</div>
                <div class="nc-excerpt">PMC hosted an international symposium on modern medical education, attended by
                  200+ delegates.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>PMC Admin</span>
                  <span class="nc-read">4 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 5. Sports Society -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#6A1B9A,#7B1FA2);">
                <i class="bi bi-trophy-fill"></i><span>Society</span>
                <div class="nc-date-pill"><span class="day">10</span><span class="month">Mar</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">Annual Sports Gala 2025 — PMC Sports Society</div>
                <div class="nc-excerpt">A week-long celebration of sportsmanship and team spirit featuring cricket,
                  football, and more.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>Sports Society</span>
                  <span class="nc-read">3 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 6. Blood Donation -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#C62828,#E53935);">
                <i class="bi bi-heart-fill"></i><span>Society</span>
                <div class="nc-date-pill"><span class="day">28</span><span class="month">Jan</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">SWS Blood Donation Drive — Winter 2025 Exceeds Target</div>
                <div class="nc-excerpt">The Social Welfare Society conducted a successful blood donation drive with over
                  150 units collected.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>Social Welfare Society</span>
                  <span class="nc-read">2 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 7. Convocation -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#37474F,#546E7A);">
                <i class="bi bi-mortarboard-fill"></i><span>General</span>
                <div class="nc-date-pill"><span class="day">05</span><span class="month">Dec</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">Annual Convocation 2024 — 90+ Doctors Graduate from PMC</div>
                <div class="nc-excerpt">PMC celebrated another cohort at the Annual Convocation 2024 with degrees
                  conferred to over 90 new MBBS graduates.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>PMC Admin</span>
                  <span class="nc-read">3 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 8. Scholarship -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#00695C,#2E7D32);">
                <i class="bi bi-award"></i><span>Admissions</span>
                <div class="nc-date-pill"><span class="day">01</span><span class="month">Oct</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">PMC Scholarship Policy Updated for Session 2024–25</div>
                <div class="nc-excerpt">The updated scholarship policy for MBBS Session 2024–25 has been announced.
                </div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>Admissions Office</span>
                  <span class="nc-read">3 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- 9. Faculty Research -->
          <div class="col-md-6 news-item">
            <a class="news-card" href="single-news.php">
              <div class="nc-img-placeholder" style="background:linear-gradient(135deg,#0A1628,#00695C);">
                <i class="bi bi-journal-richtext"></i><span>Research</span>
                <div class="nc-date-pill"><span class="day">14</span><span class="month">Aug</span></div>
              </div>
              <div class="nc-body">
                <div class="nc-title">PMC Faculty Research Published in International Peer-Reviewed Journals</div>
                <div class="nc-excerpt">Multiple PMC faculty members have had research papers published in
                  internationally peer-reviewed medical journals.</div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i>PMC Communications</span>
                  <span class="nc-read">4 min read <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

        </div> <!-- /newsGrid -->

        <!-- Pagination (static, non‑functional) -->
        <div class="news-pagination fu">
          <a class="pg-btn disabled" href="#"><i class="bi bi-chevron-left"></i></a>
          <a class="pg-btn active" href="#">1</a>
          <a class="pg-btn" href="#">2</a>
          <a class="pg-btn" href="#">3</a>
          <a class="pg-btn" href="#"><i class="bi bi-chevron-right"></i></a>
        </div>

      </div> <!-- /col-lg-8 -->

      <!-- RIGHT: Sidebar -->
      <div class="col-lg-4">

        <!-- Recent News -->
        <div class="news-sidebar-widget fu">
          <div class="nsw-head"><i class="bi bi-clock-history"></i> Recent News</div>
          <div class="nsw-body" style="padding:0;">
            <a class="recent-item" href="single-news.php">
              <div class="ri-thumb"><i class="bi bi-mortarboard-fill"></i></div>
              <div style="flex:1;min-width:0;">
                <div class="ri-title">MBBS Admissions Open for Session 2025–26</div>
                <div class="ri-date"><i class="bi bi-calendar3"></i> June 15, 2025</div>
              </div>
            </a>
            <a class="recent-item" href="single-news.php">
              <div class="ri-thumb"><i class="bi bi-award-fill"></i></div>
              <div style="flex:1;min-width:0;">
                <div class="ri-title">PMC Retains #1 Ranking Among Private Medical Colleges in KP</div>
                <div class="ri-date"><i class="bi bi-calendar3"></i> May 15, 2025</div>
              </div>
            </a>
            <a class="recent-item" href="single-news.php">
              <div class="ri-thumb"><i class="bi bi-flask-fill"></i></div>
              <div style="flex:1;min-width:0;">
                <div class="ri-title">UMR Society Annual Medical Research Conference 2025</div>
                <div class="ri-date"><i class="bi bi-calendar3"></i> April 22, 2025</div>
              </div>
            </a>
          </div>
        </div>

        <!-- Categories (static) -->
        <div class="news-sidebar-widget fu">
          <div class="nsw-head"><i class="bi bi-tags-fill"></i> Categories</div>
          <div class="nsw-body">
            <a class="nsw-link" href="#"><i class="bi bi-grid"></i>All News <span class="ms-auto pmc-tag">9</span></a>
            <a class="nsw-link" href="#"><i class="bi bi-chevron-right"></i>Admissions <span
                class="ms-auto pmc-tag">2</span></a>
            <a class="nsw-link" href="#"><i class="bi bi-chevron-right"></i>Achievement <span
                class="ms-auto pmc-tag">1</span></a>
            <a class="nsw-link" href="#"><i class="bi bi-chevron-right"></i>Research <span
                class="ms-auto pmc-tag">2</span></a>
            <a class="nsw-link" href="#"><i class="bi bi-chevron-right"></i>Conference <span
                class="ms-auto pmc-tag">1</span></a>
            <a class="nsw-link" href="#"><i class="bi bi-chevron-right"></i>Society <span
                class="ms-auto pmc-tag">2</span></a>
            <a class="nsw-link" href="#"><i class="bi bi-chevron-right"></i>General <span
                class="ms-auto pmc-tag">1</span></a>
          </div>
        </div>

      </div> <!-- /sidebar -->

    </div> <!-- /row -->
  </div> <!-- /container -->
</section>

<?php include("includes/footer.php"); ?>






<script>
  /* ═══════════════════════════════════════════════
     ALL-NEWS PAGE SCRIPTS
  ═══════════════════════════════════════════════ */
  let activeCat = 'all';

  // ── Category pills ────────────────────────────────────────────────
  document.querySelectorAll('.cat-pill').forEach(p => {
    p.addEventListener('click', () => {
      document.querySelectorAll('.cat-pill').forEach(x => x.classList.remove('active'));
      p.classList.add('active');
      activeCat = p.dataset.cat;
      applyNewsFilters();
    });
  });

  function filterNewsCat(cat) {
    activeCat = cat;
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.toggle('active', p.dataset.cat === cat));
    document.getElementById('newsCat').value = cat === 'all' ? '' : cat;
    applyNewsFilters();
    document.getElementById('newsGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // ── Search / selects ──────────────────────────────────────────────
  document.getElementById('newsSearch').addEventListener('input', applyNewsFilters);
  document.getElementById('newsCat').addEventListener('change', function () {
    activeCat = this.value || 'all';
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.toggle('active', p.dataset.cat === activeCat));
    applyNewsFilters();
  });
  document.getElementById('newsYear').addEventListener('change', applyNewsFilters);

  function applyNewsFilters() {
    const q = document.getElementById('newsSearch').value.trim().toLowerCase();
    const year = document.getElementById('newsYear').value;
    const items = [...document.querySelectorAll('.news-item')];
    const fc = document.getElementById('featuredCard');
    let visible = 0;

    // Featured card
    if (fc) {
      const fcCat = fc.dataset.cat;
      const fcYear = fc.dataset.year;
      const show = (activeCat === 'all' || fcCat === activeCat) && (!year || fcYear === year);
      fc.style.display = show ? '' : 'none';
    }

    items.forEach(item => {
      const matchCat = activeCat === 'all' || item.dataset.cat === activeCat;
      const matchYear = !year || item.dataset.year === year;
      const matchQ = !q || item.dataset.title.includes(q) || item.dataset.excerpt.includes(q);
      const show = matchCat && matchYear && matchQ;
      item.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    document.getElementById('visibleCount').textContent = visible;
    document.getElementById('newsEmpty').style.display = visible === 0 ? 'block' : 'none';
  }

  function resetNewsFilters() {
    document.getElementById('newsSearch').value = '';
    document.getElementById('newsCat').value = '';
    document.getElementById('newsYear').value = '';
    activeCat = 'all';
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.toggle('active', p.dataset.cat === 'all'));
    applyNewsFilters();
  }

  // ── Newsletter subscribe ──────────────────────────────────────────
  function handleNLSub(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type=submit]');
    btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Subscribed!';
    btn.disabled = true;
    btn.style.background = '#2E7D32';
    setTimeout(() => { btn.innerHTML = '<i class="bi bi-envelope-check"></i> Subscribe'; btn.disabled = false; btn.style.background = ''; e.target.reset(); }, 3000);
  }

  // ── Fade-up ───────────────────────────────────────────────────────
  const obs = new IntersectionObserver(entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } }), { threshold: .08 });
  document.querySelectorAll('.fu').forEach(el => obs.observe(el));

  // ── Navbar scroll ─────────────────────────────────────────────────
  const nav = document.getElementById('mainNav');
  window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 40), { passive: true });

  // ── Back to top ───────────────────────────────────────────────────
  const btt = document.getElementById('backToTop');
  window.addEventListener('scroll', () => btt.classList.toggle('visible', scrollY > 500), { passive: true });
  btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>