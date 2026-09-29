<?php

namespace App\Models;

use CodeIgniter\Model;

class VacantSeatModel extends Model
{
    protected $table         = 'vacant_seats';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['programme', 'session_label', 'seats', 'sort_order', 'is_active'];

    /** @return list<array<string,mixed>> */
    public function activeOrdered(): array
    {
        return $this->where('is_active', 1)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
    }
}
