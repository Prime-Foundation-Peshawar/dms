<?php

namespace App\Controllers\Acp;

use App\Models\SettingModel;

class VacantSeats extends BaseAcpController
{
    public function index()
    {
        return $this->acpView('vacant_seats/index', [
            'title' => 'Vacant seats',
            'data'  => model(SettingModel::class)->vacantSeats(),
        ]);
    }

    public function save()
    {
        $years    = $this->request->getPost('year') ?? [];
        $sessions = $this->request->getPost('session') ?? [];
        $seats    = $this->request->getPost('seats') ?? [];
        $notes    = $this->request->getPost('note') ?? [];
        $rows     = [];
        if (is_array($years)) {
            foreach ($years as $i => $year) {
                $year = trim((string) $year);
                if ($year === '') {
                    continue;
                }
                $rows[] = [
                    'year'    => $year,
                    'session' => trim((string) ($sessions[$i] ?? '')),
                    'seats'   => trim((string) ($seats[$i] ?? '0')),
                    'note'    => trim((string) ($notes[$i] ?? '')),
                ];
            }
        }

        $noticeRaw = (string) $this->request->getPost('notices');
        $notices   = array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $noticeRaw) ?: [])));

        $payload = [
            'intro'       => trim((string) $this->request->getPost('intro')),
            'heading'     => trim((string) $this->request->getPost('heading')),
            'rows'        => $rows,
            'notices'     => $notices,
            'last_date'   => trim((string) $this->request->getPost('last_date')),
            'apply_url'   => trim((string) $this->request->getPost('apply_url')),
            'apply_label' => trim((string) $this->request->getPost('apply_label')) ?: 'Apply Online for Migration',
        ];

        model(SettingModel::class)->setJson('vacant_seats', $payload);

        return redirect()->to(site_url('acp/vacant-seats'))->with('success', 'Vacant seats saved.');
    }
}
