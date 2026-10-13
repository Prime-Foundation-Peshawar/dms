<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value', 'updated_at'];
    protected $useTimestamps = false;

    public function getValue(string $key, ?string $default = null): ?string
    {
        $row = $this->where('key', $key)->first();
        return $row ? (string) $row['value'] : $default;
    }

    /** @return list<array<string,mixed>> */
    public function newsletters(): array
    {
        $raw = $this->getValue('newsletters', '[]');
        $data = json_decode((string) $raw, true);
        return is_array($data) ? $data : [];
    }
}
