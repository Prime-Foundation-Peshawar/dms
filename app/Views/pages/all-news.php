<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<style>
  /* â•â• FILTER BAR â•â• */
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
    box-shadow: 0 0 0 3px rgba(21,95,122, .12);
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

  /* â•â• CAT PILLS â•â• */
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

  /* â•â• FEATURED CARD â•â• */
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

  /* â•â• NEWS CARD â•â• */
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

  /* â•â• CATEGORY BADGE COLORS â•â• */
  .cat-admissions {
    background: rgba(21,95,122, .85);
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

  /* â•â• SIDEBAR â•â• */
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

  /* â•â• EMPTY â•â• */
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

  /* â•â• PAGINATION â•â• */
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




<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>News &amp; Events</h1>
    <div class="breadcrumb-pmc">
      <a href="./">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">News</span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="results-bar fu">
          <i class="bi bi-newspaper" style="color:var(--teal);"></i>
          Showing <strong><?= (int) $total ?></strong> of <strong><?= (int) $total ?></strong> items
        </div>

        <?php if ($featured):
          $fHref = dms_cms_public_href($featured, "single-news");
          $parts = dms_cms_date_parts($featured["published_at"] ?? null);
        ?>
        <div class="featured-news-card fu" style="margin-bottom:36px;">
          <div class="fnc-placeholder" style="background:<?= htmlspecialchars($featured["card_gradient"] ?? "linear-gradient(135deg,#0A1628,#1a3a6b)") ?>;">
            <i class="bi <?= htmlspecialchars($featured["card_icon"] ?? "bi-newspaper") ?>" style="font-size:5rem;color:rgba(255,255,255,.15);"></i>
          </div>
          <div class="fnc-body">
            <div class="fnc-eyebrow"><i class="bi bi-star-fill"></i> Featured · <?= htmlspecialchars(dms_cms_category_label((string) ($featured["category"] ?? ""))) ?></div>
            <h2 class="fnc-title"><?= htmlspecialchars($featured["title"] ?? "") ?></h2>
            <p class="fnc-excerpt"><?= htmlspecialchars($featured["excerpt"] ?? "") ?></p>
            <div class="fnc-meta">
              <?php if ($d = dms_cms_format_date($featured["published_at"] ?? null)): ?><span><i class="bi bi-calendar3"></i> <?= htmlspecialchars($d) ?></span><?php endif; ?>
              <?php if (!empty($featured["author"])): ?><span><i class="bi bi-person"></i> <?= htmlspecialchars($featured["author"]) ?></span><?php endif; ?>
              <?php if (!empty($featured["read_minutes"])): ?><span><i class="bi bi-clock"></i> <?= (int) $featured["read_minutes"] ?> min read</span><?php endif; ?>
            </div>
            <a href="<?= htmlspecialchars($fHref) ?>" class="btn-pmc btn-pmc-gold" style="align-self:flex-start;">
              <i class="bi bi-arrow-right-circle"></i> Read Full Article
            </a>
          </div>
        </div>
        <?php endif; ?>

        <div class="row g-4" id="newsGrid">
          <?php foreach ($posts as $item):
            $href = dms_cms_public_href($item, "single-news");
            $parts = dms_cms_date_parts($item["published_at"] ?? null);
            $ext = preg_match("#^https?://#i", $href);
          ?>
          <div class="col-md-6 news-item" data-cat="<?= htmlspecialchars($item["category"] ?? "") ?>">
            <a class="news-card" href="<?= htmlspecialchars($href) ?>" <?= $ext ? "target=\"_blank\" rel=\"noopener\"" : "" ?>>
              <div class="nc-img-placeholder" style="background:<?= htmlspecialchars($item["card_gradient"] ?? "linear-gradient(135deg,#0A1628,#1a3a6b)") ?>;">
                <i class="bi <?= htmlspecialchars($item["card_icon"] ?? "bi-newspaper") ?>"></i><span><?= htmlspecialchars(dms_cms_category_label((string) ($item["category"] ?? ""))) ?></span>
                <?php if ($parts["day"] !== ""): ?>
                <div class="nc-date-pill"><span class="day"><?= htmlspecialchars($parts["day"]) ?></span><span class="month"><?= htmlspecialchars($parts["month"]) ?></span></div>
                <?php endif; ?>
              </div>
              <div class="nc-body">
                <div class="nc-title"><?= htmlspecialchars($item["title"] ?? "") ?></div>
                <div class="nc-excerpt"><?= htmlspecialchars($item["excerpt"] ?? "") ?></div>
                <div class="nc-footer">
                  <span class="nc-author"><i class="bi bi-person-circle"></i><?= htmlspecialchars($item["author"] ?? "PMC") ?></span>
                  <span class="nc-read"><?= !empty($item["read_minutes"]) ? ((int) $item["read_minutes"] . " min read") : "Read" ?> <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="col-lg-4">
        <aside class="news-sidebar fu">
          <div class="nsw-card">
            <h4 class="nsw-title">Categories</h4>
            <a class="nsw-link" href="all-news"><i class="bi bi-grid"></i>All News <span class="ms-auto pmc-tag"><?= (int) $total ?></span></a>
            <?php foreach ($cats as $cat => $count): ?>
              <a class="nsw-link" href="all-news"><i class="bi bi-chevron-right"></i><?= htmlspecialchars(dms_cms_category_label($cat)) ?> <span class="ms-auto pmc-tag"><?= (int) $count ?></span></a>
            <?php endforeach; ?>
          </div>
          <div class="nsw-card">
            <h4 class="nsw-title">Recent</h4>
            <?php foreach (array_slice($posts, 0, 5) as $r): ?>
              <a class="recent-item" href="<?= htmlspecialchars(dms_cms_public_href($r, "single-news")) ?>">
                <div class="ri-title"><?= htmlspecialchars($r["title"] ?? "") ?></div>
                <div class="ri-date"><?= htmlspecialchars(dms_cms_format_date($r["published_at"] ?? null)) ?></div>
              </a>
            <?php endforeach; ?>
          </div>
        </aside>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
