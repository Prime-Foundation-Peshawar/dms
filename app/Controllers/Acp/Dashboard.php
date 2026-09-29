<?php

namespace App\Controllers\Acp;

use App\Controllers\BaseController;
use App\Models\AnalyticsSettingsModel;
use App\Models\CmsPageModel;
use App\Models\EventModel;
use App\Models\FacultyProfileModel;
use App\Models\FacultySubmissionModel;
use App\Models\GalleryAlbumModel;
use App\Models\GalleryImageModel;
use App\Models\HomepageSlideModel;
use App\Models\NewsletterModel;
use App\Models\NewsPostModel;
use App\Models\VacantSeatModel;
use App\Models\WebsiteContributionModel;

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

        $newsPublished = $this->safeCount(static fn () => model(NewsPostModel::class)->where('status', 'published')->countAllResults());
        $eventsPublished = $this->safeCount(static fn () => model(EventModel::class)->where('status', 'published')->countAllResults());
        $pagesPublished = $this->safeCount(static fn () => model(CmsPageModel::class)->where('status', 'published')->countAllResults());
        $slides = $this->safeCount(static fn () => model(HomepageSlideModel::class)->where('is_active', 1)->countAllResults());
        $albums = $this->safeCount(static fn () => model(GalleryAlbumModel::class)->where('is_active', 1)->countAllResults());
        $galleryImages = $this->safeCount(static fn () => model(GalleryImageModel::class)->where('is_active', 1)->countAllResults());
        $newsletters = $this->safeCount(static fn () => model(NewsletterModel::class)->where('is_active', 1)->countAllResults());
        $vacantRows = $this->safeCount(static fn () => model(VacantSeatModel::class)->where('is_active', 1)->countAllResults());

        $month = date('Y-m');
        $contributions = [];
        try {
            $contributions = model(WebsiteContributionModel::class)->forMonth($month);
        } catch (\Throwable $e) {
            $contributions = [];
        }
        $top = array_values(array_filter($contributions, static fn ($r) => ($r['rank_band'] ?? '') === 'top'));
        $low = array_values(array_filter($contributions, static fn ($r) => ($r['rank_band'] ?? '') === 'low'));

        $analytics = [];
        try {
            $analytics = model(AnalyticsSettingsModel::class)->getSettings();
        } catch (\Throwable $e) {
            $analytics = [];
        }

        $kpiTarget = 2; // MoM: ≥2 approved updates / month
        $unitsMeetingKpi = 0;
        foreach ($contributions as $c) {
            if ((int) ($c['approved'] ?? 0) >= $kpiTarget) {
                $unitsMeetingKpi++;
            }
        }
        $kpiRate = count($contributions) > 0
            ? (int) round(($unitsMeetingKpi / count($contributions)) * 100)
            : 0;

        $upcomingEvents = [];
        try {
            $upcomingEvents = model(EventModel::class)
                ->where('status', 'published')
                ->where('event_date >=', date('Y-m-d'))
                ->orderBy('event_date', 'ASC')
                ->findAll(5);
        } catch (\Throwable $e) {
            $upcomingEvents = [];
        }

        return view('acp/dashboard/index', [
            'title'    => 'Dashboard',
            'subtitle' => 'Website Cell command center — content health, KPIs, and weekly review cues from the RCP website management protocol.',
            'nav'      => 'dashboard',
            'pending'  => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'live'     => $live,
            'recent'   => $recent,
            'contentStats' => [
                ['label' => 'News published', 'value' => $newsPublished, 'icon' => 'bi-newspaper', 'hint' => 'Live'],
                ['label' => 'Events published', 'value' => $eventsPublished, 'icon' => 'bi-calendar-event', 'hint' => 'Live'],
                ['label' => 'CMS pages', 'value' => $pagesPublished, 'icon' => 'bi-file-richtext', 'hint' => 'Live'],
                ['label' => 'Gallery images', 'value' => $galleryImages, 'icon' => 'bi-camera', 'hint' => $albums . ' albums'],
                ['label' => 'Slides active', 'value' => $slides, 'icon' => 'bi-images', 'hint' => 'Home'],
                ['label' => 'Newsletters', 'value' => $newsletters, 'icon' => 'bi-file-earmark-pdf', 'hint' => 'Archive'],
                ['label' => 'Vacant seat rows', 'value' => $vacantRows, 'icon' => 'bi-person-bounding-box', 'hint' => 'Migration'],
                ['label' => 'Faculty live', 'value' => $live, 'icon' => 'bi-people', 'hint' => 'Profiles'],
            ],
            'analytics' => $analytics,
            'topContributors' => array_slice($top, 0, 5),
            'lowContributors' => array_slice($low, 0, 5),
            'kpiRate' => $kpiRate,
            'unitsMeetingKpi' => $unitsMeetingKpi,
            'unitsTotal' => count($contributions),
            'monthKey' => $month,
            'upcomingEvents' => $upcomingEvents,
            'modules' => [
                ['key' => 'analytics', 'label' => 'Analytics · GA', 'status' => 'live', 'href' => site_url('acp/analytics'), 'icon' => 'bi-graph-up-arrow'],
                ['key' => 'reports', 'label' => 'Reports & KPIs', 'status' => 'live', 'href' => site_url('acp/reports'), 'icon' => 'bi-clipboard2-data'],
                ['key' => 'faculty', 'label' => 'Faculty profiles', 'status' => 'live', 'href' => site_url('acp/faculty/profiles'), 'icon' => 'bi-people'],
                ['key' => 'submissions', 'label' => 'Profile submissions', 'status' => 'live', 'href' => site_url('acp/faculty/submissions'), 'icon' => 'bi-inbox'],
                ['key' => 'news', 'label' => 'News', 'status' => 'live', 'href' => site_url('acp/news'), 'icon' => 'bi-newspaper'],
                ['key' => 'events', 'label' => 'Events', 'status' => 'live', 'href' => site_url('acp/events'), 'icon' => 'bi-calendar-event'],
                ['key' => 'slider', 'label' => 'Homepage slider', 'status' => 'live', 'href' => site_url('acp/slider'), 'icon' => 'bi-images'],
                ['key' => 'gallery', 'label' => 'Gallery', 'status' => 'live', 'href' => site_url('acp/gallery'), 'icon' => 'bi-camera'],
                ['key' => 'departments', 'label' => 'Departments', 'status' => 'live', 'href' => site_url('acp/departments'), 'icon' => 'bi-building'],
                ['key' => 'vacant', 'label' => 'Vacant seats', 'status' => 'live', 'href' => site_url('acp/vacant-seats'), 'icon' => 'bi-person-bounding-box'],
                ['key' => 'newsletters', 'label' => 'Newsletters', 'status' => 'live', 'href' => site_url('acp/newsletters'), 'icon' => 'bi-file-earmark-pdf'],
                ['key' => 'pages', 'label' => 'Pages', 'status' => 'live', 'href' => site_url('acp/pages'), 'icon' => 'bi-file-richtext'],
            ],
        ]);
    }

    protected function safeCount(callable $fn): int
    {
        try {
            return (int) $fn();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
