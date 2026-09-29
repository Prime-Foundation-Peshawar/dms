<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Libraries\CmsContent;
use App\Models\NewsPostModel;

class News extends BaseController
{
    public function index()
    {
        $rows = model(NewsPostModel::class)
            ->orderBy('published_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('acp/news/index', [
            'title'      => 'News',
            'nav'        => 'news',
            'rows'       => $rows,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function create()
    {
        return view('acp/news/edit', [
            'title'      => 'New news post',
            'nav'        => 'news',
            'row'        => $this->blank(),
            'categories' => CmsContent::newsCategories(),
            'flashOk'    => null,
            'flashError' => null,
        ]);
    }

    public function edit(int $id)
    {
        $row = model(NewsPostModel::class)->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/news'))->with('error', 'Post not found.');
        }
        $row = CmsContent::normalizeTags($row);

        return view('acp/news/edit', [
            'title'      => 'Edit news',
            'nav'        => 'news',
            'row'        => $row,
            'categories' => CmsContent::newsCategories(),
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save(?int $id = null)
    {
        $model = model(NewsPostModel::class);
        $existing = $id ? $model->find($id) : null;
        if ($id && !is_array($existing)) {
            return redirect()->to(site_url('acp/news'))->with('error', 'Post not found.');
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

        $tagsRaw = (string) $this->request->getPost('tags');
        $tags = array_values(array_filter(array_map('trim', preg_split('/[\n,]+/', $tagsRaw) ?: [])));

        $cover = CmsContent::storeUpload($this->request->getFile('cover_file'), 'images/news', $slug);
        if ($cover === null) {
            $cover = trim((string) $this->request->getPost('cover_image')) ?: ($existing['cover_image'] ?? null);
        }

        $payload = [
            'slug'           => $slug,
            'title'          => $title !== '' ? $title : 'Untitled',
            'excerpt'        => trim((string) $this->request->getPost('excerpt')) ?: null,
            'body_html'      => (string) $this->request->getPost('body_html'),
            'author'         => trim((string) $this->request->getPost('author')) ?: null,
            'category'       => trim((string) $this->request->getPost('category')) ?: 'general',
            'status'         => $this->request->getPost('status') === 'published' ? 'published' : 'draft',
            'published_at'   => trim((string) $this->request->getPost('published_at')) ?: date('Y-m-d H:i:s'),
            'read_minutes'   => (int) $this->request->getPost('read_minutes') ?: null,
            'cover_image'    => $cover,
            'card_icon'      => trim((string) $this->request->getPost('card_icon')) ?: null,
            'card_gradient'  => trim((string) $this->request->getPost('card_gradient')) ?: null,
            'link_url'       => trim((string) $this->request->getPost('link_url')) ?: null,
            'link_label'     => trim((string) $this->request->getPost('link_label')) ?: null,
            'deadline_label' => trim((string) $this->request->getPost('deadline_label')) ?: null,
            'tags'           => json_encode($tags, JSON_UNESCAPED_UNICODE),
            'is_featured'    => $this->request->getPost('is_featured') ? 1 : 0,
            'show_on_home'   => $this->request->getPost('show_on_home') ? 1 : 0,
            'home_section'   => trim((string) $this->request->getPost('home_section')) ?: null,
            'sort_order'     => (int) $this->request->getPost('sort_order'),
        ];

        if ($id) {
            $model->update($id, $payload);
        } else {
            $id = (int) $model->insert($payload);
        }

        return redirect()->to(site_url('acp/news/' . $id))->with('ok', 'News post saved.');
    }

    /**
     * @return array<string,mixed>
     */
    protected function blank(): array
    {
        return [
            'id' => 0,
            'slug' => '',
            'title' => '',
            'excerpt' => '',
            'body_html' => '',
            'author' => 'PMC Admin',
            'category' => 'general',
            'status' => 'draft',
            'published_at' => date('Y-m-d H:i:s'),
            'read_minutes' => 3,
            'cover_image' => '',
            'card_icon' => 'bi-newspaper',
            'card_gradient' => 'linear-gradient(135deg,#0A1628,#1a3a6b)',
            'link_url' => '',
            'link_label' => '',
            'deadline_label' => '',
            'tags' => [],
            'is_featured' => 0,
            'show_on_home' => 0,
            'home_section' => '',
            'sort_order' => 0,
        ];
    }
}
