<?php

namespace App\Libraries;

use App\Models\DepartmentDataLogModel;
use App\Models\FacultySubmissionModel;
use App\Models\NewsPostModel;
use App\Services\FacultyDirectoryService;

/**
 * Builds the monthly / quarterly / annual / custom-range report used by the ACP dashboard and Reports page.
 *
 * Everything is a plain count per department, and only faculty profiles and activities are reported:
 *   - activities         department_activities rows created (ACP-added or approved submissions)
 *   - pending activities public activity forms still waiting for review (approved ones are already activities)
 *   - faculty profiles   update forms + profiles saved in the ACP + profiles with CV details saved in the period
 *
 * Contributions = activities + pending activities + faculty profiles. Tiers compare each visible department
 * with the average number of contributions of all visible departments.
 *
 * Faculty totals are today's position, not limited to the period: the department's people come from HRMS plus
 * public profiles, and a profile is "available" once it has CV details (experience, publications or skills).
 */
class ReportService
{
    /** False when the HRMS list could not be read, so totals fall back to public profiles only. */
    private bool $hrmsOk = true;

    /**
     * @param string $ref month "Y-m", quarter "Y-Qn", year "Y", range "Y-m-d_Y-m-d"
     * @return array{type:string,ref:string,label:string,start:string,end:string,prev_start:string,prev_end:string,prev_label:string,days:int,last_day:string,months:int,range_label:string,prev_last:string}
     */
    public function resolvePeriod(string $type, string $ref = ''): array
    {
        $type = in_array($type, ['month', 'quarter', 'year', 'range'], true) ? $type : 'month';
        $now  = time();

        if ($type === 'range') {
            [$from, $to] = array_pad(explode('_', $ref, 2), 2, '');
            $valid = static fn (string $d) => (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;
            if (! $valid($from) || ! $valid($to)) {
                [$from, $to] = [date('Y-m-01', $now), date('Y-m-d', $now)];
            }
            if ($from > $to) {
                [$from, $to] = [$to, $from];
            }
            $end   = date('Y-m-d', strtotime($to . ' +1 day'));
            $days  = (int) round((strtotime($end) - strtotime($from)) / 86400);
            $prev  = date('Y-m-d', strtotime($from . " -{$days} days"));
            $label = date('d M Y', strtotime($from)) . ' – ' . date('d M Y', strtotime($to));
            $prevLabel = date('d M Y', strtotime($prev)) . ' – ' . date('d M Y', strtotime($from . ' -1 day'));

            return $this->periodPack($type, "{$from}_{$to}", $label, $from, $end, $prev, $from, $prevLabel);
        }

        if ($type === 'year') {
            $y     = preg_match('/^\d{4}$/', $ref) ? (int) $ref : (int) date('Y', $now);
            $start = sprintf('%04d-01-01', $y);
            $end   = sprintf('%04d-01-01', $y + 1);
            $prev  = sprintf('%04d-01-01', $y - 1);

            return $this->periodPack($type, (string) $y, (string) $y, $start, $end, $prev, $start, (string) ($y - 1));
        }

        if ($type === 'quarter') {
            if (preg_match('/^(\d{4})-Q([1-4])$/', $ref, $m)) {
                [$y, $q] = [(int) $m[1], (int) $m[2]];
            } else {
                [$y, $q] = [(int) date('Y', $now), (int) ceil((int) date('n', $now) / 3)];
            }
            $start = sprintf('%04d-%02d-01', $y, ($q - 1) * 3 + 1);
            $end   = date('Y-m-d', strtotime($start . ' +3 months'));
            $prev  = date('Y-m-d', strtotime($start . ' -3 months'));
            $pq    = $q === 1 ? 4 : $q - 1;
            $py    = $q === 1 ? $y - 1 : $y;

            return $this->periodPack($type, "{$y}-Q{$q}", "Q{$q} {$y}", $start, $end, $prev, $start, "Q{$pq} {$py}");
        }

        $ym    = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $ref) ? $ref : date('Y-m', $now);
        $start = $ym . '-01';
        $end   = date('Y-m-d', strtotime($start . ' +1 month'));
        $prev  = date('Y-m-d', strtotime($start . ' -1 month'));

        return $this->periodPack($type, $ym, date('F Y', strtotime($start)), $start, $end, $prev, $start, date('F Y', strtotime($prev)));
    }

