<?php

namespace App\Models;

use CodeIgniter\Model;

class EventMediaModel extends Model
{
    protected $table         = 'event_media';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['event_id', 'path', 'caption', 'sort_order'];
    protected $useTimestamps = false;

    /** @return list<array<string,mixed>> */
    public function forEvent(int $eventId): array
    {
        return $this->where('event_id', $eventId)->orderBy('sort_order', 'ASC')->findAll();
    }
}
