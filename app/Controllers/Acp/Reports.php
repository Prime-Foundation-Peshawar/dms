<?php

namespace App\Controllers\Acp;

use App\Libraries\ReportService;

class Reports extends BaseAcpController
{
    public function index()
    {
        return $this->acpView('reports/index', [
            'title'  => 'Reports',
            'report' => $this->buildReport(),
        ]);
    }

    /** Department data table as CSV for the selected period. */
    public function export()
    {
        $report = $this->buildReport();
        $period = $report['period'];

        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, ['Data from', $period['range_label']]);
        fputcsv($fh, ['Generated', date('Y-m-d H:i')]);
        fputcsv($fh, []);
        $t = $report['totals'];
        fputcsv($fh, ['Departments', $t['departments']]);
        fputcsv($fh, ['Departments with no activity', count($report['missing']['no_activity'])]);
        fputcsv($fh, ['Total activities', $t['activities']]);
        fputcsv($fh, ['Total faculty', $t['faculty']]);
        fputcsv($fh, ['Faculty profiles done', $t['available']]);
        fputcsv($fh, ['Faculty profiles pending', $t['faculty_missing']]);
        fputcsv($fh, []);
        fputcsv($fh, ['Department', 'Group', 'Contribution', 'Total faculty', 'Profiles done', 'Profiles pending', 'Activities', 'Months without activity', 'Last received']);
        foreach ($report['details'] as $d) {
            fputcsv($fh, [
                $d['name'], $d['unit'], ucfirst($d['tier']), $d['faculty'], $d['available'], $d['missing'],
                $d['activities'], $d['months_missing'], $d['last'] !== '' ? date('Y-m-d', strtotime($d['last'])) : '',
            ]);
        }
        fputcsv($fh, []);
        fputcsv($fh, ['Faculty profiles']);
        fputcsv($fh, ['Department', 'Faculty member', 'Profile']);
        foreach ($report['details'] as $d) {
            foreach ($d['done_names'] as $name) {
                fputcsv($fh, [$d['name'], $name, 'Done']);
            }
            foreach ($d['missing_names'] as $name) {
                fputcsv($fh, [$d['name'], $name, 'Pending']);
            }
        }
        fputcsv($fh, []);
        fputcsv($fh, ['Activities received']);
        fputcsv($fh, ['Department', 'Date', 'Activity', 'Status']);
        foreach ($report['details'] as $d) {
            foreach ($d['activity_items'] as $it) {
                fputcsv($fh, [$d['name'], date('Y-m-d', strtotime($it['date'])), $it['item'], $it['status']]);
            }
        }
        rewind($fh);
        $csv = (string) stream_get_contents($fh);
        fclose($fh);

        return $this->response->download('dms-report-' . $period['ref'] . '.csv', "\xEF\xBB\xBF" . $csv)
            ->setContentType('text/csv');
    }

    /** Reads period=month|quarter|year|range with ref, or from/to for a custom range. */
    private function buildReport(): array
    {
        $type = (string) $this->request->getGet('period');
        $ref  = (string) $this->request->getGet('ref');
        if ($type === 'range' && $this->request->getGet('from') !== null) {
            $ref = trim((string) $this->request->getGet('from')) . '_' . trim((string) $this->request->getGet('to'));
        }

        return (new ReportService())->build($type, $ref);
    }
}
