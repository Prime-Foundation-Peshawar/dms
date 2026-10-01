<?php

namespace App\Controllers;

class Media extends PublicController
{
    public function gallery(): string
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';
        $albums = dms_gallery_albums_with_images();
        $categories = [];
        foreach ($albums as $a) {
            $cat = (string) ($a['category'] ?? '');
            if ($cat !== '' && ! isset($categories[$cat])) {
                $categories[$cat] = [
                    'key'  => $cat,
                    'icon' => (string) ($a['icon'] ?? 'bi-images'),
                ];
            }
        }

        return $this->renderPage('pages/gallery', compact('albums', 'categories'));
    }

    public function vacantSeats(): string
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';
        $seats = dms_vacant_seats();
        $settings = dms_vacant_seats_settings();
        $instructions = $settings['instructions_json'] ?? [];
        if (! is_array($instructions)) {
            $instructions = [];
        }
        $alertHtml = (string) ($settings['alert_html'] ?? '');
        $introHtml = (string) ($settings['intro_html'] ?? '');
        $applyDeadline = (string) ($settings['apply_deadline'] ?? '');
        $applyDeadlineNote = (string) ($settings['apply_deadline_note'] ?? '');
        $meritDate = (string) ($settings['merit_date'] ?? '');
        $meritDateNote = (string) ($settings['merit_date_note'] ?? '');
        $applyUrl = (string) ($settings['apply_url'] ?? 'https://pmc.prime.edu.pk/vacant_admission/');

        return $this->renderPage('pages/vacant-seats', compact(
            'seats',
            'settings',
            'instructions',
            'alertHtml',
            'introHtml',
            'applyDeadline',
            'applyDeadlineNote',
            'meritDate',
            'meritDateNote',
            'applyUrl'
        ));
    }

    public function newsletter(): string
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';

        $page_title = 'Newsletter — Department of Medical Sciences - Riphah International University (Peshawar Campus)';
        $page_description = 'Download the official newsletter of the Department of Medical Sciences — Riphah International University (Peshawar Campus). Stay updated with academic news, events, and research.';

        $newsletters_raw = dms_newsletters_all();
        $newsletters_all = [];
        foreach ($newsletters_raw as $n) {
            $newsletters_all[] = [
                'title' => (string) ($n['title'] ?? ''),
                'date'  => (string) ($n['date_label'] ?? ''),
                'pdf'   => (string) ($n['pdf_url'] ?? ''),
                'image' => (string) ($n['cover_url'] ?? ''),
            ];
        }

        $per_page = 3;
        $total_items = count($newsletters_all);
        $total_pages = max(1, (int) ceil($total_items / $per_page));
        $current_page = (int) ($this->request->getGet('page') ?? 1);
        if ($current_page < 1) {
            $current_page = 1;
        }
        if ($current_page > $total_pages) {
            $current_page = $total_pages;
        }
        $offset = ($current_page - 1) * $per_page;
        $newsletters = array_slice($newsletters_all, $offset, $per_page);

        return $this->renderPage('pages/newsletter', compact(
            'page_title',
            'page_description',
            'newsletters_all',
            'newsletters',
            'per_page',
            'total_items',
            'total_pages',
            'current_page',
            'offset'
        ));
    }
}
