<?php
/**
 * Public CMS content bridge (news / events / homepage slides).
 */

require_once __DIR__ . '/db.php';

/**
 * @return list<array<string,mixed>>
 */
function dms_news_published(int $limit = 0): array
{
    try {
        if (class_exists(\App\Models\NewsPostModel::class)) {
            return model(\App\Models\NewsPostModel::class)->published($limit);
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        $sql = "SELECT * FROM news_posts WHERE status = 'published' ORDER BY is_featured DESC, published_at DESC, sort_order ASC";
        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        return array_map('dms_cms_normalize_row', $pdo->query($sql)->fetchAll());
    } catch (Throwable $e) {
        return [];
    }
}

function dms_news_by_slug(string $slug): ?array
{
    if ($slug === '') {
        return null;
    }
    try {
        if (class_exists(\App\Models\NewsPostModel::class)) {
            $row = model(\App\Models\NewsPostModel::class)->findPublishedBySlug($slug);
            return $row ? dms_cms_normalize_row($row) : null;
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        $stmt = $pdo->prepare("SELECT * FROM news_posts WHERE slug = ? AND status = 'published' LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ? dms_cms_normalize_row($row) : null;
    } catch (Throwable $e) {
        return null;
    }
}

/**
 * @return list<array<string,mixed>>
 */
function dms_news_home(string $section): array
{
    try {
        if (class_exists(\App\Models\NewsPostModel::class)) {
            return array_map('dms_cms_normalize_row', model(\App\Models\NewsPostModel::class)->homeSection($section));
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        $stmt = $pdo->prepare("SELECT * FROM news_posts WHERE status = 'published' AND show_on_home = 1 AND home_section = ? ORDER BY sort_order ASC, published_at DESC");
        $stmt->execute([$section]);
        return array_map('dms_cms_normalize_row', $stmt->fetchAll());
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * @return list<array<string,mixed>>
 */
function dms_news_related(string $category, int $excludeId, int $limit = 3): array
{
    try {
        if (class_exists(\App\Models\NewsPostModel::class)) {
            return array_map('dms_cms_normalize_row', model(\App\Models\NewsPostModel::class)->related($category, $excludeId, $limit));
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        $stmt = $pdo->prepare("SELECT * FROM news_posts WHERE status = 'published' AND category = ? AND id != ? ORDER BY published_at DESC LIMIT " . (int) $limit);
        $stmt->execute([$category, $excludeId]);
        return array_map('dms_cms_normalize_row', $stmt->fetchAll());
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * @return list<array<string,mixed>>
 */
function dms_events_published(int $limit = 0): array
{
    try {
        if (class_exists(\App\Models\EventModel::class)) {
            return array_map('dms_cms_normalize_row', model(\App\Models\EventModel::class)->published($limit));
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        $sql = "SELECT * FROM events WHERE status = 'published' ORDER BY is_featured DESC, event_date DESC, published_at DESC, sort_order ASC";
        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        return array_map('dms_cms_normalize_row', $pdo->query($sql)->fetchAll());
    } catch (Throwable $e) {
        return [];
    }
}

function dms_event_by_slug(string $slug): ?array
{
    if ($slug === '') {
        return null;
    }
    try {
        if (class_exists(\App\Models\EventModel::class)) {
            $row = model(\App\Models\EventModel::class)->findPublishedBySlug($slug);
            return $row ? dms_cms_normalize_row($row) : null;
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        $stmt = $pdo->prepare("SELECT * FROM events WHERE slug = ? AND status = 'published' LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ? dms_cms_normalize_row($row) : null;
    } catch (Throwable $e) {
        return null;
    }
}

/**
 * @return list<array<string,mixed>>
 */
function dms_events_related(string $category, int $excludeId, int $limit = 3): array
{
    try {
        if (class_exists(\App\Models\EventModel::class)) {
            return array_map('dms_cms_normalize_row', model(\App\Models\EventModel::class)->related($category, $excludeId, $limit));
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        $stmt = $pdo->prepare("SELECT * FROM events WHERE status = 'published' AND category = ? AND id != ? ORDER BY event_date DESC LIMIT " . (int) $limit);
        $stmt->execute([$category, $excludeId]);
        return array_map('dms_cms_normalize_row', $stmt->fetchAll());
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * @return list<array<string,mixed>>
 */
function dms_homepage_slides(): array
{
    try {
        if (class_exists(\App\Models\HomepageSlideModel::class)) {
            return model(\App\Models\HomepageSlideModel::class)->activeOrdered();
        }
    } catch (Throwable $e) {
        // fall through
    }
    try {
        $pdo = dms_db();
        return $pdo->query('SELECT * FROM homepage_slides WHERE is_active = 1 ORDER BY sort_order ASC, id ASC')->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * @param array<string,mixed> $row
 * @return array<string,mixed>
 */
function dms_cms_normalize_row(array $row): array
{
    if (isset($row['tags']) && is_string($row['tags']) && $row['tags'] !== '') {
        $decoded = json_decode($row['tags'], true);
        $row['tags'] = is_array($decoded) ? $decoded : [];
    } elseif (!isset($row['tags']) || !is_array($row['tags'])) {
        $row['tags'] = [];
    }
    return $row;
}

function dms_cms_category_label(string $cat): string
{
    if (class_exists(\App\Libraries\CmsContent::class)) {
        return \App\Libraries\CmsContent::categoryLabel($cat);
    }
    return ucfirst($cat);
}

function dms_cms_public_href(array $row, string $detailPage): string
{
    $link = trim((string) ($row['link_url'] ?? ''));
    if ($link !== '') {
        return $link;
    }
    $slug = (string) ($row['slug'] ?? '');
    return $detailPage . ($slug !== '' ? ('?slug=' . rawurlencode($slug)) : '');
}

function dms_cms_format_date(?string $dt): string
{
    if ($dt === null || $dt === '' || $dt === '0000-00-00' || str_starts_with((string) $dt, '0000-00-00')) {
        return '';
    }
    $ts = strtotime($dt);
    if ($ts === false) {
        return (string) $dt;
    }
    return date('M j, Y', $ts);
}

function dms_cms_date_parts(?string $dt): array
{
    $ts = $dt ? strtotime($dt) : false;
    if ($ts === false) {
        return ['day' => '', 'month' => ''];
    }
    return ['day' => date('d', $ts), 'month' => date('M', $ts)];
}

function dms_vacant_seats(): array
{
  try {
    if (class_exists(\App\Models\VacantSeatModel::class)) {
      return model(\App\Models\VacantSeatModel::class)->activeOrdered();
    }
  } catch (Throwable $e) {}
  try {
    return dms_db()->query("SELECT * FROM vacant_seats WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();
  } catch (Throwable $e) { return []; }
}

function dms_vacant_seats_settings(): array
{
  try {
    if (class_exists(\App\Models\VacantSeatsSettingsModel::class)) {
      return model(\App\Models\VacantSeatsSettingsModel::class)->getSettings();
    }
  } catch (Throwable $e) {}
  try {
    $row = dms_db()->query("SELECT * FROM vacant_seats_settings WHERE id = 1")->fetch();
    if (!$row) return [];
    $inst = json_decode((string)($row["instructions_json"] ?? "[]"), true);
    $row["instructions_json"] = is_array($inst) ? $inst : [];
    return $row;
  } catch (Throwable $e) { return []; }
}

function dms_newsletters_all(): array
{
  try {
    if (class_exists(\App\Models\NewsletterModel::class)) {
      return model(\App\Models\NewsletterModel::class)->activeOrdered();
    }
  } catch (Throwable $e) {}
  try {
    return dms_db()->query("SELECT * FROM newsletters WHERE is_active = 1 ORDER BY sort_order ASC, published_at DESC, id DESC")->fetchAll();
  } catch (Throwable $e) { return []; }
}

function dms_gallery_albums_with_images(): array
{
  try {
    if (class_exists(\App\Models\GalleryAlbumModel::class)) {
      $albums = model(\App\Models\GalleryAlbumModel::class)->activeOrdered();
      $imgModel = model(\App\Models\GalleryImageModel::class);
      foreach ($albums as &$a) {
        $a["images"] = $imgModel->forAlbum((int)$a["id"]);
      }
      unset($a);
      return $albums;
    }
  } catch (Throwable $e) {}
  try {
    $pdo = dms_db();
    $albums = $pdo->query("SELECT * FROM gallery_albums WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();
    $stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE album_id = ? AND is_active = 1 ORDER BY sort_order ASC, id ASC");
    foreach ($albums as &$a) {
      $stmt->execute([(int)$a["id"]]);
      $a["images"] = $stmt->fetchAll();
    }
    unset($a);
    return $albums;
  } catch (Throwable $e) { return []; }
}
