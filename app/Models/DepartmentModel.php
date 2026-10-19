<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'slug', 'name', 'icon', 'group_name', 'hod', 'intro_json', 'faculty_seed_json',
        'hidden', 'oric_id', 'content_updated_at', 'created_at', 'updated_at',
    ];
    protected $useTimestamps = true;

    /** @return list<array<string,mixed>> */
    public function visibleDepartments(): array
    {
        // Keep seed/legacy insertion order within each group (not alphabetical by name).
        $rows = $this->where('hidden', 0)->orderBy('group_name')->orderBy('id')->findAll();
        return array_map([$this, 'hydrate'], $rows);
    }

    /** @return array<string,mixed>|null */
    public function findBySlug(string $slug, bool $allowHidden = false): ?array
    {
        $builder = $this->where('slug', $slug);
        if (! $allowHidden) {
            $builder->where('hidden', 0);
        }
        $row = $builder->first();
        return $row ? $this->hydrate($row) : null;
    }

    /** @param array<string,mixed> $row @return array<string,mixed> */
    protected function hydrate(array $row): array
    {
        $row['intro'] = json_decode((string) ($row['intro_json'] ?? '[]'), true) ?: [];
        $row['faculty'] = json_decode((string) ($row['faculty_seed_json'] ?? '[]'), true) ?: [];
        $row['group'] = $row['group_name'] ?? 'Other';
        $db = db_connect();
        $actBuilder = $db->table('department_activities')
            ->where('department_id', $row['id'])
            ->orderBy('sort_order');
        if ($db->fieldExists('enabled', 'department_activities')) {
            $actBuilder->where('enabled', 1);
        }
        $acts = $actBuilder->get()->getResultArray();
        $row['activities'] = array_map(static function ($a) {
            $photos = [];
            $rawPhotos = $a['photos_json'] ?? null;
            if (is_array($rawPhotos)) {
                $decoded = $rawPhotos;
            } elseif (is_string($rawPhotos) && $rawPhotos !== '') {
                $decoded = json_decode($rawPhotos, true);
            } else {
                $decoded = null;
            }
            if (is_array($decoded)) {
                foreach ($decoded as $p) {
                    $p = str_replace('\\', '/', trim((string) $p));
                    if ($p !== '' && ! str_contains($p, '..')) {
                        $photos[] = $p;
                    }
                }
            }

            return [
                'title'  => $a['title'],
                'date'   => str_replace("\u{00E2}\u{20AC}\u{201C}", "\u{2013}", (string) $a['date_label']),
                'text'   => $a['body'],
                'photos' => $photos,
            ];
        }, $acts);
        return $row;
    }
}
