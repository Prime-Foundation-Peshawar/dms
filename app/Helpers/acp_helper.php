<?php

if (! function_exists('acp_options_with_current')) {
    /**
     * Ensure the currently saved value remains selectable even if not in the closed list.
     *
     * @param list<string> $options
     * @return list<string>
     */
    function acp_options_with_current(array $options, ?string $current): array
    {
        $current = trim((string) $current);
        $out     = [];
        foreach ($options as $opt) {
            $opt = (string) $opt;
            if ($opt === '' || in_array($opt, $out, true)) {
                continue;
            }
            $out[] = $opt;
        }
        if ($current !== '' && ! in_array($current, $out, true)) {
            $out[] = $current;
        }

        return $out;
    }
}

if (! function_exists('acp_select')) {
    /**
     * Render a <select> with closed options, preserving unknown current values.
     *
     * @param list<string>         $options
     * @param array<string,mixed>  $attrs   Extra attributes (class, required, id, …)
     */
    function acp_select(
        string $name,
        array $options,
        ?string $selected = null,
        string $emptyLabel = '— Select —',
        array $attrs = []
    ): string {
        $selected = trim((string) $selected);
        $options  = acp_options_with_current($options, $selected);

        $attrStr = '';
        $attrs   = array_merge(['class' => 'form-select', 'name' => $name], $attrs);
        foreach ($attrs as $k => $v) {
            if ($v === null || $v === false) {
                continue;
            }
            if ($v === true) {
                $attrStr .= ' ' . esc($k, 'attr');
                continue;
            }
            $attrStr .= ' ' . esc((string) $k, 'attr') . '="' . esc((string) $v, 'attr') . '"';
        }

        $html = '<select' . $attrStr . '>';
        if ($emptyLabel !== '') {
            $html .= '<option value="">' . esc($emptyLabel) . '</option>';
        }
        foreach ($options as $opt) {
            $sel = $selected === $opt ? ' selected' : '';
            $html .= '<option value="' . esc($opt, 'attr') . '"' . $sel . '>' . esc($opt) . '</option>';
        }
        $html .= '</select>';

        return $html;
    }
}

if (! function_exists('acp_is_view_only')) {
    /** True when the signed-in ACP user may only view the dashboard and reports. */
    function acp_is_view_only(): bool
    {
        $role = (string) (session()->get('acp_user_role') ?? '');

        return in_array($role, config(\Config\AcpLists::class)->viewOnlyRoles(), true);
    }
}

if (! function_exists('acp_can_open')) {
    /** Whether the signed-in ACP user may open an ACP path (e.g. "acp/news/edit/4"). */
    function acp_can_open(string $path): bool
    {
        if (! acp_is_view_only()) {
            return true;
        }
        $path = trim((string) (parse_url($path, PHP_URL_PATH) ?: ''), '/');

        return in_array($path, config(\Config\AcpLists::class)->viewOnlyPaths(), true);
    }
}

if (! function_exists('acp_link_open')) {
    /**
     * Opening <a> to an ACP path, or a <span> with the same class when the user may not open it.
     * Pair with acp_link_close() using the same path.
     */
    function acp_link_open(string $path, string $class = ''): string
    {
        $cls = $class !== '' ? ' class="' . esc($class, 'attr') . '"' : '';

        return acp_can_open($path)
            ? '<a href="' . esc(site_url($path), 'attr') . '"' . $cls . '>'
            : '<span' . $cls . '>';
    }
}

if (! function_exists('acp_link_close')) {
    function acp_link_close(string $path): string
    {
        return acp_can_open($path) ? '</a>' : '</span>';
    }
}

if (! function_exists('acp_tip_attrs')) {
    /**
     * Attributes that give any element the rich hover card (Bootstrap tooltip, initialised in acp.js):
     * an icon and title, a plain-language explanation and an optional worked example.
     */
    function acp_tip_attrs(string $title, string $text, string $icon = 'bi-info-circle', string $example = ''): string
    {
        $html = '<div class="acp-rtip-head"><span class="acp-rtip-icon"><i class="bi ' . esc($icon, 'attr') . '"></i></span>'
            . '<span>' . esc($title) . '</span></div><div class="acp-rtip-body">' . esc($text) . '</div>';
        if ($example !== '') {
            $html .= '<div class="acp-rtip-eg"><i class="bi bi-lightbulb"></i><span>' . esc($example) . '</span></div>';
        }

        return ' data-bs-toggle="tooltip" data-bs-html="true" data-bs-custom-class="acp-rtip" data-bs-title="' . esc($html, 'attr') . '"';
    }
}

if (! function_exists('acp_tip')) {
    /** Small (i) icon that opens the rich hover card. */
    function acp_tip(string $title, string $text, string $example = ''): string
    {
        return '<i class="bi bi-info-circle acp-tip" tabindex="0" role="button" aria-label="' . esc($title . ': ' . $text, 'attr') . '"'
            . acp_tip_attrs($title, $text, 'bi-info-circle', $example) . '></i>';
    }
}
