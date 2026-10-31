<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Department data entered in the ACP on a department's behalf (department info, faculty profiles).
 * Feeds the Reports page alongside activities and public submissions.
 */
class DepartmentDataLogModel extends Model
{
    public const TYPE_DEPARTMENT_INFO = 'department_info';
    public const TYPE_FACULTY_PROFILE = 'faculty_profile';

    protected $table         = 'department_data_log';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['department_id', 'data_type', 'ref_key', 'source', 'user_id', 'created_at'];

    /** @var array<string,int>|null */
    private static ?array $lookup = null;

    public function ensureTable(): void
    {
        $db = db_connect();
        if ($db->tableExists($this->table)) {
            return;
        }
        $sql = ROOTPATH . 'db/changes/20261030_1200_department_data_log.sql';
        $raw = is_file($sql) ? file_get_contents($sql) : false;
        if (! is_string($raw) || trim($raw) === '') {
            return;
        }
        $db->query(trim(preg_replace('/^\s*--.*$/m', '', $raw) ?? $raw));
    }

    public function record(int $departmentId, string $type, string $refKey = '', string $source = 'acp'): void
    {
        if ($departmentId < 1) {
            return;
        }
        $row = [
            'department_id' => $departmentId,
            'data_type'     => $type,
            'ref_key'       => $refKey !== '' ? mb_substr($refKey, 0, 191) : null,
            'source'        => $source,
            'user_id'       => is_cli() ? null : ((int) session()->get('acp_user_id') ?: null),
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        try {
            $this->ensureTable();
            db_connect()->table($this->table)->insert($row);
        } catch (\Throwable $e) {
            log_message('error', 'Department data log failed: ' . $e->getMessage());
        }
    }

    /** Department id for a slug or (loosely spelled) department name; 0 when unknown. */
    public static function departmentIdFor(?string $slug, ?string $name = null): int
    {
        $lookup = self::departmentLookup();

        return $lookup[self::normalizeName((string) $slug)] ?? $lookup[self::normalizeName((string) $name)] ?? 0;
    }

    /** @return array<string,int> normalized name/slug => department id */
    public static function departmentLookup(): array
    {
        if (self::$lookup === null) {
            self::$lookup = [];
            foreach (db_connect()->table('departments')->select('id, slug, name')->get()->getResultArray() as $d) {
                self::$lookup[self::normalizeName((string) $d['name'])] = (int) $d['id'];
                self::$lookup[self::normalizeName((string) $d['slug'])] = (int) $d['id'];
            }
            unset(self::$lookup['']);
        }

        return self::$lookup;
    }

    /** Lower-case, alias-aware key so "Orthopaedics" matches "Orthopedics", etc. */
    public static function normalizeName(string $s): string
    {
        helper('faculty');
        $s = strtolower(faculty_canonical_dept(trim($s)));
        $s = preg_replace('/^(department|dept\.?)\s+of\s+/', '', $s) ?? $s;

        return preg_replace('/[^a-z0-9]+/', '', $s) ?? $s;
    }
}
