<?php

namespace App\Controllers\Acp;

use App\Models\DepartmentActivityModel;
use App\Models\DepartmentModel;
use App\Models\EventModel;
use App\Models\FacultyProfileModel;
use App\Models\NewsPostModel;
use App\Models\PageModel;
use App\Models\UserModel;

class Dashboard extends BaseAcpController
{
    public function index()
    {
        $db = db_connect();
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        $pagesTotal = model(PageModel::class)->countAllResults(false);
        $pagesPublished = model(PageModel::class)->where('status', 'published')->countAllResults();
        $pagesDraft = model(PageModel::class)->where('status', 'draft')->countAllResults();
        $pagesCms = model(PageModel::class)->where('use_cms', 1)->countAllResults();

        $newsTotal = model(NewsPostModel::class)->countAllResults(false);
        $newsPublished = model(NewsPostModel::class)->where('status', 'published')->countAllResults();
        $newsDraft = model(NewsPostModel::class)->where('status', 'draft')->countAllResults();
        $newsExpired = (int) ($db->table('news_posts')
            ->where('expires_at IS NOT NULL')
            ->where('expires_at !=', '')
            ->where('expires_at <', $now)
            ->countAllResults());

        $eventsTotal = model(EventModel::class)->countAllResults(false);
        $eventsPublished = model(EventModel::class)->where('status', 'published')->countAllResults();
        $eventsDraft = model(EventModel::class)->where('status', 'draft')->countAllResults();
        $eventsUpcoming = (int) ($db->table('events')
            ->where('status', 'published')
            ->where('event_date >=', $today)
            ->countAllResults());

        $deptsTotal = model(DepartmentModel::class)->countAllResults(false);
        $deptsHidden = model(DepartmentModel::class)->where('hidden', 1)->countAllResults();

        $facultyTotal = model(FacultyProfileModel::class)->countAllResults(false);
        $hasPublic = $db->fieldExists('is_public', 'faculty_profiles');
        $hasOverride = $db->fieldExists('dept_override', 'faculty_profiles');
        $hasEmpId = $db->fieldExists('emp_id', 'faculty_profiles');
        $hasDeptName = $db->fieldExists('department_name', 'faculty_profiles');
        $facultyPublic = $hasPublic
            ? model(FacultyProfileModel::class)->where('is_public', 1)->countAllResults()
            : $facultyTotal;
        $facultyPhoto = (int) ($db->query(
            "SELECT COUNT(*) AS c FROM faculty_profiles WHERE photo IS NOT NULL AND TRIM(photo) != ''"
        )->getRowArray()['c'] ?? 0);
        $facultyOverride = $hasOverride
            ? model(FacultyProfileModel::class)->where('dept_override', 1)->countAllResults()
            : 0;
        $facultyMissingPhoto = (int) ($db->query(
            "SELECT COUNT(*) AS c FROM faculty_profiles WHERE photo IS NULL OR TRIM(photo) = ''"
        )->getRowArray()['c'] ?? 0);
        $facultyMissingEmp = $hasEmpId
            ? (int) ($db->query(
                "SELECT COUNT(*) AS c FROM faculty_profiles WHERE emp_id IS NULL OR TRIM(emp_id) = ''"
            )->getRowArray()['c'] ?? 0)
            : $facultyTotal;
        $facultyLinked = max(0, $facultyTotal - $facultyMissingEmp);
        $facultyComplete = $hasEmpId
            ? (int) ($db->query(
                "SELECT COUNT(*) AS c FROM faculty_profiles
                 WHERE photo IS NOT NULL AND TRIM(photo) != ''
                   AND emp_id IS NOT NULL AND TRIM(emp_id) != ''"
            )->getRowArray()['c'] ?? 0)
            : 0;
        $facultyNeedsPhoto = $hasEmpId
            ? (int) ($db->query(
                "SELECT COUNT(*) AS c FROM faculty_profiles
                 WHERE (photo IS NULL OR TRIM(photo) = '')
                   AND emp_id IS NOT NULL AND TRIM(emp_id) != ''"
            )->getRowArray()['c'] ?? 0)
            : $facultyMissingPhoto;

        $activities = model(DepartmentActivityModel::class)->countAllResults();
        $users = model(UserModel::class)->countAllResults();

        $deptGroups = $db->table('departments')
            ->select('group_name, COUNT(*) as c')
            ->groupBy('group_name')
            ->orderBy('c', 'DESC')
            ->get()->getResultArray();

        $facultyByDept = $hasDeptName
            ? $db->table('faculty_profiles')
                ->select("COALESCE(NULLIF(department_name,''), 'Unassigned') as dept, COUNT(*) as c")
                ->groupBy('dept')
                ->orderBy('c', 'DESC')
                ->limit(8)
                ->get()->getResultArray()
            : [];

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[] = date('Y-m', strtotime("-{$i} months"));
        }
        $newsByMonth = array_fill_keys($months, 0);
        $eventsByMonth = array_fill_keys($months, 0);

