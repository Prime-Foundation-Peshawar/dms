<?php

namespace App\Controllers\Acp;

use App\Models\UserModel;

class Auth extends BaseAcpController
{
    private const MAX_ATTEMPTS = 8;
    private const LOCK_SECONDS = 300;

    public function login()
    {
        if (session()->get('acp_user_id')) {
            return redirect()->to(site_url('acp'));
        }

        return view('acp/auth/login', [
            'error' => session()->getFlashdata('error'),
        ]);
    }

    public function attempt()
    {
        $session = session();
        $lockedUntil = (int) ($session->get('acp_lock_until') ?? 0);
        if ($lockedUntil > time()) {
            return redirect()->to(site_url('acp/login'))
                ->with('error', 'Too many failed attempts. Try again in a few minutes.');
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        $user = model(UserModel::class)->findActiveByEmail($email);
        if (! $user || ! password_verify($password, (string) $user['password_hash'])) {
            $attempts = (int) ($session->get('acp_login_attempts') ?? 0) + 1;
            $session->set('acp_login_attempts', $attempts);
            if ($attempts >= self::MAX_ATTEMPTS) {
                $session->set('acp_lock_until', time() + self::LOCK_SECONDS);
                $session->remove('acp_login_attempts');
            }

            return redirect()->to(site_url('acp/login'))->with('error', 'Invalid email or password.');
        }

        $session->remove(['acp_login_attempts', 'acp_lock_until']);
        $session->regenerate(true);
        $session->set([
            'acp_user_id'   => (int) $user['id'],
            'acp_user_name' => (string) ($user['name'] ?: $user['email']),
            'acp_user_role' => (string) ($user['role'] ?: 'admin'),
        ]);

        return redirect()->to(site_url('acp'));
    }

    public function logout()
    {
        session()->remove(['acp_user_id', 'acp_user_name', 'acp_user_role']);
        session()->regenerate(true);

        return redirect()->to(site_url('acp/login'));
    }
}
