<?php

namespace App\Controllers;

use App\Models\DepartmentModel;
use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Public department activity form — intentionally simple for non-technical staff.
 * Submissions stay pending until CMS review (wired next; local preview shows the form).
 */
class ActivityUpdate extends BaseController
{
    public function index(): string
    {
        $depts = model(DepartmentModel::class)->visibleDepartments();
        usort($depts, static fn ($a, $b) => strcasecmp((string) ($a['name'] ?? ''), (string) ($b['name'] ?? '')));

        $selectedSlug = trim((string) ($this->request->getGet('dept') ?? ''));
        $recent = $this->recentActivities($selectedSlug, 8);

        return view('pages/activity-update', [
            'page_title'       => 'Activity Submission | Department of Medical Sciences',
            'page_description' => 'Official form for submitting departmental academic activities for the PMC website.',
            'robots'           => 'noindex, nofollow',
            'thanks'           => $this->request->getGet('thanks') !== null,
            'errorMsg'         => trim((string) ($this->request->getGet('error') ?? '')),
            'departments'      => $depts,
            'selectedSlug'     => $selectedSlug,
            'recent'           => $recent,
        ]);
    }

    public function submit()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->to(site_url('update-activity'));
        }
        // Honeypot
        if (trim((string) $this->request->getPost('website')) !== '') {
            return redirect()->to(site_url('update-activity?thanks=1'));
        }

        $deptSlug = trim((string) $this->request->getPost('department_slug'));
        $title    = trim((string) $this->request->getPost('title'));
        $dateLabel = trim((string) $this->request->getPost('date_label'));
        $body     = trim((string) $this->request->getPost('body'));

        if ($deptSlug === '') {
            return $this->fail('Please choose your department.');
        }
        $dept = model(DepartmentModel::class)->findBySlug($deptSlug);
        if (! $dept) {
            return $this->fail('Please choose a department from the list.');
        }
        if ($title === '') {
            return $this->fail('Please write a short title for the activity.');
        }
        if ($body === '') {
            return $this->fail('Please add a brief description of the activity.');
        }
        if (strlen($title) > 190) {
            $title = substr($title, 0, 190);
        }
        if (strlen($dateLabel) > 120) {
            $dateLabel = substr($dateLabel, 0, 120);
        }
        if (strlen($body) > 4000) {
            $body = substr($body, 0, 4000);
        }

        $photos = $this->storePhotos();
        if (isset($photos['error'])) {
            return $this->fail($photos['error']);
        }

        $saved = $this->storePending([
            'department_id'   => (int) $dept['id'],
            'department_slug' => (string) $dept['slug'],
            'department_name' => (string) $dept['name'],
            'title'           => $title,
            'date_label'      => $dateLabel,
            'body'            => $body,
            'photos'          => $photos['paths'],
            'ip'              => (string) $this->request->getIPAddress(),
            'submitted_at'    => date('Y-m-d H:i:s'),
            'status'          => 'pending',
        ]);

        if (! $saved) {
            return $this->fail('Could not save. Please try again.');
        }

        return redirect()->to(site_url('update-activity?thanks=1&dept=' . rawurlencode($deptSlug)));
    }

    private function fail(string $message)
    {
        $dept = trim((string) $this->request->getPost('department_slug'));
        $q = 'error=' . rawurlencode($message);
        if ($dept !== '') {
            $q .= '&dept=' . rawurlencode($dept);
        }

        return redirect()->to(site_url('update-activity?' . $q));
    }

    /**
     * @return list<array{dept:string,slug:string,title:string,date:string,href:string}>
     */
    private function recentActivities(string $selectedSlug, int $limit = 8): array
    {
        $db = db_connect();
        if (! $db->tableExists('department_activities') || ! $db->tableExists('departments')) {
            return [];
        }

        $builder = $db->table('department_activities a')
            ->select('a.title, a.date_label, a.body, a.id, d.name AS dept_name, d.slug AS dept_slug')
            ->join('departments d', 'd.id = a.department_id')
            ->where('d.hidden', 0)
            ->orderBy('a.id', 'DESC')
            ->limit($limit);

        if ($selectedSlug !== '') {
            $builder->where('d.slug', $selectedSlug);
        }

        $rows = $builder->get()->getResultArray();
        $out  = [];
        foreach ($rows as $row) {
            $slug = (string) ($row['dept_slug'] ?? '');
            $out[] = [
                'dept'  => (string) ($row['dept_name'] ?? ''),
                'slug'  => $slug,
                'title' => (string) ($row['title'] ?? ''),
                'date'  => str_replace("\u{00E2}\u{20AC}\u{201C}", "\u{2013}", (string) ($row['date_label'] ?? '')),
                'href'  => site_url('department?slug=' . rawurlencode($slug) . '#activities'),
            ];
        }

        return $out;
    }

    /**
     * @return array{paths:list<string>}|array{error:string}
     */
    private function storePhotos(): array
    {
        $files = $this->request->getFileMultiple('photos') ?: [];
        if (! $files) {
            $single = $this->request->getFile('photos');
            $files  = $single ? [$single] : [];
        }

        $paths = [];
        $map   = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
        ];
        $dir = ROOTPATH . 'data/activity-submissions/photos';
        foreach ($files as $file) {
            if (! $file instanceof UploadedFile || $file->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if (! $file->isValid() || $file->hasMoved()) {
                return ['error' => 'One image failed to upload. Try a smaller JPG or PNG.'];
            }
            if ($file->getSize() > 4 * 1024 * 1024) {
                return ['error' => 'Each image must be under 4 MB.'];
            }
            $mime = (string) $file->getMimeType();
            if (! isset($map[$mime])) {
                return ['error' => 'Images must be JPG, PNG, WEBP, or GIF.'];
            }
            if (count($paths) >= 6) {
                break;
            }
            if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
                return ['error' => 'Could not save images. Please try again.'];
            }
            $name = date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . $map[$mime];
            $file->move($dir, $name, true);
            @chmod($dir . '/' . $name, 0644);
            $paths[] = 'photos/' . $name;
        }

        return ['paths' => $paths];
    }

    /** @param array<string,mixed> $payload */
    private function storePending(array $payload): bool
    {
        $dir = ROOTPATH . 'data/activity-submissions';
        if (! is_dir($dir) && ! @mkdir($dir, 0755, true) && ! is_dir($dir)) {
            return false;
        }
        $key = date('Ymd-His') . '-' . bin2hex(random_bytes(3));
        $path = $dir . '/' . $key . '.json';
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false || @file_put_contents($path, $json . "\n") === false) {
            return false;
        }
        @chmod($path, 0644);

        try {
            $db = db_connect();
            if ($db->tableExists('department_activity_submissions')) {
                $row = [
                    'submission_key'  => $key,
                    'submitted_at'    => $payload['submitted_at'],
                    'ip'              => $payload['ip'] !== '' ? $payload['ip'] : null,
                    'department_id'   => $payload['department_id'],
                    'department_slug' => $payload['department_slug'],
                    'department_name' => $payload['department_name'],
                    'title'           => $payload['title'],
                    'date_label'      => $payload['date_label'] !== '' ? $payload['date_label'] : null,
                    'body'            => $payload['body'] !== '' ? $payload['body'] : null,
                    'status'          => 'pending',
                    'created_at'      => $payload['submitted_at'],
                ];
                if ($db->fieldExists('photos_json', 'department_activity_submissions')) {
                    $row['photos_json'] = json_encode($payload['photos'] ?? [], JSON_UNESCAPED_UNICODE);
                }
                $db->table('department_activity_submissions')->insert($row);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Activity submission DB save skipped: ' . $e->getMessage());
        }

        return true;
    }
}
