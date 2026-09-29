<?php

namespace App\Libraries;

use App\Models\FacultyProfileModel;
use App\Models\FacultySubmissionModel;

/**
 * Apply faculty form submissions onto live faculty_profiles.
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
        $sub = $this->submissions->findNormalized($submissionId);
        if (!$sub) {
            return ['ok' => false, 'error' => 'Submission not found.'];
        }
        if (($sub['status'] ?? '') === 'approved') {
            return ['ok' => false, 'error' => 'Already approved.'];
        }

        $slug = (string) ($sub['slug'] ?? '');
        if ($slug === '') {
            return ['ok' => false, 'error' => 'Missing faculty slug.'];
        }

        $existing = $this->profiles->findBySlug($slug);
        $photo = $this->publishPhoto($sub, $existing['photo'] ?? null);

        $payload = [
            'slug'                  => $slug,
            'name'                  => (string) ($sub['emp_name'] ?? ($existing['name'] ?? $slug)),
            'department'            => (string) ($sub['dep_name'] ?? ($existing['department'] ?? '')),
            'designation'           => (string) ($sub['des_title'] ?? ($existing['designation'] ?? '')),
            'research_preferences'  => json_encode($sub['research_preferences'] ?? [], JSON_UNESCAPED_UNICODE),
            'publications_url'      => $sub['publications_url'] ?: ($existing['publications_url'] ?? null),
            'qualifications'        => json_encode(
                !empty($sub['qualifications']) ? $sub['qualifications'] : ($existing['qualifications'] ?? []),
                JSON_UNESCAPED_UNICODE
            ),
            'skills'                => json_encode(
                !empty($sub['skills']) ? $sub['skills'] : ($existing['skills'] ?? []),
                JSON_UNESCAPED_UNICODE
            ),
            'contact_phone'         => $sub['contact_phone'] ?: ($existing['contact_phone'] ?? null),
            'photo'                 => $photo,
            'source'                => 'faculty-update',
            'updated_at'            => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $payload['aliases'] = json_encode($existing['aliases'] ?? [], JSON_UNESCAPED_UNICODE);
            $payload['experience'] = json_encode($existing['experience'] ?? [], JSON_UNESCAPED_UNICODE);
            $payload['publications'] = json_encode($existing['publications'] ?? [], JSON_UNESCAPED_UNICODE);
            $payload['hod'] = !empty($existing['hod']) ? 1 : 0;
            $this->profiles->update((int) $existing['id'], $payload);
        } else {
            $payload['aliases'] = json_encode([$slug], JSON_UNESCAPED_UNICODE);
            $payload['experience'] = json_encode([], JSON_UNESCAPED_UNICODE);
            $payload['publications'] = json_encode([], JSON_UNESCAPED_UNICODE);
            $payload['hod'] = 0;
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->profiles->insert($payload);
        }

        $this->submissions->update($submissionId, [
            'status'      => 'approved',
            'review_note' => $note !== '' ? $note : null,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => $reviewerId,
        ]);

        return ['ok' => true];
    }

    /**
     * @return array{ok:bool,error?:string}
     */
    public function reject(int $submissionId, int $reviewerId, string $note = ''): array
    {
        $sub = $this->submissions->findNormalized($submissionId);
        if (!$sub) {
            return ['ok' => false, 'error' => 'Submission not found.'];
        }

        $this->submissions->update($submissionId, [
            'status'      => 'rejected',
            'review_note' => $note !== '' ? $note : null,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => $reviewerId,
        ]);

        return ['ok' => true];
    }

    /**
     * @param array<string,mixed> $sub
     */
    protected function publishPhoto(array $sub, ?string $fallback): ?string
    {
        $rel = (string) ($sub['photo'] ?? '');
        if ($rel === '') {
            return $fallback ?: null;
        }

        $src = ROOTPATH . 'data/faculty-submissions/' . ltrim($rel, '/');
        if (!is_file($src)) {
            return $fallback ?: null;
        }

        $slug = preg_replace('/[^a-z0-9\-]+/', '-', strtolower((string) ($sub['slug'] ?? 'faculty'))) ?: 'faculty';
        $ext = pathinfo($src, PATHINFO_EXTENSION) ?: 'jpg';
        $destRel = 'assets/images/faculty/' . $slug . '.' . $ext;
        $dest = FCPATH . $destRel;
        $dir = dirname($dest);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (@copy($src, $dest)) {
            @chmod($dest, 0644);

            return $destRel;
        }

        return $fallback ?: null;
    }
}
