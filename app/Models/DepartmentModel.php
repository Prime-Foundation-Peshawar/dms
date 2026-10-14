<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'slug', 'name', 'icon', 'group_name', 'hod', 'intro_json', 'faculty_seed_json',
        'hidden', 'oric_id', 'content_updated_at', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** @return list<array<string,mixed>> */
    public function visibleDepartments(): array
    {
        // Keep seed/legacy insertion order within each group (not alphabetical by name).
        $rows = $this->where('hidden', 0)->orderBy('group_name')->orderBy('id')->findAll();
        return array_map([$this, 'hydrate'], $rows);
    }

    /** @return array<string,mixed>|null */
    public function findBySlug(string $slug, bool $allowHidden = false): ?array
    {
        $builder = $this->where('slug', $slug);
        if (! $allowHidden) {
            $builder->where('hidden', 0);
        }
        $row = $builder->first();
        return $row ? $this->hydrate($row) : null;
    }

    /** @param array<string,mixed> $row @return array<string,mixed> */
    protected function hydrate(array $row): array
    {
        $row['intro'] = json_decode((string) ($row['intro_json'] ?? '[]'), true) ?: [];
        $row['faculty'] = json_decode((string) ($row['faculty_seed_json'] ?? '[]'), true) ?: [];
        $row['group'] = $row['group_name'] ?? 'Other';
        $acts = db_connect()->table('department_activities')
            ->where('department_id', $row['id'])
            ->orderBy('sort_order')
            ->get()->getResultArray();
        $row['activities'] = array_map(static function ($a) {
            return [
                'title' => $a['title'],
                'date' => $a['date_label'],
                'text' => $a['body'],
            ];
        }, $acts);
        return $row;
    }
}
