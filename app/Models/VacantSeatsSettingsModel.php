<?php

namespace App\Models;

use CodeIgniter\Model;

class VacantSeatsSettingsModel extends Model
{
    protected $table         = 'vacant_seats_settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'alert_html', 'intro_html', 'instructions_json',
        'apply_deadline', 'apply_deadline_note', 'merit_date', 'merit_date_note', 'apply_url', 'updated_at',
    ];

    public function getSettings(): array
    {
        $row = $this->find(1);
        if (!is_array($row)) {
            return [
                'id' => 1,
                'alert_html' => '',
                'intro_html' => '',
                'instructions_json' => [],
                'apply_deadline' => '',
                'apply_deadline_note' => '',
                'merit_date' => '',
                'merit_date_note' => '',
                'apply_url' => '',
            ];
        }
        $inst = $row['instructions_json'] ?? [];
        if (is_string($inst)) {
            $decoded = json_decode($inst, true);
            $inst = is_array($decoded) ? $decoded : [];
        }
        $row['instructions_json'] = is_array($inst) ? array_values($inst) : [];

        return $row;
    }
}
