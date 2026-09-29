<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Models\CmsUserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session('cms_user_id')) {
            return redirect()->to(site_url('acp'));
        }

        $users = model(CmsUserModel::class);
        $users->ensureBootstrapAdmin();

        return view('acp/auth/login', [
            'title' => 'ACP Sign in',
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function attempt()
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        $users = model(CmsUserModel::class);
        $users->ensureBootstrapAdmin();
        $user = $users->findByEmail($email);

        if (!$user || empty($user['is_active']) || !password_verify($password, (string) $user['password_hash'])) {
            return redirect()->to(site_url('acp/login'))->with('error', 'Invalid email or password.');
        }

        $users->update((int) $user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        session()->set([
            'cms_user_id'    => (int) $user['id'],
            'cms_user_name'  => (string) $user['name'],
            'cms_user_email' => (string) $user['email'],
            'cms_user_role'  => (string) $user['role'],
        ]);

        return redirect()->to(site_url('acp'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('acp/login'));
    }
}