    private function periodPack(string $type, string $ref, string $label, string $start, string $end, string $prevStart, string $prevEnd, string $prevLabel): array
    {
        // Data can only exist up to today, so the current month / quarter / year stops there.
        $lastDay = max($start, min(date('Y-m-d'), date('Y-m-d', strtotime($end . ' -1 day'))));
        $soFar   = (int) round((strtotime($lastDay) - strtotime($start)) / 86400);

        return [
            'type'        => $type,
            'ref'         => $ref,
            'label'       => $label,
            'start'       => $start,
            'end'         => $end,
            'prev_start'  => $prevStart,
            'prev_end'    => $prevEnd,
            'prev_label'  => $prevLabel,
            'days'        => (int) round((strtotime($end) - strtotime($start)) / 86400),
            'last_day'    => $lastDay,
            'months'      => (((int) substr($lastDay, 0, 4) - (int) substr($start, 0, 4)) * 12) + (int) substr($lastDay, 5, 2) - (int) substr($start, 5, 2) + 1,
            'range_label' => $this->rangeLabel($type, $start, $lastDay),
            // Same length as the part of the period so far, so a part-year is not compared with a full year.
            'prev_last'   => min(date('Y-m-d', strtotime($prevEnd . ' -1 day')), date('Y-m-d', strtotime($prevStart . " +{$soFar} days"))),
        ];
    }

    /** "January 2026 to October 2026" for quarters and years, exact days for months and custom ranges. */
    private function rangeLabel(string $type, string $start, string $lastDay): string
    {
        if ($type === 'month' || $type === 'range') {
            return date('j F Y', strtotime($start)) . ' to ' . date('j F Y', strtotime($lastDay));
        }
        $from = date('F Y', strtotime($start));
        $to   = date('F Y', strtotime($lastDay));

        return $from === $to ? $from : "{$from} to {$to}";
    }

    /**
     * Selector options for each period type, newest first.
     *
     * @return array<string, array<string,string>>
     */
    public function periodOptions(): array
    {
        $months = [];
        for ($i = 0; $i < 24; $i++) {
            $ts                       = strtotime(date('Y-m-01') . " -{$i} months");
            $months[date('Y-m', $ts)] = date('F Y', $ts);
        }
        $quarters = [];
        $y        = (int) date('Y');
        $q        = (int) ceil((int) date('n') / 3);
        for ($i = 0; $i < 8; $i++) {
            $quarters["{$y}-Q{$q}"] = "Q{$q} {$y}";
            if (--$q === 0) {
                $q = 4;
                $y--;
            }
        }
        $years = [];
        for ($i = 0; $i < 5; $i++) {
            $years[(string) (date('Y') - $i)] = (string) (date('Y') - $i);
        }

        return ['month' => $months, 'quarter' => $quarters, 'year' => $years];
    }

    /**
     * @return array<string,mixed>
     */
    public function build(string $type = 'month', string $ref = ''): array
    {
        $period = $this->resolvePeriod($type, $ref);
        $depts  = $this->departments();

        $items  = [];
        $counts = $this->contributions($depts, $period['start'], $period['end'], $items);
        $ranked = $this->rank($depts, $counts, $period['months']);
        $tiers  = ['top' => [], 'average' => [], 'low' => []];
        foreach ($ranked['rows'] as $row) {
            $tiers[$row['tier']][] = $row;
        }

        $report = [
            'period'      => $period,
            'options'     => $this->periodOptions(),
            'departments' => $ranked['rows'],
            'average'     => $ranked['average'],
            'tiers'       => $tiers,
            'totals'      => $this->totals($ranked['rows'], $period),
            'details'     => $this->details($ranked['rows'], $items),
            'upcoming'    => $this->upcomingEvents(),
            'missing'     => $this->missingDepartments($ranked['rows']),
            'analytics'   => $this->analytics($period),
            'trend'       => $this->monthlyTrend($depts),
            'growth'      => $this->profileGrowth($ranked['rows']),
            'hrms_ok'     => $this->hrmsOk,
        ];
        $report['suggestions'] = $this->suggestions($report);

        return $report;
    }

