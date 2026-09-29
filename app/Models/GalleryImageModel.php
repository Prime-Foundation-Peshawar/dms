<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryImageModel extends Model
{
    protected $table         = 'gallery_images';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'album_id', 'title', 'caption', 'image_path', 'span_class', 'sort_order', 'is_active',
    ];

    /** @return list<array<string,mixed>> */
    public function forAlbum(int $albumId): array
    {
        return $this->where('album_id', $albumId)
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
