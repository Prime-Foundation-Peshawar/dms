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
        $path = ltrim($path, '/');
        $full = FCPATH . $path;
        $v = is_file($full) ? (string) filemtime($full) : (string) time();
        return base_url($path) . '?v=' . rawurlencode($v);
    }
}
