<?php

namespace App\Controllers\Acp;

use App\Models\GalleryAlbumModel;
use App\Models\GalleryItemModel;
use App\Models\PageModel;

class Gallery extends BaseAcpController
{
    public function index()
    {
        $albums = model(GalleryAlbumModel::class)->orderedWithItems();
        $seo    = model(PageModel::class)->where('slug', 'gallery')->first();

        return $this->acpView('gallery/index', [
            'title'  => 'Gallery',
            'albums' => $albums,
            'seo'    => $seo,
        ]);
    }

    public function storeAlbum()
    {
        $title = trim((string) $this->request->getPost('title'));
        if ($title === '') {
            return redirect()->to(site_url('acp/gallery'))->with('error', 'Album title is required.');
        }
        $slug = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($title);
        $model = model(GalleryAlbumModel::class);
        if ($model->where('slug', $slug)->first()) {
            $slug .= '-' . time();
        }
        $maxRow = $model->selectMax('sort_order')->first();
        $max    = (int) ($maxRow['sort_order'] ?? 0);
        $model->insert([
            'slug'       => $slug,
            'title'      => $title,
            'icon'       => trim((string) $this->request->getPost('icon')) ?: 'bi-images',
            'sort_order' => $max + 1,
        ]);

        return redirect()->to(site_url('acp/gallery'))->with('success', 'Album created.');
    }

    public function updateAlbum(int $id)
    {
        $model = model(GalleryAlbumModel::class);
        $row   = $model->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/gallery'))->with('error', 'Album not found.');
        }
        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($title);
        $dup   = $model->where('slug', $slug)->where('id !=', $id)->first();
        if ($dup) {
            return redirect()->back()->with('error', 'Slug already in use.');
        }
        $model->update($id, [
            'title' => $title !== '' ? $title : $row['title'],
            'slug'  => $slug,
            'icon'  => trim((string) $this->request->getPost('icon')) ?: ($row['icon'] ?? 'bi-images'),
        ]);

        return redirect()->to(site_url('acp/gallery/album/' . $id))->with('success', 'Album updated.');
    }

    public function deleteAlbum(int $id)
    {
        model(GalleryItemModel::class)->where('album_id', $id)->delete();
        model(GalleryAlbumModel::class)->delete($id);

        return redirect()->to(site_url('acp/gallery'))->with('success', 'Album deleted.');
    }

    public function reorderAlbums()
    {
        return $this->reorderIds(model(GalleryAlbumModel::class));
    }

    public function album(int $id)
    {
        $album = model(GalleryAlbumModel::class)->find($id);
        if (! $album) {
            return redirect()->to(site_url('acp/gallery'))->with('error', 'Album not found.');
        }
        $items = model(GalleryItemModel::class)
            ->where('album_id', $id)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        return $this->acpView('gallery/album', [
            'title' => 'Album · ' . ($album['title'] ?? ''),
            'album' => $album,
            'items' => $items,
        ]);
    }

    public function storeItems(int $albumId)
    {
        $album = model(GalleryAlbumModel::class)->find($albumId);
        if (! $album) {
            return redirect()->to(site_url('acp/gallery'))->with('error', 'Album not found.');
        }

        $paths = $this->storeUploads('images', 'gallery', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        // Also allow linking existing asset paths without upload
        $manual = trim((string) $this->request->getPost('image_path'));
        if ($manual !== '') {
            $paths[] = ltrim($manual, '/');
        }
        if (! $paths) {
            return redirect()->back()->with('error', 'No images uploaded.');
        }

        $items = model(GalleryItemModel::class);
        $order = (int) $items->where('album_id', $albumId)->countAllResults();
        $title = trim((string) $this->request->getPost('title'));
        $caption = trim((string) $this->request->getPost('caption'));

        foreach ($paths as $path) {
            $items->insert([
                'album_id'   => $albumId,
                'image_path' => $path,
                'title'      => $title !== '' ? $title : null,
                'caption'    => $caption !== '' ? $caption : null,
                'span'       => null,
                'sort_order' => $order++,
            ]);
        }

        return redirect()->to(site_url('acp/gallery/album/' . $albumId))->with('success', count($paths) . ' photo(s) added.');
    }

    public function updateItem(int $id)
    {
        $items = model(GalleryItemModel::class);
        $row   = $items->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/gallery'))->with('error', 'Item not found.');
        }
        $data = [
            'title'   => trim((string) $this->request->getPost('title')),
            'caption' => trim((string) $this->request->getPost('caption')),
            'span'    => null,
        ];
        $path = trim((string) $this->request->getPost('image_path'));
        if ($path !== '') {
            $data['image_path'] = ltrim($path, '/');
        }
        $upload = $this->storeUpload('image', 'gallery', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        if ($upload) {
            $data['image_path'] = $upload;
        }
        $items->update($id, $data);

        return redirect()->to(site_url('acp/gallery/album/' . $row['album_id']))->with('success', 'Photo updated.');
    }

    public function deleteItem(int $id)
    {
        $items = model(GalleryItemModel::class);
        $row   = $items->find($id);
        if ($row) {
            $items->delete($id);

            return redirect()->to(site_url('acp/gallery/album/' . $row['album_id']))->with('success', 'Photo deleted.');
        }

        return redirect()->to(site_url('acp/gallery'));
    }

    public function reorderItems()
    {
        return $this->reorderIds(model(GalleryItemModel::class));
    }

    /** @param \CodeIgniter\Model $model */
    private function reorderIds($model)
    {
        $ids = $this->request->getPost('ids') ?? [];
        if (! is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false]);
        }
        $order = 0;
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $model->update($id, ['sort_order' => $order++]);
            }
        }

        return $this->response->setJSON(['ok' => true]);
    }
}
