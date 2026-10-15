<?php

namespace App\Controllers\Acp;

use App\Models\UserModel;

class Users extends BaseAcpController
{
    public function index()
    {
        $rows = model(UserModel::class)->orderBy('email', 'ASC')->findAll();

        return $this->acpView('users/index', [
            'title' => 'Users',
            'rows'  => $rows,
        ]);
    }

    public function create()
    {
        return $this->acpView('users/form', [
            'title' => 'Create user',
            'row'   => null,
        ]);
    }

    public function store()
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $pass  = (string) $this->request->getPost('password');
        if ($email === '' || strlen($pass) < 6) {
            return redirect()->back()->withInput()->with('error', 'Email and password (min 6) required.');
        }

        model(UserModel::class)->insert([
            'email'         => $email,
            'password_hash' => password_hash($pass, PASSWORD_DEFAULT),
            'name'          => trim((string) $this->request->getPost('name')),
            'role'          => trim((string) $this->request->getPost('role')) ?: 'admin',
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to(site_url('acp/users'))->with('success', 'User created.');
    }

    public function edit(int $id)
    {
        $row = model(UserModel::class)->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/users'))->with('error', 'User not found.');
        }

        return $this->acpView('users/form', [
            'title' => 'Edit user',
            'row'   => $row,
        ]);
    }

    public function update(int $id)
    {
        $model = model(UserModel::class);
        $row   = $model->find($id);
        if (! $row) {
            return redirect()->to(site_url('acp/users'))->with('error', 'User not found.');
        }

        $data = [
            'email'     => strtolower(trim((string) $this->request->getPost('email'))),
            'name'      => trim((string) $this->request->getPost('name')),
            'role'      => trim((string) $this->request->getPost('role')) ?: 'admin',
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
        $pass = (string) $this->request->getPost('password');
        if ($pass !== '') {
            if (strlen($pass) < 6) {
                return redirect()->back()->with('error', 'Password must be at least 6 characters.');
            }
            $data['password_hash'] = password_hash($pass, PASSWORD_DEFAULT);
        }
        $model->update($id, $data);

        return redirect()->to(site_url('acp/users'))->with('success', 'User updated.');
    }

    public function delete(int $id)
    {
        if ((int) session()->get('acp_user_id') === $id) {
            return redirect()->to(site_url('acp/users'))->with('error', 'You cannot delete your own account.');
        }
        model(UserModel::class)->delete($id);

        return redirect()->to(site_url('acp/users'))->with('success', 'User deleted.');
    }
}
