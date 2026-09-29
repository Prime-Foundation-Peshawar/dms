<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Libraries\CmsContent;
use App\Models\GalleryAlbumModel;
use App\Models\GalleryImageModel;

class Gallery extends BaseController
{
    public function index()
    {
        $rows = model(GalleryAlbumModel::class)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
        $imgModel = model(GalleryImageModel::class);
        foreach ($rows as &$row) {
            $row['image_count'] = $imgModel->where('album_id', (int) $row['id'])->countAllResults();
        }
        unset($row);

        return view('acp/gallery/index', [
            'title'      => 'Gallery',
            'nav'        => 'gallery',
            'rows'       => $rows,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function create()
    {
        return view('acp/gallery/edit', [
            'title'      => 'New album',
            'nav'        => 'gallery',
            'row'        => $this->blankAlbum(),
            'images'     => [],
            'flashOk'    => null,
            'flashError' => null,
        ]);
    }

    public function edit(int $id)
    {
        $row = model(GalleryAlbumModel::class)->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/gallery'))->with('error', 'Album not found.');
        }
        $images = model(GalleryImageModel::class)
            ->where('album_id', $id)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('acp/gallery/edit', [
            'title'      => 'Edit album',
            'nav'        => 'gallery',
            'row'        => $row,
            'images'     => $images,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save(?int $id = null)
    {
        $model = model(GalleryAlbumModel::class);
        $existing = $id ? $model->find($id) : null;
        if ($id && !is_array($existing)) {
            return redirect()->to(site_url('acp/gallery'))->with('error', 'Album not found.');
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
        $category = trim((string) $this->request->getPost('category')) ?: $slug;

        $payload = [
            'slug'       => $slug,
            'title'      => $title !== '' ? $title : 'Untitled album',
            'category'   => $category,
            'icon'       => trim((string) $this->request->getPost('icon')) ?: 'bi-images',
            'sort_order' => (int) $this->request->getPost('sort_order'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $model->update($id, $payload);
        } else {
            $id = (int) $model->insert($payload);
        }

        $this->syncImages($id);

        return redirect()->to(site_url('acp/gallery/' . $id))->with('ok', 'Album saved.');
    }

    protected function syncImages(int $albumId): void
    {
        $imgModel = model(GalleryImageModel::class);
        $ids = $this->request->getPost('img_id') ?? [];
        $titles = $this->request->getPost('img_title') ?? [];
        $captions = $this->request->getPost('img_caption') ?? [];
        $paths = $this->request->getPost('img_path') ?? [];
        $spans = $this->request->getPost('img_span') ?? [];
        $orders = $this->request->getPost('img_sort') ?? [];
        $actives = $this->request->getPost('img_active') ?? [];
        if (!is_array($ids)) {
            $ids = [];
        }

        $kept = [];
        foreach ($ids as $i => $rawId) {
            $imgId = (int) $rawId;
            $title = trim((string) ($titles[$i] ?? ''));
            $path = trim((string) ($paths[$i] ?? ''));
            $fileKey = 'img_file_' . $i;
            $uploaded = CmsContent::storeUpload($this->request->getFile($fileKey), 'images/gallery', 'album-' . $albumId . '-' . ($imgId ?: time() . '-' . $i));
            if ($uploaded) {
                $path = $uploaded;
            }
            if ($title === '' && $path === '') {
                if ($imgId > 0) {
                    $imgModel->delete($imgId);
                }
                continue;
            }
            $row = [
                'album_id'   => $albumId,
                'title'      => $title !== '' ? $title : 'Untitled',
                'caption'    => trim((string) ($captions[$i] ?? '')) ?: null,
                'image_path' => $path !== '' ? $path : 'assets/images/campus/pmc.jpg',
                'span_class' => trim((string) ($spans[$i] ?? '')) ?: null,
                'sort_order' => (int) ($orders[$i] ?? (($i + 1) * 10)),
                'is_active'  => !empty($actives[$i]) ? 1 : 0,
            ];
            if ($imgId > 0) {
                $imgModel->update($imgId, $row);
                $kept[] = $imgId;
            } else {
                $kept[] = (int) $imgModel->insert($row);
            }
        }

        // New image row via dedicated fields
        $newTitle = trim((string) $this->request->getPost('new_img_title'));
        $newPath = trim((string) $this->request->getPost('new_img_path'));
        $newUpload = CmsContent::storeUpload($this->request->getFile('new_img_file'), 'images/gallery', 'album-' . $albumId . '-new-' . time());
        if ($newUpload) {
            $newPath = $newUpload;
        }
        if ($newTitle !== '' || $newPath !== '') {
            $imgModel->insert([
                'album_id'   => $albumId,
                'title'      => $newTitle !== '' ? $newTitle : 'Untitled',
                'caption'    => trim((string) $this->request->getPost('new_img_caption')) ?: null,
                'image_path' => $newPath !== '' ? $newPath : 'assets/images/campus/pmc.jpg',
                'span_class' => trim((string) $this->request->getPost('new_img_span')) ?: null,
                'sort_order' => (int) $this->request->getPost('new_img_sort') ?: 100,
                'is_active'  => 1,
            ]);
        }
    }

    /** @return array<string,mixed> */
    protected function blankAlbum(): array
    {
        return [
            'id' => 0,
            'slug' => '',
            'title' => '',
            'category' => '',
            'icon' => 'bi-images',
            'sort_order' => 10,
            'is_active' => 1,
        ];
    }
}
