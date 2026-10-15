<?php /** @var list<array<string,mixed>> $news_posts */ ?>
<section class="py-5">
  <div class="container">
    <div class="mb-4">
      <h1 class="h2 mb-2">News &amp; Updates</h1>
      <p class="text-muted mb-0">Latest announcements from Peshawar Medical College.</p>
    </div>

    <?php if (empty($news_posts)): ?>
      <div class="alert alert-light border">No published news yet.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($news_posts as $post): ?>
          <div class="col-md-6 col-lg-4">
            <article class="h-100 border rounded-3 overflow-hidden bg-white shadow-sm">
              <?php if (! empty($post['cover_image'])): ?>
                <a href="<?= site_url('single-news') . '?slug=' . rawurlencode($post['slug']) ?>">
                  <img src="<?= base_url($post['cover_image']) ?>" alt="<?= esc($post['title']) ?>" class="w-100" style="height:180px;object-fit:cover;">
                </a>
              <?php endif; ?>
              <div class="p-3">
                <div class="small text-muted mb-1">
                  <?= esc($post['category'] ?? 'News') ?>
                  <?php if (! empty($post['published_at'])): ?>
                    · <?= esc(date('d M Y', strtotime($post['published_at']))) ?>
                  <?php endif; ?>
                </div>
                <h2 class="h5">
                  <a class="text-decoration-none" href="<?= site_url('single-news') . '?slug=' . rawurlencode($post['slug']) ?>">
                    <?= esc($post['title']) ?>
                  </a>
                </h2>
                <?php if (! empty($post['excerpt'])): ?>
                  <p class="small text-muted mb-2"><?= esc($post['excerpt']) ?></p>
                <?php endif; ?>
                <a class="small fw-semibold" href="<?= site_url('single-news') . '?slug=' . rawurlencode($post['slug']) ?>">Read more →</a>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