    /** @return list<array<string,mixed>> */
    private function departments(): array
    {
        $db   = db_connect();
        $rows = $db->table('departments')
            ->select('id, slug, name, group_name, hidden')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        $roster = $this->facultyRoster();

        foreach ($rows as &$r) {
            $people           = $roster[(int) $r['id']] ?? ['profiles' => [], 'missing' => []];
            $r['unit']        = trim((string) ($r['group_name'] ?? '')) ?: 'Other';
            $r['profiles']    = $people['profiles'];
            $r['missing_names'] = $people['missing'];
            $r['available']   = count($people['profiles']);
            $r['faculty']     = $r['available'] + count($people['missing']);
            $r['with_photo']  = count(array_filter($people['profiles'], static fn ($p) => $p['has_photo']));
            $r['photo_pct']   = $r['available'] > 0 ? (int) round($r['with_photo'] / $r['available'] * 100) : 0;
        }
        unset($r);

        return $rows;
    }

    /**
     * Each department's people today: filled profiles, and the names of everyone without one.
     * People and their departments come from the public faculty directory (HRMS plus ACP overrides), so the
     * report matches the website. Only when HRMS cannot be read does it fall back to public profiles.
     *
     * @return array<int, array{profiles:list<array<string,mixed>>,missing:list<string>}>
     */
    private function facultyRoster(): array
    {
        $byEmp = $public = [];
        foreach (db_connect()->table('faculty_profiles')
            ->select('slug, name, department_slug, department_name, emp_id, photo, is_public, experience_json, publications_json, skills_json, updated_at')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray() as $f) {
            $emp = trim((string) ($f['emp_id'] ?? ''));
            if ($emp !== '') {
                $byEmp[$emp] = $f;
            }
            if ((int) $f['is_public'] === 1) {
                $public[(string) $f['slug']] = $f;
            }
        }

        $roster = [];
        $place  = function (int $id, string $name, ?array $profile) use (&$roster): void {
            if (! $id) {
                return;
            }
            $roster[$id] ??= ['profiles' => [], 'missing' => []];
            if ($profile && $this->hasCvDetails($profile)) {
                $roster[$id]['profiles'][] = [
                    'slug'       => (string) $profile['slug'],
                    'name'       => $name,
                    'has_photo'  => trim((string) ($profile['photo'] ?? '')) !== '',
                    'updated_at' => (string) ($profile['updated_at'] ?? ''),
                ];
            } else {
                $roster[$id]['missing'][] = $name;
            }
        };

        $this->hrmsOk = (new HrmsClient())->fetchAll()['ok'];
        if (! $this->hrmsOk) {
            foreach ($public as $f) {
                $place(DepartmentDataLogModel::departmentIdFor((string) $f['department_slug'], (string) $f['department_name']), (string) $f['name'], $f);
            }

            return $roster;
        }

        foreach ((new FacultyDirectoryService())->publicDirectory() as $person) {
            $profile = $byEmp[(string) $person['empId']] ?? null;
            if ($profile && (int) $profile['is_public'] !== 1) {
                continue;
            }
            $place(DepartmentDataLogModel::departmentIdFor('', (string) $person['depName']), (string) $person['empName'], $profile);
        }

        return $roster;
    }

