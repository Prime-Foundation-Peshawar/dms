<?php /* Shared styles: event-single and single-news detail pages. */ ?>
<style>
  .article-header { margin-bottom: 22px; }
  .article-cat-badge {
    display: inline-block; font-family: var(--font-head); font-size: .72rem; font-weight: 800;
    letter-spacing: .06em; text-transform: uppercase; color: var(--teal); background: var(--teal-pale);
    padding: 5px 12px; border-radius: 100px; margin-bottom: 12px;
  }
  .article-title {
    font-family: var(--font-head); font-size: 1.85rem; font-weight: 900; color: var(--navy);
    line-height: 1.25; margin-bottom: 14px;
  }
  .article-byline {
    display: flex; flex-wrap: wrap; gap: 10px 16px; align-items: center;
    font-family: var(--font-body); font-size: .85rem; color: var(--gray-mid);
  }
  .byline-item { display: inline-flex; align-items: center; gap: 6px; }
  .byline-item i { color: var(--teal); }
  .event-hero-img {
    width: 100%; max-height: 420px; object-fit: cover; border-radius: var(--r-lg);
    margin: 22px 0; box-shadow: var(--shadow-sm);
  }
  .event-hero-placeholder {
    width: 100%; min-height: 220px; border-radius: var(--r-lg); margin: 22px 0;
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;
    color: rgba(255,255,255,.55);
  }
  .event-hero-placeholder i { font-size: 2.4rem; }
  .event-meta-bar {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;
    background: white; border: 1px solid var(--border); border-radius: var(--r-lg);
    padding: 18px; margin-bottom: 28px;
  }
  .emb-item { display: flex; gap: 12px; align-items: flex-start; }
  .emb-icon {
    width: 40px; height: 40px; border-radius: 10px; background: var(--teal-pale); color: var(--teal);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .emb-label {
    display: block; font-size: .7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: var(--gray-mid); margin-bottom: 2px;
  }
  .emb-value { font-family: var(--font-head); font-weight: 700; color: var(--navy); font-size: .92rem; }
  .article-body {
    font-family: var(--font-body); font-size: .98rem; line-height: 1.75; color: var(--gray-dark);
    background: white; border: 1px solid var(--border); border-radius: var(--r-lg); padding: 28px 30px;
  }
  .article-body p { margin-bottom: 1rem; }
  .article-body h2, .article-body h3, .article-body h4 {
    font-family: var(--font-head); color: var(--navy); margin-top: 1.4rem; margin-bottom: .7rem;
  }
  .event-media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-top: 22px; }
  .event-media-grid img {
    width: 100%; height: 140px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border);
  }
  .upcoming-card {
    display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit;
  }
  .upcoming-card:last-child { border-bottom: none; }
  .upcoming-date-block {
    width: 52px; border-radius: 10px; background: var(--navy); color: white; text-align: center;
    padding: 8px 4px; flex-shrink: 0;
  }
  .udb-day { display: block; font-family: var(--font-head); font-weight: 900; font-size: 1.1rem; line-height: 1; }
  .udb-month { display: block; font-size: .68rem; font-weight: 700; text-transform: uppercase; opacity: .8; margin-top: 2px; }
  .ub-title { font-family: var(--font-head); font-weight: 700; font-size: .9rem; color: var(--navy); margin-bottom: 3px; }
  .ub-venue { font-size: .75rem; color: var(--gray-mid); }
</style>
