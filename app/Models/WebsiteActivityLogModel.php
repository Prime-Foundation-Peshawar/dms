<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteActivityLogModel extends Model
{
    protected $table         = 'website_activity_log';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'month_key', 'unit_name', 'activity_title', 'received_at', 'approved_at', 'published_at',
        'turnaround_hours', 'status', 'shared_social', 'notes',
    ];

    /** @return list<array<string,mixed>> */
    public function forMonth(string $monthKey): array
    {
        return $this->where('month_key', $monthKey)
            ->orderBy('received_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }
}
