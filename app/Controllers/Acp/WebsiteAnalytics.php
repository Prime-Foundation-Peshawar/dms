<?php

namespace App\Controllers\Acp;

use App\Libraries\GoogleAnalytics;
use App\Libraries\ReportService;
use App\Models\SettingModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class WebsiteAnalytics extends BaseAcpController
{
    public function index()
    {
        $service = new ReportService();
        $type    = (string) $this->request->getGet('period');
        $ref     = (string) $this->request->getGet('ref');
        if ($type === 'range' && $this->request->getGet('from') !== null) {
            $ref = trim((string) $this->request->getGet('from')) . '_' . trim((string) $this->request->getGet('to'));
        }
        $period = $service->resolvePeriod($type, $ref);
        $ga     = new GoogleAnalytics();
        $ready  = $ga->isConfigured();

        return $this->acpView('reports/analytics', [
            'title'       => 'Website Analytics',
            'period'      => $period,
            'options'     => $service->periodOptions(),
            'connected'   => $ready,
            'data'        => $ready ? $ga->report($period) : ['ok' => false],
            'liveNow'     => $ready ? $ga->realtime() : null,
            'published'   => $ready ? $service->publishedCounts($period) : [],
            'gaPropertyId'=> $ga->propertyId(),
            'gaHasKey'    => $ga->hasCredentialsFile(),
        ]);
    }

    public function connect()
    {
        if (acp_is_view_only()) {
            return redirect()->to(site_url('acp/website-analytics'))->with('error', 'View-only users cannot connect Google Analytics.');
        }

        $propertyId = preg_replace('/\D+/', '', (string) $this->request->getPost('ga4_property_id')) ?? '';
        if ($propertyId === '') {
            return redirect()->to(site_url('acp/website-analytics'))->with('error', 'Enter the numeric GA4 Property ID (digits only).');
        }

        $streamId = preg_replace('/\D+/', '', (string) $this->request->getPost('ga4_stream_id')) ?? '';
        if ($streamId === '') {
            $streamId = GoogleAnalytics::DEFAULT_STREAM_ID;
        }

        $settings = model(SettingModel::class);
        $settings->setValue(GoogleAnalytics::SETTING_PROPERTY_ID, $propertyId);
        $settings->setValue(GoogleAnalytics::SETTING_STREAM_ID, $streamId);

        $file = $this->request->getFile('ga4_credentials');
        if ($file instanceof UploadedFile && $file->isValid() && ! $file->hasMoved()) {
            $raw = (string) file_get_contents($file->getTempName());
            $json = json_decode($raw, true);
            $isServiceAccount = ($json['type'] ?? '') === 'service_account'
                && ! empty($json['client_email'])
                && ! empty($json['private_key']);
            $isOAuthUser = (($json['type'] ?? '') === 'authorized_user' || ! empty($json['refresh_token']))
                && ! empty($json['client_id'])
                && ! empty($json['refresh_token']);
            if (! is_array($json) || (! $isServiceAccount && ! $isOAuthUser)) {
                return redirect()->to(site_url('acp/website-analytics'))->with('error', 'Upload a valid Google service-account JSON key or OAuth authorized_user JSON.');
            }

            $destDir = rtrim(WRITEPATH, '\\/');
            if (! is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $encoded = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $dest = $destDir . DIRECTORY_SEPARATOR . 'ga4-service-account.json';
            if ($encoded === false || file_put_contents($dest, $encoded) === false) {
                return redirect()->to(site_url('acp/website-analytics'))->with('error', 'Could not save the credentials file to writable/.');
            }
            @chmod($dest, 0600);
            // Keep a DB copy so a missing writable file after deploy can be restored.
            $settings->setValue(GoogleAnalytics::SETTING_CREDENTIALS, $encoded);
        } elseif (! is_file(WRITEPATH . 'ga4-service-account.json')) {
            $backup = (string) $settings->getValue(GoogleAnalytics::SETTING_CREDENTIALS, '');
            if ($backup === '' || json_decode($backup, true) === null) {
                return redirect()->to(site_url('acp/website-analytics'))->with('error', 'Upload the service-account JSON key (one-time).');
            }
            if (file_put_contents(WRITEPATH . 'ga4-service-account.json', $backup) === false) {
                return redirect()->to(site_url('acp/website-analytics'))->with('error', 'Could not restore credentials to writable/.');
            }
            @chmod(WRITEPATH . 'ga4-service-account.json', 0600);
        } else {
            // File already on disk — backfill DB backup if missing.
            $existing = (string) file_get_contents(WRITEPATH . 'ga4-service-account.json');
            if ($existing !== '' && (string) $settings->getValue(GoogleAnalytics::SETTING_CREDENTIALS, '') === '') {
                $settings->setValue(GoogleAnalytics::SETTING_CREDENTIALS, $existing);
            }
        }

        $this->syncEnv($propertyId, $streamId);

        return redirect()->to(site_url('acp/website-analytics'))->with('success', 'Google Analytics connected. Figures will load from GA4.');
    }

    private function syncEnv(string $propertyId, string $streamId): void
    {
        $envPath = ROOTPATH . '.env';
        if (! is_file($envPath) || ! is_writable($envPath)) {
            return;
        }
        $text = (string) file_get_contents($envPath);
        $lines = [
            'ga4.propertyId'      => $propertyId,
            'ga4.streamId'        => $streamId,
            'ga4.credentialsFile' => GoogleAnalytics::DEFAULT_CREDENTIALS,
        ];
        foreach ($lines as $key => $value) {
            $pattern = '/^' . preg_quote($key, '/') . '\s*=.*$/m';
            $line    = $key . ' = ' . $value;
            if (preg_match($pattern, $text)) {
                $text = preg_replace($pattern, $line, $text, 1) ?? $text;
            } else {
                $text = rtrim($text) . "\n" . $line . "\n";
            }
        }
        file_put_contents($envPath, $text);
    }
}

