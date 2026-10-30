<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Files\UploadedFile;

abstract class BaseAcpController extends BaseController
{
    protected function acpView(string $view, array $data = [])
    {
        $data['acp_user_name'] = session()->get('acp_user_name') ?? 'Admin';
        $data['acp_user_role'] = session()->get('acp_user_role') ?? 'admin';

        return view('acp/' . $view, $data);
    }

    /**
     * Homepage button fields from the news / event forms.
     * Accepts http(s) URLs or site paths (e.g. "admissions"); anything else is dropped.
     *
     * @return array{link_url:?string,link_label:?string}
     */
    protected function postedLink(): array
    {
        $url   = trim((string) $this->request->getPost('link_url'));
        $label = trim((string) $this->request->getPost('link_label'));
        $isWeb = (bool) preg_match('#^https?://[^\s]+$#i', $url);
        $isPath = (bool) preg_match('#^/?[a-z0-9][a-z0-9\-/_.?=&%]*$#i', $url) && ! str_contains($url, ':');
        if (! $isWeb && ! $isPath) {
            $url = '';
        }

        return [
            'link_url'   => $url !== '' ? mb_substr($url, 0, 500) : null,
            'link_label' => $url !== '' && $label !== '' ? mb_substr($label, 0, 60) : null,
        ];
    }

    protected function makeSlug(string $title): string
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : 'item-' . time();
    }

    /**
     * @return list<string>
     */
    protected function storeUploads(string $field, string $subdir, array $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf']): array
    {
        $files = $this->request->getFileMultiple($field);
        if (! $files) {
            $single = $this->request->getFile($field);
            $files  = $single ? [$single] : [];
        }

        $saved = [];
        $dest  = FCPATH . 'uploads/' . trim($subdir, '/') . '/' . date('Y/m') . '/';
        if (! is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid() || $file->hasMoved()) {
                continue;
            }
            $ext = strtolower($file->getClientExtension());
            if (! in_array($ext, $allowed, true)) {
                continue;
            }
            if ($file->getSize() > 8 * 1024 * 1024) {
                continue;
            }
            $name = $file->getRandomName();
            $file->move($dest, $name);
            $saved[] = 'uploads/' . trim($subdir, '/') . '/' . date('Y/m') . '/' . $name;
        }

        return $saved;
    }

    protected function storeUpload(string $field, string $subdir, array $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf']): ?string
    {
        $paths = $this->storeUploads($field, $subdir, $allowed);

        return $paths[0] ?? null;
    }

    /** @return array{0:?string,1:?string} html, text */
    protected function readDualBody(string $htmlField = 'body_html', string $textField = 'body_text'): array
    {
        $html = trim((string) $this->request->getPost($htmlField));
        $text = trim((string) $this->request->getPost($textField));
        if ($html === '' && $text !== '') {
            $parts = preg_split("/\n{2,}/", $text) ?: [];
            $html  = implode("\n", array_map(
                static fn ($p) => '<p>' . nl2br(htmlspecialchars(trim($p), ENT_QUOTES | ENT_HTML5, 'UTF-8')) . '</p>',
                array_filter(array_map('trim', $parts))
            ));
        }
        if ($text === '' && $html !== '') {
            $text = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return [$html !== '' ? $html : null, $text !== '' ? $text : null];
    }
}
