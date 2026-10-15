<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsMediaModel extends Model
{
    protected $table         = 'news_media';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['news_id', 'path', 'caption', 'sort_order'];
    protected $useTimestamps = false;

    /** @return list<array<string,mixed>> */
    public function forNews(int $newsId): array
    {
        return $this->where('news_id', $newsId)->orderBy('sort_order', 'ASC')->findAll();
    }
}
