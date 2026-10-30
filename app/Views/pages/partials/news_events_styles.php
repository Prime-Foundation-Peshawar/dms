<?php /* Shared styles: events listing and all-news (filter bar, pills, featured card, cards, sidebar). */ ?>
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
    box-shadow: 0 0 0 3px rgba(194,65,12, .12);
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
    background: rgba(60, 70, 80, .85);
  }

  .cat-convocation {
    background: rgba(21, 95, 122, .9);
  }

  .cat-admissions {
    background: rgba(194,65,12, .85);
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
    box-shadow: 0 4px 18px rgba(194,65,12, .35);
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
