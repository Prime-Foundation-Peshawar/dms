<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Libraries\CmsContent;
use App\Models\HomepageSlideModel;

class Slider extends BaseController
{
    public function index()
    {
        $rows = model(HomepageSlideModel::class)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('acp/slider/index', [
            'title'      => 'Homepage slider',
            'nav'        => 'slider',
            'rows'       => $rows,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function create()
    {
        return view('acp/slider/edit', [
            'title'      => 'New slide',
            'nav'        => 'slider',
            'row'        => $this->blank(),
            'flashOk'    => null,
            'flashError' => null,
        ]);
    }

    public function edit(int $id)
    {
        $row = model(HomepageSlideModel::class)->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/slider'))->with('error', 'Slide not found.');
        }

        return view('acp/slider/edit', [
            'title'      => 'Edit slide',
            'nav'        => 'slider',
            'row'        => $row,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save(?int $id = null)
    {
        $model = model(HomepageSlideModel::class);
        $existing = $id ? $model->find($id) : null;
        if ($id && !is_array($existing)) {
            return redirect()->to(site_url('acp/slider'))->with('error', 'Slide not found.');
        }

        $basename = 'slide-' . ($id ?: time());
        $mediaType = $this->request->getPost('media_type') === 'video' ? 'video' : 'image';

        $image = CmsContent::storeUpload($this->request->getFile('image_file'), 'images/slider', $basename);
        $poster = CmsContent::storeUpload($this->request->getFile('poster_file'), 'images/slider', $basename . '-poster');
        $video720 = CmsContent::storeUpload($this->request->getFile('video_720_file'), 'videos', $basename . '-720');
        $video480 = CmsContent::storeUpload($this->request->getFile('video_480_file'), 'videos', $basename . '-480');

        $payload = [
            'sort_order'       => (int) $this->request->getPost('sort_order'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
            'media_type'       => $mediaType,
            'image_path'       => $image ?: (trim((string) $this->request->getPost('image_path')) ?: ($existing['image_path'] ?? null)),
            'image_webp_path'  => trim((string) $this->request->getPost('image_webp_path')) ?: ($existing['image_webp_path'] ?? null),
            'poster_path'      => $poster ?: (trim((string) $this->request->getPost('poster_path')) ?: ($existing['poster_path'] ?? null)),
            'poster_webp_path' => trim((string) $this->request->getPost('poster_webp_path')) ?: ($existing['poster_webp_path'] ?? null),
            'video_720_path'   => $video720 ?: (trim((string) $this->request->getPost('video_720_path')) ?: ($existing['video_720_path'] ?? null)),
            'video_480_path'   => $video480 ?: (trim((string) $this->request->getPost('video_480_path')) ?: ($existing['video_480_path'] ?? null)),
            'brand'            => trim((string) $this->request->getPost('brand')) ?: null,
            'title_html'       => (string) $this->request->getPost('title_html'),
            'body'             => trim((string) $this->request->getPost('body')) ?: null,
            'body_sub'         => trim((string) $this->request->getPost('body_sub')) ?: null,
            'cta1_label'       => trim((string) $this->request->getPost('cta1_label')) ?: null,
            'cta1_url'         => trim((string) $this->request->getPost('cta1_url')) ?: null,
            'cta1_style'       => trim((string) $this->request->getPost('cta1_style')) ?: 'primary',
            'cta2_label'       => trim((string) $this->request->getPost('cta2_label')) ?: null,
            'cta2_url'         => trim((string) $this->request->getPost('cta2_url')) ?: null,
            'cta2_style'       => trim((string) $this->request->getPost('cta2_style')) ?: 'outline-white',
            'aria_label'       => trim((string) $this->request->getPost('aria_label')) ?: null,
        ];

        if ($id) {
            $model->update($id, $payload);
        } else {
            $id = (int) $model->insert($payload);
        }

        return redirect()->to(site_url('acp/slider/' . $id))->with('ok', 'Slide saved.');
    }

    /**
     * @return array<string,mixed>
     */
    protected function blank(): array
    {
        return [
            'id' => 0,
            'sort_order' => 10,
            'is_active' => 1,
            'media_type' => 'image',
            'image_path' => '',
            'image_webp_path' => '',
            'poster_path' => '',
            'poster_webp_path' => '',
            'video_720_path' => '',
            'video_480_path' => '',
            'brand' => 'Department of Medical Sciences',
            'title_html' => '',
            'body' => '',
            'body_sub' => '',
            'cta1_label' => 'Admissions Info',
            'cta1_url' => 'admissions',
            'cta1_style' => 'primary',
            'cta2_label' => 'About PMC',
            'cta2_url' => 'pmc',
            'cta2_style' => 'outline-white',
            'aria_label' => '',
        ];
    }
}
