<?php

namespace App\Libraries;

use App\Models\FacultyProfileModel;
use App\Models\FacultySubmissionModel;
use App\Services\FacultyDirectoryService;

/**
 * Apply a faculty self-update onto the live faculty_profiles row after CMS review.
 */
class FacultyReviewService
{
    public function __construct(
        protected FacultySubmissionModel $submissions = new FacultySubmissionModel(),
        protected FacultyProfileModel $profiles = new FacultyProfileModel(),
    ) {
    }

    /**
     * @return array{ok:bool,error?:string}
     */
    public function approve(int $submissionId, int $reviewerId, string $note = ''): array
    {
        helper('faculty');
        $this->submissions->ensureTable();
        $sub = $this->submissions->findNormalized($submissionId);
        if (! $sub) {
            return ['ok' => false, 'error' => 'Submission not found.'];
        }
        if (($sub['status'] ?? '') === 'approved') {
            return ['ok' => false, 'error' => 'Already approved.'];
        }

        $slug = (string) ($sub['slug'] ?? '');
        if ($slug === '') {
            return ['ok' => false, 'error' => 'Missing faculty slug.'];
        }

        $existing = $this->profiles->findBySlugOrAlias($slug);
        if (! $existing) {
            $empId = $this->directoryEmpId($slug);
            if ($empId !== '') {
                $existing = $this->profiles->findByEmpId($empId);
            }
        }

        $photo = $this->publishPhoto($sub, $existing['photo'] ?? null);
        $extra = [];
        if ($existing) {
            $extra = json_decode((string) ($existing['extra_json'] ?? '{}'), true) ?: [];
            if (! is_array($extra)) {
                $extra = [];
            }
        }

        if (! empty($sub['research_preferences'])) {
            $extra['research_preferences'] = array_values($sub['research_preferences']);
        }
        if (trim((string) ($sub['publications_url'] ?? '')) !== '') {
            $extra['publications_url'] = (string) $sub['publications_url'];
        }
        if (trim((string) ($sub['contact_phone'] ?? '')) !== '') {
            $extra['contact_phone'] = (string) $sub['contact_phone'];
        }

        $payload = [
            'photo'      => $photo,
            'extra_json' => $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : null,
        ];

        if (! empty($sub['qualifications'])) {
            $payload['qualifications_json'] = json_encode(array_values($sub['qualifications']), JSON_UNESCAPED_UNICODE);
        }
        if (! empty($sub['skills'])) {
            $payload['skills_json'] = json_encode(array_values($sub['skills']), JSON_UNESCAPED_UNICODE);
        }
        $designation = trim((string) ($sub['des_title'] ?? ''));
        if ($designation !== '' && trim((string) ($existing['designation'] ?? '')) === '') {
            $payload['designation'] = $designation;
        }
        $depName = trim((string) ($sub['dep_name'] ?? ''));
        if ($depName !== '' && trim((string) ($existing['department_name'] ?? '')) === '') {
            $payload['department_name'] = $depName;
            $payload['department_slug'] = faculty_slug($depName);
        }

        if ($existing) {
            $this->profiles->update((int) $existing['id'], $payload);
        } else {
            $name = trim((string) ($sub['emp_name'] ?? ''));
            if ($name === '') {
                $name = $slug;
            }
            $payload = array_merge($payload, [
                'slug'                => $slug,
                'emp_id'              => $this->directoryEmpId($slug) ?: null,
                'name'                => $name,
                'department_slug'     => $depName !== '' ? faculty_slug($depName) : null,
                'department_name'     => $depName,
                'dept_override'       => 0,
                'designation'         => $designation,
                'is_hod'              => 0,
                'is_public'           => 1,
                'qualifications_json' => $payload['qualifications_json'] ?? '[]',
                'experience_json'     => '[]',
                'skills_json'         => $payload['skills_json'] ?? '[]',
                'publications_json'   => '[]',
            ]);
            $this->profiles->insert($payload);
        }

        $this->submissions->update($submissionId, [
            'status'      => 'approved',
            'review_note' => $note !== '' ? $note : null,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => $reviewerId > 0 ? $reviewerId : null,
        ]);

        return ['ok' => true];
    }

    /**
     * @return array{ok:bool,error?:string}
     */
    public function reject(int $submissionId, int $reviewerId, string $note = ''): array
    {
        $this->submissions->ensureTable();
        $sub = $this->submissions->findNormalized($submissionId);
        if (! $sub) {
            return ['ok' => false, 'error' => 'Submission not found.'];
        }

        $this->submissions->update($submissionId, [
            'status'      => 'rejected',
            'review_note' => $note !== '' ? $note : null,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => $reviewerId > 0 ? $reviewerId : null,
        ]);

        return ['ok' => true];
    }

    protected function directoryEmpId(string $slug): string
    {
        helper('faculty');
        try {
            foreach ((new FacultyDirectoryService())->publicDirectory() as $row) {
                if (faculty_slugs_match($slug, faculty_slug((string) ($row['empName'] ?? '')))) {
                    return trim((string) ($row['empId'] ?? ''));
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Faculty review directory lookup failed: ' . $e->getMessage());
        }

        return '';
    }

    /**
     * @param array<string,mixed> $sub
     */
    protected function publishPhoto(array $sub, ?string $fallback): ?string
    {
        $rel = (string) ($sub['photo'] ?? '');
        if ($rel === '' || str_contains($rel, '..')) {
            return $fallback ?: null;
        }

        $src = ROOTPATH . 'data/faculty-submissions/' . ltrim($rel, '/');
        if (! is_file($src)) {
            return $fallback ?: null;
        }

        $slug = preg_replace('/[^a-z0-9\-]+/', '-', strtolower((string) ($sub['slug'] ?? 'faculty'))) ?: 'faculty';
        $ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION) ?: 'jpg');
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return $fallback ?: null;
        }

        $destRel = 'uploads/faculty/approved/' . $slug . '.' . $ext;
        $dest    = FCPATH . $destRel;
        $dir     = dirname($dest);
        if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            return $fallback ?: null;
        }
        if (@copy($src, $dest)) {
            @chmod($dest, 0644);

            return $destRel;
        }

        return $fallback ?: null;
    }
}
