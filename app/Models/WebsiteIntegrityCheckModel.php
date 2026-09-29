<?php

namespace App\Models;

use CodeIgniter\Model;

class WebsiteIntegrityCheckModel extends Model
{
    protected $table         = 'website_integrity_checks';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['check_date', 'check_type', 'status', 'checked_by', 'notes'];

    /** @return list<array<string,mixed>> */
    public function recent(int $limit = 14): array
    {
        return $this->orderBy('check_date', 'DESC')->orderBy('id', 'DESC')->findAll($limit);
    }
}
