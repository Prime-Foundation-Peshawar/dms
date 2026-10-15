<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryAlbumModel extends Model
{
    protected $table         = 'gallery_albums';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['slug', 'title', 'icon', 'sort_order', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    /** @return list<array<string,mixed>> */
    public function orderedWithItems(): array
    {
        $albums = $this->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
        $itemsModel = model(GalleryItemModel::class);
        foreach ($albums as &$album) {
            $album['items'] = $itemsModel
                ->where('album_id', (int) $album['id'])
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();
        }
        unset($album);

        return $albums;
    }
}
