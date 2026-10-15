<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table         = 'events';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'slug', 'title', 'excerpt', 'body_html', 'body_text', 'category', 'cover_image',
        'event_date', 'location', 'status', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** @return list<array<string,mixed>> */
    public function published(int $limit = 0): array
    {
        $builder = $this->where('status', 'published')->orderBy('event_date', 'DESC');
        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /** @return array<string,mixed>|null */
    public function findPublishedBySlug(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->where('status', 'published')->first();

        return $row ?: null;
    }
}
