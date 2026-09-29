<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteSuggestionModel extends Model
{
    protected $table         = 'website_suggestions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['month_key', 'suggestion', 'source', 'status'];

    /** @return list<array<string,mixed>> */
    public function forMonth(string $monthKey): array
    {
        return $this->where('month_key', $monthKey)->orderBy('id', 'DESC')->findAll();
    }
}
