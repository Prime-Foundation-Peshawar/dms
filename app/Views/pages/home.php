<?php
/** Auto-ported content body from legacy index.php */
?>
<?php



$hospitals = [
  [
    'id'           => 'kth',
    'code'         => 'KTH',
    'name'         => 'Kuwait Teaching Hospital',
    'desc'         => 'Major tertiary care centre providing comprehensive clinical rotations across medical and surgical specialties.',
    'url'          => 'https://kth.riphahpsh.edu.pk/',
    'dept_url'     => 'https://kth.prime.edu.pk/index.php',
    'services_url' => 'https://kth.prime.edu.pk/serviceskth.html',
    'highlights'   => ['Cardiology & CCU', 'Angioplasty 24/7', 'CT & MRI', 'Dialysis'],
    'departments'  => [
      'Medicine & Allied',
      'Family Medicine & Allied',
      'Medical ICU & Dialysis',
      'Surgery & Allied',
      'Surgical HDU',
      'Cardiology & CCU',
      'Gynaecology & Obstetrics',
      'Paediatrics',
      'Ophthalmology',
      'ENT',
      'Dermatology',
      'Radiology',
    ],
    'services' => [
      'Outpatient Department (OPD)',
      'Casualty / Emergency',
      'Laboratory',
      'X-Ray',
      'ECG',
      'Angiography',
      'Angioplasty',
      'Operation Theatre',
      'Pharmacy',
      'Ultrasound',
      'ECHO',
      'ETT',
      'CT Scan',
      'MRI',
      'Liver Cancer Treatment',
      'Endoscopy / Colonoscopy',
      'Dialysis',
      'Labour Room',
    ],
    'facilities' => [],
  ],
  [
    'id'           => 'mth',
    'code'         => 'MTH',
    'name'         => 'Mercy Teaching Hospital',
    'desc'         => 'Community-focused hospital with high patient volume, ideal for broad, everyday clinical experience.',
    'url'          => 'https://mth.riphahpsh.edu.pk/',
    'dept_url'     => 'https://mth.prime.edu.pk/index.php',
    'services_url' => 'https://mth.prime.edu.pk/services2mth.php',
    'highlights'   => ['Gynae & Obstetrics', 'Nursery / NICU', 'Emergency 24/7', 'Psychiatry'],
    'departments'  => [
      'General Medicine',
      'Pulmonology',
      'General Surgery',
      'Gynaecology & Obstetrics',
      'Paediatrics',
      'Nursery / NICU',
      'Orthopaedics',
      'Endocrinology',
      'Ophthalmology',
      'Dermatology',
      'Radiology',
      'Anaesthesiology',
      'Pathology',
      'ICU',
      'Psychiatry',
    ],
    'services' => [
      'Outpatient Department (OPD)',
      'Casualty / Emergency',
      'Laboratory',
      'X-Ray',
      'ECG / ECHO',
      'Operation Theatre',
      'Pharmacy',
      'Ultrasound',
      'Labour Room',
    ],
    'facilities' => [],
  ],
  [
    'id'           => 'pth',
    'code'         => 'PTH',
    'name'         => 'Prime Teaching Hospital',
    'desc'         => 'Modern diagnostic and surgical facilities for intensive clinical and surgical training rotations.',
    'url'          => 'https://pth.riphahpsh.edu.pk/',
    'dept_url'     => 'https://pth.riphahpsh.edu.pk/prm_opd.php',
    'services_url' => 'https://pth.riphahpsh.edu.pk/prm_services.php',
    'highlights'   => ['Neurosurgery', 'Cardiac Surgery', 'ERCP & Endoscopy', 'CT & MRI'],
    'departments'  => [
      'Gastroenterology & Hepatology',
      'Medicine',
      'Neurosurgery',
      'Neurology',
      'Orthopaedics',
      'Urology',
      'Nephrology',
      'Paediatrics',
      'Gynaecology & Obstetrics',
      'General Surgery',
      'Vascular & Endovascular Surgery',
      'Pulmonology',
      'Radiology',
      'Ophthalmology',
      'Psychiatry',
      'Cardiology',
      'Cardiac Surgery',
      'Family Medicine',
      'Accident & Emergency',
    ],
    'services' => [
      'Specialist OPD',
      'General OPD',
      'Emergency (Casualty)',
      'ICU (Intensive Care Unit)',
      'Operation Theatre',
      'Laboratory',
      'Special Chemistry Lab Tests',
      'Pharmacy',
      'Ultrasound',
      'X-Ray',
      'CT Scan',
      'MRI',
      'Endoscopy, Colonoscopy & Sigmoidoscopy',
      'ERCP, PEG Tube & Metallic Stenting',
      'ECG',
      'ECHO',
      'ETT',
      'Physiotherapy',
      'Wards & Private Rooms',
    ],
    'facilities' => ['Cafeteria', 'Masjid', '24/7 Generator Support', 'Spacious Parking'],
  ],
];

