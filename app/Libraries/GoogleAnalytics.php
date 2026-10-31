<?php

namespace App\Libraries;

/**
 * Google Analytics 4 Data API client (service account, no SDK) with a file cache.
 *
 * Needs in .env:
 *   ga4.propertyId      numeric GA4 property id (not the G-XXXX measurement id)
 *   ga4.credentialsFile service account JSON key, absolute or relative to the project root
 */
class GoogleAnalytics
{
    private const API   = 'https://analyticsdata.googleapis.com/v1beta/properties/';
    private const SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';

    /** Google allows five reports per batch request. */
    private const BATCH_SIZE = 5;

    private const CACHE_SECONDS      = 3600;
    private const CACHE_PAST_SECONDS = 86400;
    private const REALTIME_SECONDS   = 120;

    /** Plain-English names for Google's traffic groups. */
    public const CHANNELS = [
        'Direct'          => 'Typed our address or used a bookmark',
        'Organic Search'  => 'Google or another search engine',
        'Paid Search'     => 'Paid search adverts',
        'Organic Social'  => 'Social media (Facebook, Instagram...)',
        'Paid Social'     => 'Paid social media adverts',
        'Referral'        => 'A link on another website',
        'Email'           => 'A link in an email',
        'Organic Video'   => 'Video sites (YouTube...)',
        'Display'         => 'Banner adverts',
        'Unassigned'      => 'Not known',
    ];

    public const DEVICES = ['mobile' => 'Mobile phone', 'desktop' => 'Computer or laptop', 'tablet' => 'Tablet', 'smart tv' => 'Smart TV'];

    public const VISITOR_TYPES = ['new' => 'First-time visitors', 'returning' => 'Came back again'];

    public const GENDERS = ['male' => 'Men', 'female' => 'Women'];

    /** Website actions worth naming; Google's other internal events are left out. */
    public const EVENTS = [
        'page_view'           => 'Opened a page',
        'session_start'       => 'Started a visit',
        'first_visit'         => 'Visited for the first time',
        'user_engagement'     => 'Spent time reading a page',
        'scroll'              => 'Scrolled to the bottom of a page',
        'click'               => 'Clicked a link to another website',
        'file_download'       => 'Downloaded a file',
        'form_start'          => 'Started filling in a form',
        'form_submit'         => 'Submitted a form',
        'view_search_results' => 'Searched on the website',
        'video_start'         => 'Started a video',
        'video_progress'      => 'Watched part of a video',
        'video_complete'      => 'Finished a video',
    ];

    private string $propertyId;
    private string $credentialsFile;

    /** Reports on the Website Analytics page: dimensions, metrics, and how many rows to keep. */
    private const REPORTS = [
        'channels'  => [['sessionDefaultChannelGroup'], ['sessions', 'activeUsers'], 12],
        'sources'   => [['sessionSource'], ['sessions', 'activeUsers'], 10],
        'pages'     => [['pagePath', 'pageTitle'], ['screenPageViews', 'activeUsers', 'userEngagementDuration'], 15],
        'landing'   => [['landingPage'], ['sessions', 'activeUsers'], 10],
        'countries' => [['country'], ['activeUsers', 'sessions'], 10],
        'cities'    => [['city'], ['activeUsers', 'sessions'], 10],
        'devices'   => [['deviceCategory'], ['activeUsers', 'sessions'], 5],
        'browsers'  => [['browser'], ['activeUsers', 'sessions'], 8],
        'systems'   => [['operatingSystem'], ['activeUsers', 'sessions'], 8],
        'visitors'  => [['newVsReturning'], ['activeUsers', 'sessions'], 3],
        'languages' => [['language'], ['activeUsers', 'sessions'], 8],
        'events'    => [['eventName'], ['eventCount'], 25],
        'ages'      => [['userAgeBracket'], ['activeUsers'], 8],
        'genders'   => [['userGender'], ['activeUsers'], 4],
        'days'      => [['dayOfWeek'], ['sessions'], 7],
        'hours'     => [['hour'], ['sessions'], 24],
    ];

