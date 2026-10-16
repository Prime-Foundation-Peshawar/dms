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

    public function ensureTable(): void
    {
        $db = db_connect();
        if ($db->tableExists($this->table)) {
            return;
        }
        $sql = ROOTPATH . 'db/changes/20261016_2000_faculty_profile_submissions.sql';
        $raw = is_file($sql) ? file_get_contents($sql) : false;
        if (! is_string($raw) || trim($raw) === '') {
            return;
        }
        $db->query($raw);
    }

    /**
     * @return list<array<string,mixed>>
     */
    public function listByStatus(?string $status = null, int $limit = 200): array
    {
        $this->builder()->resetQuery();
        $this->orderBy('submitted_at', 'DESC');
        if ($status !== null && $status !== '' && $status !== 'all') {
            $this->where('status', $status);
        }
        $rows = $this->findAll($limit);
        $out  = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                $out[] = $this->normalize($row);
            }
        }

        return $out;
    }

    public function countByStatus(string $status): int
    {
        $this->builder()->resetQuery();

        return $this->where('status', $status)->countAllResults();
    }

    /** @return array<string,mixed>|null */
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
            $raw = $row[$key] ?? null;
            if (is_array($raw)) {
                $row[$key] = array_values($raw);
                continue;
            }
            $decoded   = json_decode((string) $raw, true);
            $row[$key] = is_array($decoded) ? array_values($decoded) : [];
        }

        return $row;
    }
}
