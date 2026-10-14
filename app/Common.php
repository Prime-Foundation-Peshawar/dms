<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('hub_base')) {
    /** Campus hub base URL (riphahpsh.edu.pk or staging /riphahpsh/). */
    function hub_base(): string
    {
        $host = strtolower($_SERVER['HTTP_HOST'] ?? '');
        $isStaging = $host === 'staging.riphahpsh.edu.pk' || $host === 'www.staging.riphahpsh.edu.pk';

        return $isStaging
            ? 'https://staging.riphahpsh.edu.pk/riphahpsh/'
            : 'https://riphahpsh.edu.pk/';
    }
}
