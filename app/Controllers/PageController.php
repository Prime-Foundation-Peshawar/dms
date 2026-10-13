<?php

namespace App\Controllers;

use App\Models\PageModel;
use App\Models\SettingModel;

class PageController extends BaseController
{
    public function redirectMedicalEducation()
    {
        return redirect()->to(site_url('pmc') . '#mbbs');
    }

    public function show(string $slug = '')
    {
        $slug = trim($slug, '/');
        if ($slug === '' || $slug === 'home') {
            return redirect()->to('/');
        }

        $page = model(PageModel::class)->findPublished($slug);
        $viewKey = $page['view_key'] ?? $slug;
        $viewFile = APPPATH . 'Views/pages/' . $viewKey . '.php';
        if (! is_file($viewFile)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound($slug);
        }

        $data = [
            'page_title' => $page['title'] ?? ucwords(str_replace('-', ' ', $slug)),
            'page_description' => $page['description'] ?? '',
            'canonical_url' => site_url($slug),
            'page' => $page,
        ];

        if ($slug === 'newsletter') {
            $data['newsletters_all'] = model(SettingModel::class)->newsletters();
        }

        return view('pages/content_wrap', array_merge($data, [
            'viewKey' => $viewKey,
        ]));
    }
}
