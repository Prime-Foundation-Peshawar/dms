<?php

namespace App\Models;

use CodeIgniter\Model;

class PageViewModel extends Model
{
    protected $table         = 'page_views';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['day', 'path', 'dept_slug', 'views'];

    public function ensureTable(): void
    {
        $db = db_connect();
        if ($db->tableExists($this->table)) {
            return;
        }
        $sql = ROOTPATH . 'db/changes/20261029_0900_page_views.sql';
        $raw = is_file($sql) ? file_get_contents($sql) : false;
        if (! is_string($raw) || trim($raw) === '') {
            return;
        }
        $db->query(trim(preg_replace('/^\s*--.*$/m', '', $raw) ?? $raw));
    }

    public function record(string $path, ?string $deptSlug = null): void
    {
        $sql = 'INSERT INTO `page_views` (`day`, `path`, `dept_slug`, `views`) VALUES (?, ?, ?, 1)
                ON DUPLICATE KEY UPDATE `views` = `views` + 1';
        $args = [date('Y-m-d'), mb_substr($path, 0, 191), $deptSlug];

        try {
            db_connect()->query($sql, $args);
        } catch (\Throwable $e) {
            $this->ensureTable();
            db_connect()->query($sql, $args);
        }
    }
}
