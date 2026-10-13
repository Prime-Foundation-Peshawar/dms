<?php include("includes/header.php"); ?>

<style>
  /* ── Card styles similar to newsletter page ── */
  .edu-section { margin-bottom: 48px; }
  .edu-section-title { 
    font-family: var(--font-head); 
    font-size: 1.4rem; 
    font-weight: 800; 
    color: var(--navy); 
    margin-bottom: 24px; 
    display: flex; 
    align-items: center; 
    gap: 10px; 
  }
  .edu-section-title::before { 
    content: ''; 
    display: inline-block; 
    width: 5px; 
    height: 32px; 
    background: var(--teal); 
    border-radius: 3px; 
  }

  .edu-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    overflow: hidden;
    transition: transform .25s, box-shadow .25s, border-color .25s;
  }
  .edu-card:hover { 
    transform: translateY(-4px); 
    box-shadow: var(--shadow-lg); 
    border-color: var(--teal); 
  }

  .edu-card-img-container {
    height: 420px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  .edu-card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }
  .edu-card-body { 
    padding: 20px 24px; 
    display: flex;
    flex-direction: column;
    flex: 1;
  }
  .edu-card-title { 
    font-family: var(--font-head); 
    font-size: .95rem; 
    font-weight: 700; 
    color: var(--navy); 
    line-height: 1.4; 
    margin-bottom: 16px;
  }
  .btn-teal {
    background-color: var(--teal);
    color: #fff;
    border: none;
    padding: 8px 18px;
    font-family: var(--font-head);
    font-size: .8rem;
    font-weight: 700;
    border-radius: var(--r-sm);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
    margin-top: auto;
  }
  .btn-teal:hover {
    background-color: #00796b;
    color: #fff;
  }
  .hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
  }
  .transition {
    transition: all 0.2s ease;
  }
  /* Equal height cards in a row */
  .row > .d-flex.align-items-stretch .card {
    height: 100%;
  }

  @media (min-width: 768px) {
    .edu-card-img-container {
      height: 450px !important;
    }
  }
  @media (min-width: 1200px) {
    .edu-card-img-container {
      height: 500px !important;
    }
  }
