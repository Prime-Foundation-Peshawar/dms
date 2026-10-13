<?php

namespace App\Controllers\Acp;

use App\Models\PageModel;

class Pages extends BaseAcpController
{
    public function index()
    {
        $rows = model(PageModel::class)->orderBy('slug', 'ASC')->findAll();

        return $this->acpView('pages/index', [
            'title' => 'Pages',
            'rows'  => $rows,
        ]);
    }

    public function create()
    {
        return $this->acpView('pages/form', [
            'title' => 'Create page',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($title);
        [$bodyHtml, $bodyText] = $this->readBodies();

        model(PageModel::class)->insert([
            'slug'        => $slug,
            'title'       => $title,
            'label'       => trim((string) $this->request->getPost('label')),
            'section'     => trim((string) $this->request->getPost('section')),
            'description' => trim((string) $this->request->getPost('description')),
            'view_key'    => trim((string) $this->request->getPost('view_key')) ?: $slug,
            'body_html'   => $bodyHtml,
            'body_text'   => $bodyText,
            'use_cms'     => $this->request->getPost('use_cms') ? 1 : 0,
            'seo_json'    => (string) $this->request->getPost('seo_json') ?: '{}',
            'status'      => in_array($this->request->getPost('status'), ['published', 'draft'], true)
                ? $this->request->getPost('status') : 'draft',
            'priority'    => (float) ($this->request->getPost('priority') ?: 0.5),
            'changefreq'  => trim((string) $this->request->getPost('changefreq')) ?: 'monthly',
        ]);

        return redirect()->to(site_url('acp/pages'))->with('success', 'Page created.');
    }

    public function edit(int $id)
    {
        $row = model(PageModel::class)->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/pages'))->with('error', 'Page not found.');
        }

        return $this->acpView('pages/form', [
            'title' => 'Edit page',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        $model = model(PageModel::class);
        $row   = $model->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/pages'))->with('error', 'Page not found.');
        }

        [$bodyHtml, $bodyText] = $this->readBodies();
        $model->update($id, [
            'title'       => trim((string) $this->request->getPost('title')),
            'label'       => trim((string) $this->request->getPost('label')),
            'section'     => trim((string) $this->request->getPost('section')),
            'description' => trim((string) $this->request->getPost('description')),
            'view_key'    => trim((string) $this->request->getPost('view_key')) ?: $row['view_key'],
            'body_html'   => $bodyHtml,
            'body_text'   => $bodyText,
            'use_cms'     => $this->request->getPost('use_cms') ? 1 : 0,
            'seo_json'    => (string) $this->request->getPost('seo_json'),
            'status'      => in_array($this->request->getPost('status'), ['published', 'draft'], true)
                ? $this->request->getPost('status') : 'published',
            'priority'    => (float) $this->request->getPost('priority'),
            'changefreq'  => trim((string) $this->request->getPost('changefreq')) ?: 'monthly',
        ]);

        return redirect()->to(site_url('acp/pages'))->with('success', 'Page updated.');
    }

    public function delete(int $id)
    {
        model(PageModel::class)->delete($id);

        return redirect()->to(site_url('acp/pages'))->with('success', 'Page deleted.');
    }

    /** @return array{0:?string,1:?string} */
    private function readBodies(): array
    {
        $html = trim((string) $this->request->getPost('body_html'));
        $text = trim((string) $this->request->getPost('body_text'));
        if ($html === '' && $text !== '') {
            $parts = preg_split("/\n{2,}/", $text) ?: [];
            $html  = implode("\n", array_map(
                static fn ($p) => '<p>' . nl2br(htmlspecialchars(trim($p), ENT_QUOTES | ENT_HTML5, 'UTF-8')) . '</p>',
                array_filter(array_map('trim', $parts))
            ));
        }
        if ($text === '' && $html !== '') {
            $text = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return [$html !== '' ? $html : null, $text !== '' ? $text : null];
    }
}
