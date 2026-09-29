<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Libraries\FacultyReviewService;
use App\Models\FacultyProfileModel;
use App\Models\FacultySubmissionModel;

class Faculty extends BaseController
{
    public function submissions()
    {
        $status = (string) ($this->request->getGet('status') ?? 'pending');
        $subs = model(FacultySubmissionModel::class);

        return view('acp/faculty/submissions', [
            'title'      => 'Faculty submissions',
            'nav'        => 'submissions',
            'status'     => $status,
            'rows'       => $subs->listByStatus($status, 200),
            'counts'     => [
                'pending'  => $subs->countByStatus('pending'),
                'approved' => $subs->countByStatus('approved'),
                'rejected' => $subs->countByStatus('rejected'),
                'all'      => $subs->countAllResults(),
            ],
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function submission(int $id)
    {
        $sub = model(FacultySubmissionModel::class)->findNormalized($id);
        if (!$sub) {
            return redirect()->to(site_url('acp/faculty/submissions'))->with('error', 'Submission not found.');
        }

        $existing = model(FacultyProfileModel::class)->findBySlug((string) ($sub['slug'] ?? ''));

        return view('acp/faculty/submission_detail', [
            'title'      => 'Review submission',
            'nav'        => 'submissions',
            'sub'        => $sub,
            'existing'   => $existing,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function approve(int $id)
    {
        $note = trim((string) $this->request->getPost('review_note'));
        $result = (new FacultyReviewService())->approve($id, (int) session('cms_user_id'), $note);
        if (empty($result['ok'])) {
            return redirect()->to(site_url('acp/faculty/submissions/' . $id))
                ->with('error', $result['error'] ?? 'Approve failed.');
        }

        return redirect()->to(site_url('acp/faculty/submissions'))
            ->with('ok', 'Submission approved and applied to the live profile.');
    }

    public function reject(int $id)
    {
        $note = trim((string) $this->request->getPost('review_note'));
        $result = (new FacultyReviewService())->reject($id, (int) session('cms_user_id'), $note);
        if (empty($result['ok'])) {
            return redirect()->to(site_url('acp/faculty/submissions/' . $id))
                ->with('error', $result['error'] ?? 'Reject failed.');
        }

        return redirect()->to(site_url('acp/faculty/submissions'))
            ->with('ok', 'Submission rejected.');
    }

    public function profiles()
    {
        $rows = model(FacultyProfileModel::class)->allNormalized();

        return view('acp/faculty/profiles', [
            'title' => 'Live faculty profiles',
            'nav'   => 'profiles',
            'rows'  => $rows,
        ]);
    }

    public function editProfile(int $id)
    {
        $model = model(FacultyProfileModel::class);
        $row = $model->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/faculty/profiles'))->with('error', 'Profile not found.');
        }
        $row = $model->normalize($row);

        return view('acp/faculty/profile_edit', [
            'title'      => 'Edit profile',
            'nav'        => 'profiles',
            'row'        => $row,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function saveProfile(int $id)
    {
        $model = model(FacultyProfileModel::class);
        $row = $model->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/faculty/profiles'))->with('error', 'Profile not found.');
        }

        $lines = static function ($raw): array {
            $parts = preg_split('/\r\n|\r|\n/', (string) $raw) ?: [];
            $out = [];
            foreach ($parts as $p) {
                $t = trim($p);
                if ($t !== '') {
                    $out[] = $t;
                }
            }

            return $out;
        };

        $model->update($id, [
            'name'                 => trim((string) $this->request->getPost('name')),
            'department'           => trim((string) $this->request->getPost('department')),
            'designation'          => trim((string) $this->request->getPost('designation')),
            'publications_url'     => trim((string) $this->request->getPost('publications_url')) ?: null,
            'research_preferences' => json_encode($lines($this->request->getPost('research_preferences')), JSON_UNESCAPED_UNICODE),
            'qualifications'       => json_encode($lines($this->request->getPost('qualifications')), JSON_UNESCAPED_UNICODE),
            'skills'               => json_encode($lines($this->request->getPost('skills')), JSON_UNESCAPED_UNICODE),
            'updated_at'           => date('Y-m-d H:i:s'),
            'source'               => 'acp',
        ]);

        return redirect()->to(site_url('acp/faculty/profiles/' . $id))
            ->with('ok', 'Profile saved.');
    }

    public function downloadFile(int $id, string $type)
    {
        $sub = model(FacultySubmissionModel::class)->findNormalized($id);
        if (!$sub) {
            return $this->response->setStatusCode(404)->setBody('Not found');
        }

        $rel = $type === 'photo' ? (string) ($sub['photo'] ?? '') : (string) ($sub['publications_file'] ?? '');
        if ($rel === '') {
            return $this->response->setStatusCode(404)->setBody('File missing');
        }

        $path = ROOTPATH . 'data/faculty-submissions/' . ltrim($rel, '/');
        if (!is_file($path)) {
            return $this->response->setStatusCode(404)->setBody('File missing');
        }

        return $this->response->download($path, null);
    }
}
