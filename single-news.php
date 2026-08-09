<?php include("includes/header.php"); ?>

<style>
  /* ── Reading Progress ── */
  .reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    z-index: 9999;
    background: var(--border);
  }

  .reading-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--teal), var(--gold));
    width: 0%;
    transition: width .1s linear;
  }

  /* ── Article Layout ── */
  .article-hero {
    width: 100%;
    height: 420px;
    border-radius: var(--r-lg);
    overflow: hidden;
    margin-bottom: 32px;
    position: relative;
    background-image: linear-gradient(135deg, var(--navy), var(--navy-mid));
    background-size: cover;
    background-position: center;
  }

  .article-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(10, 22, 40, .1) 0%, rgba(10, 22, 40, .55) 100%);
  }

  .article-hero-placeholder {
    width: 100%;
    height: 420px;
    border-radius: var(--r-lg);
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 14px;
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
  }

  .article-hero-placeholder i {
    font-size: 4rem;
    color: rgba(255, 255, 255, .2);
  }

  .article-hero-placeholder span {
    font-family: var(--font-head);
    font-size: .8rem;
    color: rgba(255, 255, 255, .2);
    text-transform: uppercase;
    letter-spacing: .1em;
  }

  /* ── Article Header ── */
  .article-cat-badge {
    display: inline-block;
    font-family: var(--font-head);
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: white;
    background: var(--teal);
    padding: 4px 13px;
    border-radius: 100px;
    margin-bottom: 14px;
  }

  .article-title {
    font-family: var(--font-head);
    font-size: clamp(1.55rem, 3.5vw, 2.2rem);
    font-weight: 900;
    color: var(--navy);
    line-height: 1.18;
    margin-bottom: 18px;
  }

  .article-byline {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 0;
  }

  .byline-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-body);
    font-size: .82rem;
    font-weight: 600;
    color: var(--gray-mid);
  }

  .byline-item i {
    color: var(--teal);
    font-size: .88rem;
  }

  .byline-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--border);
  }

  /* ── Share Strip ── */
  .share-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    margin: 22px 0 30px;
    flex-wrap: wrap;
  }

  .share-label {
    font-family: var(--font-head);
    font-size: .7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--gray-mid);
  }

  .share-btn {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-dark);
    font-size: .88rem;
    text-decoration: none;
    cursor: pointer;
    background: white;
    transition: all .2s;
  }

  .share-btn:hover {
    color: white;
    border-color: transparent;
  }

  .share-btn.fb:hover {
    background: #1877F2;
  }

  .share-btn.tw:hover {
    background: #000;
  }

  .share-btn.wa:hover {
    background: #25D366;
  }

  .share-btn.li:hover {
    background: #0A66C2;
  }

  .share-btn.cp:hover {
    background: var(--teal);
  }

  /* ── Article Body ── */
  .article-body {
    font-family: var(--font-body);
    font-size: 1rem;
    color: var(--gray-dark);
    line-height: 1.88;
    font-weight: 500;
  }

  .article-body p {
    margin-bottom: 20px;
  }

  .article-body h3 {
    font-family: var(--font-head);
    font-size: 1.22rem;
    font-weight: 800;
    color: var(--navy);
    margin: 34px 0 13px;
  }

  .article-body h4 {
    font-family: var(--font-head);
    font-size: 1.03rem;
    font-weight: 700;
    color: var(--navy);
    margin: 26px 0 10px;
  }

  .article-body ul,
  .article-body ol {
    padding-left: 0;
    list-style: none;
    margin-bottom: 20px;
  }

  .article-body ul li,
  .article-body ol li {
    padding-left: 22px;
    position: relative;
    margin-bottom: 9px;
    line-height: 1.7;
  }

  .article-body ul li::before {
    content: '›';
    position: absolute;
    left: 0;
    color: var(--teal);
    font-weight: 700;
    font-size: 1.1rem;
  }

  .article-body ol {
    counter-reset: item;
  }

  .article-body ol li {
    counter-increment: item;
  }

  .article-body ol li::before {
    content: counter(item)'.';
    position: absolute;
    left: 0;
    color: var(--teal);
    font-weight: 800;
    font-size: .85rem;
  }

  .article-body blockquote {
    border-left: 4px solid var(--teal);
    background: var(--teal-pale);
    padding: 20px 24px;
    border-radius: 0 var(--r-sm) var(--r-sm) 0;
    margin: 28px 0;
  }

  .article-body blockquote p {
    font-style: italic;
    color: var(--navy);
    font-size: 1.04rem;
    font-weight: 600;
    margin: 0;
  }

  .article-body blockquote cite {
    display: block;
    margin-top: 8px;
    font-family: var(--font-head);
    font-size: .74rem;
    font-weight: 700;
    color: var(--teal);
    text-transform: uppercase;
    letter-spacing: .07em;
    font-style: normal;
  }

  .article-body a {
    color: var(--teal);
    font-weight: 700;
    text-decoration: underline;
    text-underline-offset: 3px;
  }

  /* Info box */
  .info-box {
    background: var(--teal-pale);
    border: 1px solid rgba(0, 168, 150, .22);
    border-radius: var(--r-md);
    padding: 22px 24px;
    margin: 28px 0;
  }

  .info-box-title {
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 800;
    color: var(--teal);
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .info-box-title i {
    font-size: 1rem;
  }

  /* Tags */
  .article-tags {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding: 18px 0;
    border-top: 1px solid var(--border);
    margin-top: 36px;
  }

  .tag-label {
    font-family: var(--font-head);
    font-size: .68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--gray-mid);
  }

  .article-tag {
    font-family: var(--font-body);
    font-size: .75rem;
    font-weight: 700;
    background: var(--gray-light);
    color: var(--gray-dark);
    padding: 4px 13px;
    border-radius: 100px;
    text-decoration: none;
    transition: all .2s;
  }

  .article-tag:hover {
    background: var(--teal-pale);
    color: var(--teal);
  }

  /* Author box */
  .author-box {
    background: var(--off-white);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 26px;
    display: flex;
    gap: 18px;
    align-items: flex-start;
    margin-top: 34px;
  }

  .author-avatar {
    width: 66px;
    height: 66px;
    flex-shrink: 0;
    background: var(--navy);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold);
    font-size: 1.5rem;
  }

  .author-name {
    font-family: var(--font-head);
    font-size: .93rem;
    font-weight: 800;
    color: var(--navy);
    margin-bottom: 2px;
  }

  .author-role {
    font-family: var(--font-body);
    font-size: .73rem;
    font-weight: 600;
    color: var(--teal);
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 8px;
  }

  .author-bio {
    font-family: var(--font-body);
    font-size: .84rem;
    color: var(--gray-mid);
    line-height: 1.6;
  }

  /* Related */
  .related-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform .25s, box-shadow .25s, border-color .25s;
  }

  .related-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--teal);
    color: inherit;
  }

  .rc-img {
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .rc-img i {
    font-size: 2.2rem;
    color: rgba(255, 255, 255, .3);
  }

  .rc-body {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .rc-cat {
    font-family: var(--font-head);
    font-size: .62rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--teal);
    margin-bottom: 5px;
  }

  .rc-title {
    font-family: var(--font-head);
    font-size: .84rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.38;
    flex: 1;
  }

  .rc-date {
    font-family: var(--font-body);
    font-size: .72rem;
    color: var(--gray-mid);
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .rc-date i {
    color: var(--teal);
  }

  /* Prev / Next */
  .prev-next {
    display: flex;
    gap: 12px;
    margin-top: 40px;
    flex-wrap: wrap;
  }

  .pn-card {
    flex: 1;
    min-width: 200px;
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    padding: 16px 18px;
    text-decoration: none;
    color: inherit;
    transition: border-color .2s, box-shadow .2s;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .pn-card:hover {
    border-color: var(--teal);
    box-shadow: var(--shadow-sm);
    color: inherit;
  }

  .pn-dir {
    font-family: var(--font-head);
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--gray-mid);
    margin-bottom: 3px;
  }

  .pn-title {
    font-family: var(--font-head);
    font-size: .85rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .pn-icon {
    font-size: 1.3rem;
    color: var(--teal);
    flex-shrink: 0;
  }

  /* TOC Sidebar */
  .toc-widget {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    overflow: hidden;
    margin-bottom: 22px;
  }

  .toc-head {
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

  .toc-head i {
    color: var(--gold);
  }

  .toc-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    font-family: var(--font-body);
    font-size: .84rem;
    font-weight: 600;
    color: var(--navy);
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all .2s;
  }

  .toc-link:hover,
  .toc-link.active {
    background: var(--teal-pale);
    color: var(--teal);
    border-left-color: var(--teal);
  }

  .toc-link i {
    color: var(--teal);
    font-size: .76rem;
    flex-shrink: 0;
  }

  .toc-num {
    font-family: var(--font-head);
    font-size: .62rem;
    font-weight: 800;
    color: rgba(255, 255, 255, .7);
    background: var(--teal);
    width: 19px;
    height: 19px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-left: auto;
  }

  @media(max-width:767.98px) {

    .article-hero,
    .article-hero-placeholder {
      height: 240px;
    }

    .author-box {
      flex-direction: column;
    }

    .prev-next {
      flex-direction: column;
    }
  }
</style>

<!-- Reading progress bar -->
<div class="reading-progress">
  <div class="reading-progress-fill" id="readingBar"></div>
</div>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>News &amp; Events</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="all-news.php">News &amp; Events</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Achievement</span>
    </div>
  </div>
</div>

<!-- ARTICLE -->
<section class="pmc-section bg-off">
  <div class="container">
    <div class="row g-5">

      <!-- ── ARTICLE MAIN ──────────────────────────────────── -->
      <div class="col-lg-8">

        <!-- Header -->
        <div class="fu">
          <div class="article-cat-badge">Achievement</div>
          <h1 class="article-title">PMC Retains #1 Ranking Among Private Medical Colleges in KP — Over 80% Score in
            PM&DC Inspection</h1>
          <div class="article-byline">
            <span class="byline-item"><i class="bi bi-person-circle"></i>PMC Communications Office</span>
            <div class="byline-dot"></div>
            <span class="byline-item"><i class="bi bi-calendar3"></i>Wednesday, May 15, 2025</span>
            <div class="byline-dot"></div>
            <span class="byline-item"><i class="bi bi-clock"></i>3 min read</span>
            <div class="byline-dot"></div>
            <span class="byline-item"><i class="bi bi-eye"></i>2,140 views</span>
          </div>
        </div>

        <!-- Share strip -->
        <div class="share-strip fu">
          <span class="share-label">Share</span>
          <a href="#" class="share-btn fb" title="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="share-btn tw" title="X / Twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="share-btn wa" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a href="#" class="share-btn li" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <button class="share-btn cp" title="Copy link" onclick="copyLink(this)"><i
              class="bi bi-link-45deg"></i></button>
          <div class="ms-auto">
            <span class="byline-item" style="font-size:.78rem;cursor:pointer;">
              <i class="bi bi-bookmark-plus" style="color:var(--teal);"></i> Save Article
            </span>
          </div>
        </div>

        <!-- Hero image (using placeholder as there's no real image) -->
        <div class="article-hero-placeholder fu">
          <i class="bi bi-award-fill"></i>
          <span>PMC Achievement — May 2025</span>
        </div>

        <!-- Article Body -->
        <div class="article-body fu" id="articleBody">

          <p>Peshawar Medical College has once again been ranked <strong>first among all private medical colleges in
              Khyber Pakhtunkhwa</strong>, following the latest inspection by the Pakistan Medical &amp; Dental Council
            (PM&amp;DC). The college secured a score exceeding <strong>80%</strong> — the highest recorded by any
            private medical institution in the province — reaffirming PMC's position as the benchmark of private medical
            education in KP.</p>

          <p>This achievement comes in the wake of an extensive and rigorous multi-day inspection covering every facet
            of college operations: faculty qualifications, clinical exposure at affiliated teaching hospitals,
            laboratory and library infrastructure, student welfare services, and academic outcomes.</p>

          <h3 id="inspection">About the PM&amp;DC Inspection</h3>

          <p>PM&amp;DC conducts annual inspections of all recognised medical colleges to ensure compliance with national
            standards for medical education. The inspection evaluates institutions across several mandatory domains:</p>

          <ul>
            <li><strong>Faculty Strength &amp; Qualifications</strong> — Number of qualified professors, associate
              professors, assistant professors per department</li>
            <li><strong>Clinical Training Facilities</strong> — Adequacy of affiliated teaching hospitals and
              outpatient/inpatient case load</li>
            <li><strong>Infrastructure</strong> — Lecture halls, laboratories, library, skills labs, and student
              facilities</li>
            <li><strong>Academic Outcomes</strong> — Professional examination pass rates and student research output
            </li>
            <li><strong>Student Welfare</strong> — Hostels, cafeteria, counseling services, and transport</li>
          </ul>

          <blockquote>
            <p>"This recognition is a reflection of the combined dedication of our faculty, staff, and students. PMC has
              always placed excellence at the forefront — and this ranking affirms that our approach to medical
              education is working."</p>
            <cite>— Principal, Peshawar Medical College</cite>
          </blockquote>

          <h3 id="key-strengths">Key Strengths Highlighted</h3>

          <p>The PM&amp;DC inspection team noted several areas where PMC demonstrated exceptional performance:</p>

          <div class="info-box">
            <div class="info-box-title"><i class="bi bi-patch-check-fill"></i> PMC Inspection Highlights — 2025</div>
            <ol>
              <li><strong>Faculty-to-Student Ratio</strong> — Among the best maintained ratios in private sector medical
                colleges in KP</li>
              <li><strong>Three Affiliated Teaching Hospitals</strong> — Kuwait, Mercy, and Prime Teaching Hospitals
                provide exceptional clinical diversity</li>
              <li><strong>Research Culture</strong> — Active undergraduate research program with verified student
                publications</li>
              <li><strong>Modern Clinical Skills Laboratory</strong> — Simulation-based training facility recognised for
                its equipment and usage rates</li>
              <li><strong>Student Welfare Programs</strong> — Comprehensive support including counseling, day care,
                hostel, and transportation</li>
            </ol>
          </div>

          <h4 id="looking-ahead">Commitment Going Forward</h4>

          <p>The PMC administration has announced plans to further expand facilities in response to the inspection
            findings, including upgrades to the library and learning resource centre, additional simulation equipment
            for the clinical skills lab, and expanded hostel capacity for the incoming MBBS batch of 2025–26.</p>

          <p>PMC continues to welcome applications from local, overseas Pakistani, and international students for the
            upcoming MBBS Session 2025–26. With limited seats remaining, eligible students are encouraged to <a
              href="admissions.php">apply now</a>.</p>

          <!-- Tags -->
          <div class="article-tags">
            <span class="tag-label">Tags:</span>
            <a class="article-tag" href="all-news.php">Achievement</a>
            <a class="article-tag" href="all-news.php">PM&amp;DC</a>
            <a class="article-tag" href="all-news.php">Ranking</a>
            <a class="article-tag" href="all-news.php">PMC</a>
            <a class="article-tag" href="all-news.php">KP Medical Colleges</a>
          </div>

        </div><!-- /.article-body -->

        <!-- Author Box -->
        <div class="author-box fu">
          <div class="author-avatar"><i class="bi bi-building"></i></div>
          <div>
            <div class="author-name">PMC Communications Office</div>
            <div class="author-role">Official · Peshawar Medical College</div>
            <div class="author-bio">Official news and communications from Peshawar Medical College administration.
              For enquiries: <a href="mailto:info@pmc.edu.pk" style="color:var(--teal);">info@pmc.edu.pk</a></div>
          </div>
        </div>

        <!-- Related News (static) -->
        <div class="mt-5 fu">
          <h3
            style="font-family:var(--font-head);font-size:1.12rem;font-weight:800;color:var(--navy);margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid var(--border);">
            <i class="bi bi-grid-3x3-gap me-2" style="color:var(--teal);"></i>You May Also Like
          </h3>
          <div class="row g-3">
            <div class="col-md-4">
              <a class="related-card" href="single-news.php">
                <div class="rc-img" style="background:linear-gradient(135deg,#0A1628,#1a3a6b);"><i
                    class="bi bi-mortarboard-fill"></i></div>
                <div class="rc-body">
                  <div class="rc-cat">Admissions</div>
                  <div class="rc-title">MBBS Admissions Open for Session 2025–26</div>
                  <div class="rc-date"><i class="bi bi-calendar3"></i>June 15, 2025</div>
                </div>
              </a>
            </div>
            <div class="col-md-4">
              <a class="related-card" href="single-news.php">
                <div class="rc-img" style="background:linear-gradient(135deg,#00695C,#00897B);"><i
                    class="bi bi-flask-fill"></i></div>
                <div class="rc-body">
                  <div class="rc-cat">Research</div>
                  <div class="rc-title">UMR Society Annual Medical Research Conference 2025</div>
                  <div class="rc-date"><i class="bi bi-calendar3"></i>April 22, 2025</div>
                </div>
              </a>
            </div>
            <div class="col-md-4">
              <a class="related-card" href="single-news.php">
                <div class="rc-img" style="background:linear-gradient(135deg,#1565C0,#1976D2);"><i
                    class="bi bi-mic-fill"></i></div>
                <div class="rc-body">
                  <div class="rc-cat">Conference</div>
                  <div class="rc-title">International Medical Education Symposium 2025</div>
                  <div class="rc-date"><i class="bi bi-calendar3"></i>Feb 18, 2025</div>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- Prev / Next (static) -->
        <div class="prev-next fu">
          <a class="pn-card" href="single-news.php">
            <i class="bi bi-chevron-left pn-icon"></i>
            <div>
              <div class="pn-dir">Previous Article</div>
              <div class="pn-title">International Medical Education Symposium 2025</div>
            </div>
          </a>
          <a class="pn-card" href="single-news.php" style="justify-content:flex-end;text-align:right;">
            <div>
              <div class="pn-dir">Next Article</div>
              <div class="pn-title">MBBS Admissions Open for Session 2025–26</div>
            </div>
            <i class="bi bi-chevron-right pn-icon"></i>
          </a>
        </div>

      </div><!-- /.col-lg-8 -->

      <!-- ── SIDEBAR (static) ───────────────────────────── -->
      <div class="col-lg-4">
        <div style="position:sticky;top:90px;">

          <!-- TOC -->
          <div class="toc-widget fu">
            <div class="toc-head"><i class="bi bi-list-ul"></i> In This Article</div>
            <div>
              <a class="toc-link active" href="#inspection"><i class="bi bi-chevron-right"></i>PM&amp;DC Inspection<span
                  class="toc-num">1</span></a>
              <a class="toc-link" href="#key-strengths"><i class="bi bi-chevron-right"></i>Key Strengths<span
                  class="toc-num">2</span></a>
              <a class="toc-link" href="#looking-ahead"><i class="bi bi-chevron-right"></i>Commitment Ahead<span
                  class="toc-num">3</span></a>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="sidebar-widget fu">
            <div class="sw-head" style="background:var(--teal);"><i class="bi bi-mortarboard-fill"></i> Quick Actions
            </div>
            <div class="sw-body" style="padding:16px 18px;">
              <a href="admissions.php" class="btn-pmc btn-pmc-primary w-100 justify-content-center mb-2"
                style="font-size:.82rem;padding:11px;">Apply for MBBS 2025–26</a>
              <a href="vacant-seats.php" class="btn-pmc btn-pmc-outline w-100 justify-content-center mb-2"
                style="font-size:.82rem;padding:11px;">Check Vacant Seats</a>
              <a href="faculty-all.php" class="btn-pmc btn-pmc-navy w-100 justify-content-center"
                style="font-size:.82rem;padding:11px;"><i class="bi bi-people me-1"></i>Our Faculty</a>
            </div>
          </div>

          <!-- Browse More -->
          <div class="sidebar-widget fu mt-3">
            <div class="sw-head"><i class="bi bi-newspaper"></i> Browse More</div>
            <div class="sw-body">
              <a class="sw-link" href="all-news.php"><i class="bi bi-grid" style="color:var(--teal);"></i>All News &amp;
                Events</a>
              <a class="sw-link" href="events.html"><i class="bi bi-calendar-event" style="color:var(--teal);"></i>Event
                Calendar</a>
              <a class="sw-link" href="newsletter.php"><i class="bi bi-envelope-paper"
                  style="color:var(--teal);"></i>Newsletter Archive</a>
              <a class="sw-link" href="gallery.html"><i class="bi bi-images" style="color:var(--teal);"></i>Photo
                Gallery</a>
            </div>
          </div>

        </div>
      </div>

    </div><!-- /.row -->
  </div>
</section>

<!-- Recognition strip (static) -->
<section class="pmc-section-sm recog-strip">
  <div class="container">
    <div class="recog-grid">
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-patch-check-fill"></i></div>
        <div class="recog-name">PM&DC</div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-building-fill"></i></div>
        <div class="recog-name">Riphah International University</div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-award-fill"></i></div>
        <div class="recog-name">CPSP</div>
      </div>
      <div class="recog-cell">
        <div class="recog-ico"><i class="bi bi-hospital-fill"></i></div>
        <div class="recog-name">Ministry of Health</div>
      </div>
      <div class="recog-cell" style="border-right:none;">
        <div class="recog-ico"><i class="bi bi-globe-americas"></i></div>
        <div class="recog-name">WHO</div>
      </div>
    </div>
  </div>
</section>

<?php include("includes/footer.php"); ?>

<script>
  /* Reading progress */
  function updateBar() {
    const ab = document.getElementById('articleBody');
    if (!ab) return;
    const pct = Math.min(100, Math.max(0, ((scrollY - ab.offsetTop + innerHeight * .5) / ab.offsetHeight) * 100));
    document.getElementById('readingBar').style.width = pct + '%';
  }

  /* TOC scroll-spy */
  const tocLinks = document.querySelectorAll('.toc-link');
  const headings = [...document.querySelectorAll('.article-body h3[id], .article-body h4[id]')];
  function updateTOC() {
    let cur = '';
    headings.forEach(h => { if (scrollY >= h.offsetTop - 120) cur = h.id; });
    tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + cur));
  }
  tocLinks.forEach(l => {
    l.addEventListener('click', e => {
      const t = document.querySelector(l.getAttribute('href'));
      if (t) { e.preventDefault(); window.scrollTo({ top: t.offsetTop - 90, behavior: 'smooth' }); }
    });
  });

  window.addEventListener('scroll', () => { updateBar(); updateTOC(); }, { passive: true });

  /* Copy link */
  function copyLink(btn) {
    navigator.clipboard.writeText(location.href).then(() => {
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-check-lg"></i>';
      btn.style.cssText += 'background:var(--teal);border-color:var(--teal);color:white;';
      setTimeout(() => { btn.innerHTML = orig; btn.style.cssText = ''; }, 2000);
    });
  }

  /* Fade-up */
  const obs = new IntersectionObserver(e => e.forEach(x => { if (x.isIntersecting) { x.target.classList.add('vis'); obs.unobserve(x.target); } }), { threshold: .08 });
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