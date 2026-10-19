<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentActivityModel extends Model
{
    protected $table         = 'department_activities';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'department_id', 'title', 'date_label', 'body', 'photos_json', 'sort_order', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** @return list<array<string,mixed>> */
    public function forDepartment(int $departmentId): array
    {
        return $this->where('department_id', $departmentId)->orderBy('sort_order', 'ASC')->findAll();
    }
}
