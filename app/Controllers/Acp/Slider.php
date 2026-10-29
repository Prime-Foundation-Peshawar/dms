<?php

namespace App\Controllers\Acp;

use App\Models\SettingModel;

class Slider extends BaseAcpController
{
    private const KEY = 'home_slider';

    public function index()
    {
        return $this->acpView('slider/index', [
            'title'  => 'Home slider',
            'slides' => $this->slides(),
        ]);
    }

    public function save()
    {
        $slides = [];
        $titles = $this->request->getPost('title') ?? [];
        $texts = $this->request->getPost('text') ?? [];
        $links = $this->request->getPost('link_url') ?? [];
        $labels = $this->request->getPost('link_label') ?? [];
        $mediaTypes = $this->request->getPost('media_type') ?? [];
        $videoSources = $this->request->getPost('video_source') ?? [];
        $videoPaths = $this->request->getPost('video_path') ?? [];
        $paths = $this->request->getPost('image_path') ?? [];
        $enabled = $this->request->getPost('enabled') ?? [];
        $motion = $this->request->getPost('motion_enabled') ?? [];
        $uploads = $this->request->getFileMultiple('image') ?? [];
        $videoUploads = $this->request->getFileMultiple('video') ?? [];

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $mediaType = ($mediaTypes[$i] ?? 'image') === 'video' ? 'video' : 'image';
            $videoSource = ($videoSources[$i] ?? 'current') === 'new' ? 'new' : 'current';
            $imagePath = ltrim(trim((string) ($paths[$i] ?? '')), '/');
            $videoPath = ltrim(trim((string) ($videoPaths[$i] ?? '')), '/');

            if ($mediaType === 'image') {
                $upload = $uploads[$i] ?? null;
                if ($upload && $upload->isValid() && ! $upload->hasMoved()) {
                    $imagePath = $this->storeUploadAt($upload, ['jpg', 'jpeg', 'png', 'webp'], 8 * 1024 * 1024);
                    if ($imagePath === null) {
                        return redirect()->back()->withInput()->with('error', 'Images must be JPEG, PNG or WebP and no larger than 8 MB.');
                    }
                }
                $hasMedia = $imagePath !== '';
            } elseif ($videoSource === 'current') {
                $hasMedia = true;
            } else {
                $upload = $videoUploads[$i] ?? null;
                if ($upload && $upload->isValid() && ! $upload->hasMoved()) {
                    $videoPath = $this->storeUploadAt($upload, ['mp4', 'webm'], 100 * 1024 * 1024);
                    if ($videoPath === null) {
                        return redirect()->back()->withInput()->with('error', 'Videos must be MP4 or WebM and no larger than 100 MB.');
                    }
                }
                $hasMedia = $videoPath !== '';
            }

            if ($title === '' && ! $hasMedia) {
                continue;
            }
            if (! $hasMedia) {
                $message = $mediaType === 'image'
                    ? 'Every image slide needs an image path or upload.'
                    : 'Every custom video slide needs a video path or upload.';
                return redirect()->back()->withInput()->with('error', $message);
            }
            $slides[] = [
                'media_type' => $mediaType,
                'video_source' => $videoSource,
                'video_path' => $videoPath,
                'title'      => $title,
                'text'       => trim((string) ($texts[$i] ?? '')),
                'link_url'   => trim((string) ($links[$i] ?? '')),
                'link_label' => trim((string) ($labels[$i] ?? '')),
                'image_path' => $imagePath,
                'enabled'    => isset($enabled[$i]),
                'motion_enabled' => isset($motion[$i]),
            ];
        }

        model(SettingModel::class)->setJson(self::KEY, $slides);
        return redirect()->to(site_url('acp/slider'))->with('success', 'Home slider saved.');
    }

    private function slides(): array
    {
        $raw = model(SettingModel::class)->getValue(self::KEY, '[]');
        $slides = json_decode((string) $raw, true);
        return is_array($slides) ? array_values($slides) : [];
    }

    private function storeUploadAt($file, array $allowed, int $maxBytes): ?string
    {
        $ext = strtolower($file->getClientExtension());
        if (! in_array($ext, $allowed, true) || $file->getSize() > $maxBytes) {
            return null;
        }
        $subdir = 'slider/' . date('Y/m');
        $dest = FCPATH . 'uploads/' . $subdir;
        if (! is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        $name = $file->getRandomName();
        $file->move($dest, $name);
        return 'uploads/' . $subdir . '/' . $name;
    }
}
