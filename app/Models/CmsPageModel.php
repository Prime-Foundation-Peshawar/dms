<?php

namespace App\Models;

use CodeIgniter\Model;

class CmsPageModel extends Model
{
    protected $table         = 'cms_pages';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'slug', 'title', 'hero_title', 'breadcrumb_label', 'body_html',
        'meta_title', 'meta_description', 'status', 'show_sidebar', 'sort_order',
    ];

    public function findPublishedBySlug(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->where('status', 'published')->first();

        return is_array($row) ? $row : null;
    }

    /** @return list<array<string,mixed>> */
    public function publishedOrdered(): array
    {
        return $this->where('status', 'published')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('title', 'ASC')
            ->findAll();
    }
}
