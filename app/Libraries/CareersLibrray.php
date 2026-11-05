<?php

namespace App\Libraries;

class CareersLibrray
{
    private $baseUrl;
    private $email;
    private $password;

    public function __construct(string $baseUrl, string $email, string $password)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->email = $email;
        $this->password = $password;
    }

    public function jobs(array $campusIds, int $page = 1, int $perPage = 20): array
    {
        // Keep writable outside your website's public document root.
        $path = WRITEPATH . 'campus_jobs_tokens.json';
        $file = fopen($path, 'c+');

        if ($file === false) {
            throw new \RuntimeException('Cannot open API token storage.');
        }

        @chmod($path, 0600);

        try {
            // Prevent concurrent requests from reusing a rotated refresh token.
            if (!flock($file, LOCK_EX)) {
                throw new \RuntimeException('Cannot lock API token storage.');
            }

            rewind($file);
            $tokens = json_decode((string) stream_get_contents($file), true);

            if (!is_array($tokens)) {
                $tokens = [];
            }

            if (empty($tokens['access_token'])
                || ($tokens['expires_at'] ?? 0) <= time() + 30) {
                $tokens = $this->renew($tokens);
                $this->save($file, $tokens);
            }

            $body = [
                'campus_ids' => array_values($campusIds),
                'page' => $page,
                'per_page' => $perPage,
            ];

            $result = $this->request(
                '/api/v1/jobs/by-campus',
                $body,
                $tokens['access_token']
            );

            // Handle tokens invalidated by another sign-in or server changes.
            if ($result['status'] === 401) {
                $tokens = $this->renew($tokens);
                $this->save($file, $tokens);

                $result = $this->request(
                    '/api/v1/jobs/by-campus',
                    $body,
                    $tokens['access_token']
                );
            }

            if ($result['status'] !== 200
                || empty($result['body']['success'])) {
                throw new \RuntimeException(
                    'Jobs API request failed. HTTP ' . $result['status']
                );
            }

            return $result['body']['data'];
        } finally {
            flock($file, LOCK_UN);
            fclose($file);
        }
    }

    /** Fetch every API page, then order vacancies by newest posting first. */
    public function allJobs(array $campusIds): array
    {
        $jobs = [];
        $page = 1;
        $previousPage = null;
        do {
            $data = $this->jobs($campusIds, $page, 20);
            $rows = $data['jobs'] ?? [];
            if (!is_array($rows)) {
                throw new \RuntimeException('Invalid jobs API response.');
            }
            $hasMore = !empty($data['pagination']['has_more']);
            if ($hasMore && ($rows === [] || $rows === $previousPage)) {
                throw new \RuntimeException('Jobs API pagination did not advance.');
            }
            $jobs = array_merge($jobs, $rows);
            $previousPage = $rows;
            $page++;
        } while ($hasMore);

        usort($jobs, static fn (array $a, array $b): int =>
            (strtotime((string) ($b['posted_at'] ?? '')) ?: 0)
            <=> (strtotime((string) ($a['posted_at'] ?? '')) ?: 0));

        return $jobs;
    }

    private function renew(array $tokens): array
    {
        if (!empty($tokens['refresh_token'])) {
            $result = $this->request('/api/v1/auth/refresh', [
                'refresh_token' => $tokens['refresh_token'],
            ]);

            if ($result['status'] === 200) {
                return $this->readTokens($result);
            }

            // Retry sign-in only when the refresh token is invalid/expired.
            if ($result['status'] !== 401) {
                throw new \RuntimeException(
                    'API refresh failed. HTTP ' . $result['status']
                );
            }
        }

        $result = $this->request('/api/v1/auth/sign-in', [
            'adm_email' => $this->email,
            'adm_pwd' => $this->password,
        ]);

        if ($result['status'] !== 200) {
            throw new \RuntimeException(
                'API sign-in failed. HTTP ' . $result['status']
            );
        }

        return $this->readTokens($result);
    }

    private function readTokens(array $result): array
    {
        $tokens = $result['body']['data'] ?? [];

        if (empty($tokens['access_token'])
            || empty($tokens['refresh_token'])
            || empty($tokens['expires_in'])) {
            throw new \RuntimeException('Invalid API token response.');
        }

        $tokens['expires_at'] = time() + (int) $tokens['expires_in'];

        return $tokens;
    }

    private function save($file, array $tokens): void
    {
        $json = json_encode($tokens, JSON_THROW_ON_ERROR);

        rewind($file);

        if (!ftruncate($file, 0)
            || fwrite($file, $json) !== strlen($json)
            || !fflush($file)) {
            throw new \RuntimeException('Cannot save API tokens.');
        }
    }

    private function request(
        string $endpoint,
        array $body,
        ?string $accessToken = null
    ): array {
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        if ($accessToken !== null) {
            $headers[] = 'Authorization: Bearer ' . $accessToken;
        }

        $curl = curl_init($this->baseUrl . $endpoint);

        if ($curl === false) {
            throw new \RuntimeException('Cannot initialize cURL.');
        }

        try {
            curl_setopt_array($curl, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($body, JSON_THROW_ON_ERROR),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 2,
            ]);

            $response = curl_exec($curl);
            
            if ($response === false) {
                throw new \RuntimeException(
                    'API connection failed: ' . curl_error($curl)
                );
            }

            return [
                'status' => (int) curl_getinfo($curl, CURLINFO_HTTP_CODE),
                'body' => json_decode($response, true),
            ];
        } finally {
            curl_close($curl);
        }
    }
}