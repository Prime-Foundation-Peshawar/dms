<?php

namespace App\Controllers;

class Home extends PublicController
{
    public function index(): string
    {
        require_once ROOTPATH . 'legacy/includes/cms-content.php';

        $heroSlides = dms_homepage_slides();
        $homeNotices = dms_news_home('notices');
        $homeCampus = dms_news_home('campus');
        $homeAdmissions = array_values(array_filter($homeNotices, static fn ($r) => ($r['category'] ?? '') === 'admissions'));
        $homeCareers = array_values(array_filter($homeNotices, static fn ($r) => ($r['category'] ?? '') === 'career'));

        if ($heroSlides === []) {
            $preload_images = ['assets/images/slider/pmc-hero-poster.webp'];
        } else {
            $first = $heroSlides[0];
            $preload_images = [];
            foreach (['poster_webp_path', 'poster_path', 'image_webp_path', 'image_path'] as $k) {
                if (! empty($first[$k])) {
                    $preload_images[] = $first[$k];
                    break;
                }
            }
        }

        $slideCount = count($heroSlides);
        $ctaClass = static function (string $style): string {
            return $style === 'outline-white' ? 'btn-pmc btn-pmc-outline-white' : 'btn-pmc btn-pmc-primary';
        };

        return $this->renderPage('pages/home', compact(
            'heroSlides',
            'homeNotices',
            'homeCampus',
            'homeAdmissions',
            'homeCareers',
            'preload_images',
            'slideCount',
            'ctaClass'
        ));
    }
}
