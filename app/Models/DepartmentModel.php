<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table         = 'departments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'slug',
        'name',
        'icon',
        'dept_group',
        'hod_name',
        'intro',
        'faculty_fallback',
        'oric_id',
        'sort_order',
        'updated_on',
        'is_active',
    ];

    /**
     * @return list<array<string,mixed>>
     */
    public function allActive(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->first();

        return is_array($row) ? $row : null;
    }
}