    public function __construct()
    {
        $this->propertyId = trim((string) env('ga4.propertyId', ''));
        $file = trim((string) env('ga4.credentialsFile', ''));
        if ($file !== '' && ! str_starts_with($file, '/') && ! preg_match('/^[A-Za-z]:[\\\\\/]/', $file)) {
            $file = ROOTPATH . $file;
        }
        $this->credentialsFile = $file;
    }

    public function isConfigured(): bool
    {
        return $this->propertyId !== '' && $this->credentialsFile !== '' && is_file($this->credentialsFile);
    }

    /** Headline numbers for the Reports page. */
    public function summary(array $period): array
    {
        return $this->cached('summary', $period, fn () => $this->fetchSummary($period));
    }

    /** Everything shown on the Website Analytics page. */
    public function report(array $period): array
    {
        return $this->cached('report', $period, function () use ($period) {
            $out = $this->fetchSummary($period);

            $byMonth = $period['months'] > 2;
            $dim     = $byMonth ? 'yearMonth' : 'date';
            $specs   = ['trend' => [[$dim], ['activeUsers', 'sessions', 'screenPageViews'], 400]] + self::REPORTS;
            $ranges  = [['startDate' => $period['start'], 'endDate' => $period['last_day']]];

            foreach (array_chunk($specs, self::BATCH_SIZE, true) as $chunk) {
                $requests = [];
                foreach ($chunk as [$dims, $metrics, $limit]) {
                    $inOrder    = in_array($dims[0], ['date', 'yearMonth', 'dayOfWeek', 'hour'], true);
                    $requests[] = [
                        'dateRanges' => $ranges,
                        'dimensions' => array_map(static fn ($d) => ['name' => $d], $dims),
                        'metrics'    => array_map(static fn ($m) => ['name' => $m], $metrics),
                        'limit'      => $limit,
                        'orderBys'   => [$inOrder
                            ? ['dimension' => ['dimensionName' => $dims[0]]]
                            : ['metric' => ['metricName' => $metrics[0]], 'desc' => true]],
                    ];
                }
                $res = $this->call(':batchRunReports', ['requests' => $requests]);
                foreach (array_keys($chunk) as $i => $name) {
                    $out[$name] = $this->rows($res['reports'][$i] ?? [], $chunk[$name][0], $chunk[$name][1]);
                }
            }

            return $out;
        });
    }

    /** People on the website in the last 30 minutes; null when Google cannot be reached. */
    public function realtime(): ?int
    {
        $path = $this->cachePath('realtime', []);
        if (is_file($path) && time() - (int) filemtime($path) < self::REALTIME_SECONDS) {
            return (int) file_get_contents($path);
        }
        try {
            $res = $this->call(':runRealtimeReport', ['metrics' => [['name' => 'activeUsers']]]);
            $n   = (int) ($res['rows'][0]['metricValues'][0]['value'] ?? 0);
            @file_put_contents($path, (string) $n);

            return $n;
        } catch (\Throwable $e) {
            log_message('error', 'Google Analytics realtime failed: ' . $e->getMessage());

            return null;
        }
    }

    private function fetchSummary(array $period): array
    {
        $metrics = ['activeUsers', 'newUsers', 'sessions', 'screenPageViews', 'engagedSessions', 'averageSessionDuration', 'screenPageViewsPerSession'];
        $res     = $this->call(':runReport', [
            'dateRanges' => [
                ['name' => 'now', 'startDate' => $period['start'], 'endDate' => $period['last_day']],
                ['name' => 'prev', 'startDate' => $period['prev_start'], 'endDate' => $period['prev_last']],
            ],
            'metrics' => array_map(static fn ($m) => ['name' => $m], $metrics),
        ]);

        $totals = ['now' => array_fill_keys($metrics, 0), 'prev' => array_fill_keys($metrics, 0)];
        foreach ($res['rows'] ?? [] as $row) {
            $range = (string) ($row['dimensionValues'][0]['value'] ?? 'now');
            foreach ($metrics as $i => $m) {
                $totals[$range][$m] = (float) ($row['metricValues'][$i]['value'] ?? 0);
            }
        }

        return [
            'ok'         => true,
            'totals'     => $totals['now'],
            'prev'       => $totals['prev'],
            'views'      => (int) $totals['now']['screenPageViews'],
            'prev_views' => (int) $totals['prev']['screenPageViews'],
        ];
    }