        foreach ($db->table('news_posts')->select('published_at, created_at')->get()->getResultArray() as $r) {
            $ym = substr((string) (($r['published_at'] ?? '') !== '' ? $r['published_at'] : ($r['created_at'] ?? '')), 0, 7);
            if (isset($newsByMonth[$ym])) {
                $newsByMonth[$ym]++;
            }
        }

        foreach ($db->table('events')->select('event_date, created_at')->get()->getResultArray() as $r) {
            $ym = substr((string) (($r['event_date'] ?? '') !== '' ? $r['event_date'] : ($r['created_at'] ?? '')), 0, 7);
            if (isset($eventsByMonth[$ym])) {
                $eventsByMonth[$ym]++;
            }
        }

        $recentNews = model(NewsPostModel::class)->orderBy('published_at', 'DESC')->findAll(6);
        $upcomingEvents = $db->table('events')
            ->where('event_date >=', $today)
            ->orderBy('event_date', 'ASC')
            ->limit(6)
            ->get()->getResultArray();
        if (count($upcomingEvents) < 3) {
            $upcomingEvents = model(EventModel::class)->orderBy('event_date', 'DESC')->findAll(6);
        }

        $empSelect = $hasEmpId ? 'emp_id' : 'NULL AS emp_id';
        $deptSelect = $hasDeptName ? 'department_name' : "'' AS department_name";
        $attentionMissingPhoto = $db->query(
            "SELECT id, name, {$empSelect}, photo, {$deptSelect}
             FROM faculty_profiles
             WHERE photo IS NULL OR TRIM(photo) = ''
             ORDER BY name ASC
             LIMIT 10"
        )->getResultArray();

        $attentionCvOnly = $hasEmpId
            ? $db->query(
                "SELECT id, name, emp_id, photo, {$deptSelect}
                 FROM faculty_profiles
                 WHERE emp_id IS NULL OR TRIM(emp_id) = ''
                 ORDER BY name ASC
                 LIMIT 8"
            )->getResultArray()
            : [];

        $draftNews = model(NewsPostModel::class)
            ->where('status', 'draft')
            ->orderBy('updated_at', 'DESC')
            ->findAll(4);

