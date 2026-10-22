<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentActivityModel extends Model
{
    protected $table         = 'department_activities';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'department_id', 'title', 'date_label', 'body', 'photos_json', 'sort_order', 'enabled', 'created_at', 'updated_at',
    ];

    public function ensureEnabledColumn(): void
    {
        try {
            $db = db_connect();
            if ($db->tableExists($this->table) && ! $db->fieldExists('enabled', $this->table)) {
                $db->query('ALTER TABLE `department_activities` ADD COLUMN `enabled` TINYINT(1) NOT NULL DEFAULT 1 AFTER `sort_order`');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Could not ensure department_activities.enabled: ' . $e->getMessage());
        }
    }
    protected $useTimestamps = true;

    /** @return list<array<string,mixed>> */
    public function forDepartment(int $departmentId): array
    {
        return $this->where('department_id', $departmentId)->orderBy('sort_order', 'ASC')->findAll();
    }
}
