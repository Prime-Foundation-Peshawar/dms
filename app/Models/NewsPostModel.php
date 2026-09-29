<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsPostModel extends Model
{
    protected $table         = 'news_posts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'slug', 'title', 'excerpt', 'body_html', 'author', 'category', 'status',
        'published_at', 'read_minutes', 'cover_image', 'card_icon', 'card_gradient',
        'link_url', 'link_label', 'deadline_label', 'tags', 'is_featured',
        'show_on_home', 'home_section', 'sort_order',
    ];

    /**
     * @return list<array<string,mixed>>
     */
    public function published(int $limit = 0): array
    {
        $b = $this->where('status', 'published')
            ->orderBy('is_featured', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->orderBy('sort_order', 'ASC');
        if ($limit > 0) {
            $b->limit($limit);
        }

        return $b->findAll();
    }

    public function findPublishedBySlug(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->where('status', 'published')->first();

        return is_array($row) ? $row : null;
    }

    /**
     * @return list<array<string,mixed>>
     */
    public function homeSection(string $section): array
    {
        return $this->where('status', 'published')
            ->where('show_on_home', 1)
            ->where('home_section', $section)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('published_at', 'DESC')
            ->findAll();
    }

    /**
     * @return list<array<string,mixed>>
     */
    public function related(string $category, int $excludeId, int $limit = 3): array
    {
        return $this->where('status', 'published')
            ->where('category', $category)
            ->where('id !=', $excludeId)
            ->orderBy('published_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
