<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryItemModel extends Model
{
    protected $table         = 'gallery_items';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['album_id', 'image_path', 'title', 'caption', 'span', 'sort_order', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
