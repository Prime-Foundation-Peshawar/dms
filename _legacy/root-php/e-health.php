<?php
$page_title = 'E-Health Service | Peshawar Medical College — Free Health Info by Phone';
$page_description = 'Free health information phone service by Peshawar Medical College (Prime Foundation). Dial 091-2385262, listen, then enter a 3-digit topic code after the beep.';
$extra_head = '<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;600;700&display=swap" rel="stylesheet" />';

/* Topic codes from PMC / Prime Foundation e-health index (edit here to update). */
$ehealth_groups = [
  [
    'title_en' => 'Emergencies & First Aid',
    'title_ur' => 'ہنگامی حالات اور ابتدائی طبی امداد',
    'topics' => [
      ['code' => '211', 'ur' => 'گلے میں کسی چیز کا پھنس جانا'],
      ['code' => '212', 'ur' => 'ناک میں کسی چیز کا پھنس جانا'],
      ['code' => '214', 'ur' => 'دودھ پیتے وقت جھاگ آنا'],
      ['code' => '215', 'ur' => 'مرگی کا دورہ کس طرح پڑتا ہے'],
      ['code' => '216', 'ur' => 'گردن کا ایک طرف اکڑ جانا'],
      ['code' => '217', 'ur' => 'آنکھ میں گندگی چلے جانے پر کیا کرنا چاہیے'],
      ['code' => '218', 'ur' => 'کوئی اچانک بے ہوش ہو جائے تو کیا کیا جائے'],
      ['code' => '221', 'ur' => 'زخم لگ جائے تو کیا کریں؟'],
      ['code' => '223', 'ur' => 'پاؤں کا ٹیڑھا ہونا'],
      ['code' => '224', 'ur' => 'گھٹنے پر چوٹ یا ناک سے خون آنا'],
      ['code' => '225', 'ur' => 'نکسیر سے خون بہنا'],
      ['code' => '226', 'ur' => 'شہد کی مکھی یا بچھو کا کاٹنا'],
      ['code' => '227', 'ur' => 'سانپ یا شہد کی مکھی کے کاٹنے پر'],
      ['code' => '231', 'ur' => 'کتے کا کاٹنا اور حفاظتی ٹیکے'],
      ['code' => '232', 'ur' => 'سانپ کاٹنا: کیا کریں'],
      ['code' => '233', 'ur' => 'زہریلی چیز کھالیں تو کیا کریں'],
      ['code' => '234', 'ur' => 'ڈوبتے کو بچانا'],
      ['code' => '235', 'ur' => 'ہڈی ٹوٹ جانا'],
      ['code' => '236', 'ur' => 'کٹ جانا، چھل جانا یا رگڑ لگنا'],
      ['code' => '237', 'ur' => 'اکیلے میں غشی ہو جائے'],
      ['code' => '238', 'ur' => 'پیٹ کا شدید درد'],
    ],
  ],
  [
    'title_en' => 'Eye Health',
    'title_ur' => 'آنکھوں کی صحت',
    'topics' => [
      ['code' => '228', 'ur' => 'موتیا'],
      ['code' => '251', 'ur' => 'رات کو کم نظر آنا'],
      ['code' => '252', 'ur' => 'بچوں کی آنکھیں کب چیک کرائیں'],
      ['code' => '253', 'ur' => 'آنکھ کا درد'],
      ['code' => '254', 'ur' => 'ٹیڑھا پن یا بھینگا پن'],
      ['code' => '255', 'ur' => 'نظر کی کمزوری'],
      ['code' => '256', 'ur' => 'موتیا'],
      ['code' => '257', 'ur' => 'کالا پانی'],
      ['code' => '258', 'ur' => 'کالا پانی'],
    ],
  ],
  [
    'title_en' => 'Breastfeeding & Child Health',
    'title_ur' => 'ماں کا دودھ اور بچوں کی صحت',
    'topics' => [
      ['code' => '311', 'ur' => 'بچوں کو ماں کا دودھ پلانا کب شروع کیا جائے'],
      ['code' => '312', 'ur' => 'بچے کی پیدائش سے 4-6 ماہ کی عمر تک کی غذا'],
      ['code' => '313', 'ur' => '6 ماہ سے ایک سال تک کے بچے کی غذا'],
      ['code' => '314', 'ur' => 'بچے کا دودھ پینے کے بعد رونا'],
      ['code' => '315', 'ur' => 'پہلے 3 دن کا دودھ کیوں پلائیں'],
      ['code' => '316', 'ur' => 'ماں کے دودھ کا فائدہ'],
      ['code' => '317', 'ur' => 'بچوں کا وزن اور بڑھنے کی رفتار'],
      ['code' => '318', 'ur' => 'بچوں میں خون کی کمی کی علامات'],
    ],
  ],
];

