<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\FacultySubmissionModel;
use App\Models\WebsiteContributionModel;

class Reports extends BaseController
{
    public function index()
    {
        $month = trim((string) $this->request->getGet('month')) ?: date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $rows = model(WebsiteContributionModel::class)->forMonth($month);
        $top = [];
        $avg = [];
        $low = [];
        foreach ($rows as $r) {
            $band = (string) ($r['rank_band'] ?? 'average');
            if ($band === 'top') {
                $top[] = $r;
            } elseif ($band === 'low') {
                $low[] = $r;
            } else {
                $avg[] = $r;
            }
        }

        $kpiTarget = 2;
        $meeting = 0;
        $onTimeTotal = 0;
        $approvedTotal = 0;
        $submissionsTotal = 0;
        foreach ($rows as $r) {
            $approvedTotal += (int) ($r['approved'] ?? 0);
            $submissionsTotal += (int) ($r['submissions'] ?? 0);
            $onTimeTotal += (int) ($r['on_time'] ?? 0);
            if ((int) ($r['approved'] ?? 0) >= $kpiTarget) {
                $meeting++;
            }
        }

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

        return view('acp/reports/index', [
            'title'      => 'Reports & KPIs',
            'subtitle'   => 'Monthly Website Cell dashboard from the RCP Website Management protocol (6 Aug 2026) — contributors, KPIs, and weekly review.',
            'nav'        => 'reports',
            'monthKey'   => $month,
            'rows'       => $rows,
            'top'        => $top,
            'avg'        => $avg,
            'low'        => $low,
            'kpiTarget'  => $kpiTarget,
            'meetingKpi' => $meeting,
            'unitsTotal' => count($rows),
            'approvedTotal' => $approvedTotal,
            'submissionsTotal' => $submissionsTotal,
            'onTimeTotal' => $onTimeTotal,
            'pendingFaculty' => $pendingFaculty,
            'upcoming'   => $upcoming,
            'flashOk'    => session()->getFlashdata('ok'),
            'flashError' => session()->getFlashdata('error'),
        ]);
    }

    public function saveContribution(?int $id = null)
    {
        $model = model(WebsiteContributionModel::class);
        $month = trim((string) $this->request->getPost('month_key')) ?: date('Y-m');
        $approved = (int) $this->request->getPost('approved');
        $band = (string) $this->request->getPost('rank_band');
        if (!in_array($band, ['top', 'average', 'low'], true)) {
            $band = $approved >= 4 ? 'top' : ($approved <= 1 ? 'low' : 'average');
        }

        $payload = [
            'month_key'     => $month,
            'unit_name'     => trim((string) $this->request->getPost('unit_name')) ?: 'Untitled unit',
            'unit_type'     => trim((string) $this->request->getPost('unit_type')) ?: 'unit',
            'submissions'   => (int) $this->request->getPost('submissions'),
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

        return redirect()->to(site_url('acp/reports?month=' . rawurlencode($month)))->with('ok', 'Contribution saved.');
    }
}
