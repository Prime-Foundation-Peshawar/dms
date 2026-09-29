<?php

namespace App\Libraries;

use App\Models\DepartmentActivityModel;
use App\Models\DepartmentModel;

/**
 * Load department content from MySQL in the legacy public shape.
 */
class DepartmentService
{
    public function __construct(
        protected DepartmentModel $departments = new DepartmentModel(),
        protected DepartmentActivityModel $activities = new DepartmentActivityModel(),
    ) {
    }

    /**
     * @return array<string,array<string,mixed>> slug => department pack
     */
    public function allKeyed(): array
    {
        $out = [];
        foreach ($this->departments->allActive() as $row) {
            $pack = $this->toLegacyPack($row);
            $out[$pack['slug']] = $pack;
        }

        return $out;
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->departments->findBySlug($slug);
        if (!$row) {
            return null;
        }

        return $this->toLegacyPack($row);
    }

    /**
     * @param array<string,mixed> $row
     * @return array<string,mixed>
     */
    public function toLegacyPack(array $row): array
    {
        $id = (int) ($row['id'] ?? 0);
        $acts = $id > 0 ? $this->activities->forDepartment($id) : [];
        $activities = [];
        foreach ($acts as $a) {
            $activities[] = [
                'id'    => (int) ($a['id'] ?? 0),
                'title' => (string) ($a['title'] ?? ''),
                'date'  => (string) ($a['activity_date'] ?? ''),
                'text'  => (string) ($a['body'] ?? ''),
            ];
        }

        return [
            'id'       => $id,
            'slug'     => (string) ($row['slug'] ?? ''),
            'name'     => (string) ($row['name'] ?? ''),
            'icon'     => (string) ($row['icon'] ?? 'bi-building'),
            'group'    => (string) ($row['dept_group'] ?? 'Other'),
            'hod'      => (string) ($row['hod_name'] ?? ''),
            'intro'    => dms_json_list($row['intro'] ?? null),
            'faculty'  => dms_json_list($row['faculty_fallback'] ?? null),
            'activities' => $activities,
            'updated'  => (string) ($row['updated_on'] ?? ''),
            'oric_id'  => isset($row['oric_id']) ? (int) $row['oric_id'] : null,
        ];
    }

    /**
     * @param list<string> $paragraphs
     */
    public function saveIntro(int $id, array $paragraphs, string $hodName = '', string $updatedOn = ''): void
    {
        $payload = [
            'intro'    => json_encode(array_values($paragraphs), JSON_UNESCAPED_UNICODE),
            'hod_name' => $hodName !== '' ? $hodName : null,
        ];
        if ($updatedOn !== '') {
            $payload['updated_on'] = $updatedOn;
        } else {
            $payload['updated_on'] = date('Y-m-d');
        }
        $this->departments->update($id, $payload);
    }

    /**
     * Replace all activities for a department from ACP form rows.
     *
     * @param list<array{title?:string,date?:string,text?:string}> $rows
     */
    public function replaceActivities(int $departmentId, array $rows): void
    {
        $this->activities->where('department_id', $departmentId)->delete();
        $sort = 0;
        foreach ($rows as $row) {
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $sort++;
            $this->activities->insert([
                'department_id' => $departmentId,
                'title'         => $title,
                'activity_date' => trim((string) ($row['date'] ?? '')),
                'body'          => trim((string) ($row['text'] ?? '')),
                'sort_order'    => $sort,
            ]);
        }
        $this->departments->update($departmentId, ['updated_on' => date('Y-m-d')]);
    }
}
