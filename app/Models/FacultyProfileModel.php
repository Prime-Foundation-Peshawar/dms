<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyProfileModel extends Model
{
    protected $table = 'faculty_profiles';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'slug', 'name', 'department_slug', 'department_name', 'designation', 'photo',
        'is_hod', 'is_public', 'qualifications_json', 'experience_json', 'skills_json',
        'publications_json', 'extra_json', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** @return list<array<string,mixed>> */
    public function publicRows(): array
    {
        $rows = $this->where('is_public', 1)->findAll();
        return array_map([$this, 'hydrate'], $rows);
    }

    /** @return array<string,mixed>|null */
    public function findBySlugOrAlias(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->first();
        if ($row) {
            return $this->hydrate($row);
        }
        $alias = db_connect()->table('faculty_profile_aliases')->where('alias', $slug)->get()->getRowArray();
        if (! $alias) {
            return null;
        }
        $row = $this->find($alias['profile_id']);
        return $row ? $this->hydrate($row) : null;
    }

    /** @param array<string,mixed> $row @return array<string,mixed> */
    protected function hydrate(array $row): array
    {
        $row['qualifications'] = json_decode((string) ($row['qualifications_json'] ?? '[]'), true) ?: [];
        $row['experience'] = json_decode((string) ($row['experience_json'] ?? '[]'), true) ?: [];
        $row['skills'] = json_decode((string) ($row['skills_json'] ?? '[]'), true) ?: [];
        $row['publications'] = json_decode((string) ($row['publications_json'] ?? '[]'), true) ?: [];
        $extra = json_decode((string) ($row['extra_json'] ?? '{}'), true) ?: [];
        $row = array_merge($row, $extra);
        $aliases = db_connect()->table('faculty_profile_aliases')
            ->where('profile_id', $row['id'])->get()->getResultArray();
        $row['aliases'] = array_column($aliases, 'alias');
        $row['hod'] = ! empty($row['is_hod']);
        $row['department'] = $row['department_name'] ?? '';
        return $row;
    }
}
