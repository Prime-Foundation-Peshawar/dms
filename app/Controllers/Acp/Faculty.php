<?php

namespace App\Controllers\Acp;

use App\Libraries\FacultyReviewService;
use App\Models\DepartmentModel;
use App\Models\FacultyProfileModel;
use App\Models\FacultySubmissionModel;

class Faculty extends BaseAcpController
{
    public function index()
    {
        $rows = model(FacultyProfileModel::class)->orderBy('name', 'ASC')->findAll();

        return $this->acpView('faculty/index', [
            'title' => 'Faculty profiles',
            'rows'  => $rows,
        ]);
    }

    public function create()
    {
        $prefill = [
            'name'            => trim((string) $this->request->getGet('name')),
            'emp_id'          => trim((string) $this->request->getGet('emp_id')),
            'department_name' => trim((string) $this->request->getGet('department_name')),
            'department_slug' => trim((string) $this->request->getGet('department_slug')),
            'designation'     => trim((string) $this->request->getGet('designation')),
            'slug'            => '',
            'is_public'       => 1,
            'dept_override'   => 0,
        ];
        if ($prefill['name'] !== '') {
            $prefill['slug'] = $this->makeSlug($prefill['name']);
        }

        return $this->acpView('faculty/form', [
            'title'       => 'Create faculty profile',
            'row'         => $prefill['name'] !== '' ? $prefill : null,
            'departments' => model(DepartmentModel::class)->orderBy('name', 'ASC')->findAll(),
            'lists'       => [
                'qualifications' => '',
                'experience'     => '',
                'skills'         => '',
                'publications'   => '',
            ],
            'return_to'   => $this->safeReturnTo(),
        ]);
    }

