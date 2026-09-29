<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultySubmissionModel extends Model
{
    protected $table         = 'faculty_profile_submissions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'submission_key',
        'submitted_at',
        'ip',
        'college',
        'emp_name',
        'slug',
        'des_title',
        'dep_name',
        'research_preferences',
        'publications_url',
        'publications_file',
        'publications',
        'qualifications',
        'skills',
        'contact_phone',
        'photo',
        'status',
        'review_note',
        'reviewed_at',
        'reviewed_by',
        'created_at',
    ];

    /**
     * @return list<array<string,mixed>>
     */
    public function listByStatus(?string $status = null, int $limit = 100): array
    {
        $builder = $this->orderBy('submitted_at', 'DESC')->limit($limit);
        if ($status !== null && $status !== '' && $status !== 'all') {
            $builder->where('status', $status);
        }
        $rows = $builder->findAll();
        $out = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                $out[] = $this->normalize($row);
            }
        }

        return $out;
    }

    public function countByStatus(string $status): int
    {
        return (int) $this->builder()->where('status', $status)->countAllResults();
    }

    public function findNormalized(int $id): ?array
    {
        $row = $this->find($id);

        return is_array($row) ? $this->normalize($row) : null;
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    public function normalize(array $row): array
    {
        foreach (['research_preferences', 'publications', 'qualifications', 'skills'] as $key) {
            $row[$key] = dms_json_list($row[$key] ?? null);
        }

        return $row;
    }
}