        return $this->acpView('dashboard', [
            'title' => 'Dashboard',
            'kpis'  => [
                [
                    'key'   => 'pages',
                    'label' => 'Pages',
                    'icon'  => 'bi-file-earmark-text',
                    'total' => $pagesTotal,
                    'sub'   => $pagesPublished . ' published · ' . $pagesDraft . ' draft',
                    'href'  => 'acp/pages',
                    'tone'  => 'teal',
                ],
                [
                    'key'   => 'news',
                    'label' => 'News',
                    'icon'  => 'bi-newspaper',
                    'total' => $newsTotal,
                    'sub'   => $newsPublished . ' live · ' . $newsDraft . ' draft',
                    'href'  => 'acp/news',
                    'tone'  => 'navy',
                ],
                [
                    'key'   => 'events',
                    'label' => 'Events',
                    'icon'  => 'bi-calendar-event',
                    'total' => $eventsTotal,
                    'sub'   => $eventsUpcoming . ' upcoming · ' . $eventsDraft . ' draft',
                    'href'  => 'acp/events',
                    'tone'  => 'gold',
                ],
                [
                    'key'   => 'departments',
                    'label' => 'Departments',
                    'icon'  => 'bi-building',
                    'total' => $deptsTotal,
                    'sub'   => ($deptsTotal - $deptsHidden) . ' visible · ' . $deptsHidden . ' hidden',
                    'href'  => 'acp/departments',
                    'tone'  => 'teal',
                ],
                [
                    'key'   => 'faculty',
                    'label' => 'Faculty',
                    'icon'  => 'bi-people',
                    'total' => $facultyTotal,
                    'sub'   => $facultyLinked . ' HRMS-linked · ' . $facultyMissingEmp . ' CV-only',
                    'href'  => 'acp/faculty',
                    'tone'  => 'navy',
                ],
                [
                    'key'   => 'photos',
                    'label' => 'Photos',
                    'icon'  => 'bi-image',
                    'total' => $facultyPhoto,
                    'sub'   => $facultyMissingPhoto . ' missing · ' . $facultyOverride . ' overrides',
                    'href'  => 'acp/faculty',
                    'tone'  => 'gold',
                ],
                [
                    'key'   => 'activities',
                    'label' => 'Activities',
                    'icon'  => 'bi-list-check',
                    'total' => $activities,
                    'sub'   => 'Department activities',
                    'href'  => 'acp/departments',
                    'tone'  => 'teal',
                ],
                [
                    'key'   => 'users',
                    'label' => 'Users',
                    'icon'  => 'bi-person-gear',
                    'total' => $users,
                    'sub'   => 'ACP accounts',
                    'href'  => 'acp/users',
                    'tone'  => 'navy',
                ],
            ],
            'charts' => [
                'newsStatus' => [
                    'labels' => ['Published', 'Draft', 'Expired'],
                    'data'   => [$newsPublished, $newsDraft, $newsExpired],
                ],
                'facultyHealth' => [
                    'labels' => ['Complete', 'Needs photo', 'CV-only'],
                    'data'   => [
                        $facultyComplete,
                        $facultyNeedsPhoto,
                        $facultyMissingEmp,
                    ],
                ],
                'deptGroups' => [
                    'labels' => array_map(static fn ($r) => $r['group_name'] ?: 'Other', $deptGroups),
                    'data'   => array_map(static fn ($r) => (int) $r['c'], $deptGroups),
                ],
                'facultyDepts' => [
                    'labels' => array_map(static fn ($r) => $r['dept'], $facultyByDept),
                    'data'   => array_map(static fn ($r) => (int) $r['c'], $facultyByDept),
                ],
                'months' => [
                    'labels' => array_map(static fn ($m) => date('M Y', strtotime($m . '-01')), $months),
                    'news'   => array_values($newsByMonth),
                    'events' => array_values($eventsByMonth),
                ],
                'pagesSource' => [
                    'labels' => ['CMS', 'Template'],
                    'data'   => [$pagesCms, max(0, $pagesTotal - $pagesCms)],
                ],
            ],
            'stats' => [
                'facultyMissingPhoto' => $facultyMissingPhoto,
                'facultyMissingEmp'   => $facultyMissingEmp,
                'facultyComplete'     => $facultyComplete,
                'newsDraft'           => $newsDraft,
                'eventsUpcoming'      => $eventsUpcoming,
            ],
            'recentNews'            => $recentNews,
            'upcomingEvents'        => $upcomingEvents,
            'attentionMissingPhoto' => $attentionMissingPhoto,
            'attentionCvOnly'       => $attentionCvOnly,
            'draftNews'             => $draftNews,
        ]);
    }
}
