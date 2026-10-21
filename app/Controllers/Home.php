<?php

namespace App\Controllers;

use App\Models\PageModel;

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

        return view('pages/home_wrap', [
            'page_title' => $page['title'] ?? 'Peshawar Medical College | PMC - MBBS in Peshawar',
            'page_description' => $page['description'] ?? '',
            'canonical_url' => site_url('/'),
            'preload_images' => [base_url('assets/images/slider/pmc-hero-poster.webp') . '?v=' . $posterV],
        ]);
    }
}
