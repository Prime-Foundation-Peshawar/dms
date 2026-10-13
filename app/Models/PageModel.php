<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'slug', 'title', 'label', 'section', 'description', 'view_key', 'body_html', 'body_text',
        'use_cms', 'seo_json', 'status', 'priority', 'changefreq', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** @return array<string,mixed>|null */
    public function findPublished(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->where('status', 'published')->first();
        if (! $row) {
            return null;
        }
        $row['seo'] = json_decode((string) ($row['seo_json'] ?? '{}'), true) ?: [];
        return $row;
    }

    /** @return list<array<string,mixed>> */
    public function sitemapRows(): array
    {
        return $this->where('status', 'published')->orderBy('priority', 'DESC')->findAll();
    }
}
