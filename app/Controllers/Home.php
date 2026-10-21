<?php

namespace App\Controllers;

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
                $slides = array_values(array_filter($stored, static fn ($slide) => is_array($slide) && ! empty($slide['enabled']) && ! empty($slide['image_path'])));
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
        ]);
    }
}
