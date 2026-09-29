<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Models\AnalyticsSettingsModel;

class Analytics extends BaseController
{
    public function index()
    {
        return view('acp/analytics/index', [
            'title'      => 'Analytics · Google Analytics',
            'subtitle'   => 'Website analytics for the Website Cell weekly review — sessions, users, top pages, and GA property linkage.',
            'nav'        => 'analytics',
            'row'        => model(AnalyticsSettingsModel::class)->getSettings(),
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save()
    {
        $model = model(AnalyticsSettingsModel::class);
        $pagesRaw = (string) $this->request->getPost('top_pages');
        $pages = [];
        foreach (preg_split('/\r\n|\n|\r/', $pagesRaw) ?: [] as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            // path|views
            $parts = array_map('trim', explode('|', $line));
            $pages[] = [
                'path'  => $parts[0] !== '' ? $parts[0] : '/',
                'views' => isset($parts[1]) ? (int) $parts[1] : 0,
            ];
        }

        $payload = [
            'ga_measurement_id' => trim((string) $this->request->getPost('ga_measurement_id')) ?: null,
            'ga_property_id'    => trim((string) $this->request->getPost('ga_property_id')) ?: null,
            'sessions_mtd'      => (int) $this->request->getPost('sessions_mtd'),
            'users_mtd'         => (int) $this->request->getPost('users_mtd'),
            'pageviews_mtd'     => (int) $this->request->getPost('pageviews_mtd'),
            'bounce_rate'       => (float) $this->request->getPost('bounce_rate'),
            'avg_session_sec'   => (int) $this->request->getPost('avg_session_sec'),
            'top_pages_json'    => json_encode($pages, JSON_UNESCAPED_UNICODE),
            'notes'             => trim((string) $this->request->getPost('notes')) ?: null,
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        if ($model->find(1)) {
            $model->update(1, $payload);
        } else {
            $payload['id'] = 1;
            $model->insert($payload);
        }

        return redirect()->to(site_url('acp/analytics'))->with('ok', 'Analytics settings saved.');
    }
}