    /** @param array<string,mixed> $profile */
    private function hasCvDetails(array $profile): bool
    {
        foreach (['experience_json', 'publications_json', 'skills_json'] as $field) {
            $list = json_decode((string) ($profile[$field] ?? ''), true);
            if (is_array($list) && array_filter($list, static fn ($v) => ! is_string($v) || trim($v) !== '') !== []) {
                return true;
            }
        }

        return false;
    }

    /**
     * Raw per-department counts for a date range [start, end).
     * When $items is passed, every individual record received is appended to it (for the detail view).
     *
     * @param list<array<string,mixed>> $depts
     * @param list<array<string,mixed>>|null $items
     * @return array<int, array{activities:int,submissions:int,faculty_updates:int,activity_months:int,last_received:string}>
     */
    private function contributions(array $depts, string $start, string $end, ?array &$items = null): array
    {
        $db      = db_connect();
        $out     = [];
        $collect = $items !== null;
        $months  = [];
        $seen    = static function (array &$row, string $at): void {
            if ($at > $row['last_received']) {
                $row['last_received'] = $at;
            }
        };
        $add = static function (?array &$items, int $dept, string $type, string $date, string $item, string $source, string $status = '') use ($collect): void {
            if ($collect) {
                $items[] = compact('dept', 'type', 'date', 'item', 'source', 'status');
            }
        };

        foreach ($depts as $d) {
            $out[(int) $d['id']] = [
                'activities'      => 0,
                'submissions'     => 0,
                'faculty_updates' => 0,
                'activity_months' => 0,
                'last_received'   => '',
            ];
        }

        foreach ($db->table('department_activities')
            ->select('department_id, title, created_at')
            ->where('created_at >=', $start)
            ->where('created_at <', $end)
            ->get()->getResultArray() as $r) {
            $id = (int) $r['department_id'];
            if (isset($out[$id])) {
                $out[$id]['activities']++;
                $months[$id][substr((string) $r['created_at'], 0, 7)] = true;
                $seen($out[$id], (string) $r['created_at']);
                $add($items, $id, 'Activity', (string) $r['created_at'], (string) $r['title'], 'Published on department page', 'published');
            }
        }

        // Approved forms already appear above as activities, so only those still waiting are added here.
        foreach ((new ActivityReviewService())->listByStatus('pending', 5000) as $sub) {
            $at = (string) ($sub['submitted_at'] ?? '');
            $id = (int) ($sub['department_id'] ?? 0);
            if ($at >= $start && $at < $end && isset($out[$id])) {
                $out[$id]['submissions']++;
                $months[$id][substr($at, 0, 7)] = true;
                $seen($out[$id], $at);
                $add($items, $id, 'Activity', $at, (string) ($sub['title'] ?? 'Untitled activity'), 'Activity update form', 'pending');
            }
        }

        // Faculty profiles: public update forms + profiles entered in the ACP (each profile once per period).
        $profiles = [];
        model(FacultySubmissionModel::class)->ensureTable();
        if ($db->tableExists('faculty_profile_submissions')) {
            foreach ($db->table('faculty_profile_submissions')
                ->select('dep_name, slug, emp_name, submitted_at, status')
                ->where('submitted_at >=', $start)
                ->where('submitted_at <', $end)
                ->where('status !=', 'rejected')
                ->get()->getResultArray() as $r) {
                $id = DepartmentDataLogModel::departmentIdFor('', (string) $r['dep_name']);
                if ($id && isset($out[$id])) {
                    $profiles[$id][(string) ($r['slug'] ?: $r['emp_name'])] = true;
                    $seen($out[$id], (string) $r['submitted_at']);
                    $add($items, $id, 'Faculty profile', (string) $r['submitted_at'], (string) $r['emp_name'], 'Faculty update form', (string) $r['status']);
                }
            }
        }

        model(DepartmentDataLogModel::class)->ensureTable();
        $logRows = $db->table('department_data_log')
            ->select('department_id, ref_key, user_id, created_at')
            ->where('data_type', DepartmentDataLogModel::TYPE_FACULTY_PROFILE)
            ->where('created_at >=', $start)
            ->where('created_at <', $end)
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        $names = $users = [];
        if ($collect && $logRows) {
            $slugs = array_values(array_unique(array_filter(array_column($logRows, 'ref_key'))));
            if ($slugs) {
                foreach ($db->table('faculty_profiles')->select('slug, name')->whereIn('slug', $slugs)->get()->getResultArray() as $f) {
                    $names[$f['slug']] = $f['name'];
                }
            }
            $ids = array_values(array_unique(array_filter(array_map('intval', array_column($logRows, 'user_id')))));
            if ($ids) {
                foreach ($db->table('users')->select('id, name, email')->whereIn('id', $ids)->get()->getResultArray() as $u) {
                    $users[(int) $u['id']] = $u['name'] ?: $u['email'];
                }
            }
        }

        foreach ($logRows as $r) {
            $id = (int) $r['department_id'];
            if (! isset($out[$id])) {
                continue;
            }
            $by = 'Entered by IT' . (isset($users[(int) $r['user_id']]) ? ' (' . $users[(int) $r['user_id']] . ')' : '');
            $profiles[$id][(string) $r['ref_key']] = true;
            $add($items, $id, 'Faculty profile', (string) $r['created_at'], (string) ($names[$r['ref_key']] ?? $r['ref_key']), $by, 'saved');
            $seen($out[$id], (string) $r['created_at']);
        }

        // Profiles filled in before the log existed, or by a data import, only carry updated_at.
        foreach ($depts as $d) {
            $id = (int) $d['id'];
            foreach ($d['profiles'] as $p) {
                if ($p['updated_at'] >= $start && $p['updated_at'] < $end && ! isset($profiles[$id][$p['slug']])) {
                    $profiles[$id][$p['slug']] = true;
                    $seen($out[$id], $p['updated_at']);
                    $add($items, $id, 'Faculty profile', $p['updated_at'], $p['name'], 'Profile on the website', 'saved');
                }
            }
        }

        foreach ($profiles as $id => $keys) {
            $out[$id]['faculty_updates'] = count($keys);
        }
        foreach ($months as $id => $list) {
            $out[$id]['activity_months'] = count($list);
        }

        return $out;
    }

