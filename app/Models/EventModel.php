<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table         = 'events';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'slug', 'title', 'excerpt', 'body_html', 'author', 'category', 'status',
        'published_at', 'event_date', 'event_end_date', 'venue', 'read_minutes',
        'cover_image', 'card_icon', 'card_gradient', 'link_url', 'link_label',
        'tags', 'is_featured', 'sort_order',
    ];

    /**
     * @return list<array<string,mixed>>
     */
    public function published(int $limit = 0): array
    {
        $b = $this->where('status', 'published')
            ->orderBy('is_featured', 'DESC')
            ->orderBy('event_date', 'DESC')
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
    public function related(string $category, int $excludeId, int $limit = 3): array
    {
        return $this->where('status', 'published')
            ->where('category', $category)
            ->where('id !=', $excludeId)
            ->orderBy('event_date', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
