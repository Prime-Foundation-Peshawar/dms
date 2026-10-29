<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value', 'updated_at'];
    protected $useTimestamps = false;

    public const HOME_SLIDER_KEY = 'home_slider';

    /** The campus film that ships with the site ("current video"). */
    public const CURRENT_VIDEO_720  = 'assets/videos/pmc-hero-720.mp4';
    public const CURRENT_VIDEO_480  = 'assets/videos/pmc-hero-480.mp4';
    public const CURRENT_POSTER     = 'assets/images/slider/pmc-hero-poster.jpg';
    public const CURRENT_POSTER_WEB = 'assets/images/slider/pmc-hero-poster.webp';

    public function getValue(string $key, ?string $default = null): ?string
    {
        $row = $this->where('key', $key)->first();
        return $row ? (string) $row['value'] : $default;
    }

    /** @return array{intro:string,heading:string,rows:list<array<string,string>>,notices:list<string>,last_date:string,apply_url:string,apply_label:string} */
    public function vacantSeats(): array
    {
        $raw  = $this->getValue('vacant_seats', '{}');
        $data = json_decode((string) $raw, true);
        if (! is_array($data)) {
            $data = [];
        }

        return [
            'intro'       => (string) ($data['intro'] ?? ''),
            'heading'     => (string) ($data['heading'] ?? ''),
            'rows'        => is_array($data['rows'] ?? null) ? array_values($data['rows']) : [],
            'notices'     => is_array($data['notices'] ?? null) ? array_values($data['notices']) : [],
            'last_date'   => (string) ($data['last_date'] ?? ''),
            'apply_url'   => (string) ($data['apply_url'] ?? ''),
            'apply_label' => (string) ($data['apply_label'] ?? 'Apply Online for Migration'),
        ];
    }

    public function setJson(string $key, mixed $value): void
    {
        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($encoded === false) {
            $encoded = '[]';
        }
        $existing = $this->where('key', $key)->first();
        $payload  = [
            'value'      => $encoded,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($existing) {
            $this->db->table($this->table)->where($this->primaryKey, $existing[$this->primaryKey])->update($payload);
        } else {
            $this->insert(array_merge(['key' => $key], $payload));
        }
    }

    /**
     * The banner that was hard-coded on the home page, as an editable slide.
     * Used to pre-fill the slider until the first save, and as the
     * front-end fallback when no slide is enabled.
     */
    public static function defaultHeroSlide(): array
    {
        return self::normaliseSlide([
            'media_type'   => 'video',
            'video_source' => 'current',
            'eyebrow'      => 'Department of Medical Sciences',
            'title'        => 'Your *MBBS* Journey Starts Here',
            'text'         => "Peshawar Medical College offers a PM&DC-recognized five-year MBBS — rigorous basic sciences, early clinical exposure, and mentors who teach medicine with integrity.\n"
                            . 'Study at Riphah International University – Peshawar Campus and build the competence to serve communities across KP and beyond.',
            'link_label'   => 'Admissions Info',
            'link_url'     => 'admissions',
            'link2_label'  => 'About PMC',
            'link2_url'    => 'pmc',
            'enabled'      => true,
        ]);
    }

    /** Fills every key a slide can have, so older saved slides keep working. */
    public static function normaliseSlide(array $s): array
    {
        $type   = ($s['media_type'] ?? 'image') === 'video' ? 'video' : 'image';
        $source = ($s['video_source'] ?? 'current') === 'new' ? 'new' : 'current';

        return [
            'media_type'     => $type,
            'video_source'   => $source,
            'video_path'     => (string) ($s['video_path'] ?? ''),
            'image_path'     => (string) ($s['image_path'] ?? ''),
            'eyebrow'        => (string) ($s['eyebrow'] ?? ''),
            'title'          => (string) ($s['title'] ?? ''),
            'text'           => (string) ($s['text'] ?? ''),
            'link_label'     => (string) ($s['link_label'] ?? ''),
            'link_url'       => (string) ($s['link_url'] ?? ''),
            'link2_label'    => (string) ($s['link2_label'] ?? ''),
            'link2_url'      => (string) ($s['link2_url'] ?? ''),
            'enabled'        => ! array_key_exists('enabled', $s) || ! empty($s['enabled']),
            'motion_enabled' => ! array_key_exists('motion_enabled', $s) || ! empty($s['motion_enabled']),
        ];
    }

    /**
     * Home slider slides.
     * Never saved yet → the current banner as one editable slide.
     * $forHomePage → only enabled slides, falling back to the current banner.
     */
    public function homeSlides(bool $forHomePage = false): array
    {
        $raw = $this->getValue(self::HOME_SLIDER_KEY);
        if ($raw === null) {
            return [self::defaultHeroSlide()];
        }

        $slides = json_decode($raw, true);
        $slides = is_array($slides) ? array_values(array_map([self::class, 'normaliseSlide'], array_filter($slides, 'is_array'))) : [];

        if ($forHomePage) {
            $slides = array_values(array_filter($slides, static fn ($s) => $s['enabled']));
            if ($slides === []) {
                return [self::defaultHeroSlide()];
            }
        }

        return $slides;
    }
}
