<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Libraries\DepartmentService;
use App\Models\DepartmentModel;

class Departments extends BaseController
{
    public function index()
    {
        $rows = model(DepartmentModel::class)->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll();

        return view('acp/departments/index', [
            'title'      => 'Departments',
            'nav'        => 'departments',
            'rows'       => $rows,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function edit(int $id)
    {
        $svc = new DepartmentService();
        $model = model(DepartmentModel::class);
        $row = $model->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Department not found.');
        }
        $pack = $svc->toLegacyPack($row);

        return view('acp/departments/edit', [
            'title'      => 'Edit ' . ($pack['name'] ?? 'department'),
            'nav'        => 'departments',
            'row'        => $row,
            'pack'       => $pack,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function save(int $id)
    {
        $model = model(DepartmentModel::class);
        $row = $model->find($id);
        if (!is_array($row)) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Department not found.');
        }

        $introRaw = (string) $this->request->getPost('intro');
        $paragraphs = preg_split('/\r\n\r\n|\n\n/', $introRaw) ?: [];
        $paragraphs = array_values(array_filter(array_map('trim', $paragraphs)));

        $titles = $this->request->getPost('act_title') ?? [];
        $dates = $this->request->getPost('act_date') ?? [];
        $texts = $this->request->getPost('act_text') ?? [];
        if (!is_array($titles)) {
            $titles = [];
        }
        if (!is_array($dates)) {
            $dates = [];
        }
        if (!is_array($texts)) {
            $texts = [];
        }

        $acts = [];
        foreach ($titles as $i => $title) {
            $acts[] = [
                'title' => (string) $title,
                'date'  => (string) ($dates[$i] ?? ''),
                'text'  => (string) ($texts[$i] ?? ''),
            ];
        }

        $svc = new DepartmentService();
        $svc->saveIntro(
            $id,
            $paragraphs,
            trim((string) $this->request->getPost('hod_name')),
            trim((string) $this->request->getPost('updated_on'))
        );
        $model->update($id, [
            'name'       => trim((string) $this->request->getPost('name')) ?: $row['name'],
            'icon'       => trim((string) $this->request->getPost('icon')) ?: $row['icon'],
            'dept_group' => trim((string) $this->request->getPost('dept_group')) ?: $row['dept_group'],
        ]);
        $svc->replaceActivities($id, $acts);

        return redirect()->to(site_url('acp/departments/' . $id))->with('ok', 'Department saved.');
    }
}
