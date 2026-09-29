<?php

namespace App\Models;

use CodeIgniter\Model;

class CmsUserModel extends Model
{
    protected $table         = 'cms_users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'name',
        'email',
        'password_hash',
        'role',
        'is_active',
        'last_login_at',
    ];

    public function findByEmail(string $email): ?array
    {
        $row = $this->where('email', strtolower(trim($email)))->first();

        return is_array($row) ? $row : null;
    }

    public function ensureBootstrapAdmin(): void
    {
        if ($this->countAllResults(false) > 0) {
            return;
        }

        $email = strtolower(trim((string) env('CMS_ADMIN_EMAIL', 'admin@riphahpsh.edu.pk')));
        $password = (string) env('CMS_ADMIN_PASSWORD', '');
        if ($email === '' || $password === '' || $password === 'change_me') {
            // Still create a locked placeholder so the table is non-empty;
            // login will fail until CMS_ADMIN_PASSWORD is set properly.
            $password = bin2hex(random_bytes(16));
        }

        $this->insert([
            'name'          => 'DMS Admin',
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'admin',
            'is_active'     => 1,
        ]);
    }
}