if (!function_exists('hs_e')) {
  function hs_e($s)
  {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
  }
}


?>

<style>
  /* ═══ Home: Teaching Hospitals (extends existing .hosp-card) ═══ */
  .home-hospitals {
    --hh-gold: var(--gold-light, #e3c47d);
    --hh-teal: var(--teal, #0e9384);
    --hh-navy: var(--navy, #0b2545);
    --hh-line: rgba(255, 255, 255, .12);
  }

  /* Card additions */
  .hosp-card--rich .hosp-desc {
    flex-grow: 0;
    margin-bottom: 18px;
  }

  @media (min-width: 992px) {
    .hosp-card--rich .hosp-desc {
      min-height: calc(3 * 1.75em);
    }
  }

  .hosp-facts {
    display: flex;
    margin: 0 0 16px;
    padding: 14px 0;
    list-style: none;
    border-top: 1px solid var(--hh-line);
    border-bottom: 1px solid var(--hh-line);
  }

  .hosp-facts li {
    flex: 1;
    font-family: var(--font-head, 'Montserrat', sans-serif);
    font-size: .64rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, .55);
  }

  .hosp-facts li+li {
    padding-left: 14px;
    border-left: 1px solid var(--hh-line);
  }

  .hosp-facts strong {
    display: block;
    margin-bottom: 2px;
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: -.01em;
    line-height: 1.1;
    color: #fff;
  }

  .hosp-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    flex-grow: 1;
    align-content: flex-start;
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .hosp-tags li {
    padding: 4px 10px;
    font-family: var(--font-body, 'Quicksand', sans-serif);
    font-size: .74rem;
    font-weight: 600;
    color: rgba(255, 255, 255, .82);
    background: rgba(255, 255, 255, .06);
    border: 1px solid var(--hh-line);
    border-radius: 999px;
  }

  .hosp-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 22px;
  }

  .hosp-actions .hosp-link {
    margin-top: 0;
    text-decoration: none;
  }

  .hosp-more {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    font-family: var(--font-head, 'Montserrat', sans-serif);
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .02em;
    color: var(--hh-navy);
    text-decoration: none;
    background: var(--hh-gold);
    border-radius: 999px;
    transition: background .2s ease, color .2s ease;
  }

  .hosp-more:hover,
  .hosp-more:focus-visible {
    color: var(--hh-navy);
    background: #fff;
  }

  /* ── Detail dialog ── */
  .hosp-dialog {
    width: min(1000px, calc(100vw - 32px));
    max-height: min(86vh, 760px);
    padding: 0;
    color: #13233a;
    background: #fff;
    border: 0;
    border-radius: var(--r-lg, 18px);
    box-shadow: 0 40px 90px -30px rgba(0, 0, 0, .6);
    overflow: hidden;
  }

  .hosp-dialog[open] {
    position: fixed;
    top: 50%;
    left: 50%;
    right: auto;
    bottom: auto;
    margin: 0;
    transform: translate(-50%, -50%);
    display: flex;
    flex-direction: column;
    animation: hdIn .25s ease both;
  }

  .hosp-dialog::backdrop {
    background: rgba(6, 18, 36, .6);
    backdrop-filter: blur(3px);
  }

  @keyframes hdIn {
    from {
      opacity: 0;
      transform: translate(-50%, calc(-50% + 12px)) scale(.98);
    }

    to {
      opacity: 1;
      transform: translate(-50%, -50%);
    }
  }

  .hd-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 26px 18px;
    color: #fff;
    background: var(--hh-navy);
    border-top: 3px solid var(--hh-gold);
  }

  .hd-code {
    font-family: var(--font-head, 'Montserrat', sans-serif);
    font-size: .66rem;
    font-weight: 800;
    letter-spacing: .14em;
    color: var(--hh-gold);
  }

  .hd-head h3 {
    margin: 4px 0 0;
    font-family: var(--font-head, 'Montserrat', sans-serif);
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
  }

  .hd-close {
    flex: none;
    display: grid;
    place-items: center;
    width: 36px;
    height: 36px;
    color: #fff;
    background: rgba(255, 255, 255, .1);
    border: 0;
    border-radius: 50%;
    cursor: pointer;
  }

  .hd-close:hover,
  .hd-close:focus-visible {
    background: rgba(255, 255, 255, .22);
  }

  .hd-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    padding: 22px 26px;
    overflow-y: auto;
    background: #f4f7fa;
  }

  .hd-panel {
    padding: 18px 20px 8px;
    background: #fff;
    border: 1px solid #e3e9f0;
    border-radius: 14px;
  }

  .hd-panel-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    margin-bottom: 4px;
    border-bottom: 2px solid var(--hh-navy);
  }

  .hd-panel-ico {
    display: grid;
    place-items: center;
    width: 30px;
    height: 30px;
    font-size: .9rem;
    color: var(--hh-teal);
    background: rgba(14, 147, 132, .1);
    border-radius: 8px;
  }

  .hd-panel-head h4 {
    flex: 1;
    margin: 0;
    font-family: var(--font-head, 'Montserrat', sans-serif);
    font-size: .8rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--hh-navy);
  }

  .hd-count {
    min-width: 28px;
    padding: 2px 8px;
    font-family: var(--font-head, 'Montserrat', sans-serif);
    font-size: .72rem;
    font-weight: 800;
    text-align: center;
    color: var(--hh-navy);
    background: var(--hh-gold);
    border-radius: 999px;
  }

  .hd-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 18px;
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .hd-list li {
    position: relative;
    display: flex;
    align-items: center;
    min-height: 40px;
    padding: 8px 0 8px 16px;
    font-size: .82rem;
    font-weight: 600;
    line-height: 1.35;
    color: #2a3b52;
    border-bottom: 1px solid #eef2f6;
  }

  .hd-list li::before {
    content: "";
    position: absolute;
    left: 2px;
    top: 50%;
    width: 6px;
    height: 6px;
    margin-top: -3px;
    background: var(--hh-teal);
    border-radius: 50%;
  }

  .hd-list li:last-child,
  .hd-list li:nth-last-child(2):nth-child(odd) {
    border-bottom: 0;
  }

  .hd-foot {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px 20px;
    padding: 14px 26px;
    font-size: .82rem;
    background: #f6f8fb;
    border-top: 1px solid #e3e9f0;
  }

  .hd-foot a {
    font-weight: 600;
    color: var(--hh-teal);
    text-decoration: none;
  }

  .hd-foot a:hover {
    text-decoration: underline;
  }

  .hd-foot .hd-visit {
    margin-left: auto;
    padding: 8px 16px;
    color: #fff;
    background: var(--hh-navy);
    border-radius: 999px;
  }

  .hd-foot .hd-visit:hover {
    text-decoration: none;
    background: var(--hh-teal);
  }

  html.hosp-dialog-open {
    overflow: hidden;
  }

  @media (max-width: 767.98px) {
    .hd-body {
      grid-template-columns: 1fr;
      gap: 14px;
      padding: 14px;
    }

    .hd-panel {
      padding: 16px 16px 6px;
    }

    .hd-head,
    .hd-foot {
      padding-left: 18px;
      padding-right: 18px;
    }

    .hd-foot .hd-visit {
      margin-left: 0;
    }

    .hosp-dialog {
      max-height: 90vh;
    }
  }

  @media (max-width: 420px) {
    .hd-list {
      grid-template-columns: 1fr;
    }

    .hd-list li:nth-last-child(2):nth-child(odd) {
      border-bottom: 1px solid #eef2f6;
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .hosp-dialog[open] {
      animation: none;
    }
  }
</style>

<?php if (! empty($home_slides)): ?>
<!-- Home slider managed in ACP -->
<div id="heroSlider" class="home-image-slider" aria-label="Featured announcements">
  <?php foreach ($home_slides as $index => $slide): ?>
  <div class="hero-slide <?= $index === 0 ? 'active' : '' ?> <?= !array_key_exists('motion_enabled', $slide) || !empty($slide['motion_enabled']) ? 'has-motion' : '' ?>">
    <?php
    $isVideoSlide = ($slide['media_type'] ?? 'image') === 'video';
    $isCurrentVideo = ($slide['video_source'] ?? 'current') === 'current';
    $mediaPath = $isVideoSlide
        ? ($isCurrentVideo ? \App\Models\SettingModel::CURRENT_VIDEO_720 : (string) $slide['video_path'])
        : (string) $slide['image_path'];
    $posterPath = ! empty($slide['image_path'])
        ? ltrim((string) $slide['image_path'], '/')
        : \App\Models\SettingModel::CURRENT_POSTER;
    $mediaVersion = @filemtime(FCPATH . ltrim($mediaPath, '/')) ?: time();
    $posterVersion = @filemtime(FCPATH . $posterPath) ?: time();
    ?>
    <div class="slide-media <?= $isVideoSlide ? 'has-video' : '' ?>" style="background-image:url('<?= esc(base_url($isVideoSlide ? $posterPath : ltrim($mediaPath, '/')) . '?v=' . ($isVideoSlide ? $posterVersion : $mediaVersion), 'attr') ?>')">
      <?php if ($isVideoSlide): ?>
        <video class="managed-slide-video" muted playsinline loop preload="none" poster="<?= esc(base_url($posterPath) . '?v=' . $posterVersion, 'attr') ?>" src="<?= esc(base_url(ltrim($mediaPath, '/')) . '?v=' . $mediaVersion, 'attr') ?>" aria-hidden="true"></video>
      <?php endif; ?>
    </div>
    <div class="slide-overlay"></div>
    <div class="slide-grid"></div><div class="slide-circle-1"></div><div class="slide-circle-2"></div>
    <div class="container slide-inner"><div class="row align-items-center"><div class="col-lg-7"><div class="slide-content">
      <?php if (! empty($slide['title'])): ?><h1 class="slide-title"><?= esc($slide['title']) ?></h1><?php endif; ?>
      <?php if (! empty($slide['text'])): ?><p class="slide-body"><?= esc($slide['text']) ?></p><?php endif; ?>
      <?php if (! empty($slide['link_url'])): ?><div class="slide-actions"><a href="<?= esc($slide['link_url'], 'attr') ?>" class="btn-pmc btn-pmc-primary"><?= esc($slide['link_label'] ?: 'Learn more') ?></a></div><?php endif; ?>
    </div></div></div></div>
  </div>
  <?php endforeach; ?>
  <?php if (count($home_slides) > 1): ?><div class="slider-chrome"><button class="slider-prev" type="button" aria-label="Previous slide"><i class="bi bi-chevron-left"></i></button><div class="slider-indicators" role="tablist"><?php foreach ($home_slides as $index => $slide): ?><button class="slider-dot <?= $index === 0 ? 'active' : '' ?>" type="button" aria-label="Show slide <?= $index + 1 ?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"><span class="slider-dot-track"><span class="slider-dot-bar"></span></span></button><?php endforeach; ?></div><button class="slider-next" type="button" aria-label="Next slide"><i class="bi bi-chevron-right"></i></button></div><?php endif; ?>
</div>
<?php else: ?>
<?php
$heroPosterJpg = FCPATH . 'assets/images/slider/pmc-hero-poster.jpg';
$heroPosterWebp = FCPATH . 'assets/images/slider/pmc-hero-poster.webp';
$heroVideo720 = FCPATH . 'assets/videos/pmc-hero-720.mp4';
$heroVideo480 = FCPATH . 'assets/videos/pmc-hero-480.mp4';
$heroPosterJpgV = @filemtime($heroPosterJpg) ?: time();
$heroPosterWebpV = @filemtime($heroPosterWebp) ?: time();
$heroVideo720V = @filemtime($heroVideo720) ?: time();
$heroVideo480V = @filemtime($heroVideo480) ?: time();
?>
<!-- ═══ HERO — cinematic campus film ═══ -->
<div id="heroSlider" class="hero-cinematic" aria-label="Peshawar Medical College campus film">
  <div class="hero-slide active">
    <div class="slide-media slide-bg-hero has-video">
      <picture class="hero-poster">
        <source srcset="<?= base_url('assets/images/slider/pmc-hero-poster.webp') ?>?v=<?= $heroPosterWebpV ?>" type="image/webp">
        <img src="<?= base_url('assets/images/slider/pmc-hero-poster.jpg') ?>?v=<?= $heroPosterJpgV ?>" alt="" width="1600" height="900" fetchpriority="high" decoding="async">
      </picture>
      <video
        class="hero-video"
        muted
        playsinline
        loop
        preload="none"
        disablepictureinpicture
        aria-hidden="true"
        data-src-720="<?= base_url('assets/videos/pmc-hero-720.mp4') ?>?v=<?= $heroVideo720V ?>"
        data-src-480="<?= base_url('assets/videos/pmc-hero-480.mp4') ?>?v=<?= $heroVideo480V ?>"></video>
    </div>
    <div class="slide-overlay slide-overlay-film"></div>
    <div class="container slide-inner">
      <div class="slide-content">
        <p class="slide-brand">Department of Medical Sciences</p>
        <h1 class="slide-title">Your <span class="hl-teal">MBBS</span> Journey Starts Here</h1>
        <p class="slide-body">Peshawar Medical College offers a PM&amp;DC-recognized five-year MBBS — rigorous basic sciences, early clinical exposure, and mentors who teach medicine with integrity.</p>
        <p class="slide-body slide-body-sub">Study at Riphah International University – Peshawar Campus and build the competence to serve communities across KP and beyond.</p>
        <div class="slide-actions">
          <a href="<?= site_url('admissions') ?>" class="btn-pmc btn-pmc-primary"><i class="bi bi-mortarboard"></i> Admissions Info</a>
          <a href="<?= site_url('pmc') ?>" class="btn-pmc btn-pmc-outline-white">About PMC</a>
        </div>
      </div>
    </div>
    <button type="button" class="hero-video-toggle" aria-label="Pause campus film" hidden>
      <i class="bi bi-pause-fill"></i>
    </button>
  </div>
</div>

<?php endif; ?>

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
    <div class="row g-4 home-prog-grid">
      <div class="col-lg-6 fu fu-delay-1">
        <a class="home-prog-card home-prog-card--ug" href="<?= site_url('pmc') ?>">
          <span class="home-prog-code">Undergraduate</span>
          <h3>Peshawar Medical College</h3>
          <p>MBBS education aligned with the community health needs of KP and Pakistan, with clinical training at three affiliated teaching hospitals.</p>
          <ul class="home-prog-meta">
            <li>MBBS · 5 years</li>
            <li>3 teaching hospitals</li>
            <li>PM&amp;DC recognised</li>
          </ul>
          <span class="home-prog-cta">Learn more</span>
        </a>
      </div>
      <div class="col-lg-6 fu fu-delay-2">
        <a class="home-prog-card home-prog-card--pg" href="<?= site_url('pg-medical-education') ?>">
          <span class="home-prog-code">Postgraduate</span>
          <h3>Postgraduate Medical Education</h3>
          <p>FCPS and MCPS programmes recognized by CPSP since 2011, with plans to introduce MS and diploma pathways.</p>
          <ul class="home-prog-meta">
            <li>FCPS</li>
            <li>MCPS</li>
            <li>CPSP since 2011</li>
          </ul>
          <span class="home-prog-cta">Explore programmes</span>
        </a>
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
        </div>
      </div>
      <div class="col-lg-7 fu fu-delay-2">
        <span class="sec-eyebrow">About PMC</span>
        <h2 class="sec-title">Department of Medical Sciences</h2>
        <p class="sec-desc">A constituent department of Riphah International University – Peshawar Campus. It comprises <strong>Peshawar Medical College</strong> and postgraduate medical programmes, preparing physicians with competence, integrity, commitment, and research ability aligned to the health needs of KP and Pakistan.</p>
        <p class="sec-desc">Recognized by PM&amp;DC. In the 2024 inspection, we stood first among private medical colleges of KP with more than 80% score.</p>
        <a href="<?= site_url('about') ?>" class="btn-pmc btn-pmc-primary mt-2"><i class="bi bi-arrow-right-circle"></i> Read Full About</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHY PMC ═══ -->
<section class="pmc-section" id="why-dms">
  <div class="container">
    <div class="home-sec-head text-center fu">
      <span class="sec-eyebrow">Why PMC</span>
      <h2 class="sec-title">Why Choose Peshawar Medical College</h2>
      <p class="sec-desc">Looking for the best medical college in Peshawar or a top private medical college in Pakistan. Peshawar Medical College is PM&amp;DC recognized, ranked first among private medical colleges of KP in the 2024 inspection, and trains MBBS doctors for KP and Pakistan.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3 fu fu-delay-1">
        <div class="why-card">
          <div class="why-ico"><i class="bi bi-award-fill"></i></div>
          <h4>PM&amp;DC Recognised</h4>
          <p>Top among private medical colleges of KP in the 2024 PM&amp;DC inspection, with more than 80% score.</p>
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
          <h4>Professional Ethics &amp; Competence</h4>
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
<section class="pmc-section bg-navy home-hospitals" id="hospitals" data-hosp>
  <div class="container">
    <div class="home-sec-head text-center fu">
      <span class="sec-eyebrow">Affiliated Teaching Hospitals</span>
      <h2 class="sec-title">Clinical Training at Its Finest</h2>
      <p class="sec-desc">Students rotate through three affiliated teaching hospitals for comprehensive, real-world clinical exposure throughout MBBS training.</p>
    </div>

    <div class="row g-4">
      <?php foreach ($hospitals as $i => $h): ?>
        <div class="col-lg-4 col-md-6 fu fu-delay-<?= $i + 1 ?>">
          <article class="hosp-card hosp-card--rich">
            <span class="hosp-code"><?= hs_e($h['code']) ?></span>
            <h3 class="hosp-name"><?= hs_e($h['name']) ?></h3>
            <p class="hosp-desc"><?= hs_e($h['desc']) ?></p>

            <ul class="hosp-facts">
              <li><strong><?= count($h['departments']) ?></strong> Departments</li>
              <li><strong><?= count($h['services']) ?></strong> Services</li>
            </ul>

            <!-- <ul class="hosp-tags" aria-label="Highlights">
              <?php foreach ($h['highlights'] as $t): ?><li><?= hs_e($t) ?></li><?php endforeach; ?>
            </ul> -->

            <div class="hosp-actions">
              <a class="hosp-more" href="<?= hs_e($h['services_url']) ?>" target="_blank" rel="noopener"
                data-hosp-open="hosp-dlg-<?= hs_e($h['id']) ?>">
                <i class="bi bi-list-ul" aria-hidden="true"></i> Departments &amp; services
              </a>
              <a class="hosp-link" href="<?= hs_e($h['url']) ?>" target="_blank" rel="noopener">Visit <i class="bi bi-arrow-right"></i></a>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php foreach ($hospitals as $h): ?>
    <dialog class="hosp-dialog" id="hosp-dlg-<?= hs_e($h['id']) ?>" aria-labelledby="hosp-dlg-<?= hs_e($h['id']) ?>-title">
      <div class="hd-head">
        <div>
          <span class="hd-code"><?= hs_e($h['code']) ?></span>
          <h3 id="hosp-dlg-<?= hs_e($h['id']) ?>-title"><?= hs_e($h['name']) ?></h3>
        </div>
        <button type="button" class="hd-close" data-hosp-close aria-label="Close"><i class="bi bi-x-lg"></i></button>
      </div>

      <div class="hd-body">
        <section class="hd-panel">
          <header class="hd-panel-head">
            <span class="hd-panel-ico" aria-hidden="true"><i class="bi bi-diagram-3"></i></span>
            <h4>Departments</h4>
            <span class="hd-count"><?= count($h['departments']) ?></span>
          </header>
          <ul class="hd-list">
            <?php foreach ($h['departments'] as $d): ?><li><?= hs_e($d) ?></li><?php endforeach; ?>
          </ul>
        </section>

        <section class="hd-panel">
          <header class="hd-panel-head">
            <span class="hd-panel-ico" aria-hidden="true"><i class="bi bi-clipboard2-pulse"></i></span>
            <h4>Services</h4>
            <span class="hd-count"><?= count($h['services']) ?></span>
          </header>
          <ul class="hd-list">
            <?php foreach ($h['services'] as $sv): ?><li><?= hs_e($sv) ?></li><?php endforeach; ?>
          </ul>
        </section>
      </div>

      <div class="hd-foot">
        <a href="<?= hs_e($h['dept_url']) ?>" target="_blank" rel="noopener">All departments <i class="bi bi-box-arrow-up-right"></i></a>
        <a href="<?= hs_e($h['services_url']) ?>" target="_blank" rel="noopener">Services page <i class="bi bi-box-arrow-up-right"></i></a>
        <a class="hd-visit" href="<?= hs_e($h['url']) ?>" target="_blank" rel="noopener">Visit hospital website <i class="bi bi-arrow-right"></i></a>
      </div>
    </dialog>
  <?php endforeach; ?>
</section>


<!-- ═══ E-HEALTH CTA ═══ -->
<section class="ehealth-home-strip" id="e-health">
  <div class="container">
    <div class="ehealth-home-inner fu">
      <div class="ehealth-home-copy">
        <div class="ehealth-home-ico" aria-hidden="true"><i class="bi bi-heart-pulse-fill"></i></div>
        <div>
          <span class="ehealth-home-eyebrow">Public service · Free</span>
          <h2>Free health information by phone</h2>
          <p>Call PMC’s e-Health line, listen to the prompts, then enter a 3-digit topic code after the beep.</p>
        </div>
      </div>
      <div class="ehealth-home-actions">
        <a class="ehealth-home-phone" href="tel:0912385262">
          <span class="ehealth-home-phone-label">Dial now</span>
          <span class="ehealth-home-phone-num"><i class="bi bi-telephone-fill"></i>091-2385262</span>
        </a>
        <a href="<?= site_url('e-health') ?>" class="btn-pmc btn-pmc-outline-white ehealth-home-link">Topic codes &amp; how to use <i class="bi bi-arrow-right"></i></a>
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
        <a href="<?= site_url('about') ?>#campus" class="btn-pmc btn-pmc-outline">View Campus Details <i class="bi bi-arrow-right"></i></a>
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

<!-- ═══ ADMISSIONS UPDATED ═══ -->
<section class="pmc-section bg-off" id="admissions-updates">
  <div class="container">
    <div class="row align-items-end mb-4 mb-lg-5">
      <div class="col-lg-12 fu">
        <span class="sec-eyebrow">Admissions</span>
        <h2 class="sec-title">Admission Updates</h2>
        <p class="sec-desc mb-0">Current programme openings and application guidance for Peshawar Medical College and postgraduate medical programmes.</p>
      </div>
    </div>
    <?php if (! empty($admission_cards)): ?>
    <div class="row g-4">
      <?php foreach ($admission_cards as $i => $card): ?>
        <?= view('pages/partials/home_news_card', ['card' => $card, 'delay' => $i + 1, 'variant' => 'admissions']) ?>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="row">
      <div class="col-lg-12 fu-scale">
        <div class="alert alert-warning d-inline-flex align-items-center gap-2 py-2 px-3 mb-0 small rounded-pill" role="status">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>No admissions are open at the moment.</span>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══ WE ARE HIRING ═══ -->
<section class="pmc-section" id="careers">
  <div class="container">
    <div class="row align-items-end mb-4 mb-lg-5">
      <div class="col-lg-8 fu">
        <span class="sec-eyebrow">Careers</span>
        <h2 class="sec-title">We Are Hiring</h2>
        <p class="sec-desc mb-0">Faculty and staff opportunities at Riphah International University – Peshawar Campus.</p>
      </div>
      <div class="col-lg-4 text-lg-end fu fu-delay-1 mt-3 mt-lg-0">
        <a href="https://careers.riphahpsh.edu.pk" target="_blank" rel="noopener" class="btn-pmc btn-pmc-outline"><i class="bi bi-briefcase"></i> Career Portal</a>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-lg-6 col-md-6 fu fu-delay-2">
        <article class="news-card news-card-text news-card--career hub-career-portal-card">
          <div class="nc-body">
            <div class="nc-meta">
              <span class="nc-cat nc-cat-career">Career Portal</span>
            </div>
            <h3 class="nc-title">Explore Open Roles Online</h3>
            <p class="nc-excerpt">Browse current vacancies and submit applications through the official Career Portal.</p>
            <div class="nc-actions">
              <a href="https://careers.riphahpsh.edu.pk" target="_blank" rel="noopener" class="nc-btn nc-btn-primary">Visit Career Portal <i class="bi bi-box-arrow-up-right"></i></a>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>

<!-- ═══ LATEST FROM CAMPUS ═══ -->
<section class="pmc-section bg-off" id="news">
  <div class="container">
    <div class="row align-items-end mb-4 mb-lg-5">
      <div class="col-lg-8 fu">
        <span class="sec-eyebrow">News &amp; Events</span>
        <h2 class="sec-title">Latest from Campus</h2>
        <p class="sec-desc mb-0">Campus notices, research activity, and student life highlights.</p>
      </div>
      <!-- <div class="col-lg-4 text-lg-end fu fu-delay-1 mt-3 mt-lg-0">
        <a href="<?= site_url('all-news') ?>" class="btn-pmc btn-pmc-outline"><i class="bi bi-calendar-event"></i> View All</a>
      </div> -->
    </div>
    <?php if (! empty($campus_cards)): ?>
    <div class="row g-4">
      <?php foreach ($campus_cards as $i => $card): ?>
        <?= view('pages/partials/home_news_card', ['card' => $card, 'delay' => $i + 1, 'variant' => 'campus']) ?>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="row">
      <div class="col-lg-12 fu-scale">
        <div class="alert alert-light border d-inline-flex align-items-center gap-2 py-2 px-3 mb-0 small rounded-pill" role="status">
          <i class="bi bi-newspaper"></i>
          <span>No news found. Please check back soon for campus updates.</span>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══ CTA BAND ═══ -->
<section class="cta-band home-cta">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7 fu">
        <h2 class="mb-2">Ready to Join Department of Medical Sciences?</h2>
        <p class="mb-0">Explore MBBS admissions information, postgraduate pathways, or contact us for guidance.</p>
      </div>
      <div class="col-lg-5 text-lg-end fu fu-delay-2">
        <div class="home-cta-actions">
          <a href="<?= site_url('admissions') ?>" class="btn-pmc btn-pmc-outline-white"><i class="bi bi-mortarboard"></i> Admissions Info</a>
          <a href="<?= site_url('contact') ?>" class="btn-pmc btn-pmc-outline-white"><i class="bi bi-telephone"></i> Contact Us</a>
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

<script>
  /* Home: Teaching Hospitals detail dialogs */
  (function() {
    'use strict';

    function boot() {
      var root = document.querySelector('[data-hosp]');
      if (!root || typeof HTMLDialogElement === 'undefined') return; // links fall back to hospital services pages

      var lastTrigger = null;

      function close(dlg) {
        if (dlg.open) dlg.close();
      }

      root.querySelectorAll('[data-hosp-open]').forEach(function(btn) {
        var dlg = document.getElementById(btn.getAttribute('data-hosp-open'));
        if (!dlg) return;
        btn.setAttribute('role', 'button');
        btn.setAttribute('aria-haspopup', 'dialog');
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          lastTrigger = btn;
          dlg.showModal();
          document.documentElement.classList.add('hosp-dialog-open');
        });
      });

      root.querySelectorAll('.hosp-dialog').forEach(function(dlg) {
        dlg.querySelectorAll('[data-hosp-close]').forEach(function(b) {
          b.addEventListener('click', function() {
            close(dlg);
          });
        });
        // Click on backdrop closes
        dlg.addEventListener('click', function(e) {
          if (e.target !== dlg) return;
          var r = dlg.getBoundingClientRect();
          var inside = e.clientX >= r.left && e.clientX <= r.right && e.clientY >= r.top && e.clientY <= r.bottom;
          if (!inside) close(dlg);
        });
        dlg.addEventListener('close', function() {
          document.documentElement.classList.remove('hosp-dialog-open');
          if (lastTrigger) lastTrigger.focus();
        });
      });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
    else boot();
  })();
</script>