    /**
     * Every department with what it provided and what is still missing, A to Z.
     *
     * @param list<array<string,mixed>> $rows  ranked departments
     * @param list<array<string,mixed>> $items records from contributions()
     * @return list<array<string,mixed>>
     */
    private function details(array $rows, array $items): array
    {
        $byDept = [];
        foreach ($items as $it) {
            $byDept[$it['dept']][] = $it;
        }
        usort($rows, static fn ($a, $b) => strcasecmp($a['name'], $b['name']));
        $out = [];
        foreach ($rows as $r) {
            $list = array_values(array_filter($byDept[(int) $r['id']] ?? [], static fn ($it) => $it['type'] === 'Activity'));
            usort($list, static fn ($a, $b) => strcmp($b['date'], $a['date']));
            $out[] = [
                'id'             => (int) $r['id'],
                'name'           => (string) $r['name'],
                'unit'           => (string) $r['unit'],
                'tier'           => (string) $r['tier'],
                'faculty'        => (int) $r['faculty'],
                'available'      => (int) $r['available'],
                'missing'        => $r['faculty'] - $r['available'],
                'done_names'     => array_column($r['profiles'], 'name'),
                'missing_names'  => $r['missing_names'],
                'activities'     => $r['activities'] + $r['submissions'],
                'months_total'   => (int) $r['months_total'],
                'months_missing' => (int) $r['months_missing'],
                'last'           => (string) $r['last_received'],
                'activity_items' => $list,
            ];
        }

        return $out;
    }

