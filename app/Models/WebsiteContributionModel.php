<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteContributionModel extends Model
{
    protected $table         = 'website_contributions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'month_key', 'unit_name', 'unit_type', 'submissions', 'approved',
        'on_time', 'quality_score', 'rank_band', 'notes',
    ];

    /** @return list<array<string,mixed>> */
    public function forMonth(string $monthKey): array
    {
        return $this->where('month_key', $monthKey)
            ->orderBy('approved', 'DESC')
            ->orderBy('quality_score', 'DESC')
            ->findAll();
    }
}
