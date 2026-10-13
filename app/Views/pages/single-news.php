<?php
/** @var array<string,mixed> $news_post */
/** @var list<array<string,mixed>> $news_media */
?>
<section class="py-5">
  <div class="container" style="max-width:880px;">
    <a href="<?= site_url('all-news') ?>" class="small text-decoration-none">â† All news</a>
    <h1 class="h2 mt-2 mb-2"><?= esc($news_post['title']) ?></h1>
    <div class="text-muted small mb-4">
      <?= esc($news_post['category'] ?? 'News') ?>
      <?php if (! empty($news_post['published_at'])): ?>
        Â· <?= esc(date('d M Y', strtotime($news_post['published_at']))) ?>
      <?php endif; ?>
    </div>
    <?php if (! empty($news_post['cover_image'])): ?>
      <img src="<?= base_url($news_post['cover_image']) ?>" alt="" class="img-fluid rounded-3 mb-4 w-100" style="max-height:420px;object-fit:cover;">
    <?php endif; ?>
    <?php if (! empty($news_post['excerpt'])): ?>
      <p class="lead"><?= esc($news_post['excerpt']) ?></p>
    <?php endif; ?>
    <div class="content-body">
      <?php if (! empty($news_post['body_html'])): ?>
        <?= $news_post['body_html'] ?>
      <?php elseif (! empty($news_post['body_text'])): ?>
        <p><?= nl2br(esc($news_post['body_text'])) ?></p>
      <?php endif; ?>
    </div>
    <?php if (! empty($news_media)): ?>
      <div class="row g-3 mt-4">
        <?php foreach ($news_media as $m): ?>
          <div class="col-md-4">
            <a href="<?= base_url($m['path']) ?>" target="_blank" rel="noopener">
              <img src="<?= base_url($m['path']) ?>" alt="<?= esc($m['caption'] ?? '') ?>" class="img-fluid rounded border">
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