include('includes/header.php');
?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Public Health Service</span>
    <h1>E-Health Service</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">E-Health Service</span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content">

          <div class="fu">
            <span class="sec-eyebrow">Free Phone Service</span>
            <h2 class="sec-title" style="font-size:1.75rem;">Health Information by Phone</h2>
            <p>
              A free public health information service from <strong>Peshawar Medical College</strong> (Prime Foundation).
              Call from any network, listen to the prompts, then enter a 3-digit topic code after the beep.
            </p>
          </div>

          <div class="ehealth-dial fu fu-delay-1">
            <div class="ehealth-dial-icon"><i class="bi bi-telephone-fill"></i></div>
            <div class="ehealth-dial-body">
              <span class="ehealth-dial-label">Dial this number</span>
              <a class="ehealth-dial-num" href="tel:0912385262">091-2385262</a>
              <span class="ehealth-dial-note">All networks · Keypad · Android · PTCL · Free service</span>
            </div>
            <a href="tel:0912385262" class="btn-pmc btn-pmc-primary ehealth-dial-btn">
              <i class="bi bi-telephone"></i> Call Now
            </a>
          </div>

          <div class="about-block fu" id="how-to">
            <div class="about-block-head">
              <h3>How to Use</h3>
              <p>Three simple steps — works on keypad phones, Android, and PTCL lines.</p>
            </div>
            <div class="ehealth-steps">
              <div class="ehealth-step">
                <span class="ehealth-step-num">1</span>
                <div>
                  <strong>Dial</strong>
                  <p>Call <a href="tel:0912385262">091-2385262</a> from any phone network.</p>
                </div>
              </div>
              <div class="ehealth-step">
                <span class="ehealth-step-num">2</span>
                <div>
                  <strong>Listen</strong>
                  <p>After connecting, listen carefully to the voice instructions.</p>
                </div>
              </div>
              <div class="ehealth-step">
                <span class="ehealth-step-num">3</span>
                <div>
                  <strong>Enter a 3-digit code</strong>
                  <p>After the beep, enter the topic code for the health information you need.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="about-block fu" id="codes">
            <div class="about-block-head">
              <h3>Topic Codes</h3>
              <p>Find your topic, note the 3-digit code, then dial and enter it after the beep.</p>
            </div>

            <div class="ehealth-filter">
              <i class="bi bi-search"></i>
              <input type="search" id="ehealthSearch" placeholder="Search topics or codes…" autocomplete="off" aria-label="Search topic codes" />
            </div>

            <?php foreach ($ehealth_groups as $gi => $group): ?>
              <div class="ehealth-group" data-group>
                <h4 class="ehealth-group-title">
                  <span class="ehealth-group-en"><?= htmlspecialchars($group['title_en']) ?></span>
                  <span class="ehealth-group-ur" lang="ur" dir="rtl"><?= htmlspecialchars($group['title_ur']) ?></span>
                </h4>
                <ul class="ehealth-topics">
                  <?php foreach ($group['topics'] as $topic): ?>
                    <li class="ehealth-topic" data-code="<?= htmlspecialchars($topic['code']) ?>" data-ur="<?= htmlspecialchars($topic['ur']) ?>">
                      <span class="ehealth-code"><?= htmlspecialchars($topic['code']) ?></span>
                      <span class="ehealth-ur" lang="ur" dir="rtl"><?= htmlspecialchars($topic['ur']) ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endforeach; ?>

            <p class="ehealth-empty d-none" id="ehealthEmpty">No topics match your search.</p>
          </div>

          <div class="ehealth-note fu">
            <i class="bi bi-info-circle-fill"></i>
            <p>
              This is a <strong>free public information service</strong>. It is not a substitute for emergency hospital care.
              For urgent medical emergencies, go to the nearest hospital or call emergency services.
            </p>
          </div>

        </div>
      </div>
      <?php include('includes/sidebar.php'); ?>
    </div>
  </div>
</section>

<script>
(function () {
  var input = document.getElementById('ehealthSearch');
  var empty = document.getElementById('ehealthEmpty');
  if (!input) return;
  input.addEventListener('input', function () {
    var q = (input.value || '').trim().toLowerCase();
    var any = false;
    document.querySelectorAll('[data-group]').forEach(function (group) {
      var visibleInGroup = 0;
      group.querySelectorAll('.ehealth-topic').forEach(function (row) {
        var hay = ((row.getAttribute('data-code') || '') + ' ' + (row.getAttribute('data-ur') || '')).toLowerCase();
        var show = !q || hay.indexOf(q) !== -1;
        row.classList.toggle('d-none', !show);
        if (show) visibleInGroup++;
      });
      group.classList.toggle('d-none', visibleInGroup === 0);
      if (visibleInGroup > 0) any = true;
    });
    empty.classList.toggle('d-none', any);
  });
})();
</script>

<?php include('includes/footer.php'); ?>
