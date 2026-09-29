<?php

/**
 * DMS helpers (auto-loaded).
 */

if (!function_exists('dms_is_staging')) {
    function dms_is_staging(): bool
    {
        $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));

        return $host === 'staging.riphahpsh.edu.pk' || $host === 'www.staging.riphahpsh.edu.pk';
    }
}

if (!function_exists('dms_hub_base')) {
    function dms_hub_base(): string
    {
        return dms_is_staging()
            ? 'https://staging.riphahpsh.edu.pk/riphahpsh/'
            : 'https://riphahpsh.edu.pk/';
    }
}

if (!function_exists('dms_asset')) {
    function dms_asset(string $path): string
    {
        $path = ltrim($path, '/');
        $full = FCPATH . $path;
        $v = is_file($full) ? (string) filemtime($full) : (string) time();

        return base_url($path) . '?v=' . rawurlencode($v);
    }
}

if (!function_exists('dms_json_list')) {
    /**
     * @param mixed $value
     * @return list<mixed>
     */
    function dms_json_list($value): array
    {
        if (is_array($value)) {
            return array_values($value);
        }
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return array_values($decoded);
            }
        }

        return [];
    }
}