    /** @return list<array<string,mixed>> */
    private function rows(array $report, array $dims, array $metrics): array
    {
        $out = [];
        foreach ($report['rows'] ?? [] as $row) {
            $r = [];
            foreach ($dims as $i => $d) {
                $r[$d] = (string) ($row['dimensionValues'][$i]['value'] ?? '');
            }
            foreach ($metrics as $i => $m) {
                $r[$m] = (float) ($row['metricValues'][$i]['value'] ?? 0);
            }
            $out[] = $r;
        }

        return $out;
    }

    /**
     * Serves the period from the cache. When Google fails, the last good copy is returned and marked as old.
     *
     * @param callable():array<string,mixed> $fetch
     */
    private function cached(string $kind, array $period, callable $fetch): array
    {
        $path = $this->cachePath($kind, [$period['start'], $period['last_day'], $period['prev_start'], $period['prev_last']]);
        $ttl  = $period['last_day'] < date('Y-m-d') ? self::CACHE_PAST_SECONDS : self::CACHE_SECONDS;
        $copy = is_file($path) ? json_decode((string) file_get_contents($path), true) : null;
        if (is_array($copy) && time() - (int) filemtime($path) < $ttl) {
            return $copy + ['fetched_at' => (int) filemtime($path), 'stale' => false];
        }

        try {
            $data = $fetch();
            @file_put_contents($path, json_encode($data));

            return $data + ['fetched_at' => time(), 'stale' => false];
        } catch (\Throwable $e) {
            log_message('error', 'Google Analytics failed: ' . $e->getMessage());
            if (is_array($copy)) {
                return $copy + ['fetched_at' => (int) filemtime($path), 'stale' => true];
            }

            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    private function cachePath(string $kind, array $key): string
    {
        return WRITEPATH . 'cache/ga4_' . $kind . '_' . md5($this->propertyId . implode('|', $key)) . '.json';
    }

    /** @return array<string,mixed> decoded API response */
    private function call(string $method, array $body): array
    {
        return $this->post(self::API . rawurlencode($this->propertyId) . $method, json_encode($body), [
            'Authorization: Bearer ' . $this->accessToken(),
            'Content-Type: application/json',
        ]);
    }

    private function accessToken(): string
    {
        static $memo = [];
        if (isset($memo[$this->credentialsFile]) && $memo[$this->credentialsFile]['expires'] > time() + 60) {
            return $memo[$this->credentialsFile]['token'];
        }

        $key = json_decode((string) file_get_contents($this->credentialsFile), true);
        if (! is_array($key) || empty($key['client_email']) || empty($key['private_key'])) {
            throw new \RuntimeException('The Google service account key file is not valid.');
        }
        $tokenUri = (string) ($key['token_uri'] ?? 'https://oauth2.googleapis.com/token');
        $now      = time();
        $b64      = static fn (string $s) => rtrim(strtr(base64_encode($s), '+/', '-_'), '=');
        $unsigned = $b64(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])) . '.' . $b64(json_encode([
            'iss'   => $key['client_email'],
            'scope' => self::SCOPE,
            'aud'   => $tokenUri,
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));
        if (! openssl_sign($unsigned, $signature, (string) $key['private_key'], OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('Could not sign with the Google service account key.');
        }

        $res = $this->post($tokenUri, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $unsigned . '.' . $b64($signature),
        ]), ['Content-Type: application/x-www-form-urlencoded']);
        if (empty($res['access_token'])) {
            throw new \RuntimeException('Google did not return an access token.');
        }
        $memo[$this->credentialsFile] = ['token' => (string) $res['access_token'], 'expires' => $now + (int) ($res['expires_in'] ?? 3600)];

        return $memo[$this->credentialsFile]['token'];
    }

    /**
     * @param list<string> $headers
     * @return array<string,mixed>
     */
    protected function post(string $url, string $body, array $headers): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $raw  = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            throw new \RuntimeException('Could not reach Google: ' . $err);
        }
        $data = json_decode((string) $raw, true);
        if ($code !== 200 || ! is_array($data)) {
            $message = is_array($data) ? (string) ($data['error']['message'] ?? $data['error_description'] ?? '') : '';

            throw new \RuntimeException('Google returned ' . $code . ($message !== '' ? ': ' . $message : ''));
        }

        return $data;
    }
}
