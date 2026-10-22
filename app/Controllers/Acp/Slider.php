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
        $paths = $this->request->getPost('image_path') ?? [];
        $enabled = $this->request->getPost('enabled') ?? [];
        $motion = $this->request->getPost('motion_enabled') ?? [];
        $uploads = $this->request->getFileMultiple('image') ?? [];

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $path = ltrim(trim((string) ($paths[$i] ?? '')), '/');
            $upload = $uploads[$i] ?? null;
            if ($upload && $upload->isValid() && ! $upload->hasMoved()) {
                $saved = $this->storeUploadAt($upload);
                if ($saved !== null) {
                    $path = $saved;
                }
            }
            if ($title === '' && $path === '') {
                continue;
            }
            if ($path === '') {
                return redirect()->back()->withInput()->with('error', 'Every slide needs an image.');
            }
            $slides[] = [
                'title'      => $title,
                'text'       => trim((string) ($texts[$i] ?? '')),
                'link_url'   => trim((string) ($links[$i] ?? '')),
                'link_label' => trim((string) ($labels[$i] ?? '')),
                'image_path' => $path,
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

    private function storeUploadAt($file): ?string
    {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower($file->getClientExtension());
        if (! in_array($ext, $allowed, true) || $file->getSize() > 8 * 1024 * 1024) {
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
