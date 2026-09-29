<?php

namespace App\Libraries;

/**
 * Shared CMS helpers for news / events / slides.
 */
class CmsContent
{
    public static function slugify(string $text): string
    {
        $s = strtolower(trim($text));
        $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';
        $s = trim($s, '-');

        return $s !== '' ? $s : 'item-' . substr(md5($text . microtime()), 0, 8);
    }

    /**
     * @param list<string> $existing
     */
    public static function uniqueSlug(string $base, array $existing, ?int $ignoreId = null): string
    {
        $slug = self::slugify($base);
        $n = 2;
        $candidate = $slug;
        while (in_array($candidate, $existing, true)) {
            $candidate = $slug . '-' . $n;
            $n++;
        }

        return $candidate;
    }

    /**
     * @return list<string>
     */
    public static function newsCategories(): array
    {
        return ['admissions', 'achievement', 'research', 'conference', 'society', 'general', 'career', 'campus'];
    }

    /**
     * @return list<string>
     */
    public static function eventCategories(): array
    {
        return ['admissions', 'achievement', 'research', 'conference', 'society', 'general'];
    }

    public static function categoryLabel(string $cat): string
    {
        $map = [
            'admissions'  => 'Admissions',
            'achievement' => 'Achievement',
            'research'    => 'Research',
            'conference'  => 'Conference',
            'society'     => 'Society',
            'general'     => 'General',
            'career'      => 'Career',
            'campus'      => 'Campus Life',
        ];

        return $map[$cat] ?? ucfirst($cat);
    }

    public static function newsCardModifier(string $cat): string
    {
        $map = [
            'admissions' => 'news-card--admissions',
            'career'     => 'news-card--career',
            'campus'     => 'news-card--campus',
            'research'   => 'news-card--campus',
            'achievement'=> 'news-card--campus',
        ];

        return $map[$cat] ?? 'news-card--campus';
    }

    public static function newsCatClass(string $cat): string
    {
        $map = [
            'admissions' => 'nc-cat-admissions',
            'career'     => 'nc-cat-career',
            'campus'     => 'nc-cat-news',
            'research'   => 'nc-cat-news',
            'achievement'=> 'nc-cat-news',
        ];

        return $map[$cat] ?? 'nc-cat-news';
    }

    /**
     * Move uploaded image into public assets; returns relative path or null.
     */
    public static function storeUpload(?\CodeIgniter\HTTP\Files\UploadedFile $file, string $subdir, string $basename): ?string
    {
        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }
        $mime = (string) $file->getMimeType();
        $map = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'video/mp4'  => 'mp4',
        ];
        if (!isset($map[$mime])) {
            return null;
        }
        $ext = $map[$mime];
        $relDir = 'assets/' . trim($subdir, '/');
        $destDir = FCPATH . $relDir;
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }
        $name = preg_replace('/[^a-z0-9\-]+/', '-', strtolower($basename)) . '.' . $ext;
        $file->move($destDir, $name, true);

        return $relDir . '/' . $name;
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    public static function normalizeTags(array $row): array
    {
        $tags = $row['tags'] ?? [];
        if (is_string($tags) && $tags !== '') {
            $decoded = json_decode($tags, true);
            $tags = is_array($decoded) ? $decoded : [];
        }
        if (!is_array($tags)) {
            $tags = [];
        }
        $row['tags'] = array_values(array_filter(array_map('strval', $tags)));

        return $row;
    }
}
