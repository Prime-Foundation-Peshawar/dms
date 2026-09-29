<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Libraries\RcpWebsiteProtocol;
use App\Models\EventModel;
use App\Models\FacultySubmissionModel;
use App\Models\WebsiteActivityLogModel;
use App\Models\WebsiteContributionModel;
use App\Models\WebsiteExpectedUnitModel;
use App\Models\WebsiteIntegrityCheckModel;
use App\Models\WebsiteSuggestionModel;

class Reports extends BaseController
{
    public function index()
    {
        $month = trim((string) $this->request->getGet('month')) ?: date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }
        $tab = trim((string) $this->request->getGet('tab')) ?: 'overview';

        $expected = [];
        try {
            $expected = model(WebsiteExpectedUnitModel::class)->activeOrdered();
        } catch (\Throwable $e) {
            $expected = RcpWebsiteProtocol::expectedRoster();
        }
        if ($expected === []) {
            $expected = RcpWebsiteProtocol::expectedRoster();
        }

        $rows = model(WebsiteContributionModel::class)->forMonth($month);
        $top = [];
        $avg = [];
        $low = [];
        $missingBand = [];
        foreach ($rows as $r) {
            $band = (string) ($r['rank_band'] ?? 'average');
            if ($band === 'top') {
                $top[] = $r;
            } elseif ($band === 'low') {
                $low[] = $r;
            } elseif ($band === 'missing') {
                $missingBand[] = $r;
            } else {
                $avg[] = $r;
            }
        }

        $missing = RcpWebsiteProtocol::missingUnits($expected, $rows);

        $kpiTarget = RcpWebsiteProtocol::KPI_APPROVED_PER_MONTH;
        $meeting = 0;
        $onTimeTotal = 0;
        $approvedTotal = 0;
        $submissionsTotal = 0;
        $belowKpi = [];
        foreach ($rows as $r) {
            $approvedTotal += (int) ($r['approved'] ?? 0);
            $submissionsTotal += (int) ($r['submissions'] ?? 0);
            $onTimeTotal += (int) ($r['on_time'] ?? 0);
            $approved = (int) ($r['approved'] ?? 0);
            if ($approved >= $kpiTarget) {
                $meeting++;
            } else {
                $belowKpi[] = $r;
            }
        }

        $history = [];
        try {
            $from = date('Y-m', strtotime($month . '-01 -2 months'));
            $history = model(WebsiteContributionModel::class)
                ->where('month_key >=', $from)
                ->where('month_key <=', $month)
                ->findAll();
        } catch (\Throwable $e) {
            $history = $rows;
        }
        $escalations = RcpWebsiteProtocol::consecutiveFailures($history, $month);

        $pendingFaculty = 0;
        try {
            $pendingFaculty = model(FacultySubmissionModel::class)->countByStatus('pending');
        } catch (\Throwable $e) {
        }

        $upcoming = [];
        try {
            $upcoming = model(EventModel::class)
                ->where('status', 'published')
                ->where('event_date >=', date('Y-m-d'))
                ->orderBy('event_date', 'ASC')
                ->findAll(6);
        } catch (\Throwable $e) {
        }

        $activities = [];
        $integrity = [];
        $suggestions = [];
        try {
            $activities = model(WebsiteActivityLogModel::class)->forMonth($month);
        } catch (\Throwable $e) {
        }
        try {
            $integrity = model(WebsiteIntegrityCheckModel::class)->recent(10);
        } catch (\Throwable $e) {
        }
        try {
            $suggestions = model(WebsiteSuggestionModel::class)->forMonth($month);
        } catch (\Throwable $e) {
        }

        $slaBreaches = 0;
        foreach ($activities as $a) {
            $hours = (int) ($a['turnaround_hours'] ?? 0);
            if ($hours > (RcpWebsiteProtocol::APPROVAL_SLA_DAYS * 24)) {
                $slaBreaches++;
            }
        }

        $bestHospital = null;
        $bestSociety = null;
        $bestIndividual = null;
        foreach ($rows as $r) {
            $type = (string) ($r['unit_type'] ?? '');
            if ($type === 'hospital' && (int) ($r['approved'] ?? 0) > 0 && ($bestHospital === null || (int) $r['approved'] > (int) $bestHospital['approved'])) {
                $bestHospital = $r;
            }
            if ($type === 'society' && (int) ($r['approved'] ?? 0) > 0 && ($bestSociety === null || (int) $r['approved'] > (int) $bestSociety['approved'])) {
                $bestSociety = $r;
            }
            if ($type === 'individual' && ($bestIndividual === null || (float) ($r['quality_score'] ?? 0) > (float) ($bestIndividual['quality_score'] ?? 0))) {
                $bestIndividual = $r;
            }
        }

