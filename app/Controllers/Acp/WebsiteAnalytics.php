<?php

namespace App\Controllers\Acp;

use App\Libraries\GoogleAnalytics;
use App\Libraries\ReportService;

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
            'title'     => 'Website Analytics',
            'period'    => $period,
            'options'   => $service->periodOptions(),
            'connected' => $ready,
            'data'      => $ready ? $ga->report($period) : ['ok' => false],
            'liveNow'   => $ready ? $ga->realtime() : null,
            'published' => $ready ? $service->publishedCounts($period) : [],
        ]);
    }
}
