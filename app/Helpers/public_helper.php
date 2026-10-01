<?php

/**
 * Public site helpers (assets + URL constants) for CI4 views.
 */

if (! function_exists('public_boot')) {
    function public_boot(): void
    {
        if (! defined('base_url')) {
            define('base_url', rtrim(base_url(), '/') . '/');
        }
        if (! defined('hub_base')) {
            if (! function_exists('dms_hub_base')) {
                helper('dms');
            }
            define('hub_base', function_exists('dms_hub_base') ? dms_hub_base() : 'https://riphahpsh.edu.pk/');
        }
        if (! function_exists('seo_apply')) {
            $seo = ROOTPATH . 'legacy/includes/seo.php';
            if (is_file($seo)) {
                require_once $seo;
            }
        }
    }
}

if (! function_exists('dms_asset')) {
    function dms_asset(string $path): string
    {
        $path = ltrim($path, '/');
        $candidates = [
            ROOTPATH . 'public/' . $path,
            ROOTPATH . $path,
            ROOTPATH . 'legacy/' . $path,
        ];
        $v = (string) time();
        foreach ($candidates as $full) {
            if (is_file($full)) {
                $v = (string) filemtime($full);

                break;
            }
        }

        return htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '?v=' . rawurlencode($v);
    }
}

if (! function_exists('dds_asset')) {
    function dds_asset(string $path): string
    {
        return dms_asset($path);
    }
}
