<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentActivityModel extends Model
{
    protected $table         = 'department_activities';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'department_id',
        'title',
        'activity_date',
        'body',
        'sort_order',
    ];

    /**
     * @return list<array<string,mixed>>
     */
    public function forDepartment(int $departmentId): array
    {
        return $this->where('department_id', $departmentId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
