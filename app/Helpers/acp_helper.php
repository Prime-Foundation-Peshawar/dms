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
