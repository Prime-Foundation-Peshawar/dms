<?php

if (! function_exists('faculty_slug')) {
    function faculty_slug(string $name): string
    {
        $n = trim($name);
        $titles = '/^(associate professor|assistant professor|senior registrar|senior lecturer|assoc\.?\s*prof\.?|asst\.?\s*prof\.?|sr\.?\s*registrar|sr\.?\s*lecturer|professor|prof\.?|dr\.?)\s+/i';
        while (preg_match($titles, $n)) {
            $n = preg_replace($titles, '', $n, 1) ?? $n;
        }
        $n = strtolower($n);
        $n = preg_replace('/[^a-z0-9]+/', '-', $n) ?? $n;
        $n = trim($n, '-');
        $n = preg_replace('/^(mohammad|muhammed)-/', 'muhammad-', $n) ?? $n;
        return $n;
    }
}

if (! function_exists('faculty_slug_tokens')) {
    /** @return list<string> */
    function faculty_slug_tokens(string $slug): array
    {
        $parts = array_values(array_filter(explode('-', trim($slug))));
        foreach ($parts as $i => $part) {
            if (in_array($part, ['m', 'md', 'mohammad', 'muhammed'], true)) {
                $parts[$i] = 'muhammad';
            }
        }
        return $parts;
    }
}

if (! function_exists('faculty_tokens_subsequence')) {
    function faculty_tokens_subsequence(array $short, array $long): bool
    {
        if (! $short) {
            return true;
        }
        if (count($short) > count($long)) {
            return false;
        }
        $i = 0;
        foreach ($long as $token) {
            if ($i < count($short) && $short[$i] === $token) {
                $i++;
            }
        }
        return $i === count($short);
    }
}

if (! function_exists('faculty_slugs_match')) {
    function faculty_slugs_match(string $a, string $b): bool
    {
        if ($a === '' || $b === '') {
            return false;
        }
        if ($a === $b) {
            return true;
        }
        $ta = faculty_slug_tokens($a);
        $tb = faculty_slug_tokens($b);
        if (! $ta || ! $tb) {
            return false;
        }
        if (implode('-', $ta) === implode('-', $tb)) {
            return true;
        }
        if ($ta[0] !== $tb[0]) {
            return false;
        }
        $la = $ta[count($ta) - 1];
        $lb = $tb[count($tb) - 1];
        if ($la !== $lb && ! str_starts_with($la, $lb) && ! str_starts_with($lb, $la)) {
            return false;
        }
        $midA = array_slice($ta, 1, -1);
        $midB = array_slice($tb, 1, -1);
        if ($midA && $midB) {
            return faculty_tokens_subsequence($midA, $midB) || faculty_tokens_subsequence($midB, $midA);
        }
        return true;
    }
}

if (! function_exists('faculty_canonical_dept')) {
    function faculty_canonical_dept(string $name): string
    {
        $raw = trim($name);
        if ($raw === '') {
            return '';
        }
        $key = strtolower(str_replace('&', 'and', $raw));
        $key = trim(preg_replace('/\s+/', ' ', $key) ?? '');
        $aliases = [
            'community health sciences' => 'CHS',
            'dhpe and research' => 'DHPE & R',
            'gynaecology and obstetrics' => 'Gynae and Obstetrics',
            'gynaecology' => 'Gynae and Obstetrics',
            'orthopaedics' => 'Orthopedics',
        ];
        return $aliases[$key] ?? $raw;
    }
}

if (! function_exists('faculty_is_directory_rank')) {
    function faculty_is_directory_rank(string $desTitle): bool
    {
        $hidden = [
            'Senior Lecturer' => true,
            'Lecturer' => true,
            'Junior Registrar' => true,
        ];
        return ! isset($hidden[trim($desTitle)]);
    }
}

if (! function_exists('dms_asset')) {
    function dms_asset(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path !== '' && ! str_starts_with($path, 'assets/')) {
            $path = 'assets/' . $path;
        }
        $full = FCPATH . $path;
        $v = is_file($full) ? (string) filemtime($full) : (string) time();

        return base_url($path) . '?v=' . rawurlencode($v);
    }
}

if (! function_exists('faculty_soft_space')) {
    function faculty_soft_space(string $text): string
    {
        $text = preg_replace('/([a-z])([A-Z])/', '$1 $2', $text) ?? $text;
        $text = preg_replace('/(Department)(Professor|Associate|Assistant)/', '$1 $2', $text) ?? $text;

        return trim(preg_replace('/\s+/', ' ', $text) ?? '');
    }
}

if (! function_exists('faculty_has_arabic')) {
    function faculty_has_arabic(string $text): bool
    {
        return (bool) preg_match('/\p{Arabic}/u', $text);
    }
}

if (! function_exists('faculty_plain_list')) {
    /** @param mixed $raw @return list<string> */
    function faculty_plain_list($raw): array
    {
        if (is_string($raw)) {
            $raw = preg_split('/[\n;|]+/', $raw) ?: [];
        }
        if (! is_array($raw)) {
            return [];
        }
        $out = [];
        $seen = [];
        foreach ($raw as $item) {
            $text = trim(faculty_soft_space((string) $item));
            if ($text === '' || isset($seen[strtolower($text)])) {
                continue;
            }
            $seen[strtolower($text)] = true;
            $out[] = $text;
        }

        return $out;
    }
}

