<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Models\FacultyProfileModel;
use App\Models\FacultySubmissionModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $subs = model(FacultySubmissionModel::class);
        $profiles = model(FacultyProfileModel::class);

        $pending = $subs->countByStatus('pending');
        $approved = $subs->countByStatus('approved');
        $rejected = $subs->countByStatus('rejected');
        $live = $profiles->countAllResults();
        $recent = $subs->listByStatus('pending', 8);

        return view('acp/dashboard/index', [
            'title'   => 'Dashboard',
            'nav'     => 'dashboard',
            'pending' => $pending,
            'approved'=> $approved,
            'rejected'=> $rejected,
            'live'    => $live,
            'recent'  => $recent,
            'modules' => [
                ['key' => 'faculty', 'label' => 'Faculty profiles', 'status' => 'live', 'href' => site_url('acp/faculty/profiles')],
                ['key' => 'submissions', 'label' => 'Profile submissions', 'status' => 'live', 'href' => site_url('acp/faculty/submissions')],
                ['key' => 'news', 'label' => 'News', 'status' => 'live', 'href' => site_url('acp/news')],
                ['key' => 'events', 'label' => 'Events', 'status' => 'live', 'href' => site_url('acp/events')],
                ['key' => 'slider', 'label' => 'Homepage slider', 'status' => 'live', 'href' => site_url('acp/slider')],
                ['key' => 'gallery', 'label' => 'Gallery', 'status' => 'live', 'href' => site_url('acp/gallery')],
                ['key' => 'departments', 'label' => 'Departments', 'status' => 'live', 'href' => site_url('acp/departments')],
                ['key' => 'vacant', 'label' => 'Vacant seats', 'status' => 'live', 'href' => site_url('acp/vacant-seats')],
                ['key' => 'newsletters', 'label' => 'Newsletters', 'status' => 'live', 'href' => site_url('acp/newsletters')],
                ['key' => 'pages', 'label' => 'Pages', 'status' => 'soon', 'href' => site_url('acp/modules/pages')],
            ],
        ]);
    }
}
