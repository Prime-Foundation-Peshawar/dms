<?php

namespace App\Controllers;

use App\Models\FacultySubmissionModel;
use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Public faculty self-update form. Saves a pending submission for CMS review.
 */
class FacultyUpdate extends BaseController
{
    protected string $college = 'dms';

    public function index(): string
    {
        return view('pages/faculty-update', [
            'page_title'       => 'Faculty Profile Form | Department of Medical Sciences',
            'page_description' => 'Add or update your PMC faculty profile for the college website.',
            'robots'           => 'noindex, nofollow',
            'thanks'           => $this->request->getGet('thanks') !== null,
            'errorMsg'         => trim((string) ($this->request->getGet('error') ?? '')),
        ]);
    }

    public function submit()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return redirect()->to(site_url('faculty-update'));
        }
        if (trim((string) $this->request->getPost('website')) !== '') {
            return redirect()->to(site_url('faculty-update?thanks=1'));
        }

        $ip = (string) $this->request->getIPAddress();
        if ($this->rateLimited($ip)) {
            return $this->fail('Please wait about a minute before submitting again.');
        }

        helper('faculty');
        $empName = trim((string) $this->request->getPost('emp_name'));
        $slug    = faculty_slug((string) ($this->request->getPost('slug') ?: $empName));
        $desTitle = trim((string) $this->request->getPost('des_title'));
        $depName  = trim((string) $this->request->getPost('dep_name'));
        $phone    = trim((string) $this->request->getPost('contact_phone'));
        $research = $this->lines((string) $this->request->getPost('research_preferences'), 8);
        $projects = $this->lines((string) $this->request->getPost('current_research_projects'), 12);
        $quals    = $this->lines((string) $this->request->getPost('qualifications'), 12);
        $skills   = $this->lines((string) $this->request->getPost('skills'), 12);
        $publicationsUrl = $this->cleanUrl((string) $this->request->getPost('publications_url'));

        if ($empName === '' || $slug === '') {
            return $this->fail('Please select your name from the list.');
        }
        if (count($research) < 1) {
            return $this->fail('Please add at least one research topic tag.');
        }

        $pubsFile = $this->request->getFile('publications_file');
        if (! $pubsFile instanceof UploadedFile || ! $pubsFile->isValid()) {
            return $this->fail('Please upload your full publications list (PDF, Word, or TXT).');
        }
        if (strlen($phone) > 40) {
            $phone = substr($phone, 0, 40);
        }

        $saved = $this->storeFiles($pubsFile, $this->request->getFile('photo'));
        if (isset($saved['error'])) {
            return $this->fail($saved['error']);
        }

        $key = $saved['key'];
        $now = date('Y-m-d H:i:s');
        $record = [
            'submission_key'        => $key,
            'submitted_at'          => $now,
            'ip'                    => $ip !== '' ? $ip : null,
            'college'               => $this->college,
            'emp_name'              => $empName,
            'slug'                  => $slug,
            'des_title'             => $desTitle !== '' ? $desTitle : null,
            'dep_name'              => $depName !== '' ? $depName : null,
            'research_preferences'       => json_encode($research, JSON_UNESCAPED_UNICODE),
            'current_research_projects'  => json_encode($projects, JSON_UNESCAPED_UNICODE),
            'publications_url'      => $publicationsUrl !== '' ? $publicationsUrl : null,
            'publications_file'     => $saved['publications_file'],
            'publications'          => '[]',
            'qualifications'        => json_encode($quals, JSON_UNESCAPED_UNICODE),
            'skills'                => json_encode($skills, JSON_UNESCAPED_UNICODE),
            'contact_phone'         => $phone !== '' ? $phone : null,
            'photo'                 => $saved['photo'],
            'status'                => 'pending',
            'created_at'            => $now,
        ];

        $dir = ROOTPATH . 'data/faculty-submissions';
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents(
            $dir . '/' . $key . '.json',
            json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n"
        );

        try {
            $submissions = model(FacultySubmissionModel::class);
            $submissions->ensureTable();
            $submissions->insert($record);
        } catch (\Throwable $e) {
            log_message('error', 'Faculty submission save failed: ' . $e->getMessage());

            return $this->fail('Could not save to database. Please try again later.');
        }

        return redirect()->to(site_url('faculty-update?thanks=1'));
    }

    private function fail(string $message)
    {
        return redirect()->to(site_url('faculty-update?error=' . rawurlencode($message)));
    }

    private function rateLimited(string $ip, int $seconds = 45): bool
    {
        $dir = ROOTPATH . 'data/faculty-submissions/.rate';
        if (! is_dir($dir) && ! @mkdir($dir, 0755, true) && ! is_dir($dir)) {
            return false;
        }
        $file = $dir . '/' . hash('sha256', $ip) . '.txt';
        $now  = time();
        if (is_file($file)) {
            $last = (int) trim((string) @file_get_contents($file));
            if ($last > 0 && ($now - $last) < $seconds) {
                return true;
            }
        }
        @file_put_contents($file, (string) $now);

        return false;
    }

    /** @return list<string> */
    private function lines(string $raw, int $max): array
    {
        $parts = preg_split('/[\r\n]+/', $raw) ?: [];
        $out   = [];
        $seen  = [];
        foreach ($parts as $part) {
            $text = trim((string) $part, " \t•●-–—");
            $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');
            if ($text === '') {
                continue;
            }
            if (strlen($text) > 220) {
                $text = substr($text, 0, 220);
            }
            $key = strtolower($text);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[]      = $text;
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    private function cleanUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return '';
        }
        if (strlen($url) > 400) {
            $url = substr($url, 0, 400);
        }

        return $url;
    }

    /**
     * @return array{key:string,photo:?string,publications_file:?string}|array{error:string}
     */
    private function storeFiles(UploadedFile $publicationsFile, mixed $photoFile): array
    {
        $key     = date('Ymd-His') . '-' . bin2hex(random_bytes(4));
        $base    = ROOTPATH . 'data/faculty-submissions';
        $photoRel = null;
        $docRel   = null;

        if ($photoFile instanceof UploadedFile && $photoFile->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $photoFile->isValid()) {
                return ['error' => 'Photo upload failed. Try a smaller JPG or PNG.'];
            }
            if ($photoFile->getSize() > (int) (2.5 * 1024 * 1024)) {
                return ['error' => 'Photo must be under 2.5 MB.'];
            }
            $map = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            $mime = (string) $photoFile->getMimeType();
            if (! isset($map[$mime])) {
                return ['error' => 'Photo must be JPG, PNG, or WEBP.'];
            }
            $dir = $base . '/photos';
            if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
                return ['error' => 'Could not create photo folder.'];
            }
            $name = $key . '.' . $map[$mime];
            $photoFile->move($dir, $name, true);
            @chmod($dir . '/' . $name, 0644);
            $photoRel = 'photos/' . $name;
        }

        if (! $publicationsFile->isValid()) {
            return ['error' => 'Publications file upload failed. Try PDF or Word again.'];
        }
        if ($publicationsFile->getSize() > 8 * 1024 * 1024) {
            return ['error' => 'Publications file must be under 8 MB.'];
        }
        $docMap = [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'text/plain' => 'txt',
            'text/rtf' => 'rtf',
            'application/rtf' => 'rtf',
        ];
        $mime = (string) $publicationsFile->getMimeType();
        $ext  = $docMap[$mime] ?? '';
        $extFromName = strtolower((string) $publicationsFile->getClientExtension());
        if ($ext === '' && in_array($extFromName, ['pdf', 'doc', 'docx', 'txt', 'rtf'], true)) {
            $ext = $extFromName;
        }
        if ($ext === '') {
            return ['error' => 'Publications file must be PDF, Word, or TXT.'];
        }
        $dir = $base . '/docs';
        if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            return ['error' => 'Could not create documents folder.'];
        }
        $name = $key . '-publications.' . $ext;
        $publicationsFile->move($dir, $name, true);
        @chmod($dir . '/' . $name, 0644);
        $docRel = 'docs/' . $name;

        return [
            'key'               => $key,
            'photo'             => $photoRel,
            'publications_file' => $docRel,
        ];
    }
}
