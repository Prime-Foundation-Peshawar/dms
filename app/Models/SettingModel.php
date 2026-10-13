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

    /** @return array{intro:string,heading:string,rows:list<array<string,string>>,notices:list<string>,last_date:string,apply_url:string,apply_label:string} */
    public function vacantSeats(): array
    {
        $raw  = $this->getValue('vacant_seats', '{}');
        $data = json_decode((string) $raw, true);
        if (! is_array($data)) {
            $data = [];
        }

        return [
            'intro'       => (string) ($data['intro'] ?? ''),
            'heading'     => (string) ($data['heading'] ?? ''),
            'rows'        => is_array($data['rows'] ?? null) ? array_values($data['rows']) : [],
            'notices'     => is_array($data['notices'] ?? null) ? array_values($data['notices']) : [],
            'last_date'   => (string) ($data['last_date'] ?? ''),
            'apply_url'   => (string) ($data['apply_url'] ?? ''),
            'apply_label' => (string) ($data['apply_label'] ?? 'Apply Online for Migration'),
        ];
    }

    public function setJson(string $key, mixed $value): void
    {
        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($encoded === false) {
            $encoded = '[]';
        }
        $existing = $this->where('key', $key)->first();
        $payload  = [
            'value'      => $encoded,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($existing) {
            $this->update($existing['id'], $payload);
        } else {
            $this->insert(array_merge(['key' => $key], $payload));
        }
    }
}