    /**
     * @param list<array<string,mixed>> $depts
     * @return array{rows:list<array<string,mixed>>,average:float}
     */
    private function rank(array $depts, array $counts, int $months): array
    {
        $rows = [];
        foreach ($depts as $d) {
            if ((int) ($d['hidden'] ?? 0) === 1) {
                continue;
            }
            $c      = $counts[(int) $d['id']];
            $rows[] = $d + $c + [
                'contributions'  => $c['activities'] + $c['submissions'] + $c['faculty_updates'],
                'months_total'   => $months,
                'months_missing' => max(0, $months - $c['activity_months']),
            ];
        }

        $avg = $rows ? array_sum(array_column($rows, 'contributions')) / count($rows) : 0.0;
        usort($rows, static fn ($a, $b) => [$b['contributions'], $a['name']] <=> [$a['contributions'], $b['name']]);

        foreach ($rows as &$r) {
            if ($r['contributions'] === 0 || $r['contributions'] < $avg * 0.5) {
                $r['tier'] = 'low';
            } elseif ($r['contributions'] >= $avg * 1.25) {
                $r['tier'] = 'top';
            } else {
                $r['tier'] = 'average';
            }
        }
        unset($r);

        return ['rows' => $rows, 'average' => round($avg, 1)];
    }

    /** @param list<array<string,mixed>> $rows */
    private function totals(array $rows, array $period): array
    {
        $db = db_connect();

        $faculty   = array_sum(array_column($rows, 'faculty'));
        $available = array_sum(array_column($rows, 'available'));

        return [
            'departments'       => count($rows),
            'reporting'         => count(array_filter($rows, static fn ($r) => $r['contributions'] > 0)),
            'activities'        => array_sum(array_column($rows, 'activities')) + array_sum(array_column($rows, 'submissions')),
            'awaiting_review'   => array_sum(array_column($rows, 'submissions')),
            'faculty'           => $faculty,
            'available'         => $available,
            'faculty_missing'   => $faculty - $available,
            'faculty_updates'   => array_sum(array_column($rows, 'faculty_updates')),
            'forms_waiting'     => $db->tableExists('faculty_profile_submissions')
                ? (int) $db->table('faculty_profile_submissions')->where('status', 'pending')->countAllResults()
                : 0,
            'news'            => (int) $db->table('news_posts')
                ->select('slug')->distinct()
                ->where('status', 'published')
                ->where('published_at >=', $period['start'])
                ->where('published_at <', $period['end'])
                ->countAllResults(),
        ];
    }

    /**
     * The same list the website shows: published, not expired, first day today or later, soonest first.
     *
     * @return list<array<string,mixed>>
     */
    private function upcomingEvents(): array
    {
        $today = strtotime(date('Y-m-d'));
        $out   = [];
        foreach (model(NewsPostModel::class)->upcomingEvents(10) as $r) {
            $r['first_day'] = (string) (($r['start_date'] ?? '') ?: $r['event_date']);
            $r['in_days']   = (int) floor((strtotime($r['first_day']) - $today) / 86400);
            $out[]          = $r;
        }

        return $out;
    }

    /**
     * Who to follow up with: departments with nothing received, no activity, or profiles still missing.
     *
     * @param list<array<string,mixed>> $rows
     * @return array{not_reporting:list<array<string,mixed>>,no_activity:list<array<string,mixed>>,profiles:list<array<string,mixed>>}
     */
    private function missingDepartments(array $rows): array
    {
        usort($rows, static fn ($a, $b) => strcasecmp($a['name'], $b['name']));
        $profiles = array_values(array_filter($rows, static fn ($r) => $r['faculty'] > $r['available']));
        usort($profiles, static fn ($a, $b) => [$b['faculty'] - $b['available'], $a['name']] <=> [$a['faculty'] - $a['available'], $b['name']]);

        return [
            'not_reporting' => array_values(array_filter($rows, static fn ($r) => $r['contributions'] === 0)),
            'no_activity'   => array_values(array_filter($rows, static fn ($r) => $r['activities'] + $r['submissions'] === 0)),
            'profiles'      => $profiles,
        ];
    }

