<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;

class Modules extends BaseController
{
    public function show(string $key)
    {
        if ($key === 'departments') {
            return redirect()->to(site_url('acp/departments'));
        }
        if ($key === 'news') {
            return redirect()->to(site_url('acp/news'));
        }
        if ($key === 'events') {
            return redirect()->to(site_url('acp/events'));
        }
        if ($key === 'slider') {
            return redirect()->to(site_url('acp/slider'));
        }
        if ($key === 'gallery') {
            return redirect()->to(site_url('acp/gallery'));
        }
        if ($key === 'vacant-seats') {
            return redirect()->to(site_url('acp/vacant-seats'));
        }
        if ($key === 'newsletters') {
            return redirect()->to(site_url('acp/newsletters'));
        }
        if ($key === 'pages') {
            return redirect()->to(site_url('acp/pages'));
        }
        $labels = [
            'news'         => 'News',
            'events'       => 'Events',
            'gallery'      => 'Gallery',
            'departments'  => 'Departments',
            'vacant-seats' => 'Vacant seats',
            'newsletters'  => 'Newsletters',
            'pages'        => 'Pages',
        ];
        if (!isset($labels[$key])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('acp/modules/stub', [
            'title' => $labels[$key],
            'nav'   => 'modules',
            'key'   => $key,
            'label' => $labels[$key],
        ]);
    }
}
