<?php

namespace App\Controllers\Acp;

use App\Models\DepartmentActivityModel;
use App\Models\DepartmentDataLogModel;
use App\Models\DepartmentModel;
use App\Models\FacultyProfileModel;
use App\Services\FacultyDirectoryService;

class Departments extends BaseAcpController
{
    public function index()
    {
        $rows = model(DepartmentModel::class)->orderBy('group_name')->orderBy('name')->findAll();

        return $this->acpView('departments/index', [
            'title' => 'Departments',
            'rows'  => $rows,
        ]);
    }

    public function edit(int $id)
    {
        $row = model(DepartmentModel::class)->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Department not found.');
        }

        $intro = json_decode((string) ($row['intro_json'] ?? '[]'), true);
        if (! is_array($intro)) {
            $intro = [];
        }

        $directory = new FacultyDirectoryService();
        $faculty = $directory->forDepartment(
            (string) $row['slug'],
            (string) $row['name'],
            [],
            (string) ($row['hod'] ?? '')
        );

        // Resolve profile ids by empId only (never by name).
        $profiles = model(FacultyProfileModel::class);
        foreach ($faculty as &$f) {
            if (! empty($f['profile_id'])) {
                continue;
            }
            $empId = trim((string) ($f['empId'] ?? ''));
            if ($empId === '') {
                continue;
            }
            $rec = $profiles->findByEmpId($empId);
            $f['profile_id'] = $rec['id'] ?? null;
        }
        unset($f);

        return $this->acpView('departments/form', [
            'title'       => 'Edit department',
            'row'         => $row,
            'introLines'  => array_values(array_map('strval', $intro)),
            'hrmsFaculty' => $faculty,
            'activities'  => model(DepartmentActivityModel::class)->forDepartment($id),
        ]);
    }

    public function update(int $id)
    {
        $model = model(DepartmentModel::class);
        $row   = $model->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Department not found.');
        }

        $introText = (string) $this->request->getPost('intro_text');
        $intro     = preg_split("/\r\n|\r|\n/", $introText) ?: [];
        $intro     = array_values(array_filter(array_map('trim', $intro), static fn ($l) => $l !== ''));

        $data = [
            'name'               => trim((string) $this->request->getPost('name')),
            'icon'               => trim((string) $this->request->getPost('icon')),
            'group_name'         => trim((string) $this->request->getPost('group_name')) ?: 'Other',
            'hod'                => trim((string) $this->request->getPost('hod')),
            'intro_json'         => json_encode($intro, JSON_UNESCAPED_UNICODE),
            // faculty_seed_json kept in DB for legacy fallback; list is HRMS-driven in ACP
            'hidden'             => $this->request->getPost('hidden') ? 1 : 0,
            'oric_id'            => $this->request->getPost('oric_id') !== '' ? (int) $this->request->getPost('oric_id') : null,
            'content_updated_at' => $this->request->getPost('content_updated_at') ?: null,
        ];
        $model->update($id, $data);

        // Count as department data received only when the page content actually changed.
        foreach (['name', 'group_name', 'hod', 'intro_json'] as $field) {
            if ((string) ($row[$field] ?? '') !== (string) $data[$field]) {
                model(DepartmentDataLogModel::class)->record($id, DepartmentDataLogModel::TYPE_DEPARTMENT_INFO, (string) $row['slug']);
                break;
            }
        }

        return redirect()->to(site_url('acp/departments/edit/' . $id))->with('success', 'Department updated.');
    }

    public function storeActivity(int $departmentId)
    {
        if (! model(DepartmentModel::class)->find($departmentId)) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Department not found.');
        }

        $activityModel = model(DepartmentActivityModel::class);
        $activityModel->ensureEnabledColumn();

        $max = $activityModel
            ->where('department_id', $departmentId)
            ->orderBy('sort_order', 'DESC')
            ->first();
        $next = (int) ($max['sort_order'] ?? -1) + 1;

        $row = [
            'department_id' => $departmentId,
            'title'         => trim((string) $this->request->getPost('title')),
            'date_label'    => trim((string) $this->request->getPost('date_label')),
            'body'          => trim((string) $this->request->getPost('body')),
            'sort_order'    => $next,
        ];
        if (db_connect()->fieldExists('enabled', 'department_activities')) {
            $row['enabled'] = 1;
        }
        $activityModel->insert($row);

        return redirect()->to(site_url('acp/departments/edit/' . $departmentId))
            ->with('success', 'Activity added.');
    }

    public function toggleActivity(int $id)
    {
        $activityModel = model(DepartmentActivityModel::class);
        $activityModel->ensureEnabledColumn();
        $act = $activityModel->find($id);
        if (! $act) {
            return redirect()->to(site_url('acp/departments'))->with('error', 'Activity not found.');
        }
        if (! db_connect()->fieldExists('enabled', 'department_activities')) {
            return redirect()->to(site_url('acp/departments/edit/' . $act['department_id']))
                ->with('error', 'Enable/disable is not available yet.');
        }

        $enabled = $this->request->getPost('enabled') ? 1 : 0;
        $activityModel->update($id, ['enabled' => $enabled]);

        return redirect()->to(site_url('acp/departments/edit/' . $act['department_id']))
            ->with('success', $enabled ? 'Activity enabled on the website.' : 'Activity disabled on the website.');
    }

    public function deleteActivity(int $id)
    {
        $act = model(DepartmentActivityModel::class)->find($id);
        if (! $act) {
            return redirect()->to(site_url('acp/departments'));
        }
        model(DepartmentActivityModel::class)->delete($id);

        return redirect()->to(site_url('acp/departments/edit/' . $act['department_id']))
            ->with('success', 'Activity removed.');
    }

    public function reorderActivities()
    {
        $ids = $this->request->getPost('ids') ?? [];
        if (! is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false]);
        }
        $model = model(DepartmentActivityModel::class);
        $order = 0;
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $model->update($id, ['sort_order' => $order++]);
            }
        }

        return $this->response->setJSON(['ok' => true]);
    }
}