    /** Website figures for the selected period: Google Analytics, or the site's own page counter until it is connected. */
    private function analytics(array $period): array
    {
        $ga = new GoogleAnalytics();
        if ($ga->isConfigured()) {
            $summary = $ga->summary($period);
            if ($summary['ok']) {
                return ['source' => 'google'] + $summary;
            }
        }

        $db    = db_connect();
        $empty = ['source' => 'site', 'views' => 0, 'prev_views' => 0];
        if (! $db->tableExists('page_views')) {
            return $empty;
        }
        $sum = static fn (string $s, string $e) => (int) ($db->table('page_views')
            ->selectSum('views', 'v')->where('day >=', $s)->where('day <', $e)
            ->get()->getRowArray()['v'] ?? 0);

        return [
            'views'      => $sum($period['start'], $period['end']),
            'prev_views' => $sum($period['prev_start'], date('Y-m-d', strtotime($period['prev_last'] . ' +1 day'))),
        ] + $empty;
    }

    /**
     * Last 12 months of department data received, for the trend chart.
     *
     * @param list<array<string,mixed>> $depts
     */
    private function monthlyTrend(array $depts): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[date('Y-m', strtotime(date('Y-m-01') . " -{$i} months"))] = [
                'Activity' => 0, 'Faculty profile' => 0,
            ];
        }
        $items = [];
        $this->contributions($depts, array_key_first($months) . '-01', date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month')), $items);

        // A profile saved several times in a month counts once for that month.
        $counted  = [];
        $calendar = [];
        foreach ($items as $it) {
            $m   = substr($it['date'], 0, 7);
            $key = $it['type'] === 'Activity' ? null : "{$m}|{$it['dept']}|{$it['type']}|{$it['item']}";
            if ($key !== null && isset($counted[$key])) {
                continue;
            }
            if ($key !== null) {
                $counted[$key] = true;
            }
            $months[$m][$it['type']]++;
            if ($it['type'] === 'Activity') {
                $calendar[$it['dept']][$m] = ($calendar[$it['dept']][$m] ?? 0) + 1;
            }
        }

        return [
            'months'     => array_keys($months),
            'calendar'   => $calendar,
            'labels'     => array_map(static fn ($m) => date('M Y', strtotime($m . '-01')), array_keys($months)),
            'activities' => array_column(array_values($months), 'Activity'),
            'faculty'    => array_column(array_values($months), 'Faculty profile'),
        ];
    }

    /**
     * Running total of profiles done, by day for up to two months of history, then by month.
     * Dated by each profile's last save, as the system keeps no earlier history for it.
     *
     * @param list<array<string,mixed>> $rows
     * @return array{labels:list<string>,done:list<int>,total:int}
     */
    private function profileGrowth(array $rows): array
    {
        $dates = [];
        foreach ($rows as $r) {
            foreach ($r['profiles'] as $p) {
                $dates[] = substr($p['updated_at'], 0, 10);
            }
        }
        sort($dates);
        $undated = count(array_filter($dates, static fn ($d) => $d === ''));
        $dates   = array_slice($dates, $undated);
        $today   = date('Y-m-d');
        $first   = $dates[0] ?? $today;
        $byMonth = (strtotime($today) - strtotime($first)) / 86400 > 62;
        $format  = $byMonth ? 'Y-m' : 'Y-m-d';

        $added = [];
        foreach ($dates as $d) {
            $key         = date($format, strtotime($d));
            $added[$key] = ($added[$key] ?? 0) + 1;
        }

        $labels = $done = [];
        $sum    = $undated;
        $cursor = strtotime($byMonth ? substr($first, 0, 7) . '-01' : $first);
        while ($cursor <= strtotime($today)) {
            $sum     += $added[date($format, $cursor)] ?? 0;
            $labels[] = date($byMonth ? 'M Y' : 'd M', $cursor);
            $done[]   = $sum;
            $cursor   = strtotime($byMonth ? '+1 month' : '+1 day', $cursor);
        }

        return ['labels' => $labels, 'done' => $done, 'total' => array_sum(array_column($rows, 'faculty'))];
    }

    /**
     * News and events published per day (or per month for longer periods), for the Website Analytics page.
     *
     * @return array<string,int> keyed Ymd, or Ym when the period covers more than two months
     */
    public function publishedCounts(array $period): array
    {
        $format = $period['months'] > 2 ? '%Y%m' : '%Y%m%d';
        $out    = [];
        foreach (db_connect()->table('news_posts')
            ->select("DATE_FORMAT(published_at, '{$format}') AS b, COUNT(DISTINCT slug) AS c", false)
            ->where('status', 'published')
            ->where('published_at >=', $period['start'])
            ->where('published_at <', $period['end'])
            ->groupBy('b')->get()->getResultArray() as $r) {
            $out[(string) $r['b']] = (int) $r['c'];
        }

        return $out;
    }

    /**
     * Follow-up actions for the office.
     *
     * @return list<array{tone:string,text:string}>
     */
    private function suggestions(array $r): array
    {
        $out    = [];
        $t      = $r['totals'];
        $label  = $r['period']['label'];
        $silent = $r['missing']['not_reporting'];
        $idle   = $r['missing']['no_activity'];
        $names  = static fn (array $list, int $max = 4) => implode(', ', array_slice(array_column($list, 'name'), 0, $max)) . (count($list) > $max ? ', …' : '');

        if ($silent) {
            $out[] = ['tone' => 'warn', 'text' => count($silent) . " of {$t['departments']} departments sent neither activities nor faculty profiles for {$label} ({$names($silent)}). Contact their heads of department."];
        }
        if ($idle) {
            $out[] = ['tone' => 'warn', 'text' => count($idle) . " of {$t['departments']} departments have not submitted activities for {$label} ({$names($idle)}). Send them a reminder with the activity form link."];
        }
        if ($t['faculty_missing'] > 0) {
            $top = implode(', ', array_map(static fn ($d) => $d['name'] . ' (' . ($d['faculty'] - $d['available']) . ')', array_slice($r['missing']['profiles'], 0, 3)));
            $out[] = ['tone' => 'warn', 'text' => "{$t['faculty_missing']} of {$t['faculty']} faculty profiles are still pending, mostly in {$top}. Ask these departments to collect them."];
        }
        if ($t['awaiting_review'] > 0) {
            $out[] = ['tone' => 'warn', 'text' => "{$t['awaiting_review']} activity submission(s) from {$label} are waiting for review. Approve or reject them."];
        }
        if ($t['forms_waiting'] > 0) {
            $out[] = ['tone' => 'warn', 'text' => "{$t['forms_waiting']} faculty profile update form(s) are waiting for review. Approve or reject them."];
        }
        $unlisted = array_values(array_filter($r['departments'], static fn ($d) => $d['faculty'] === 0));
        if ($unlisted) {
            $out[] = ['tone' => 'info', 'text' => 'No faculty are listed for ' . $names($unlisted) . '. Ask HR to check the staff list.'];
        }
        $lowPhoto = array_filter($r['departments'], static fn ($d) => $d['available'] > 0 && $d['photo_pct'] < 50);
        if ($lowPhoto) {
            usort($lowPhoto, static fn ($a, $b) => $a['photo_pct'] <=> $b['photo_pct']);
            $list = implode(', ', array_map(static fn ($d) => "{$d['name']} ({$d['with_photo']} of {$d['available']})", array_slice($lowPhoto, 0, 3)));
            $out[] = ['tone' => 'info', 'text' => count($lowPhoto) . " department(s) have photos for fewer than half of their available profiles: {$list}. Request the missing photos."];
        }
        if (! $out) {
            $out[] = ['tone' => 'ok', 'text' => 'All departments have sent their activities and faculty profiles. No follow-up is needed for this period.'];
        }

        return $out;
    }
}
