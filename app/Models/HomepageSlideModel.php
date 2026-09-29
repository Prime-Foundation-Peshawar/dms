<?php

namespace App\Models;

use CodeIgniter\Model;

class HomepageSlideModel extends Model
{
    protected $table         = 'homepage_slides';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'sort_order', 'is_active', 'media_type',
        'image_path', 'image_webp_path',
        'poster_path', 'poster_webp_path', 'video_720_path', 'video_480_path',
        'brand', 'title_html', 'body', 'body_sub',
        'cta1_label', 'cta1_url', 'cta1_style',
        'cta2_label', 'cta2_url', 'cta2_style',
        'aria_label',
    ];

    /**
     * @return list<array<string,mixed>>
     */
    public function activeOrdered(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
