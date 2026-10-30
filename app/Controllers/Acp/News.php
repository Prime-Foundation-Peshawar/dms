<?php

namespace App\Controllers\Acp;

use App\Models\NewsMediaModel;
use App\Models\NewsPostModel;

class News extends BaseAcpController
{
    public function index()
    {
        $rows = model(NewsPostModel::class)->orderBy('published_at', 'DESC')->findAll();

        return $this->acpView('news/index', [
            'title' => 'News',
            'rows'  => $rows,
        ]);
    }

    public function create()
    {
        return $this->acpView('news/form', [
            'title' => 'Create news',
            'row'   => null,
            'media' => [],
        ]);
    }

    public function store()
    {
        $model = model(NewsPostModel::class);
        $model->ensureLinkColumns();
        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($title);
        $cover = $this->storeUpload('cover_image', 'news', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        [$bodyHtml, $bodyText] = $this->readDualBody();

        $id = $model->insert([
            'slug'         => $slug,
            'title'        => $title,
            'excerpt'      => trim((string) $this->request->getPost('excerpt')),
            'body_html'    => $bodyHtml,
            'body_text'    => $bodyText,
            'category'     => trim((string) $this->request->getPost('category')),
            'cover_image'  => $cover,
            'published_at' => $this->normalizeDateTime($this->request->getPost('published_at')) ?: date('Y-m-d H:i:s'),
            'expires_at'   => $this->normalizeDateTime($this->request->getPost('expires_at')),
            'status'       => in_array($this->request->getPost('status'), ['published', 'draft'], true)
                ? $this->request->getPost('status') : 'draft',
        ] + $this->postedLink(), true);

        $this->attachMedia((int) $id);

        return redirect()->to(site_url('acp/news'))->with('success', 'News created.');
    }

    public function edit(int $id)
    {
        $row = model(NewsPostModel::class)->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/news'))->with('error', 'News not found.');
        }

        return $this->acpView('news/form', [
            'title' => 'Edit news',
            'row'   => $row,
            'media' => model(NewsMediaModel::class)->forNews($id),
        ]);
    }

    public function update(int $id)
    {
        $model = model(NewsPostModel::class);
        $model->ensureLinkColumns();
        $row   = $model->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/news'))->with('error', 'News not found.');
        }

        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($title);
        [$bodyHtml, $bodyText] = $this->readDualBody();
        $data  = [
            'slug'         => $slug,
            'title'        => $title,
            'excerpt'      => trim((string) $this->request->getPost('excerpt')),
            'body_html'    => $bodyHtml,
            'body_text'    => $bodyText,
            'category'     => trim((string) $this->request->getPost('category')),
            'published_at' => $this->normalizeDateTime($this->request->getPost('published_at')) ?: $row['published_at'],
            'expires_at'   => $this->normalizeDateTime($this->request->getPost('expires_at')),
            'status'       => in_array($this->request->getPost('status'), ['published', 'draft'], true)
                ? $this->request->getPost('status') : $row['status'],
        ] + $this->postedLink();
        $cover = $this->storeUpload('cover_image', 'news', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        if ($cover) {
            $data['cover_image'] = $cover;
        }
        $model->update($id, $data);
        $this->attachMedia($id);

        return redirect()->to(site_url('acp/news/edit/' . $id))->with('success', 'News updated.');
    }

    public function delete(int $id)
    {
        model(NewsMediaModel::class)->where('news_id', $id)->delete();
        model(NewsPostModel::class)->delete($id);

        return redirect()->to(site_url('acp/news'))->with('success', 'News deleted.');
    }

    public function deleteMedia(int $id)
    {
        $media = model(NewsMediaModel::class)->find($id);
        if ($media) {
            model(NewsMediaModel::class)->delete($id);

            return redirect()->to(site_url('acp/news/edit/' . $media['news_id']))
                ->with('success', 'Media removed.');
        }

        return redirect()->to(site_url('acp/news'));
    }

    public function reorderMedia()
    {
        $ids = $this->request->getPost('ids') ?? [];
        if (! is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false]);
        }
        $media = model(NewsMediaModel::class);
        $order = 0;
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $media->update($id, ['sort_order' => $order++]);
            }
        }

        return $this->response->setJSON(['ok' => true]);
    }

    private function attachMedia(int $newsId): void
    {
        $paths = $this->storeUploads('media', 'news', ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf']);
        $media = model(NewsMediaModel::class);
        $order = (int) $media->where('news_id', $newsId)->countAllResults();
        foreach ($paths as $path) {
            $media->insert([
                'news_id'    => $newsId,
                'path'       => $path,
                'caption'    => '',
                'sort_order' => $order++,
            ]);
        }
    }

    private function normalizeDateTime(mixed $value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }
        $ts = strtotime(str_replace('T', ' ', $raw));
        if ($ts === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $ts);
    }
}
