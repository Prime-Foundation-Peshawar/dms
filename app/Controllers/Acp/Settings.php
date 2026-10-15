<?php

namespace App\Controllers\Acp;

use App\Models\SettingModel;

class Settings extends BaseAcpController
{
    public function index()
    {
        $rows = model(SettingModel::class)->orderBy('key', 'ASC')->findAll();

        return $this->acpView('settings/index', [
            'title' => 'Settings',
            'rows'  => $rows,
        ]);
    }

    public function save()
    {
        $model = model(SettingModel::class);
        $keys  = $this->request->getPost('key') ?? [];
        $vals  = $this->request->getPost('value') ?? [];
        if (! is_array($keys) || ! is_array($vals)) {
            return redirect()->back()->with('error', 'Invalid settings payload.');
        }

        foreach ($keys as $i => $key) {
            $key = trim((string) $key);
            if ($key === '' || in_array($key, ['newsletters', 'vacant_seats'], true)) {
                continue;
            }
            $value = (string) ($vals[$i] ?? '');
            $existing = $model->where('key', $key)->first();
            if ($existing) {
                $model->update($existing['id'], [
                    'value'      => $value,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $model->insert([
                    'key'        => $key,
                    'value'      => $value,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->to(site_url('acp/settings'))->with('success', 'Settings saved.');
    }
}
