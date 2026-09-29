<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsSettingsModel extends Model
{
    protected $table         = 'analytics_settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'ga_measurement_id', 'ga_property_id',
        'sessions_mtd', 'users_mtd', 'pageviews_mtd', 'bounce_rate', 'avg_session_sec',
        'top_pages_json', 'notes', 'updated_at',
    ];

    public function getSettings(): array
    {
        $row = $this->find(1);
        if (!is_array($row)) {
            return [
                'id' => 1,
                'ga_measurement_id' => '',
                'ga_property_id' => '',
                'sessions_mtd' => 0,
                'users_mtd' => 0,
                'pageviews_mtd' => 0,
                'bounce_rate' => 0,
                'avg_session_sec' => 0,
                'top_pages_json' => [],
                'notes' => '',
            ];
        }
        $pages = $row['top_pages_json'] ?? [];
        if (is_string($pages)) {
            $decoded = json_decode($pages, true);
            $pages = is_array($decoded) ? $decoded : [];
        }
        $row['top_pages_json'] = is_array($pages) ? $pages : [];

        return $row;
    }
}