</style>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Education Literature</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="medical-education.php">Education &amp; Research</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Education Literature</span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">

    <!-- Intro -->
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8 text-center">
        <h2 class="section-heading mb-3">Education Literature</h2>
        <p class="section-subhead">
          A curated collection of health awareness and religious guidance materials prepared by the
          <strong>Department of Medical Sciences – Riphah International University (Peshawar Campus)</strong>.
          Each topic is available as a downloadable PDF for easy reference.
        </p>
      </div>
    </div>

    <?php
    // ── Helper: safely URL-encode each path segment (handles spaces, brackets, Urdu text) ──
    function eduUrl($base, $path) {
      $segments = array_map('rawurlencode', explode('/', $path));
      return $base . implode('/', $segments);
    }

    // Base folder on prime.edu.pk that holds the source images (titles/) and most PDFs
    $baseUrl = 'https://prime.edu.pk/pf/PublicEducation/';
    // A couple of PDFs live one level up, directly under /pf/
    $rootUrl = 'https://prime.edu.pk/pf/';

    $sections = [
      [
        'title' => 'Ramdhan - روزہ',
        'items' => [
          ['title' => 'روزے کا مقصد و حقیقت اور طبی مسائل', 'image' => eduUrl($baseUrl, 'titles/Mesaq_Ramadan_Ki_Fazilat_Brucher.jpg'), 'pdf' => eduUrl($baseUrl, 'Mesaq_Ramadan_Ki_Fazilat_Brucher.pdf')],
          ['title' => 'روزے کی حقیقت', 'image' => eduUrl($baseUrl, 'titles/Rozy_ka_Maqsad_aur_Haqeqa.jpg'), 'pdf' => eduUrl($baseUrl, 'rozaiKeHaqeeqat.pdf')],
          ['title' => 'رمضان چیک لسٹ', 'image' => eduUrl($baseUrl, 'titles/Rozy_ka_Maqsad_aur_Haqeqa.jpg'), 'pdf' => eduUrl($baseUrl, 'checkList.pdf')],
          ['title' => 'روزے کے حوالے سے مسلمان معالجوں کے چند رہنما اصول', 'image' => eduUrl($baseUrl, 'titles/Musalman_mualjon_kay_chand_rehnuma_Asool.jpg'), 'pdf' => eduUrl($baseUrl, 'Musalman doctors.pdf')],
          ['title' => 'انجکشن اور روزہ', 'image' => eduUrl($baseUrl, 'titles/Injection.jpg'), 'pdf' => eduUrl($baseUrl, 'Injection.pdf')],
          ['title' => 'نفسیاتی بیماریاں اور روزہ', 'image' => eduUrl($baseUrl, 'titles/Nafseyathi_Bemariya_aur_Roza.jpg'), 'pdf' => eduUrl($baseUrl, 'Nafseyathi_Bemariya_aur_Roza.pdf')],
          ['title' => 'رمضان میں حاملہ اور دودھ پلانے والی خواتین کے مسائل (Pregnancy and Lactation)', 'image' => eduUrl($baseUrl, 'titles/Ramdan_me_Hamla_Maen.jpg'), 'pdf' => eduUrl($baseUrl, 'Ramdan_me_Hamla_Maen.pdf')],
          ['title' => 'شوگر کی بیماری اور روزہ', 'image' => eduUrl($baseUrl, 'titles/Sugar_ke_Bemari_aur_Roza.jpg'), 'pdf' => eduUrl($baseUrl, 'Sugar_ke_Bemari_aur_Roza.pdf')],
          ['title' => 'یرقان اور روزہ', 'image' => eduUrl($baseUrl, 'titles/Yarqan_aur_Roza_2.jpg'), 'pdf' => eduUrl($baseUrl, 'Yarqan_aur_Roza.pdf')],
          ['title' => 'آنکھ، ناک اور کان میں دوائی کا استعمال اور دانت نکلوانا', 'image' => eduUrl($baseUrl, 'titles/Ankh.jpg'), 'pdf' => eduUrl($baseUrl, 'Aank.pdf')],
          ['title' => 'دمہ سانس کی بیماریاں (Asthma)', 'image' => eduUrl($baseUrl, 'titles/Sans_ki_bemarian.jpg'), 'pdf' => eduUrl($baseUrl, 'Sans.pdf')],
          ['title' => 'شوگر کی بیماری', 'image' => eduUrl($baseUrl, 'titles/Sugar_ki_Bemarian.jpg'), 'pdf' => eduUrl($baseUrl, 'Sugar.pdf')],
          ['title' => 'گردے کی بیماریاں', 'image' => eduUrl($baseUrl, 'titles/Kidney.jpg'), 'pdf' => eduUrl($baseUrl, 'Kidney.pdf')],
          ['title' => 'آپریشن (Surgery)', 'image' => eduUrl($baseUrl, 'titles/surgery.jpg'), 'pdf' => eduUrl($baseUrl, 'Operation.pdf')],
          ['title' => 'دل کی بیماریاں (Congestive Heart Failure)', 'image' => eduUrl($baseUrl, 'titles/Heart_diseases.jpg'), 'pdf' => eduUrl($baseUrl, 'Heart diseases.pdf')],
          ['title' => 'یرقان', 'image' => eduUrl($baseUrl, 'titles/yarqan.png'), 'pdf' => eduUrl($baseUrl, 'Yarqaan.pdf')],
          ['title' => 'چند متفرق مسائل', 'image' => eduUrl($baseUrl, 'titles/Mutafariq_masael.jpg'), 'pdf' => eduUrl($baseUrl, 'Mutafariq.pdf')],
          ['title' => 'معدے کی بیماریاں اور تیزابیت', 'image' => eduUrl($baseUrl, 'titles/maida.jpg'), 'pdf' => eduUrl($baseUrl, 'maida.pdf')],
        ]
      ],
      [
        'title' => 'Public Health',
        'items' => [
          ['title' => 'ماں اور بچے کی صحت', 'image' => eduUrl($baseUrl, 'titles/Maa-aur-Bachy-ki-Sehat-2026.jpg'), 'pdf' => eduUrl($baseUrl, 'Maa-aur-Bachy-ki-Sehat-2026.pdf')],
          ['title' => 'بلڈ پریشر اور شوگر سے بچاؤ', 'image' => eduUrl($baseUrl, 'titles/Blood-Presure-aur-Sugar-se-Bachao-2026.jpg'), 'pdf' => eduUrl($baseUrl, 'Blood-Presure-aur-Sugar-se-Bachao-2026.pdf')],
          ['title' => 'بچوں کی تربیت', 'image' => eduUrl($baseUrl, 'titles/Bachoo-ke-Tarbiyat-2026.jpg'), 'pdf' => eduUrl($baseUrl, 'Bachoo-ke-Tarbiyat-2026-2Folded.pdf')],
          ['title' => 'کالایرقان (ہیپاٹائٹس بی اور سی) اور ایڈز وجوہات، تدارک (روک تھام) اور احتیاطی تدابیر', 'image' => eduUrl($baseUrl, 'titles/Hepatitis.jpg'), 'pdf' => eduUrl($baseUrl, 'Hepatitis.pdf')],
          ['title' => 'بلڈپریشر (بلند فشار خون) ایک خاموش قاتل حقائق اور اقدامات', 'image' => eduUrl($baseUrl, 'titles/Blood_Pressure.jpg'), 'pdf' => eduUrl($baseUrl, 'Blood Presure.pdf')],
          ['title' => 'ہاتھوں کو دھونا۔ بیماریوں سے نجات اور صحت کی ضمانت', 'image' => eduUrl($baseUrl, 'titles/handwashing.jpg'), 'pdf' => eduUrl($baseUrl, 'handwashing.pdf')],
          ['title' => 'حاملہ اور دودھ پلانے والی خواتین (Pregnancy and Lactation)', 'image' => eduUrl($baseUrl, 'titles/Pregnancy.jpg'), 'pdf' => eduUrl($baseUrl, 'Pregnancy.pdf')],
          ['title' => 'بچوں کی نگہداشت اور تربیت', 'image' => eduUrl($baseUrl, 'titles/Bachoo_ke_Tarbiyat.jpg'), 'pdf' => eduUrl($baseUrl, 'Bachoo_ke_Tarbiyat.pdf')],
          ['title' => 'حاملہ مائیں اور کالا یرقان', 'image' => eduUrl($baseUrl, 'titles/Hamila_Main_aur_kala_Yarqan.jpg'), 'pdf' => eduUrl($baseUrl, 'Hamila_Main_aur_kala_Yarqan.pdf')],
          ['title' => 'ملیریا اور ڈینگی سے تحفظ', 'image' => eduUrl($baseUrl, 'titles/Maleria_aur_Dangy_se_Tahafuz.jpg'), 'pdf' => eduUrl($baseUrl, 'Maleria_aur_Dangy_se_Tahafuz.pdf')],
          ['title' => 'گھروں میں صاف اور محفوظ پانی کا انتہائی سستا اور آسان حصول', 'image' => eduUrl($baseUrl, 'titles/gharon mai saaf pani ka sasta aur asaan tareka.jpg'), 'pdf' => eduUrl($baseUrl, 'gharon mai saaf pani ka sasta aur asaan tareka.pdf')],
          ['title' => '(Hepatitis B&C) کالے یرقان (Prevention) کا تدارک (Aids) اور ایڈز', 'image' => eduUrl($baseUrl, 'titles/kaly yarqan hepatitis b and c.jpg'), 'pdf' => eduUrl($baseUrl, 'kaly yarqan hepatitis b and c.pdf')],
          ['title' => 'مرسی ٹیچنگ ہسپتال', 'image' => eduUrl($baseUrl, 'titles/MTH.jpg'), 'pdf' => eduUrl($baseUrl, 'MTH.pdf')],
          ['title' => 'Kuwait Teaching Hospital Peshawar', 'image' => eduUrl($baseUrl, 'titles/kthp.jpg'), 'pdf' => eduUrl($baseUrl, 'kthp.pdf')],
          ['title' => 'سافٹ ڈرنکس صحت کے دشمن اور چند حقائق', 'image' => eduUrl($baseUrl, 'titles/soft drinks.jpg'), 'pdf' => eduUrl($baseUrl, 'soft drinks.pdf')],
          ['title' => 'عید الاضحی میں قربانی اور ہماری ذمہ داری', 'image' => eduUrl($baseUrl, 'titles/Eid_ul_Azah_me_Qurbani_aur_Zimadari.jpg'), 'pdf' => eduUrl($baseUrl, 'Eid_ul_Azah.pdf')],
          ['title' => 'پیغام اقبال', 'image' => eduUrl($baseUrl, 'titles/Pegham-e-Iqbal.jpg'), 'pdf' => eduUrl($baseUrl, 'Pegham-e-Iqbal.pdf')],
          ['title' => 'قربانی (سنت ابراہیمی) کی تکمیل', 'image' => eduUrl($baseUrl, 'titles/qurbani_sunnate_ibrahimi.jpg'), 'pdf' => eduUrl($baseUrl, 'qurbani_sunnate_ibrahimi.pdf')],
          ['title' => 'گھروں میں پانی کا باکفایت استعمال ہمارا دینی و قومی فریضہ', 'image' => eduUrl($baseUrl, 'titles/gharon mai pani ka bakafayat istemal.jpg'), 'pdf' => eduUrl($baseUrl, 'gharon mai pani ka bakafayat istemal.pdf')],
          ['title' => 'غصہ اور اس کاعلاج', 'image' => eduUrl($baseUrl, 'titles/Ghusa_aur_iska_Elaaj_Brucher.png'), 'pdf' => eduUrl($baseUrl, 'Ghusa_aur_iska_Elaaj_Brucher.pdf')],
        ]
      ],
      [
        'title' => 'Life and Living',
        'items' => [
          ['title' => 'Ahadith Course For Medical Students', 'image' => eduUrl($baseUrl, 'titles/Hadees_For_Medical_Student_(English ).jpg'), 'pdf' => eduUrl($baseUrl, 'Hadees_For_Medical_Student_(English ).pdf')],
          ['title' => 'نصاب حدیث برائے میڈیکل طلباء', 'image' => eduUrl($baseUrl, 'titles/Hadees_For_Medical_Student_Urdu.jpg'), 'pdf' => eduUrl($baseUrl, 'Hadees_For_Medical_Student.pdf')],
          ['title' => 'رسول اللہ ﷺ کا طرز علاج', 'image' => eduUrl($baseUrl, 'titles/tarz e elaj.jpg'), 'pdf' => eduUrl($baseUrl, 'tarz e elaj.pdf')],
          ['title' => 'Islamic Hospital Guidelines', 'image' => eduUrl($baseUrl, 'titles/hospital-guidelines.png'), 'pdf' => eduUrl($baseUrl, 'Book_on_IHC_Guidelines.pdf')],
          ['title' => 'The Islamic Guide Lines of Gender Interaction in Medical Profession', 'image' => eduUrl($baseUrl, 'titles/islamic_guidelines_of_gender_interection.jpg'), 'pdf' => eduUrl($baseUrl, 'islamic_guidelines_of_gender_interection.pdf')],
          ['title' => 'مریضوں کے وضو نماز اور طہارت کے مسائل', 'image' => eduUrl($baseUrl, 'titles/mareezo_kay_wazu_namaz_aur_taharat_kay_masael.jpg'), 'pdf' => eduUrl($baseUrl, 'Marezu ke wazu.pdf')],
          ['title' => 'شعبہ طب میں فیصلوں کے لئے ہدایات شریعت اسلامی کی روشنی میں', 'image' => eduUrl($baseUrl, 'titles/Shoba_Tib_mai_Faislon_ki_hidayaat.jpg'), 'pdf' => eduUrl($baseUrl, 'Shoba_Tib_mai_Faislon_ki_hidayaat.pdf')],
          ['title' => 'شعبہ طب میں مردوزن کے باہمی تعامل کے مسائل اور شرعی رہنمائی', 'image' => eduUrl($baseUrl, 'titles/Shoba_tib_mai_mardozan_ki_bahimi.jpg'), 'pdf' => eduUrl($baseUrl, 'shoba_tib_mai_mardozan_ka_ikhtlat.pdf')],
          ['title' => 'رمضان میں مریضوں کے لئے ہدایات شریعت اسلامی کی روشنی میں', 'image' => eduUrl($baseUrl, 'titles/ramzan_mai_mareezo_kay_lye_Hidayaat.jpg'), 'pdf' => eduUrl($baseUrl, 'ramzan_mai_mareezo_kay_lye_Hidayaat.pdf')],
          ['title' => 'قرآن سیکھنا کیوں ضروری ہے اور قرآن کیسے سیکھیں؟؟', 'image' => eduUrl($baseUrl, 'titles/Quran-Kesy-Sikain-Complete-2025.jpg'), 'pdf' => eduUrl($baseUrl, 'Quran Kesy Sikain Complete 2025.pdf')],
        ]
      ],
      [
        'title' => 'Published Books',
        'items' => [
          ['title' => 'تلاوت قرآن کا ثواب، اہمیت اور تقاضے', 'image' => eduUrl($baseUrl, 'titles/تلاوت قرآن کا ثواب ایمیت اور تواضے.png'), 'pdf' => eduUrl($baseUrl, 'Tilawate Quran ka swab ahmiyat awr taqaze.pdf')],
          ['title' => 'تبدیلی بذریعہ تعلیم', 'image' => eduUrl($baseUrl, 'titles/Tabdeeli_bazarya_taleem.jpg'), 'pdf' => eduUrl($baseUrl, 'Tabdeeli_bazarya_taleem.pdf')],
          ['title' => 'اسلام میں گناہ کا تصور اور اس سے بچنے کی تدابیر جلد اول', 'image' => eduUrl($baseUrl, 'titles/islam mai gunah ka tasawar jild 1.jpg'), 'pdf' => eduUrl($baseUrl, 'islam mai gunah ka tasawar jild 1.pdf')],
          ['title' => 'اسلام میں گناہ کا تصور اور اس سے بچنے کی تدابیر جلد دوم', 'image' => eduUrl($baseUrl, 'titles/islam mai gunah ka tasawar jild 2.jpg'), 'pdf' => eduUrl($baseUrl, 'islam mai gunah ka tasawar jild 2.pdf')],
          ['title' => 'مبادیات اصول فقہ', 'image' => eduUrl($baseUrl, 'titles/asool-e-fiqqa.jpg'), 'pdf' => eduUrl($baseUrl, 'asool-e-fiqqa.pdf')],
          ['title' => 'universal Quality Education', 'image' => eduUrl($baseUrl, 'titles/universal-quality-education.jpg'), 'pdf' => eduUrl($baseUrl, 'Quality Education.pdf')],
          ['title' => 'رخصت و عزیمت قرآن و حدیث کی روشنی میں', 'image' => eduUrl($baseUrl, 'titles/rukhsat o azeemat.jpg'), 'pdf' => eduUrl($baseUrl, 'rukhsat o azeemat.pdf')],
          ['title' => 'رودادِ سفر اُردن', 'image' => eduUrl($baseUrl, 'titles/rodad safar.jpg'), 'pdf' => eduUrl($baseUrl, 'rodad safar.pdf')],
          ['title' => 'صاف اور صحت بخش پانی کے حصول کے آسان اور ارزاں طریقے', 'image' => eduUrl($baseUrl, 'titles/saafAwrSihatBakhtPaani.jpg'), 'pdf' => eduUrl($baseUrl, 'saafAwrSihatBakhtPaani.pdf')],
          ['title' => 'اندرونی خانہ فضائی آلودگی اور ہماری صحت', 'image' => eduUrl($baseUrl, 'titles/Androon-e-Khana.jpg'), 'pdf' => eduUrl($baseUrl, 'Androon-e-Khana.pdf')],
          ['title' => 'اسلامی تعلیمات کی روشنی میں غلطیوں کی اصلاح کا عملی طریقہ', 'image' => eduUrl($baseUrl, 'titles/Ghaltion ki islah ka amli tareeka.jpg'), 'pdf' => eduUrl($baseUrl, 'Ghaltion ki islah ka amli tareeka.pdf')],
          ['title' => 'ڈاکٹروں اور دوا سازکمپنیوں کے تعلقات۔ اسلامی اصولوں کی روشنی میں', 'image' => eduUrl($baseUrl, 'titles/doctors and pharma.jpg'), 'pdf' => eduUrl($rootUrl, 'Final Book Doctors-Pharma Relations Guide_Dr Najeeb.pdf')],
        ]
      ],
    ];
    ?>

    <?php foreach ($sections as $section): ?>
      <div class="edu-section">
        <h3 class="edu-section-title"><?= htmlspecialchars($section['title']) ?></h3>
        <div class="row g-4">
          <?php foreach ($section['items'] as $item): ?>
            <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
              <div class="edu-card w-100 hover-shadow transition">
                <!-- Image container -->
                <div class="edu-card-img-container">
                  <?php if (!empty($item['image'])): ?>
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                      <i class="bi bi-file-pdf" style="font-size:3rem;"></i>
                    </div>
                  <?php endif; ?>
                </div>
                <!-- Card body -->
                <div class="edu-card-body">
                  <h4 class="edu-card-title"><?= htmlspecialchars($item['title']) ?></h4>
                  <a href="<?= htmlspecialchars($item['pdf']) ?>" target="_blank" rel="noopener" class="btn btn-teal w-100">
                    <i class="bi bi-download me-2"></i> Download PDF
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</section>


<?php include("includes/footer.php"); ?>

<script>
  const obs = new IntersectionObserver(e => e.forEach(x => { if (x.isIntersecting) { x.target.classList.add('vis'); obs.unobserve(x.target); } }), { threshold: .07 });
  document.querySelectorAll('.fu').forEach(el => obs.observe(el));

  const nav = document.getElementById('mainNav');
  const btt = document.getElementById('backToTop');
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', scrollY > 40);
    btt.classList.toggle('visible', scrollY > 500);
  }, { passive: true });
  btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>