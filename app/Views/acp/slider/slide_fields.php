<?php
$slide   = $slide ?? [];
$i       = esc((string) ($i ?? 0));
$isVideo = ($slide['media_type'] ?? 'image') === 'video';
$today   = date('Y-m-d');

if ($slide === []) {
    $status = ['New', 'acp-badge-info'];
} elseif (empty($slide['enabled'])) {
    $status = ['Hidden', 'acp-badge-muted'];
} elseif ($slide['end_date'] !== '' && $slide['end_date'] < $today) {
    $status = ['Expired', 'acp-badge-warn'];
} elseif ($slide['start_date'] !== '' && $slide['start_date'] > $today) {
    $status = ['Scheduled', 'acp-badge-info'];
} else {
    $status = ['Live', 'acp-badge-ok'];
}
?>
<div class="col-12 slider-slide">
  <details class="acp-detail" <?= $slide === [] ? 'open' : '' ?>>
    <summary>
      <span class="acp-detail-name">
        <i class="bi <?= $isVideo ? 'bi-camera-video' : 'bi-image' ?> text-muted"></i>
        <strong><?= esc(str_replace('*', '', $slide['title'] ?? '') ?: 'New slide') ?></strong>
        <span class="acp-badge <?= $status[1] ?>"><?= $status[0] ?></span>
      </span>
      <span class="acp-detail-meta">
        <span class="acp-sort-handle btn-acp btn-acp-sm btn-acp-outline" style="cursor:grab"><i class="bi bi-grip-vertical"></i> Drag</span>
        <button class="btn-acp btn-acp-sm btn-acp-danger" type="button" data-remove-slide>Remove</button>
      </span>
    </summary>
    <div class="acp-fields border-top p-3">
      <div class="acp-field">
        <label class="form-label">Title</label>
        <input class="form-control" name="title[<?= $i ?>]" value="<?= esc($slide['title'] ?? '') ?>" required>
        <p class="form-text mb-0">Wrap a word in *stars* to highlight it, e.g. Your *MBBS* Journey.</p>
      </div>
      <div class="acp-field">
        <label class="form-label">Subtitle <span class="text-muted fw-normal">(optional)</span></label>
        <input class="form-control" name="subtitle[<?= $i ?>]" value="<?= esc($slide['subtitle'] ?? '') ?>" placeholder="Riphah International University">
        <p class="form-text mb-0">Shown above the title.</p>
      </div>
      <div class="acp-field span-full">
        <label class="form-label">Paragraph</label>
        <textarea class="form-control" name="text[<?= $i ?>]" rows="2" required><?= esc($slide['text'] ?? '') ?></textarea>
      </div>

      <div class="acp-field">
        <label class="form-label">Media type</label>
        <select class="form-select" name="media_type[<?= $i ?>]">
          <option value="image" <?= ! $isVideo ? 'selected' : '' ?>>Image</option>
          <option value="video" <?= $isVideo ? 'selected' : '' ?>>Video</option>
        </select>
      </div>
      <div class="acp-field">
        <label class="form-label">Upload new photo / video</label>
        <input class="form-control" type="file" name="media[<?= $i ?>]" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm">
        <p class="form-text mb-0">Leave empty to keep the current one. Image: JPEG/PNG/WebP, max 8 MB. Video: MP4/WebM, max 100 MB.</p>
      </div>
      <div class="acp-field">
        <label class="form-label">Current media</label>
        <input type="hidden" name="image_path[<?= $i ?>]" value="<?= esc($slide['image_path'] ?? '') ?>">
        <input type="hidden" name="video_path[<?= $i ?>]" value="<?= esc($slide['video_path'] ?? '') ?>">
        <?php if ($isVideo): ?>
          <p class="small mb-0"><i class="bi bi-camera-video"></i> <?= ! empty($slide['video_path']) ? esc(basename($slide['video_path'])) : 'Campus film (default)' ?></p>
        <?php elseif (! empty($slide['image_path'])): ?>
          <img src="<?= base_url($slide['image_path']) ?>" alt="" class="img-thumbnail" style="max-height:90px">
        <?php else: ?>
          <p class="small text-muted mb-0">None yet — please upload one.</p>
        <?php endif; ?>
      </div>

      <div class="acp-field">
        <label class="form-label">Button 1 label</label>
        <input class="form-control" name="link_label[<?= $i ?>]" value="<?= esc($slide['link_label'] ?? '') ?>" placeholder="Explore Academics">
      </div>
      <div class="acp-field">
        <label class="form-label">Button 1 URL</label>
        <input class="form-control" name="link_url[<?= $i ?>]" value="<?= esc($slide['link_url'] ?? '') ?>" placeholder="admissions or https://…">
      </div>
      <div class="acp-field">
        <label class="form-label">Button 2 label <span class="text-muted fw-normal">(optional)</span></label>
        <input class="form-control" name="link2_label[<?= $i ?>]" value="<?= esc($slide['link2_label'] ?? '') ?>" placeholder="About Campus">
      </div>
      <div class="acp-field">
        <label class="form-label">Button 2 URL</label>
        <input class="form-control" name="link2_url[<?= $i ?>]" value="<?= esc($slide['link2_url'] ?? '') ?>" placeholder="about or https://…">
      </div>

      <div class="acp-field">
        <label class="form-label">Display date</label>
        <input class="form-control" type="date" name="start_date[<?= $i ?>]" value="<?= esc($slide['start_date'] ?? '') ?>">
        <p class="form-text mb-0">Slide shows from this day. Leave blank to show now.</p>
      </div>
      <div class="acp-field">
        <label class="form-label">Expiry date</label>
        <input class="form-control" type="date" name="end_date[<?= $i ?>]" value="<?= esc($slide['end_date'] ?? '') ?>">
        <p class="form-text mb-0">Slide is hidden after this day. Leave blank to never expire.</p>
      </div>

      <div class="acp-field">
        <label class="form-label">Visibility</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="enabled[<?= $i ?>]" id="enabled-<?= $i ?>" <?= ! empty($slide['enabled']) || $slide === [] ? 'checked' : '' ?>>
          <label class="form-check-label" for="enabled-<?= $i ?>">Show this slide</label>
        </div>
      </div>
      <div class="acp-field">
        <label class="form-label">Motion</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="motion_enabled[<?= $i ?>]" id="motion-<?= $i ?>" <?= ! empty($slide['motion_enabled']) || $slide === [] ? 'checked' : '' ?>>
          <label class="form-check-label" for="motion-<?= $i ?>">Slow zoom on image</label>
        </div>
      </div>
    </div>
  </details>
</div>
