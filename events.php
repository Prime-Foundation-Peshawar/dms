<style>
  /* ══ FILTER BAR ══ */
  .events-filter-bar {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 22px 28px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 36px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
  }

  .ef-search-wrap {
    position: relative;
    flex: 1;
    min-width: 220px;
  }

  .ef-search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-mid);
    font-size: 1rem;
    pointer-events: none;
  }

  .ef-search {
    font-family: var(--font-body);
    font-size: .9rem;
    font-weight: 500;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: 11px 16px 11px 42px;
    width: 100%;
    color: var(--text);
    background: var(--off-white);
    transition: border-color .2s, box-shadow .2s;
  }

  .ef-search:focus {
    outline: none;
    border-color: var(--teal);
    background: white;
    box-shadow: 0 0 0 3px rgba(21,95,122, .12);
  }

  .ef-select {
    font-family: var(--font-body);
    font-size: .88rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: 11px 16px;
    min-width: 160px;
    background: var(--off-white);
    color: var(--text);
    transition: border-color .2s;
    cursor: pointer;
  }

  .ef-select:focus {
    outline: none;
    border-color: var(--teal);
  }

  /* ══ CATEGORY PILLS ══ */
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
    padding: 6px 16px;
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

  /* ══ FEATURED EVENT ══ */
  .featured-event {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
    border-radius: var(--r-lg);
    overflow: hidden;
    margin-bottom: 40px;
    position: relative;
    min-height: 340px;
    display: flex;
    align-items: stretch;
  }

  .featured-event-img {
    flex: 0 0 48%;
    background-image: var(--bg-img, none);
    background-size: cover;
    background-position: center;
    position: relative;
    min-height: 340px;
  }

  .featured-event-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, transparent 60%, var(--navy) 100%);
  }

  .featured-event-img-placeholder {
    flex: 0 0 48%;
    background: linear-gradient(135deg, #122040, #0d3060);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 340px;
  }

  .featured-event-img-placeholder i {
    font-size: 4rem;
    color: rgba(255, 255, 255, .15);
  }

  .featured-event-body {
    flex: 1;
    padding: 44px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-head);
    font-size: .65rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold-light);
    background: rgba(201, 168, 76, .14);
    border: 1px solid rgba(201, 168, 76, .3);
    padding: 4px 12px;
    border-radius: 100px;
    margin-bottom: 16px;
    align-self: flex-start;
  }

  .featured-event-title {
    font-family: var(--font-head);
    font-size: clamp(1.4rem, 3vw, 1.9rem);
    font-weight: 800;
    color: white;
    line-height: 1.2;
    margin-bottom: 14px;
  }

  .featured-event-excerpt {
    font-family: var(--font-body);
    font-size: .92rem;
    color: rgba(255, 255, 255, .65);
    line-height: 1.75;
    margin-bottom: 22px;
    font-weight: 500;
  }

  .featured-event-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 26px;
  }

  .fem-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font-body);
    font-size: .8rem;
    font-weight: 600;
    color: rgba(255, 255, 255, .55);
  }

  .fem-item i {
    color: var(--teal-light);
    font-size: .9rem;
  }

  /* ══ EVENT CARDS ══ */
  .event-card {
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

  .event-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--teal);
    color: inherit;
  }

  /* Card image */
  .ec-img {
    height: 200px;
    background-image: var(--bg-img, linear-gradient(135deg, var(--navy), var(--navy-mid)));
    background-size: cover;
    background-position: center;
    position: relative;
    flex-shrink: 0;
  }

  .ec-img-placeholder {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    flex-shrink: 0;
  }

  .ec-img-placeholder i {
    font-size: 2.5rem;
    color: rgba(255, 255, 255, .5);
  }

  .ec-img-placeholder span {
    font-family: var(--font-head);
    font-size: .72rem;
    color: rgba(255, 255, 255, .35);
    text-transform: uppercase;
    letter-spacing: .06em;
  }

  /* Gradient on images */
  .ec-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(10, 22, 40, .5) 100%);
  }

  /* Category + Date badge overlay */
  .ec-badges {
    position: absolute;
    bottom: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    z-index: 2;
  }

  .ec-cat-badge {
    font-family: var(--font-head);
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 3px 10px;
    border-radius: 100px;
    color: white;
  }

  .cat-admissions {
    background: rgba(21,95,122, .85);
  }

  .cat-research {
    background: rgba(10, 22, 40, .85);
  }

  .cat-achievement {
    background: rgba(201, 168, 76, .9);
    color: var(--navy) !important;
  }

  .cat-conference {
    background: rgba(30, 80, 200, .85);
  }

  .cat-society {
    background: rgba(130, 0, 160, .85);
  }

  .cat-general {
    background: rgba(60, 70, 80, .85);
  }

  /* Date pill */
  .ec-date-badge {
    background: white;
    border-radius: 10px;
    padding: 6px 10px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .2);
    min-width: 46px;
  }

  .ec-date-day {
    font-family: var(--font-head);
    font-size: 1.1rem;
    font-weight: 900;
    color: var(--navy);
    display: block;
    line-height: 1;
  }

  .ec-date-month {
    font-family: var(--font-body);
    font-size: .58rem;
    font-weight: 700;
    color: var(--teal);
    text-transform: uppercase;
    letter-spacing: .06em;
    display: block;
  }

  /* Card body */
  .ec-body {
    padding: 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .ec-title {
    font-family: var(--font-head);
    font-size: .95rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.4;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .ec-excerpt {
    font-family: var(--font-body);
    font-size: .84rem;
    color: var(--gray-mid);
    line-height: 1.65;
    font-weight: 500;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .ec-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid var(--border);
  }

  .ec-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-body);
    font-size: .74rem;
    font-weight: 600;
    color: var(--gray-mid);
  }

  .ec-meta-item i {
    color: var(--teal);
    font-size: .82rem;
  }

  .ec-read-link {
    font-family: var(--font-head);
    font-size: .76rem;
    font-weight: 700;
    color: var(--teal);
    display: flex;
    align-items: center;
    gap: 5px;
    transition: gap .2s, color .2s;
  }

  .event-card:hover .ec-read-link {
    gap: 9px;
    color: var(--navy);
  }

  /* ══ UPCOMING EVENTS SIDEBAR ══ */
  .upcoming-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    overflow: hidden;
    margin-bottom: 12px;
    display: flex;
    transition: border-color .2s, transform .2s;
    text-decoration: none;
    color: inherit;
  }

  .upcoming-card:hover {
    border-color: var(--teal);
    transform: translateX(4px);
    color: inherit;
  }

  .upcoming-date-block {
    flex: 0 0 64px;
    background: var(--navy);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px 8px;
  }

  .udb-day {
    font-family: var(--font-head);
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--gold-light);
    display: block;
    line-height: 1;
  }

  .udb-month {
    font-family: var(--font-body);
    font-size: .6rem;
    font-weight: 700;
    color: rgba(255, 255, 255, .5);
    text-transform: uppercase;
    letter-spacing: .06em;
    display: block;
    margin-top: 3px;
  }

  .upcoming-body {
    padding: 12px 14px;
    flex: 1;
  }

  .ub-title {
    font-family: var(--font-head);
    font-size: .82rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.35;
    margin-bottom: 4px;
  }

  .ub-venue {
    font-family: var(--font-body);
    font-size: .72rem;
    color: var(--gray-mid);
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .ub-venue i {
    color: var(--teal);
    font-size: .78rem;
  }

  /* ══ NEWSLETTER WIDGET ══ */
  .nl-widget {
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    border-radius: var(--r-md);
    padding: 28px;
  }

  .nl-widget h5 {
    font-family: var(--font-head);
    color: white;
    font-size: 1rem;
    font-weight: 800;
    margin-bottom: 8px;
  }

  .nl-widget p {
    font-family: var(--font-body);
    color: rgba(255, 255, 255, .55);
    font-size: .84rem;
    line-height: 1.6;
    margin-bottom: 18px;
  }

  .nl-input {
    font-family: var(--font-body);
    font-size: .88rem;
    border: 1.5px solid rgba(255, 255, 255, .15);
    border-radius: var(--r-sm);
    padding: 11px 14px;
    width: 100%;
    color: white;
    background: rgba(255, 255, 255, .07);
    margin-bottom: 10px;
    transition: border-color .2s;
  }

  .nl-input::placeholder {
    color: rgba(255, 255, 255, .3);
  }

  .nl-input:focus {
    outline: none;
    border-color: var(--teal);
    background: rgba(255, 255, 255, .1);
  }

  /* ══ PAGINATION ══ */
  .events-pagination {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-top: 44px;
    flex-wrap: wrap;
  }

  .pg-btn {
    width: 40px;
    height: 40px;
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

  /* ══ BACK TO TOP ══ */
  #backToTop {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 44px;
    height: 44px;
    background: var(--teal);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 999;
    opacity: 0;
    transform: translateY(10px);
    transition: opacity .25s, transform .25s, background .2s;
    box-shadow: 0 4px 18px rgba(21,95,122, .35);
  }

  #backToTop.visible {
    opacity: 1;
    transform: translateY(0);
  }

  #backToTop:hover {
    background: var(--navy);
  }

  /* ══ RESPONSIVE ══ */
  @media (max-width: 767.98px) {
    .featured-event {
      flex-direction: column;
    }

    .featured-event-img,
    .featured-event-img-placeholder {
      flex: none;
      min-height: 220px;
    }

    .featured-event-img::after {
      background: linear-gradient(to bottom, transparent 60%, var(--navy) 100%);
    }

    .featured-event-body {
      padding: 28px 22px;
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
      <span class="current">Events</span>
    </div>
  </div>
</div>

<!-- ═══ MAIN CONTENT ═══ -->
<section class="pmc-section bg-off">
  <div class="container">
    <div class="row g-5">

      <!-- ── LEFT: Events listing ─────────────────────────────── -->
      <div class="col-lg-8">

        <!-- Filter bar -->
        <div class="events-filter-bar fu">
          <div class="ef-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="ef-search" id="evSearch" placeholder="Search events, news, announcements…" />
          </div>
          <select class="ef-select" id="evYear">
            <option value="">All Years</option>
            <option value="2025" selected>2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
          </select>
          <select class="ef-select" id="evSort">
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
          </select>
        </div>

        <!-- Category pills -->
        <div class="cat-pills fu">
          <a class="cat-pill active" data-cat="all" href="#">All</a>
          <a class="cat-pill" data-cat="admissions" href="#">Admissions</a>
          <a class="cat-pill" data-cat="research" href="#">Research</a>
          <a class="cat-pill" data-cat="achievement" href="#">Achievement</a>
          <a class="cat-pill" data-cat="conference" href="#">Conference</a>
          <a class="cat-pill" data-cat="society" href="#">Societies</a>
          <a class="cat-pill" data-cat="general" href="#">General</a>
        </div>

        <!-- Featured Event -->
        <div class="featured-event fu" id="featuredEvent">
          <div class="featured-event-img-placeholder">
            <i class="bi bi-award-fill"></i>
          </div>
          <div class="featured-event-body">
            <div class="featured-badge"><i class="bi bi-star-fill"></i> Featured</div>
            <h2 class="featured-event-title">PMC Retains #1 Ranking Among Private Medical Colleges in KP</h2>
            <p class="featured-event-excerpt">Following the latest PM&DC inspection visit, Peshawar Medical College has
              once again secured the highest score among all private medical colleges in Khyber Pakhtunkhwa with over
              80% — reinforcing our commitment to academic and clinical excellence.</p>
            <div class="featured-event-meta">
              <div class="fem-item"><i class="bi bi-calendar3"></i> May 15, 2025</div>
              <div class="fem-item"><i class="bi bi-tag"></i> Achievement</div>
              <div class="fem-item"><i class="bi bi-clock"></i> 3 min read</div>
            </div>
            <a href="event-single.php" class="btn-pmc btn-pmc-gold"
              style="font-size:.85rem;padding:11px 22px;align-self:flex-start;">
              <i class="bi bi-arrow-right-circle"></i> Read Full Story
            </a>
          </div>
        </div>

        <!-- Events Grid -->
        <div class="row g-4" id="eventsGrid">

          <!-- Event 1 -->
          <div class="col-md-6 ev-item fu" data-cat="admissions" data-year="2025" data-date="2025-06-15">
            <a class="event-card" href="event-single.php">
              <div class="ec-img-placeholder" style="background:linear-gradient(135deg,#0A1628,#122040);">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Admissions</span>
              </div>
              <div style="position:relative;"><!-- badges need relative parent -->
                <div class="ec-badges"
                  style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-admissions">Admissions</span>
                  <div class="ec-date-badge"><span class="ec-date-day">15</span><span class="ec-date-month">Jun</span>
                  </div>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title">MBBS Admissions Open for Session 2026-27</div>
                <div class="ec-excerpt">Peshawar Medical College invites applications from eligible students — local,
                  overseas Pakistani, and international — for the upcoming MBBS session starting 2025.</div>
                <div class="ec-footer">
                  <span class="ec-meta-item"><i class="bi bi-person"></i> PMC Admin</span>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- Event 2 -->
          <div class="col-md-6 ev-item fu" data-cat="research" data-year="2025" data-date="2025-04-22">
            <a class="event-card" href="event-single.php">
              <div class="ec-img-placeholder" style="background:linear-gradient(135deg,#00695C,#00897B);">
                <i class="bi bi-flask-fill"></i>
                <span>Research</span>
              </div>
              <div style="position:relative;">
                <div class="ec-badges"
                  style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-research">Research</span>
                  <div class="ec-date-badge"><span class="ec-date-day">22</span><span class="ec-date-month">Apr</span>
                  </div>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title">UMR Society Annual Medical Research Conference 2025</div>
                <div class="ec-excerpt">The PMC Undergraduate Medical Research Society organised its annual conference
                  showcasing research projects across all five years of the MBBS program at PMC Main Campus.</div>
                <div class="ec-footer">
                  <span class="ec-meta-item"><i class="bi bi-geo-alt"></i> PMC Auditorium</span>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- Event 3 -->
          <div class="col-md-6 ev-item fu" data-cat="society" data-year="2025" data-date="2025-03-10">
            <a class="event-card" href="event-single.php">
              <div class="ec-img-placeholder" style="background:linear-gradient(135deg,#6A1B9A,#7B1FA2);">
                <i class="bi bi-trophy-fill"></i>
                <span>Sports</span>
              </div>
              <div style="position:relative;">
                <div class="ec-badges"
                  style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-society">Society</span>
                  <div class="ec-date-badge"><span class="ec-date-day">10</span><span class="ec-date-month">Mar</span>
                  </div>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title">Annual Sports Gala 2025 — PMC Sports Society</div>
                <div class="ec-excerpt">A week-long celebration of sportsmanship, team spirit, and healthy competition
                  featuring cricket, football, basketball, badminton and athletics across all years.</div>
                <div class="ec-footer">
                  <span class="ec-meta-item"><i class="bi bi-geo-alt"></i> PMC Sports Ground</span>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- Event 4 -->
          <div class="col-md-6 ev-item fu" data-cat="conference" data-year="2025" data-date="2025-02-18">
            <a class="event-card" href="event-single.php">
              <div class="ec-img-placeholder" style="background:linear-gradient(135deg,#1565C0,#1976D2);">
                <i class="bi bi-mic-fill"></i>
                <span>Conference</span>
              </div>
              <div style="position:relative;">
                <div class="ec-badges"
                  style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-conference">Conference</span>
                  <div class="ec-date-badge"><span class="ec-date-day">18</span><span class="ec-date-month">Feb</span>
                  </div>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title">International Medical Education Symposium — PMC 2025</div>
                <div class="ec-excerpt">PMC hosted an international symposium on modern medical education practices,
                  attended by faculty, students, and delegates from medical institutions across Pakistan and abroad.
                </div>
                <div class="ec-footer">
                  <span class="ec-meta-item"><i class="bi bi-people"></i> 200+ Attendees</span>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- Event 5 -->
          <div class="col-md-6 ev-item fu" data-cat="society" data-year="2025" data-date="2025-01-28">
            <a class="event-card" href="event-single.php">
              <div class="ec-img-placeholder" style="background:linear-gradient(135deg,#E65100,#F57C00);">
                <i class="bi bi-heart-fill"></i>
                <span>Social Welfare</span>
              </div>
              <div style="position:relative;">
                <div class="ec-badges"
                  style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-society">Society</span>
                  <div class="ec-date-badge"><span class="ec-date-day">28</span><span class="ec-date-month">Jan</span>
                  </div>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title">SWS Blood Donation Drive — Winter 2025</div>
                <div class="ec-excerpt">The Social Welfare Society conducted a successful blood donation drive with over
                  150 units collected, directly contributing to blood banks at PMC's affiliated teaching hospitals.
                </div>
                <div class="ec-footer">
                  <span class="ec-meta-item"><i class="bi bi-droplet-half"></i> 150+ Units</span>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

          <!-- Event 6 -->
          <div class="col-md-6 ev-item fu" data-cat="general" data-year="2024" data-date="2024-12-05">
            <a class="event-card" href="event-single.php">
              <div class="ec-img-placeholder" style="background:linear-gradient(135deg,#37474F,#546E7A);">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Convocation</span>
              </div>
              <div style="position:relative;">
                <div class="ec-badges"
                  style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-general">General</span>
                  <div class="ec-date-badge"><span class="ec-date-day">05</span><span class="ec-date-month">Dec</span>
                  </div>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title">Annual Convocation 2024 — Graduation Ceremony</div>
                <div class="ec-excerpt">PMC celebrated another cohort of graduating doctors at the Annual Convocation
                  2024, with degrees conferred to over 90 newly qualified MBBS graduates ready to serve Pakistan.</div>
                <div class="ec-footer">
                  <span class="ec-meta-item"><i class="bi bi-people"></i> 90+ Graduates</span>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>

        </div><!-- /#eventsGrid -->

        <!-- No results -->
        <div id="evNoResults" style="display:none;text-align:center;padding:60px 0;">
          <i class="bi bi-search" style="font-size:3rem;color:var(--gray-light);display:block;margin-bottom:14px;"></i>
          <h5 style="font-family:var(--font-head);color:var(--navy);">No events found</h5>
          <p style="font-family:var(--font-body);color:var(--gray-mid);font-size:.9rem;">Try different filters or search
            terms.</p>
          <button onclick="resetFilters()" class="btn-pmc btn-pmc-outline mt-3"
            style="font-size:.85rem;padding:9px 20px;">
            <i class="bi bi-x-circle"></i> Clear Filters
          </button>
        </div>

        <!-- Pagination -->
        <div class="events-pagination fu">
          <a class="pg-btn disabled" href="#"><i class="bi bi-chevron-left"></i></a>
          <a class="pg-btn active" href="#">1</a>
          <a class="pg-btn" href="#">2</a>
          <a class="pg-btn" href="#">3</a>
          <span class="pg-btn" style="border:none;background:none;color:var(--gray-mid);">…</span>
          <a class="pg-btn" href="#">8</a>
          <a class="pg-btn" href="#"><i class="bi bi-chevron-right"></i></a>
        </div>

      </div><!-- /.col-lg-8 -->

      <!-- ── RIGHT: Sidebar ────────────────────────────────────── -->
      <div class="col-lg-4">

        <!-- Upcoming Events -->
        <div class="sidebar-widget mb-4 fu">
          <div class="sw-head"><i class="bi bi-calendar-event"></i> Upcoming Events</div>
          <div class="sw-body" style="padding:16px;">

            <a class="upcoming-card" href="event-single.php">
              <div class="upcoming-date-block">
                <span class="udb-day">25</span>
                <span class="udb-month">Jul</span>
              </div>
              <div class="upcoming-body">
                <div class="ub-title">MDCAT Registration Deadline 2025</div>
                <div class="ub-venue"><i class="bi bi-geo-alt-fill"></i> PMC Admissions Office</div>
              </div>
            </a>

            <a class="upcoming-card" href="event-single.php">
              <div class="upcoming-date-block">
                <span class="udb-day">10</span>
                <span class="udb-month">Aug</span>
              </div>
              <div class="upcoming-body">
                <div class="ub-title">Orientation for New MBBS Students 2026-27</div>
                <div class="ub-venue"><i class="bi bi-geo-alt-fill"></i> PMC Main Auditorium</div>
              </div>
            </a>

            <a class="upcoming-card" href="event-single.php">
              <div class="upcoming-date-block">
                <span class="udb-day">02</span>
                <span class="udb-month">Sep</span>
              </div>
              <div class="upcoming-body">
                <div class="ub-title">Free Medical Camp — SWS Community Outreach</div>
                <div class="ub-venue"><i class="bi bi-geo-alt-fill"></i> Warsak Colony</div>
              </div>
            </a>

            <a class="upcoming-card" href="event-single.php">
              <div class="upcoming-date-block">
                <span class="udb-day">20</span>
                <span class="udb-month">Sep</span>
              </div>
              <div class="upcoming-body">
                <div class="ub-title">UMR Society Research Methodology Workshop</div>
                <div class="ub-venue"><i class="bi bi-geo-alt-fill"></i> PMC Seminar Room</div>
              </div>
            </a>

            <a class="upcoming-card" href="event-single.php">
              <div class="upcoming-date-block">
                <span class="udb-day">15</span>
                <span class="udb-month">Oct</span>
              </div>
              <div class="upcoming-body">
                <div class="ub-title">Literary Society Debating Competition 2025</div>
                <div class="ub-venue"><i class="bi bi-geo-alt-fill"></i> PMC Auditorium</div>
              </div>
            </a>

          </div>
        </div>

        <!-- Categories Widget -->
        <div class="sidebar-widget mb-4 fu">
          <div class="sw-head"><i class="bi bi-tags-fill"></i> Browse by Category</div>
          <div class="sw-body">
            <a class="sw-link" href="#" onclick="filterCat('admissions')"><i class="bi bi-mortarboard"></i>Admissions
              <span class="ms-auto pmc-tag" style="font-size:.68rem;">4</span></a>
            <a class="sw-link" href="#" onclick="filterCat('research')"><i class="bi bi-building"></i>Research <span
                class="ms-auto pmc-tag" style="font-size:.68rem;">8</span></a>
            <a class="sw-link" href="#" onclick="filterCat('achievement')"><i class="bi bi-award"></i>Achievements <span
                class="ms-auto pmc-tag" style="font-size:.68rem;">3</span></a>
            <a class="sw-link" href="#" onclick="filterCat('conference')"><i class="bi bi-mic"></i>Conferences <span
                class="ms-auto pmc-tag" style="font-size:.68rem;">5</span></a>
            <a class="sw-link" href="#" onclick="filterCat('society')"><i class="bi bi-people"></i>Societies <span
                class="ms-auto pmc-tag" style="font-size:.68rem;">6</span></a>
            <a class="sw-link" href="#" onclick="filterCat('general')"><i class="bi bi-newspaper"></i>General <span
                class="ms-auto pmc-tag" style="font-size:.68rem;">10</span></a>
          </div>
        </div>

      </div><!-- /.col-lg-4 sidebar -->

    </div><!-- /.row -->
  </div>
</section>


<!-- ═══ FOOTER ═══ -->
<?php include("includes/footer.php"); ?>

<button id="backToTop" aria-label="Back to top"><i class="bi bi-chevron-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/pmc-global.js"></script>
<script>
  /* ══════════════════════════════════════════════
     EVENTS PAGE SCRIPTS
  ══════════════════════════════════════════════ */

  // ── Category filter via pills ────────────────────────────────────
  const catPills = document.querySelectorAll('.cat-pill[data-cat]');
  let activeCat = 'all';

  catPills.forEach(pill => {
    pill.addEventListener('click', e => {
      e.preventDefault();
      activeCat = pill.dataset.cat;
      catPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      applyFilters();
    });
  });

  function filterCat(cat) {
    activeCat = cat;
    catPills.forEach(p => {
      p.classList.toggle('active', p.dataset.cat === cat);
    });
    applyFilters();
    document.getElementById('eventsGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // ── Search + Year filter ─────────────────────────────────────────
  document.getElementById('evSearch').addEventListener('input', applyFilters);
  document.getElementById('evYear').addEventListener('change', applyFilters);
  document.getElementById('evSort').addEventListener('change', applyFilters);

  function applyFilters() {
    const query = document.getElementById('evSearch').value.trim().toLowerCase();
    const year = document.getElementById('evYear').value;
    const items = [...document.querySelectorAll('.ev-item')];
    let visible = 0;

    items.forEach(item => {
      const cat = item.dataset.cat;
      const itemYear = item.dataset.year;
      const text = item.innerText.toLowerCase();

      const matchCat = activeCat === 'all' || cat === activeCat;
      const matchYear = !year || itemYear === year;
      const matchQ = !query || text.includes(query);

      const show = matchCat && matchYear && matchQ;
      item.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    document.getElementById('evNoResults').style.display = visible === 0 ? 'block' : 'none';
  }

  function resetFilters() {
    document.getElementById('evSearch').value = '';
    document.getElementById('evYear').value = '';
    activeCat = 'all';
    catPills.forEach(p => p.classList.toggle('active', p.dataset.cat === 'all'));
    applyFilters();
  }

  // ── Newsletter subscribe ─────────────────────────────────────────
  function handleSubscribe(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type=submit]');
    btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Subscribed!';
    btn.disabled = true;
    btn.style.background = '#2E7D32';
    setTimeout(() => {
      btn.innerHTML = '<i class="bi bi-envelope-check"></i> Subscribe';
      btn.disabled = false;
      btn.style.background = '';
      e.target.reset();
    }, 3000);
  }

  // ── Fade-up observer ─────────────────────────────────────────────
  const fuEls = document.querySelectorAll('.fu');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
  }, { threshold: 0.08 });
  fuEls.forEach(el => obs.observe(el));

  // ── Back to top + navbar scroll ──────────────────────────────────
  const btt = document.getElementById('backToTop');
  const nav = document.getElementById('mainNav');
  window.addEventListener('scroll', () => {
    btt.classList.toggle('visible', scrollY > 500);
    nav.classList.toggle('scrolled', scrollY > 40);
  });
  btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>