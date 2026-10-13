<?php

namespace App\Controllers;

use App\Models\PageModel;

class Home extends BaseController
{
    public function index()
    {
        $page = model(PageModel::class)->findPublished('home');
        return view('pages/home_wrap', [
            'page_title' => $page['title'] ?? 'Peshawar Medical College | PMC - MBBS in Peshawar',
            'page_description' => $page['description'] ?? '',
            'canonical_url' => site_url('/'),
            'preload_images' => [base_url('assets/images/slider/pmc-hero-poster.webp')],
        ]);
    }
}
