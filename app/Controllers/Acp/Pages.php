<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Libraries\CmsContent;
use App\Models\CmsPageModel;

class Pages extends BaseController
{
    public function index()
    {
        $rows = model(CmsPageModel::class)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('title', 'ASC')
            ->findAll();

        return view('acp/pages/index', [
            'title'      => 'Pages',
            'nav'        => 'pages',
            'rows'       => $rows,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function create()
    {
        return view('acp/pages/edit', [
            'title'      => 'New page',
            'nav'        => 'pages',
            'row'        => $this->blank(),
            'flashOk'    => null,
            'flashError' => null,
        ]);
    }

    public function edit(int $id)
    {
        $row = model(CmsPageModel::class)->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/pages'))->with('error', 'Page not found.');
        }

        return view('acp/pages/edit', [
            'title'      => 'Edit page',
            'nav'        => 'pages',
            'row'        => $row,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save(?int $id = null)
    {
        $model = model(CmsPageModel::class);
        $existing = $id ? $model->find($id) : null;
        if ($id && !is_array($existing)) {
            return redirect()->to(site_url('acp/pages'))->with('error', 'Page not found.');
        }

        $title = trim((string) $this->request->getPost('title'));
        $slugIn = trim((string) $this->request->getPost('slug'));
        $slugs = [];
        foreach ($model->findAll() as $r) {
            if ($id && (int) ($r['id'] ?? 0) === (int) $id) {
                continue;
            }
            $slugs[] = (string) ($r['slug'] ?? '');
        }
        $slug = CmsContent::uniqueSlug($slugIn !== '' ? $slugIn : $title, $slugs);

        $hero = trim((string) $this->request->getPost('hero_title'));
        $crumb = trim((string) $this->request->getPost('breadcrumb_label'));

        $payload = [
            'slug'              => $slug,
            'title'             => $title !== '' ? $title : 'Untitled',
            'hero_title'        => $hero !== '' ? $hero : ($title !== '' ? $title : 'Untitled'),
            'breadcrumb_label'  => $crumb !== '' ? $crumb : ($title !== '' ? $title : 'Untitled'),
            'body_html'         => (string) $this->request->getPost('body_html'),
            'meta_title'        => trim((string) $this->request->getPost('meta_title')) ?: null,
            'meta_description'  => trim((string) $this->request->getPost('meta_description')) ?: null,
            'status'            => $this->request->getPost('status') === 'published' ? 'published' : 'draft',
            'show_sidebar'      => $this->request->getPost('show_sidebar') ? 1 : 0,
            'sort_order'        => (int) $this->request->getPost('sort_order'),
        ];

        if ($id) {
            $model->update($id, $payload);
        } else {
            $id = (int) $model->insert($payload);
        }

        return redirect()->to(site_url('acp/pages/' . $id))->with('ok', 'Page saved.');
    }

    /** @return array<string,mixed> */
    protected function blank(): array
    {
        return [
            'id' => 0,
            'slug' => '',
            'title' => '',
            'hero_title' => '',
            'breadcrumb_label' => '',
            'body_html' => '',
            'meta_title' => '',
            'meta_description' => '',
            'status' => 'draft',
            'show_sidebar' => 1,
            'sort_order' => 10,
        ];
    }
}
