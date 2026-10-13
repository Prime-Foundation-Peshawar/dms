<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'email', 'password_hash', 'name', 'role', 'is_active', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** @return array<string,mixed>|null */
    public function findActiveByEmail(string $email): ?array
    {
        $row = $this->where('email', $email)->where('is_active', 1)->first();

        return $row ?: null;
    }
}
