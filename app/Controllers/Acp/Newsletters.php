<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Models\NewsletterModel;

class Newsletters extends BaseController
{
    public function index()
    {
        $rows = model(NewsletterModel::class)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('published_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('acp/newsletters/index', [
            'title'      => 'Newsletters',
            'nav'        => 'newsletters',
            'rows'       => $rows,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function create()
    {
        return view('acp/newsletters/edit', [
            'title'      => 'New newsletter',
            'nav'        => 'newsletters',
            'row'        => $this->blank(),
            'flashOk'    => null,
            'flashError' => null,
        ]);
    }

    public function edit(int $id)
    {
        $row = model(NewsletterModel::class)->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/newsletters'))->with('error', 'Newsletter not found.');
        }

        return view('acp/newsletters/edit', [
            'title'      => 'Edit newsletter',
            'nav'        => 'newsletters',
            'row'        => $row,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save(?int $id = null)
    {
        $model = model(NewsletterModel::class);
        $existing = $id ? $model->find($id) : null;
        if ($id && !is_array($existing)) {
            return redirect()->to(site_url('acp/newsletters'))->with('error', 'Newsletter not found.');
        }

        $payload = [
            'title'        => trim((string) $this->request->getPost('title')) ?: 'Untitled',
            'date_label'   => trim((string) $this->request->getPost('date_label')) ?: null,
            'pdf_url'      => trim((string) $this->request->getPost('pdf_url')) ?: '',
            'cover_url'    => trim((string) $this->request->getPost('cover_url')) ?: null,
            'published_at' => trim((string) $this->request->getPost('published_at')) ?: null,
            'sort_order'   => (int) $this->request->getPost('sort_order'),
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($payload['pdf_url'] === '') {
            return redirect()->back()->withInput()->with('error', 'PDF URL is required.');
        }

        if ($id) {
            $model->update($id, $payload);
        } else {
            $id = (int) $model->insert($payload);
        }

        return redirect()->to(site_url('acp/newsletters/' . $id))->with('ok', 'Newsletter saved.');
    }

    /** @return array<string,mixed> */
    protected function blank(): array
    {
        return [
            'id' => 0,
            'title' => '',
            'date_label' => '',
            'pdf_url' => '',
            'cover_url' => '',
            'published_at' => date('Y-m-d'),
            'sort_order' => 10,
            'is_active' => 1,
        ];
    }
}
