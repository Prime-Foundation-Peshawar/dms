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
