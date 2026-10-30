<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\NewsPostModel;
use App\Models\PageModel;
use App\Models\SettingModel;

class Home extends BaseController
{
    public function index()
    {
        $page = null;
        try {
            $page = model(PageModel::class)->findPublished('home');
        } catch (\Throwable $e) {
            log_message('error', 'Home page lookup failed: {0}', [$e->getMessage()]);
        }

        $posterWebp = FCPATH . 'assets/images/slider/pmc-hero-poster.webp';
        $posterV = @filemtime($posterWebp) ?: time();

        $slides = [];
        try {
            $stored = json_decode((string) model(SettingModel::class)->getValue('home_slider', '[]'), true);
            if (is_array($stored)) {
                $slides = array_values(array_map([SettingModel::class, 'normaliseSlide'], array_filter($stored, 'is_array')));
                $slides = array_values(array_filter($slides, static function ($slide) {
                    if (! $slide['enabled']) {
                        return false;
                    }
                    return $slide['media_type'] === 'video'
                        ? ($slide['video_source'] === 'current' || $slide['video_path'] !== '')
                        : $slide['image_path'] !== '';
                }));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Home slider lookup failed: {0}', [$e->getMessage()]);
        }

        return view('pages/home_wrap', [
            'page_title' => $page['title'] ?? 'Peshawar Medical College | PMC - MBBS in Peshawar',
            'page_description' => $page['description'] ?? '',
            'canonical_url' => site_url('/'),
            'preload_images' => [base_url('assets/images/slider/pmc-hero-poster.webp') . '?v=' . $posterV],
            'home_slides' => $slides,
            'admission_cards' => $this->homeCards(true),
            'campus_cards' => $this->homeCards(false),
        ]);
    }

    /**
     * Cards for the homepage "Admission Updates" (Admissions category) or "Latest from Campus" (everything else).
     * Live news (published, not expired) plus upcoming published events, newest first.
     *
     * @return list<array<string,mixed>>
     */
    private function homeCards(bool $admissions, int $limit = 3): array
    {
        $cards = [];
        try {
            foreach (model(NewsPostModel::class)->published() as $row) {
                $cards[] = $this->newsCard($row);
            }
            foreach (model(EventModel::class)->published() as $row) {
                if ((string) ($row['event_date'] ?? '') >= date('Y-m-d')) {
                    $cards[] = $this->eventCard($row);
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Home news/events lookup failed: {0}', [$e->getMessage()]);

            return [];
        }

        // Same item may exist as both news and event (and rows may be duplicated); keep the first per slug.
        $seen = [];
        $out  = [];
        foreach ($cards as $card) {
            if (($card['category'] === 'Admissions') !== $admissions || isset($seen[$card['slug']])) {
                continue;
            }
            $seen[$card['slug']] = true;
            $out[] = $card;
        }
        usort($out, static fn ($a, $b) => $b['sort'] <=> $a['sort']);

        return array_slice($out, 0, $limit);
    }

    /** @param array<string,mixed> $row */
    private function newsCard(array $row): array
    {
        $published = strtotime((string) ($row['published_at'] ?? '')) ?: time();
        $isAdmission = ($row['category'] ?? '') === 'Admissions';

        return $this->card($row, [
            'date'     => 'Published: ' . date('M Y', $published),
            'deadline' => $isAdmission && ! empty($row['expires_at']) ? date('d M Y', strtotime((string) $row['expires_at'])) : '',
            'page'     => site_url('single-news') . '?slug=' . rawurlencode((string) $row['slug']),
            'sort'     => $published,
        ]);
    }

    /** @param array<string,mixed> $row */
    private function eventCard(array $row): array
    {
        $when = strtotime((string) $row['event_date']) ?: time();

        return $this->card($row, [
            'date'     => date('d M Y', $when),
            'deadline' => '',
            'page'     => site_url('event-single') . '?slug=' . rawurlencode((string) $row['slug']),
            'sort'     => $when,
        ]);
    }

    /**
     * @param array<string,mixed> $row
     * @param array<string,mixed> $extra
     */
    private function card(array $row, array $extra): array
    {
        $link = trim((string) ($row['link_url'] ?? ''));
        $url  = $link === '' ? $extra['page'] : (preg_match('#^https?://#i', $link) ? $link : site_url(ltrim($link, '/')));
        $host = (string) parse_url(site_url('/'), PHP_URL_HOST);

        return [
            'slug'     => (string) $row['slug'],
            'title'    => (string) $row['title'],
            'category' => (string) ($row['category'] ?? ''),
            'excerpt'  => trim((string) ($row['excerpt'] ?? '')),
            'date'     => $extra['date'],
            'deadline' => $extra['deadline'],
            'url'      => $url,
            'label'    => $link !== '' && trim((string) ($row['link_label'] ?? '')) !== '' ? (string) $row['link_label'] : 'Read more',
            'external' => (string) parse_url($url, PHP_URL_HOST) !== $host,
            'poster'   => $this->assetUrl((string) ($row['cover_image'] ?? '')),
            'sort'     => $extra['sort'],
        ];
    }

    private function assetUrl(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        return preg_match('#^https?://#i', $path) ? $path : base_url(ltrim($path, '/'));
    }
}