    public function store()
    {
        $model = model(FacultyProfileModel::class);
        $name  = trim((string) $this->request->getPost('name'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($name);
        $photo = $this->storeUpload('photo', 'faculty', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

        $id = $model->insert($this->payload($slug, $name, $photo, null), true);

        return redirect()->to($this->afterSaveUrl((int) $id))->with('success', 'Faculty profile created.');
    }

    public function edit(int $id)
    {
        $row = model(FacultyProfileModel::class)->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Profile not found.');
        }

        return $this->acpView('faculty/form', [
            'title'       => 'Edit faculty profile',
            'row'         => $row,
            'departments' => model(DepartmentModel::class)->orderBy('name', 'ASC')->findAll(),
            'lists'       => [
                'qualifications' => $this->jsonToLines((string) ($row['qualifications_json'] ?? '[]')),
                'experience'     => $this->jsonToLines((string) ($row['experience_json'] ?? '[]')),
                'skills'         => $this->jsonToLines((string) ($row['skills_json'] ?? '[]')),
                'publications'   => $this->jsonToLines((string) ($row['publications_json'] ?? '[]')),
            ],
            'return_to'   => $this->safeReturnTo(),
        ]);
    }

    public function update(int $id)
    {
        $model = model(FacultyProfileModel::class);
        $row   = $model->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Profile not found.');
        }

        $name  = trim((string) $this->request->getPost('name'));
        $slug  = trim((string) $this->request->getPost('slug')) ?: $this->makeSlug($name);
        $photo = $this->storeUpload('photo', 'faculty', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        $data  = $this->payload($slug, $name, $photo ?: ($row['photo'] ?? null), $row);
        $model->update($id, $data);

        return redirect()->to($this->afterSaveUrl($id))->with('success', 'Profile updated.');
    }

    public function delete(int $id)
    {
        db_connect()->table('faculty_profile_aliases')->where('profile_id', $id)->delete();
        model(FacultyProfileModel::class)->delete($id);

        return redirect()->to($this->safeReturnTo() ?: site_url('acp/departments'))
            ->with('success', 'Profile deleted.');
    }

    /**
     * Switch department source: default (HRMS) or overridden (profile department).
     */
    public function setDeptSource()
    {
        $model   = model(FacultyProfileModel::class);
        $mode    = trim((string) $this->request->getPost('mode')); // default|override
        $empId   = trim((string) $this->request->getPost('emp_id'));
        $profileId = (int) $this->request->getPost('profile_id');
        $deptName = trim((string) $this->request->getPost('department_name'));
        $deptSlug = trim((string) $this->request->getPost('department_slug'));
        $name     = trim((string) $this->request->getPost('name'));
        $designation = trim((string) $this->request->getPost('designation'));

        $row = null;
        if ($profileId > 0) {
            $row = $model->find($profileId);
        } elseif ($empId !== '') {
            $row = $model->findByEmpId($empId);
        }

        if (! $row && $mode === 'override') {
            if ($empId === '' || $name === '' || $deptName === '') {
                return redirect()->to($this->safeReturnTo() ?: site_url('acp/departments'))
                    ->with('error', 'Need empId, name, and department to create an override profile.');
            }
            $profileId = (int) $model->insert([
                'slug'            => $this->makeSlug($name),
                'emp_id'          => $empId,
                'name'            => $name,
                'department_slug' => $deptSlug !== '' ? $deptSlug : $this->makeSlug($deptName),
                'department_name' => $deptName,
                'dept_override'   => 1,
                'designation'     => $designation,
                'is_public'       => 1,
                'qualifications_json' => '[]',
                'experience_json'     => '[]',
                'skills_json'         => '[]',
                'publications_json'   => '[]',
            ], true);

            return redirect()->to($this->safeReturnTo() ?: site_url('acp/departments'))
                ->with('success', 'Department override enabled.');
        }

        if (! $row) {
            return redirect()->to($this->safeReturnTo() ?: site_url('acp/departments'))
                ->with('error', 'Profile not found. Create a profile first, or enable override.');
        }

        if ($mode === 'override') {
            $patch = ['dept_override' => 1];
            // Keep an existing profile department; otherwise take the current ACP department.
            if (trim((string) ($row['department_name'] ?? '')) === '' && $deptName !== '') {
                $patch['department_name'] = $deptName;
                if ($deptSlug !== '') {
                    $patch['department_slug'] = $deptSlug;
                }
            }
            $model->update((int) $row['id'], $patch);
            $msg = 'Using overridden department.';
        } else {
            $model->update((int) $row['id'], [
                'dept_override' => 0,
            ]);
            $msg = 'Using HRMS default department.';
        }

        return redirect()->to($this->safeReturnTo() ?: site_url('acp/departments'))->with('success', $msg);
    }

    private function safeReturnTo(): string
    {
        $raw = trim((string) ($this->request->getPost('return_to') ?: $this->request->getGet('return_to')));
        if ($raw === '') {
            return '';
        }
        $path = parse_url($raw, PHP_URL_PATH) ?: '';
        if (is_string($path) && str_contains($path, '/acp/')) {
            return $raw;
        }

        return '';
    }

    private function afterSaveUrl(int $id): string
    {
        $back = $this->safeReturnTo();
        if ($back !== '') {
            return $back;
        }

        return site_url('acp/faculty/edit/' . $id);
    }

    /** @param array<string,mixed>|null $existing */
    private function payload(string $slug, string $name, ?string $photo, ?array $existing): array
    {
        $empId   = trim((string) $this->request->getPost('emp_id'));
        $deptName = trim((string) $this->request->getPost('department_name'));
        $extra   = [];
        if ($existing) {
            $extra = json_decode((string) ($existing['extra_json'] ?? '{}'), true) ?: [];
            unset($extra['aliases']);
        }

        return [
            'slug'                => $slug,
            'emp_id'              => $empId !== '' ? $empId : null,
            'name'                => $name,
            'department_slug'     => $deptName !== '' ? $this->makeSlug($deptName) : null,
            'department_name'     => $deptName,
            'dept_override'       => $this->request->getPost('dept_override') ? 1 : 0,
            'designation'         => trim((string) $this->request->getPost('designation')),
            'photo'               => $photo,
            'is_hod'              => $this->request->getPost('is_hod') ? 1 : 0,
            'is_public'           => $this->request->getPost('is_public') ? 1 : 0,
            'qualifications_json' => $this->linesToJson((string) $this->request->getPost('qualifications')),
            'experience_json'     => $this->linesToJson((string) $this->request->getPost('experience')),
            'skills_json'         => $this->linesToJson((string) $this->request->getPost('skills')),
            'publications_json'   => $this->linesToJson((string) $this->request->getPost('publications')),
            'extra_json'          => $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : null,
        ];
    }

    private function linesToJson(string $raw): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        $items = array_values(array_filter(array_map('trim', $lines), static fn ($l) => $l !== ''));

        return json_encode($items, JSON_UNESCAPED_UNICODE) ?: '[]';
    }

    private function jsonToLines(string $json): string
    {
        $data = json_decode($json, true);
        if (! is_array($data)) {
            return '';
        }
        $lines = [];
        foreach ($data as $item) {
            if (is_string($item) || is_numeric($item)) {
                $lines[] = (string) $item;
            } elseif (is_array($item)) {
                $lines[] = trim(implode(' — ', array_map('strval', $item)));
            }
        }

        return implode("\n", array_filter($lines, static fn ($l) => trim($l) !== ''));
    }

    public function submissions()
    {
        $status = (string) ($this->request->getGet('status') ?? 'pending');
        if (! in_array($status, ['pending', 'approved', 'rejected', 'all'], true)) {
            $status = 'pending';
        }
        $subs = model(FacultySubmissionModel::class);
        $subs->ensureTable();

        return $this->acpView('faculty/submissions', [
            'title'  => 'Faculty submissions',
            'status' => $status,
            'rows'   => $subs->listByStatus($status, 200),
            'counts' => [
                'pending'  => $subs->countByStatus('pending'),
                'approved' => $subs->countByStatus('approved'),
                'rejected' => $subs->countByStatus('rejected'),
                'all'      => (new FacultySubmissionModel())->countAllResults(),
            ],
        ]);
    }

    public function submission(int $id)
    {
        helper('faculty');
        $sub = model(FacultySubmissionModel::class)->findNormalized($id);
        if (! $sub) {
            return redirect()->to(site_url('acp/faculty/submissions'))->with('error', 'Submission not found.');
        }
        $existing = model(FacultyProfileModel::class)->findBySlugOrAlias((string) ($sub['slug'] ?? ''));

        return $this->acpView('faculty/submission_detail', [
            'title'    => 'Review submission',
            'sub'      => $sub,
            'existing' => $existing,
        ]);
    }

    public function approve(int $id)
    {
        $note   = trim((string) $this->request->getPost('review_note'));
        $result = (new FacultyReviewService())->approve($id, (int) session('acp_user_id'), $note);
        if (empty($result['ok'])) {
            return redirect()->to(site_url('acp/faculty/submissions/' . $id))
                ->with('error', $result['error'] ?? 'Approve failed.');
        }

        return redirect()->to(site_url('acp/faculty/submissions'))
            ->with('success', 'Submission approved and applied to the live profile.');
    }

    public function reject(int $id)
    {
        $note   = trim((string) $this->request->getPost('review_note'));
        $result = (new FacultyReviewService())->reject($id, (int) session('acp_user_id'), $note);
        if (empty($result['ok'])) {
            return redirect()->to(site_url('acp/faculty/submissions/' . $id))
                ->with('error', $result['error'] ?? 'Reject failed.');
        }

        return redirect()->to(site_url('acp/faculty/submissions'))
            ->with('success', 'Submission rejected.');
    }

    public function downloadFile(int $id, string $type)
    {
        $sub = model(FacultySubmissionModel::class)->findNormalized($id);
        if (! $sub || ! in_array($type, ['photo', 'publications'], true)) {
            return $this->response->setStatusCode(404)->setBody('Not found');
        }

        $rel = $type === 'photo' ? (string) ($sub['photo'] ?? '') : (string) ($sub['publications_file'] ?? '');
        if ($rel === '' || str_contains($rel, '..') || str_contains($rel, '\\')) {
            return $this->response->setStatusCode(404)->setBody('File missing');
        }

        $path = ROOTPATH . 'data/faculty-submissions/' . ltrim($rel, '/');
        if (! is_file($path)) {
            return $this->response->setStatusCode(404)->setBody('File missing');
        }

        return $this->response->download($path, null);
    }

}
