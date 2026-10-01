<?php

namespace App\Libraries;

/**
 * Bootstrap public helpers and rewrite legacy *.php URLs in rendered HTML.
 */
class PublicSite
{
    public static function boot(): void
    {
        helper('public');
        public_boot();
    }

    public static function rewriteLegacyUrls(string $html): string
    {
        $home = rtrim(base_url(), '/') . '/';

        return (string) preg_replace_callback(
            '/\b(href|action)=([\'"])([^\'"\s]+)\2/i',
            static function (array $m) use ($home): string {
                $attr = $m[1];
                $quote = $m[2];
                $url = $m[3];

                if ($url === '' || $url[0] === '#' || preg_match('#^(?:[a-z][a-z0-9+.-]*:|//)#i', $url)) {
                    return $m[0];
                }

                $path = $url;
                $suffix = '';
                if (preg_match('/^([^?#]+)([?#].*)$/', $url, $parts)) {
                    $path = $parts[1];
                    $suffix = $parts[2];
                }

                if (! preg_match('/\.php$/i', $path) && strtolower(basename(str_replace('\\', '/', $path))) !== 'index') {
                    return $m[0];
                }

                $clean = (string) preg_replace('/\.php$/i', '', $path);
                $base = strtolower(basename(str_replace('\\', '/', $clean)));
                if ($base === 'index') {
                    $clean = ($suffix === '')
                        ? $home
                        : rtrim($home, '/') . '/' . $suffix;

                    return $attr . '=' . $quote . $clean . $quote;
                }

                return $attr . '=' . $quote . $clean . $suffix . $quote;
            },
            $html
        );
    }
}
