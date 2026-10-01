<?php

namespace App\Controllers;

class Events extends PublicController
{
    public function index(): string
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';
        $events = dms_events_published();
        $featured = null;
        foreach ($events as $e) {
            if (! empty($e['is_featured'])) {
                $featured = $e;
                break;
            }
        }
        if (! $featured && $events) {
            $featured = $events[0];
        }
        $cats = [];
        foreach ($events as $e) {
            $c = (string) ($e['category'] ?? 'general');
            $cats[$c] = ($cats[$c] ?? 0) + 1;
        }
        $total = count($events);

        return $this->renderPage('pages/events', compact('events', 'featured', 'cats', 'total'));
    }

    public function single()
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';

        $slug = trim((string) ($this->request->getGet('slug') ?? $this->request->getGet('n') ?? ''));
        $event = $slug !== '' ? dms_event_by_slug($slug) : null;
        if (! $event) {
            $all = dms_events_published();
            foreach ($all as $e) {
                if (! empty($e['is_featured'])) {
                    $event = $e;
                    break;
                }
            }
            if (! $event && $all) {
                $event = $all[0];
            }
        }
        if (! $event) {
            return $this->redirectLegacy('events', 302);
        }

        $related = dms_events_related((string) ($event['category'] ?? 'general'), (int) ($event['id'] ?? 0), 3);
        $page_title = ($event['title'] ?? 'Event') . ' | Department of Medical Sciences';
        $page_description = (string) ($event['excerpt'] ?? $page_title);
        $gradient = $event['card_gradient'] ?? 'linear-gradient(135deg,#0A1628,#1a3a6b)';
        $icon = $event['card_icon'] ?? 'bi-calendar-event';
        $dateLabel = dms_cms_format_date($event['event_date'] ?? ($event['published_at'] ?? null));

        return $this->renderPage('pages/event-single', compact(
            'event',
            'related',
            'page_title',
            'page_description',
            'gradient',
            'icon',
            'dateLabel'
        ));
    }
}