        return view('acp/reports/index', [
            'title'      => 'Reports & KPIs',
            'subtitle'   => 'Full RCP Website Management protocol dashboard — missing units, KPI failures, escalations, reception log, integrity, and awards.',
            'nav'        => 'reports',
            'tab'        => $tab,
            'monthKey'   => $month,
            'expected'   => $expected,
            'rows'       => $rows,
            'top'        => $top,
            'avg'        => $avg,
            'low'        => $low,
            'missingBand'=> $missingBand,
            'missing'    => $missing,
            'belowKpi'   => $belowKpi,
            'escalations'=> $escalations,
            'kpiTarget'  => $kpiTarget,
            'meetingKpi' => $meeting,
            'unitsTotal' => max(count($expected), count($rows)),
            'expectedTotal' => count($expected),
            'approvedTotal' => $approvedTotal,
            'submissionsTotal' => $submissionsTotal,
            'onTimeTotal' => $onTimeTotal,
            'pendingFaculty' => $pendingFaculty,
            'upcoming'   => $upcoming,
            'activities' => $activities,
            'integrity'  => $integrity,
            'suggestions'=> $suggestions,
            'slaBreaches'=> $slaBreaches,
            'slaDays'    => RcpWebsiteProtocol::APPROVAL_SLA_DAYS,
            'bestHospital' => $bestHospital,
            'bestSociety' => $bestSociety,
            'bestIndividual' => $bestIndividual,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function saveContribution(?int $id = null)
    {
        $model = model(WebsiteContributionModel::class);
        $month = trim((string) $this->request->getPost('month_key')) ?: date('Y-m');
        $subs = (int) $this->request->getPost('submissions');
        $approved = (int) $this->request->getPost('approved');
        $band = (string) $this->request->getPost('rank_band');
        if (!in_array($band, ['top', 'average', 'low', 'missing'], true)) {
            $band = RcpWebsiteProtocol::autoBand($subs, $approved);
        }

        $payload = [
            'month_key'     => $month,
            'unit_name'     => trim((string) $this->request->getPost('unit_name')) ?: 'Untitled unit',
            'unit_type'     => trim((string) $this->request->getPost('unit_type')) ?: 'unit',
            'submissions'   => $subs,
            'approved'      => $approved,
            'on_time'       => (int) $this->request->getPost('on_time'),
            'quality_score' => (float) $this->request->getPost('quality_score'),
            'rank_band'     => $band,
            'notes'         => trim((string) $this->request->getPost('notes')) ?: null,
        ];

        if ($id) {
            $existing = $model->find($id);
            if (!is_array($existing)) {
                return redirect()->to(site_url('acp/reports'))->with('error', 'Contribution not found.');
            }
            $model->update($id, $payload);
        } else {
            $model->insert($payload);
        }

        return redirect()->to(site_url('acp/reports?month=' . rawurlencode($month) . '&tab=ledger'))->with('ok', 'Contribution saved.');
    }

    public function saveActivity()
    {
        $month = trim((string) $this->request->getPost('month_key')) ?: date('Y-m');
        $received = trim((string) $this->request->getPost('received_at')) ?: date('Y-m-d H:i:s');
        $published = trim((string) $this->request->getPost('published_at')) ?: null;
        $hours = (int) $this->request->getPost('turnaround_hours');
        if ($hours <= 0 && $published) {
            $hours = (int) max(0, (strtotime($published) - strtotime($received)) / 3600);
        }

        model(WebsiteActivityLogModel::class)->insert([
            'month_key'         => $month,
            'unit_name'         => trim((string) $this->request->getPost('unit_name')) ?: 'Unknown',
            'activity_title'    => trim((string) $this->request->getPost('activity_title')) ?: 'Untitled activity',
            'received_at'       => $received,
            'approved_at'       => trim((string) $this->request->getPost('approved_at')) ?: null,
            'published_at'      => $published,
            'turnaround_hours'  => $hours ?: null,
            'status'            => trim((string) $this->request->getPost('status')) ?: 'received',
            'shared_social'     => $this->request->getPost('shared_social') ? 1 : 0,
            'notes'             => trim((string) $this->request->getPost('notes')) ?: null,
        ]);

        return redirect()->to(site_url('acp/reports?month=' . rawurlencode($month) . '&tab=reception'))->with('ok', 'Activity reception logged.');
    }

    public function saveIntegrity()
    {
        model(WebsiteIntegrityCheckModel::class)->insert([
            'check_date' => trim((string) $this->request->getPost('check_date')) ?: date('Y-m-d'),
            'check_type' => $this->request->getPost('check_type') === 'weekly' ? 'weekly' : 'daily',
            'status'     => in_array($this->request->getPost('status'), ['clear', 'issue'], true) ? $this->request->getPost('status') : 'clear',
            'checked_by' => trim((string) $this->request->getPost('checked_by')) ?: (session('cms_user_name') ?: 'Website Manager'),
            'notes'      => trim((string) $this->request->getPost('notes')) ?: null,
        ]);

        return redirect()->to(site_url('acp/reports?tab=integrity'))->with('ok', 'Integrity check recorded.');
    }

    public function saveSuggestion()
    {
        $month = trim((string) $this->request->getPost('month_key')) ?: date('Y-m');
        model(WebsiteSuggestionModel::class)->insert([
            'month_key'  => $month,
            'suggestion' => trim((string) $this->request->getPost('suggestion')) ?: 'Untitled suggestion',
            'source'     => trim((string) $this->request->getPost('source')) ?: 'Website Cell',
            'status'     => 'open',
        ]);

        return redirect()->to(site_url('acp/reports?month=' . rawurlencode($month) . '&tab=suggestions'))->with('ok', 'Suggestion added.');
    }
}
