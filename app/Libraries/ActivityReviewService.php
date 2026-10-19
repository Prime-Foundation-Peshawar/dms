<?php

namespace App\Libraries;

use App\Models\DepartmentActivityModel;
use App\Models\DepartmentModel;

/**
 * Review queue for public activity submissions (JSON under data/activity-submissions/).
 */
class ActivityReviewService
{
    private function rootDir(): string
    {
        return ROOTPATH . 'data/activity-submissions';
    }

    private function pathForKey(string $key): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9._\-]+/', '', $key) ?: '';

        return $this->rootDir() . '/' . $safe . '.json';
    }

    public function isValidKey(string $key): bool
    {
        return $key !== '' && (bool) preg_match('/^[a-zA-Z0-9._\-]+$/', $key);
    }

    /** @return array<string,mixed>|null */
    public function find(string $key): ?array
    {
        if (! $this->isValidKey($key)) {
            return null;
        }
        $path = $this->pathForKey($key);
        if (! is_file($path)) {
            return null;
        }
        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return null;
        }
        $data = json_decode($raw, true);
        if (! is_array($data)) {
            return null;
        }
        $data['submission_key'] = $key;
        $data['status']         = (string) ($data['status'] ?? 'pending');
        $data['photos']         = is_array($data['photos'] ?? null) ? array_values($data['photos']) : [];

        return $data;
    }

    /**
     * @return list<array<string,mixed>>
     */
    public function listByStatus(string $status = 'pending', int $limit = 200): array
    {
        $dir = $this->rootDir();
        if (! is_dir($dir)) {
            return [];
        }

        $out = [];
        foreach (glob($dir . '/*.json') ?: [] as $file) {
            $key  = basename($file, '.json');
            $row  = $this->find($key);
            if (! $row) {
                continue;
            }
            $st = (string) ($row['status'] ?? 'pending');
            if ($status !== 'all' && $st !== $status) {
                continue;
            }
            $out[] = $row;
        }

        usort($out, static function ($a, $b) {
            return strcmp((string) ($b['submitted_at'] ?? ''), (string) ($a['submitted_at'] ?? ''));
        });

        return array_slice($out, 0, $limit);
    }

    public function countByStatus(string $status): int
    {
        return count($this->listByStatus($status, 5000));
    }

    /**
     * @return array{ok:bool,error?:string}
     */
    public function approve(string $key, int $userId, string $note = ''): array
    {
        $sub = $this->find($key);
        if (! $sub) {
            return ['ok' => false, 'error' => 'Submission not found.'];
        }
        if (($sub['status'] ?? '') !== 'pending') {
            return ['ok' => false, 'error' => 'This submission was already reviewed.'];
        }

        $deptId = (int) ($sub['department_id'] ?? 0);
        $dept   = $deptId > 0 ? model(DepartmentModel::class)->find($deptId) : null;
        if (! $dept && ! empty($sub['department_slug'])) {
            $dept = model(DepartmentModel::class)->findBySlug((string) $sub['department_slug']);
            $deptId = (int) ($dept['id'] ?? 0);
        }
        if (! $dept || $deptId < 1) {
            return ['ok' => false, 'error' => 'Department not found for this submission.'];
        }

        $title = trim((string) ($sub['title'] ?? ''));
        $body  = trim((string) ($sub['body'] ?? ''));
        if ($title === '' || $body === '') {
            return ['ok' => false, 'error' => 'Title and description are required to publish.'];
        }

        $max = model(DepartmentActivityModel::class)
            ->where('department_id', $deptId)
            ->orderBy('sort_order', 'DESC')
            ->first();
        $next = (int) ($max['sort_order'] ?? -1) + 1;

        $this->ensurePhotosColumn();
        $publicPhotos = $this->publishPhotos($sub, $key);

        $insert = [
            'department_id' => $deptId,
            'title'         => $title,
            'date_label'    => trim((string) ($sub['date_label'] ?? '')),
            'body'          => $body,
            'sort_order'    => $next,
        ];
        if (db_connect()->fieldExists('photos_json', 'department_activities')) {
            $insert['photos_json'] = $publicPhotos !== []
                ? json_encode($publicPhotos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : null;
        }

        model(DepartmentActivityModel::class)->insert($insert);

        $sub['status']          = 'approved';
        $sub['reviewed_at']     = date('Y-m-d H:i:s');
        $sub['reviewed_by']     = $userId > 0 ? $userId : null;
        $sub['review_note']     = $note;
        $sub['department_id']   = $deptId;
        $sub['department_name'] = (string) ($dept['name'] ?? $sub['department_name'] ?? '');
        $sub['published_photos'] = $publicPhotos;

        if (! $this->write($key, $sub)) {
            return ['ok' => false, 'error' => 'Published, but could not update submission status.'];
        }

        return ['ok' => true];
    }

    private function ensurePhotosColumn(): void
    {
        try {
            $db = db_connect();
            if ($db->tableExists('department_activities') && ! $db->fieldExists('photos_json', 'department_activities')) {
                $db->query('ALTER TABLE `department_activities` ADD COLUMN `photos_json` JSON NULL AFTER `body`');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Could not ensure department_activities.photos_json: ' . $e->getMessage());
        }
    }

    /**
     * Copy submission images into public uploads for the live site.
     *
     * @param array<string,mixed> $sub
     * @return list<string>
     */
    private function publishPhotos(array $sub, string $key): array
    {
        $photos = is_array($sub['photos'] ?? null) ? $sub['photos'] : [];
        if ($photos === []) {
            return [];
        }

        $safeKey = preg_replace('/[^a-zA-Z0-9._\-]+/', '', $key) ?: 'activity';
        $out     = [];
        $n       = 0;
        foreach ($photos as $rel) {
            $rel = str_replace('\\', '/', (string) $rel);
            if ($rel === '' || str_contains($rel, '..')) {
                continue;
            }
            if (! preg_match('#^(photos|drafts)/[a-zA-Z0-9._\-/]+$#', $rel)) {
                continue;
            }
            $src = $this->rootDir() . '/' . $rel;
            if (! is_file($src)) {
                continue;
            }
            $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION) ?: 'jpg');
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                continue;
            }
            $destRel = 'uploads/department-activities/' . date('Y/m') . '/' . $safeKey . '-' . $n . '.' . $ext;
            $dest    = FCPATH . $destRel;
            $dir     = dirname($dest);
            if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
                continue;
            }
            if (@copy($src, $dest)) {
                @chmod($dest, 0644);
                $out[] = $destRel;
                $n++;
            }
            if ($n >= 6) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array{ok:bool,error?:string}
     */
    public function reject(string $key, int $userId, string $note = ''): array
    {
        $sub = $this->find($key);
        if (! $sub) {
            return ['ok' => false, 'error' => 'Submission not found.'];
        }
        if (($sub['status'] ?? '') !== 'pending') {
            return ['ok' => false, 'error' => 'This submission was already reviewed.'];
        }

        $sub['status']      = 'rejected';
        $sub['reviewed_at'] = date('Y-m-d H:i:s');
        $sub['reviewed_by'] = $userId > 0 ? $userId : null;
        $sub['review_note'] = $note;

        if (! $this->write($key, $sub)) {
            return ['ok' => false, 'error' => 'Could not update submission status.'];
        }

        return ['ok' => true];
    }

    /**
     * Absolute path for a photo relative path stored on the submission, or null.
     */
    public function photoAbsolutePath(array $sub, int $index): ?string
    {
        $photos = is_array($sub['photos'] ?? null) ? $sub['photos'] : [];
        if (! isset($photos[$index])) {
            return null;
        }
        $rel = str_replace('\\', '/', (string) $photos[$index]);
        if ($rel === '' || str_contains($rel, '..')) {
            return null;
        }
        if (! preg_match('#^(photos|drafts)/[a-zA-Z0-9._\-/]+$#', $rel)) {
            return null;
        }
        $full = $this->rootDir() . '/' . $rel;

        return is_file($full) ? $full : null;
    }

    /** @param array<string,mixed> $payload */
    private function write(string $key, array $payload): bool
    {
        $path = $this->pathForKey($key);
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        return @file_put_contents($path, $json . "\n") !== false;
    }
}
