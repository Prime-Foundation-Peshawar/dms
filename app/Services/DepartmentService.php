<?php

namespace App\Services;

use App\Models\DepartmentModel;

class DepartmentService
{
    protected DepartmentModel $model;
    protected FacultyDirectoryService $faculty;

    public function __construct(?DepartmentModel $model = null, ?FacultyDirectoryService $faculty = null)
    {
        $this->model = $model ?? model(DepartmentModel::class);
        $this->faculty = $faculty ?? new FacultyDirectoryService();
    }

    /** @return list<array<string,mixed>> */
    public function visible(): array
    {
        return $this->model->visibleDepartments();
    }

    /** @return array<string, array<string, array<string,mixed>>> */
    public function groups(): array
    {
        $groups = [];
        foreach ($this->visible() as $dept) {
            $g = $dept['group_name'] ?? 'Other';
            $groups[$g][$dept['slug']] = $dept;
        }
        return $groups;
    }

    /** @return array<string,mixed>|null */
    public function find(string $slug): ?array
    {
        return $this->model->findBySlug($slug, false);
    }

    public function facultyCount(string $slug, string $name, array $seedFaculty = []): int
    {
        return count($this->faculty->forDepartment($slug, $name, $seedFaculty));
    }

    public function oricUrl(?int $oricId): string
    {
        if ($oricId === null) {
            return 'https://oric.riphahpsh.edu.pk/publications.php';
        }
        return 'https://oric.riphahpsh.edu.pk/dep_research.php?id=' . $oricId;
    }
}
