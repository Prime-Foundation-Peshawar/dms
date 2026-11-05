<?php

namespace App\Controllers;

use App\Libraries\CareersLibrray;
use App\Models\SettingModel;

class Careers extends BaseController
{
    public function index()
    {
        $jobs = [];
        $jobsUnavailable = false;
        try {
            $setting = json_decode(
                (string) model(SettingModel::class)->getValue('careers_api', '{}'),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            $client = new CareersLibrray(
                (string) ($setting['CAREERS_API_BASE_URL'] ?? ''),
                (string) ($setting['CAREERS_API_EMAIL'] ?? ''),
                (string) ($setting['CAREERS_API_PASSWORD'] ?? '')
            );
            $jobs = $client->allJobs([1]);
        } catch (\Throwable $e) {
            $jobsUnavailable = true;
            log_message('error', 'Careers page: ' . $e->getMessage());
        }

        return view('pages/careers', [
            'page_title' => 'Careers | Peshawar Dental College',
            'page_description' => 'Explore faculty and staff vacancies at Peshawar Dental College, Riphah International University, Peshawar Campus.',
            'canonical_url' => site_url('careers'),
            'jobs' => $jobs,
            'jobs_unavailable' => $jobsUnavailable,
        ]);
    }
}