if (! function_exists('faculty_research_preferences')) {
    /** @param array<string,mixed> $rec @return list<string> */
    function faculty_research_preferences(array $rec): array
    {
        foreach (['research_preferences', 'research_interests', 'research', 'preferences'] as $key) {
            if (empty($rec[$key])) {
                continue;
            }
            $list = faculty_plain_list($rec[$key]);
            if ($list) {
                return $list;
            }
        }

        return [];
    }
}

if (! function_exists('faculty_normalize_designation')) {
    function faculty_normalize_designation(string $raw): string
    {
        $d = trim($raw);
        $d = preg_replace('/^head of department\s*/i', '', $d) ?? $d;
        $d = preg_replace('/\band head of department\b/i', '', $d) ?? $d;
        $d = preg_replace('/\bhod\b/i', '', $d) ?? $d;
        $d = faculty_soft_space($d);
        $d = trim($d, " ,&");
        if (preg_match('/^assit\.?\s*prof\.?$/i', $d) || preg_match('/^asst\.?\s*prof\.?$/i', $d) || preg_match('/^assistant\s*prof\.?$/i', $d)) {
            return 'Assistant Professor';
        }
        if ($d === '' || strcasecmp($d, 'hod') === 0 || strcasecmp($d, 'head of department') === 0) {
            return '';
        }

        return $d;
    }
}

if (! function_exists('faculty_normalize_qualifications')) {
    /** @param list<mixed>|mixed $items @return list<string> */
    function faculty_normalize_qualifications($items): array
    {
        if (is_string($items)) {
            $items = preg_split('/[\n;,|]+/', $items) ?: [];
        }
        if (! is_array($items)) {
            return [];
        }

        return faculty_plain_list($items);
    }
}

if (! function_exists('faculty_normalize_skills')) {
    /** @param list<mixed>|mixed $items @return list<string> */
    function faculty_normalize_skills($items): array
    {
        return faculty_plain_list($items);
    }
}

if (! function_exists('faculty_explode_publications')) {
    /** @param list<mixed>|mixed $pubs @return list<string> */
    function faculty_explode_publications($pubs): array
    {
        if (is_string($pubs)) {
            $pubs = preg_split('/\n+/', $pubs) ?: [];
        }
        if (! is_array($pubs)) {
            return [];
        }
        $out = [];
        $seen = [];
        foreach ($pubs as $raw) {
            $p = trim(preg_replace('/\s+/', ' ', (string) $raw) ?? '');
            if ($p === '') {
                continue;
            }
            $key = strtolower(substr(preg_replace('/\W+/', '', $p) ?? '', 0, 90));
            if ($key === '' || isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[] = $p;
        }

        return $out;
    }
}

if (! function_exists('faculty_pub_year')) {
    function faculty_pub_year(string $cite): string
    {
        if (preg_match('/\b((?:19|20)\d{2})\b/', $cite, $m)) {
            return $m[1];
        }

        return '';
    }
}

if (! function_exists('faculty_pub_url')) {
    function faculty_pub_url(string $cite): string
    {
        if (preg_match('#https?://[^\s<>"]+#i', $cite, $m)) {
            return rtrim($m[0], '.,);');
        }
        if (preg_match('/\b(10\.\d{4,}\/[^\s<>"]+)/', $cite, $m)) {
            return 'https://doi.org/' . rtrim($m[1], '.,);');
        }

        return '';
    }
}

if (! function_exists('faculty_profile_has_cv')) {
    function faculty_profile_has_cv(array $rec): bool
    {
        if (! empty($rec['photo'])) {
            return true;
        }
        foreach (['qualifications', 'experience', 'publications', 'skills'] as $key) {
            if (! empty($rec[$key]) && is_array($rec[$key])) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('faculty_profile_link_html')) {
    /**
     * Linked faculty name for PG / department pages (legacy faculty_profile_link_html).
     */
    function faculty_profile_link_html(string $label, string $qual = ''): string
    {
        $nameHtml = '<span>' . esc($label) . '</span>';
        $qualHtml = $qual !== '' ? '<span class="pg-qual">' . esc($qual) . '</span>' : '';

        try {
            $cv = (new \App\Services\FacultyProfileService())->lookup($label);
        } catch (\Throwable $e) {
            $cv = null;
        }

        if ($cv && faculty_is_directory_rank((string) ($cv['designation'] ?? '')) && faculty_profile_has_cv($cv)) {
            $slug = (string) ($cv['slug'] ?? faculty_slug($label));
            $href = site_url('faculty-profile') . '?n=' . rawurlencode($slug);

            return '<a class="pg-staff-link" href="' . esc($href) . '">' . $nameHtml . $qualHtml . '</a>';
        }

        return $nameHtml . $qualHtml;
    }
}
