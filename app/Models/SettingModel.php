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

    public function setValue(string $key, string $value): void
    {
        $existing = $this->where('key', $key)->first();
        $payload  = [
            'value'      => $value,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($existing) {
            $this->db->table($this->table)->where($this->primaryKey, $existing[$this->primaryKey])->update($payload);
        } else {
            $this->insert(array_merge(['key' => $key], $payload));
        }
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
     * Used to pre-fill the ACP slider until the first save.
     */
    public static function defaultHeroSlide(): array
    {
        return self::normaliseSlide([
            'media_type'  => 'video',
            'subtitle'    => 'Department of Medical Sciences',
            'title'       => 'Your *MBBS* Journey Starts Here',
            'text'        => 'Peshawar Medical College offers a PM&DC-recognized five-year MBBS — rigorous basic sciences, early clinical exposure, and mentors who teach medicine with integrity.',
            'link_label'  => 'Admissions Info',
            'link_url'    => 'admissions',
            'link2_label' => 'About PMC',
            'link2_url'   => 'pmc',
        ]);
    }

    /** Fills every key a slide can have, so older saved slides keep working. */
    public static function normaliseSlide(array $s): array
    {
        return [
            'media_type'     => ($s['media_type'] ?? 'image') === 'video' ? 'video' : 'image',
            'image_path'     => (string) ($s['image_path'] ?? ''),
            // Empty video path = the campus film (older slides marked it with video_source "current").
            'video_path'     => ($s['video_source'] ?? '') === 'current' ? '' : (string) ($s['video_path'] ?? ''),
            'subtitle'       => (string) ($s['subtitle'] ?? ''),
            'title'          => (string) ($s['title'] ?? ''),
            'text'           => (string) ($s['text'] ?? ''),
            'link_label'     => (string) ($s['link_label'] ?? ''),
            'link_url'       => (string) ($s['link_url'] ?? ''),
            'link2_label'    => (string) ($s['link2_label'] ?? ''),
            'link2_url'      => (string) ($s['link2_url'] ?? ''),
            'start_date'     => (string) ($s['start_date'] ?? ''),
            'end_date'       => (string) ($s['end_date'] ?? ''),
            'enabled'        => ! array_key_exists('enabled', $s) || ! empty($s['enabled']),
            'motion_enabled' => ! array_key_exists('motion_enabled', $s) || ! empty($s['motion_enabled']),
        ];
    }

    /**
     * Home slider slides.
     * Never saved yet → the current banner as one editable slide (ACP only).
     * $liveOnly → only enabled slides whose display/expiry dates include today.
     */
    public function homeSlides(bool $liveOnly = false): array
    {
        $raw = $this->getValue(self::HOME_SLIDER_KEY);
        if ($raw === null) {
            return $liveOnly ? [] : [self::defaultHeroSlide()];
        }

        $slides = json_decode($raw, true);
        $slides = is_array($slides) ? array_map([self::class, 'normaliseSlide'], array_filter($slides, 'is_array')) : [];

        if ($liveOnly) {
            $today  = date('Y-m-d');
            $slides = array_filter($slides, static fn ($s) => $s['enabled']
                && ($s['start_date'] === '' || $s['start_date'] <= $today)
                && ($s['end_date'] === '' || $s['end_date'] >= $today)
                && ($s['media_type'] === 'video' || $s['image_path'] !== ''));
        }

        return array_values($slides);
    }
}
