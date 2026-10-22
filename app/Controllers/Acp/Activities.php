<?php

namespace App\Controllers\Acp;

use App\Libraries\ActivityReviewService;

class Activities extends BaseAcpController
{
    public function submissions()
    {
        $status = (string) ($this->request->getGet('status') ?? 'pending');
        if (! in_array($status, ['pending', 'approved', 'rejected', 'all'], true)) {
            $status = 'pending';
        }
        $svc = new ActivityReviewService();
        $synced = $svc->syncMissingPhotos();
        if ($synced > 0) {
            session()->setFlashdata('success', $synced . ' approved activit' . ($synced === 1 ? 'y' : 'ies') . ' updated with images.');
        }

        return $this->acpView('activities/submissions', [
            'title'  => 'Activity submissions',
            'status' => $status,
            'rows'   => $svc->listByStatus($status, 200),
            'counts' => [
                'pending'  => $svc->countByStatus('pending'),
                'approved' => $svc->countByStatus('approved'),
                'rejected' => $svc->countByStatus('rejected'),
                'all'      => $svc->countByStatus('all'),
            ],
        ]);
    }

    public function submission(string $key)
    {
        $svc = new ActivityReviewService();
        $sub = $svc->find($key);
        if (! $sub) {
            return redirect()->to(site_url('acp/activities/submissions'))->with('error', 'Submission not found.');
        }

        return $this->acpView('activities/submission_detail', [
            'title' => 'Review activity',
            'sub'   => $sub,
            'key'   => $key,
        ]);
    }

    public function approve(string $key)
    {
        $note   = trim((string) $this->request->getPost('review_note'));
        $result = (new ActivityReviewService())->approve($key, (int) session('acp_user_id'), $note);
        if (empty($result['ok'])) {
            return redirect()->to(site_url('acp/activities/submissions/' . rawurlencode($key)))
                ->with('error', $result['error'] ?? 'Approve failed.');
        }

        return redirect()->to(site_url('acp/activities/submissions'))
            ->with('success', 'Activity approved and published on the department page.');
    }

    public function reject(string $key)
    {
        $note   = trim((string) $this->request->getPost('review_note'));
        $result = (new ActivityReviewService())->reject($key, (int) session('acp_user_id'), $note);
        if (empty($result['ok'])) {
            return redirect()->to(site_url('acp/activities/submissions/' . rawurlencode($key)))
                ->with('error', $result['error'] ?? 'Reject failed.');
        }

        return redirect()->to(site_url('acp/activities/submissions'))
            ->with('success', 'Activity submission rejected.');
    }

    public function republishImages(string $key)
    {
        $result = (new ActivityReviewService())->republishImages($key);
        if (empty($result['ok'])) {
            return redirect()->to(site_url('acp/activities/submissions/' . rawurlencode($key)))
                ->with('error', $result['error'] ?? 'Could not publish images.');
        }

        return redirect()->to(site_url('acp/activities/submissions/' . rawurlencode($key)))
            ->with('success', (int) ($result['count'] ?? 0) . ' image(s) published to the activity page.');
    }

    public function file(string $key, int $index)
    {
        $svc = new ActivityReviewService();
        $sub = $svc->find($key);
        if (! $sub) {
            return $this->response->setStatusCode(404)->setBody('Not found');
        }
        $path = $svc->photoAbsolutePath($sub, $index);
        if (! $path) {
            return $this->response->setStatusCode(404)->setBody('File missing');
        }

        $mime = @mime_content_type($path) ?: 'application/octet-stream';
        if (str_starts_with($mime, 'image/')) {
            return $this->response
                ->setHeader('Content-Type', $mime)
                ->setHeader('Content-Length', (string) filesize($path))
                ->setHeader('Cache-Control', 'private, max-age=600')
                ->setBody((string) file_get_contents($path));
        }

        return $this->response->download($path, null);
    }
}
