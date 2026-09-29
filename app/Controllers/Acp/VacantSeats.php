<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Models\VacantSeatModel;
use App\Models\VacantSeatsSettingsModel;

class VacantSeats extends BaseController
{
    public function index()
    {
        $rows = model(VacantSeatModel::class)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('acp/vacant-seats/index', [
            'title'      => 'Vacant seats',
            'nav'        => 'vacant-seats',
            'rows'       => $rows,
            'settings'   => model(VacantSeatsSettingsModel::class)->getSettings(),
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function create()
    {
        return view('acp/vacant-seats/edit', [
            'title'      => 'New vacant seat row',
            'nav'        => 'vacant-seats',
            'row'        => $this->blankSeat(),
            'flashOk'    => null,
            'flashError' => null,
        ]);
    }

    public function edit(int $id)
    {
        $row = model(VacantSeatModel::class)->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/vacant-seats'))->with('error', 'Row not found.');
        }

        return view('acp/vacant-seats/edit', [
            'title'      => 'Edit vacant seat',
            'nav'        => 'vacant-seats',
            'row'        => $row,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save(?int $id = null)
    {
        $model = model(VacantSeatModel::class);
        $existing = $id ? $model->find($id) : null;
        if ($id && !is_array($existing)) {
            return redirect()->to(site_url('acp/vacant-seats'))->with('error', 'Row not found.');
        }

        $payload = [
            'programme'     => trim((string) $this->request->getPost('programme')) ?: 'Untitled',
            'session_label' => trim((string) $this->request->getPost('session_label')) ?: null,
            'seats'         => (int) $this->request->getPost('seats'),
            'sort_order'    => (int) $this->request->getPost('sort_order'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($id) {
            $model->update($id, $payload);
        } else {
            $id = (int) $model->insert($payload);
        }

        return redirect()->to(site_url('acp/vacant-seats/' . $id))->with('ok', 'Vacant seat row saved.');
    }

    public function settings()
    {
        return view('acp/vacant-seats/settings', [
            'title'      => 'Vacant seats settings',
            'nav'        => 'vacant-seats',
            'row'        => model(VacantSeatsSettingsModel::class)->getSettings(),
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function saveSettings()
    {
        $model = model(VacantSeatsSettingsModel::class);
        $instructionsRaw = (string) $this->request->getPost('instructions');
        $lines = preg_split('/\r\n|\n|\r/', $instructionsRaw) ?: [];
        $instructions = array_values(array_filter(array_map('trim', $lines)));

        $payload = [
            'alert_html'           => (string) $this->request->getPost('alert_html'),
            'intro_html'           => (string) $this->request->getPost('intro_html'),
            'instructions_json'    => json_encode($instructions, JSON_UNESCAPED_UNICODE),
            'apply_deadline'       => trim((string) $this->request->getPost('apply_deadline')) ?: null,
            'apply_deadline_note'  => trim((string) $this->request->getPost('apply_deadline_note')) ?: null,
            'merit_date'           => trim((string) $this->request->getPost('merit_date')) ?: null,
            'merit_date_note'      => trim((string) $this->request->getPost('merit_date_note')) ?: null,
            'apply_url'            => trim((string) $this->request->getPost('apply_url')) ?: null,
            'updated_at'           => date('Y-m-d H:i:s'),
        ];

        if ($model->find(1)) {
            $model->update(1, $payload);
        } else {
            $payload['id'] = 1;
            $model->insert($payload);
        }

        return redirect()->to(site_url('acp/vacant-seats/settings'))->with('ok', 'Settings saved.');
    }

    /** @return array<string,mixed> */
    protected function blankSeat(): array
    {
        return [
            'id' => 0,
            'programme' => '',
            'session_label' => '',
            'seats' => 0,
            'sort_order' => 10,
            'is_active' => 1,
        ];
    }
}
