<?php
$root = dirname(__DIR__);
@mkdir($root . '/app/Views/faculty', 0775, true);

$src = (string) file_get_contents($root . '/_legacy/root-php/faculty.php');
$src = preg_replace('/^[\s\S]*?include\(\s*[\'"]includes\/header\.php[\'"]\s*\);\s*/', '', $src) ?? $src;
$src = preg_replace('/include\(\s*[\'"]includes\/footer\.php[\'"]\s*\);\s*/', '', $src) ?? $src;
$src = str_replace("const API_URL = 'faculty-proxy';", "const API_URL = <?= json_encode(site_url('faculty-proxy')) ?>;", $src);
$src = str_replace("href=\"faculty-profile?", "href=\"<?= site_url('faculty-profile') ?>?", $src);
$src = preg_replace_callback(
    '/\b(src|href)=("|\')assets\/([^"\']+)\2/i',
    static fn($m) => $m[1] . '=' . $m[2] . '<?= base_url(\'assets/' . addslashes($m[3]) . '\') ?>' . $m[2],
    $src
) ?? $src;

$out = "<?= \$this->extend('layouts/main') ?>\n<?= \$this->section('content') ?>\n" . $src . "\n<?= \$this->endSection() ?>\n";
file_put_contents($root . '/app/Views/faculty/index.php', $out);
echo 'faculty index ' . strlen($out) . PHP_EOL;

// Profile view — ported shell that uses EXTRA/DIRECTORY from controller
$profile = <<<'PHP'
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
helper('faculty');
$EXTRA = $extra ?? null;
$DIRECTORY = $directory ?? null;
$name = $EXTRA['name'] ?? ($DIRECTORY['empName'] ?? 'Faculty Member');
$desig = $EXTRA['designation'] ?? ($DIRECTORY['desTitle'] ?? '');
$dept = $EXTRA['department'] ?? ($DIRECTORY['depName'] ?? '');
$photo = $EXTRA['photo'] ?? '';
$quals = $EXTRA['qualifications'] ?? [];
$exp = $EXTRA['experience'] ?? [];
$pubs = $EXTRA['publications'] ?? [];
$skills = $EXTRA['skills'] ?? [];
$has_cv = $EXTRA && ( $photo || $quals || $exp || $pubs );
?>
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Faculty</span>
    <h1><?= esc($name) ?></h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('faculty') ?>">Faculty</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= esc($name) ?></span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <article class="fp-layout">
      <aside class="fp-side">
        <div class="fp-avatar<?= $photo ? ' has-photo' : '' ?>">
          <?php if ($photo): ?>
            <img src="<?= esc(base_url(ltrim($photo, '/'))) ?>" alt="">
          <?php else: ?>
            <?= esc(strtoupper(substr(faculty_slug($name), 0, 1))) ?>
          <?php endif; ?>
        </div>
        <h2 class="fp-name"><?= esc($name) ?></h2>
        <p class="fp-desig"><?= esc($desig) ?></p>
        <p class="fp-dept"><?= esc($dept) ?></p>
        <?php if ($quals): ?>
          <div class="fp-side-block">
            <h3>Qualifications</h3>
            <ul><?php foreach ((array)$quals as $q): ?><li><?= esc(is_array($q) ? implode(' ', $q) : $q) ?></li><?php endforeach; ?></ul>
          </div>
        <?php endif; ?>
        <?php if ($skills): ?>
          <div class="fp-side-block">
            <h3>Skills</h3>
            <ul><?php foreach ((array)$skills as $s): ?><li><?= esc(is_array($s) ? implode(' ', $s) : $s) ?></li><?php endforeach; ?></ul>
          </div>
        <?php endif; ?>
      </aside>
      <div class="fp-main">
        <?php if ($exp): ?>
          <section class="fp-panel">
            <h2>Experience</h2>
            <ul class="fp-list"><?php foreach ((array)$exp as $e): ?><li><?= esc(is_array($e) ? implode(' — ', array_map('strval', $e)) : $e) ?></li><?php endforeach; ?></ul>
          </section>
        <?php endif; ?>
        <?php if ($pubs): ?>
          <section class="fp-panel">
            <h2>Publications</h2>
            <ul class="fp-list"><?php foreach ((array)$pubs as $p): ?><li><?= esc(is_array($p) ? implode(' ', $p) : $p) ?></li><?php endforeach; ?></ul>
          </section>
        <?php endif; ?>
        <?php if (!$has_cv): ?>
          <section class="fp-panel">
            <h2>Profile</h2>
            <p>Detailed CV content for this faculty member will appear here when available. Directory information is shown from HRMS.</p>
            <?php if ($DIRECTORY): ?>
              <p><strong><?= esc($DIRECTORY['desTitle'] ?? '') ?></strong> — <?= esc($DIRECTORY['depName'] ?? '') ?></p>
              <?php if (!empty($DIRECTORY['qualifications'])): ?><p><?= esc($DIRECTORY['qualifications']) ?></p><?php endif; ?>
            <?php endif; ?>
          </section>
        <?php endif; ?>
        <p class="mt-4"><a class="btn-pmc btn-pmc-outline" href="<?= site_url('faculty') ?>"><i class="bi bi-arrow-left"></i> All faculty</a></p>
      </div>
    </article>
  </div>
</section>
<?= $this->endSection() ?>
PHP;
file_put_contents($root . '/app/Views/faculty/profile.php', $profile);
echo "profile ok\n";
