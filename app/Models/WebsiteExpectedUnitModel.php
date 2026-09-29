<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteExpectedUnitModel extends Model
{
    protected $table         = 'website_expected_units';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'unit_name', 'unit_type', 'institution', 'content_examples', 'is_active', 'sort_order',
    ];

    /** @return list<array<string,mixed>> */
    public function activeOrdered(): array
    {
        return $this->where('is_active', 1)->orderBy('sort_order', 'ASC')->orderBy('unit_name', 'ASC')->findAll();
    }
}
