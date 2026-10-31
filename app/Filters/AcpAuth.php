<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AcpAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('acp_user_id')) {
            return redirect()->to(site_url('acp/login'));
        }

        // Re-read the account so role changes and deactivation apply without a fresh login.
        $user = model(UserModel::class)->find((int) $session->get('acp_user_id'));
        if (! $user || empty($user['is_active'])) {
            $session->remove(['acp_user_id', 'acp_user_name', 'acp_user_role']);

            return redirect()->to(site_url('acp/login'))->with('error', 'Your account is no longer active.');
        }
        $session->set('acp_user_role', (string) ($user['role'] ?: 'admin'));

        if (! acp_is_view_only()) {
            return null;
        }

        $path = trim($request instanceof IncomingRequest ? $request->getPath() : uri_string(), '/');
        if ($request->getMethod() === 'GET' && acp_can_open($path)) {
            return null;
        }
        if ($request instanceof IncomingRequest && $request->isAJAX()) {
            return service('response')->setStatusCode(403)->setJSON(['error' => 'View-only account.']);
        }

        return redirect()->to(site_url('acp'))->with('error', 'Your account can view the dashboard and reports only.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
