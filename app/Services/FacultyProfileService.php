<?php

namespace App\Services;

use App\Models\FacultyProfileModel;

class FacultyProfileService
{
    protected FacultyProfileModel $model;

    public function __construct(?FacultyProfileModel $model = null)
    {
        helper('faculty');
        $this->model = $model ?? model(FacultyProfileModel::class);
    }

    /** @return array<string,mixed>|null */
    public function lookup(string $slugOrName): ?array
    {
        $slug = faculty_slug($slugOrName);
        if ($slug === '') {
            return null;
        }
        $row = $this->model->findBySlugOrAlias($slug);
        if ($row) {
            return $row;
        }
        // fuzzy
        foreach ($this->model->publicRows() as $rec) {
            $keys = array_merge([$rec['slug'] ?? ''], $rec['aliases'] ?? []);
            foreach ($keys as $key) {
                if (faculty_slugs_match($slug, (string) $key)) {
                    return $rec;
                }
            }
            if (faculty_slugs_match($slug, faculty_slug((string) ($rec['name'] ?? '')))) {
                return $rec;
            }
        }
        return null;
    }
}
