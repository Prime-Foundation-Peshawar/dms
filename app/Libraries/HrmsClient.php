<?php

namespace App\Libraries;

/**
 * Live HRMS faculty directory client with file cache.
 */
class HrmsClient
{
    protected string $url;
    protected int $ttlSeconds;
    protected string $cachePath;

    public function __construct(?string $url = null, int $ttlSeconds = 900)
    {
        $this->url = $url ?: (string) (function_exists('env') ? env('hrms.apiUrl', 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php') : 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php');
        $setting = null;
        try {
            $row = db_connect()->table('settings')->where('key', 'hrms_api_url')->get()->getRowArray();
            $setting = $row['value'] ?? null;
        } catch (\Throwable $e) {
            // DB may not be ready during early boot
        }
        if (is_string($setting) && $setting !== '') {
            $this->url = $setting;
        }
        $this->ttlSeconds = $ttlSeconds;
        $this->cachePath = WRITEPATH . 'cache/dms_hrms_faculty_cache.json';
    }

    /**
     * @return array{ok:bool,employees:list<array<string,mixed>>,error:?string}
     */
    public function fetchAll(int $ttlSeconds = -1): array
    {
        static $memo = null;
        if (is_array($memo)) {
            return $memo;
        }

        $ttl = $ttlSeconds >= 0 ? $ttlSeconds : $this->ttlSeconds;
        if (is_file($this->cachePath) && (time() - (int) filemtime($this->cachePath)) < $ttl) {
            $decoded = json_decode((string) file_get_contents($this->cachePath), true);
            if (is_array($decoded)) {
                $memo = ['ok' => true, 'employees' => $decoded, 'error' => null];
                return $memo;
            }
        }

        $raw = false;
        $error = null;
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            $raw = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($raw === false || $code !== 200) {
                $error = curl_error($ch) ?: ('HTTP ' . $code);
                $raw = false;
            }
            curl_close($ch);
        } else {
            $raw = @file_get_contents($this->url);
            if ($raw === false) {
                $error = 'file_get_contents failed';
            }
        }

        if ($raw === false) {
            $memo = ['ok' => false, 'employees' => [], 'error' => $error ?: 'fetch failed'];
            return $memo;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            $memo = ['ok' => false, 'employees' => [], 'error' => 'invalid JSON'];
            return $memo;
        }

        @file_put_contents($this->cachePath, json_encode($decoded));
        $memo = ['ok' => true, 'employees' => $decoded, 'error' => null];
        return $memo;
    }
}
