<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyProfileModel extends Model
{
    protected $table         = 'faculty_profiles';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'slug',
        'aliases',
        'name',
        'department',
        'designation',
        'hod',
        'photo',
        'qualifications',
        'experience',
        'skills',
        'publications',
        'research_preferences',
        'publications_url',
        'contact_phone',
        'source',
        'updated_at',
        'created_at',
    ];

    public function findBySlug(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->first();

        return is_array($row) ? $this->normalize($row) : null;
    }

    /**
     * @return list<array<string,mixed>>
     */
    public function allNormalized(): array
    {
        $rows = $this->orderBy('name', 'ASC')->findAll();
        $out = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                $out[] = $this->normalize($row);
            }
        }

        return $out;
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    public function normalize(array $row): array
    {
        foreach (['aliases', 'qualifications', 'experience', 'skills', 'publications', 'research_preferences'] as $key) {
            $row[$key] = dms_json_list($row[$key] ?? null);
        }
        $row['hod'] = !empty($row['hod']);

        return $row;
    }
}
