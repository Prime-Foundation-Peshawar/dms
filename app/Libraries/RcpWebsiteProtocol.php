<?php

namespace App\Libraries;

/**
 * RCP Website Management protocol helpers (MoM 6 Aug 2026).
 */
class RcpWebsiteProtocol
{
    public const KPI_APPROVED_PER_MONTH = 2;
    public const APPROVAL_SLA_DAYS = 2;
    public const ESCALATION_MONTHS = 3;

    /**
     * Canonical roster of units expected to feed the Website Manager.
     *
     * @return list<array{unit_name:string,unit_type:string,institution:?string,content_examples:?string,sort_order:int}>
     */
    public static function expectedRoster(): array
    {
        return [
            ['unit_name' => 'Finance', 'unit_type' => 'unit', 'institution' => 'RCP', 'content_examples' => 'Fee structures of programs', 'sort_order' => 10],
            ['unit_name' => 'Student Affairs', 'unit_type' => 'unit', 'institution' => 'RCP', 'content_examples' => 'Admission dates & academic deadlines', 'sort_order' => 20],
            ['unit_name' => 'HR', 'unit_type' => 'unit', 'institution' => 'RCP', 'content_examples' => 'Employment opportunities', 'sort_order' => 30],
            ['unit_name' => 'Quality Enhancement Cell', 'unit_type' => 'unit', 'institution' => 'RCP', 'content_examples' => 'Seminars, workshops, accreditations, quality initiatives', 'sort_order' => 40],
            ['unit_name' => 'DHPE&R', 'unit_type' => 'unit', 'institution' => 'RCP', 'content_examples' => 'Academic program information (Health Professional Education & Research)', 'sort_order' => 50],
            ['unit_name' => 'ORIC', 'unit_type' => 'unit', 'institution' => 'RCP', 'content_examples' => 'Research opportunities, facilities, publications, awards', 'sort_order' => 60],
            ['unit_name' => 'UMR', 'unit_type' => 'unit', 'institution' => 'PMC', 'content_examples' => 'Undergraduate research activities & annual conference', 'sort_order' => 70],
            ['unit_name' => 'Literary Society', 'unit_type' => 'society', 'institution' => 'RCP', 'content_examples' => 'Society activities (approved by faculty in-charge)', 'sort_order' => 80],
            ['unit_name' => 'Social Welfare Society', 'unit_type' => 'society', 'institution' => 'RCP', 'content_examples' => 'Society / community campaigns', 'sort_order' => 90],
            ['unit_name' => 'Sports Society', 'unit_type' => 'society', 'institution' => 'RCP', 'content_examples' => 'Sports events & competitions', 'sort_order' => 100],
            ['unit_name' => 'Quran and Sunnah Society', 'unit_type' => 'society', 'institution' => 'RCP', 'content_examples' => 'Society activities', 'sort_order' => 110],
            ['unit_name' => 'Debate Society', 'unit_type' => 'society', 'institution' => 'RCP', 'content_examples' => 'Society activities', 'sort_order' => 120],
            ['unit_name' => 'Excursions & Community Trips', 'unit_type' => 'activity', 'institution' => 'RCP', 'content_examples' => 'Excursions, community trips, inter-institution competitions (within 1 week)', 'sort_order' => 130],
            ['unit_name' => 'Peshawar Medical College', 'unit_type' => 'institution', 'institution' => 'PMC', 'content_examples' => 'Institutional news, notifications, clinical/basic science dept updates', 'sort_order' => 140],
            ['unit_name' => 'Peshawar Dental College', 'unit_type' => 'institution', 'institution' => 'PDC', 'content_examples' => 'Institutional news & department updates', 'sort_order' => 150],
            ['unit_name' => 'Prime Teaching Hospital', 'unit_type' => 'hospital', 'institution' => 'PTH', 'content_examples' => 'Hospital / clinical news (MS office)', 'sort_order' => 160],
            ['unit_name' => 'Mercy Teaching Hospital', 'unit_type' => 'hospital', 'institution' => 'MTH', 'content_examples' => 'Hospital / clinical news (MS office)', 'sort_order' => 170],
            ['unit_name' => 'Kuwait Teaching Hospital', 'unit_type' => 'hospital', 'institution' => 'KTH', 'content_examples' => 'Hospital / clinical news (MS office)', 'sort_order' => 180],
        ];
    }

    public static function autoBand(int $submissions, int $approved): string
    {
        if ($submissions === 0 && $approved === 0) {
            return 'missing';
        }
        if ($approved >= 4) {
            return 'top';
        }
        if ($approved >= self::KPI_APPROVED_PER_MONTH) {
            return 'average';
        }

        return 'low';
    }

    /**
     * @param list<array<string,mixed>> $contributions
     * @param list<array<string,mixed>> $expected
     * @return list<array<string,mixed>>
     */
    public static function missingUnits(array $expected, array $contributions): array
    {
        $byName = [];
        foreach ($contributions as $c) {
            $byName[mb_strtolower((string) ($c['unit_name'] ?? ''))] = $c;
        }
        $missing = [];
        foreach ($expected as $u) {
            $key = mb_strtolower((string) ($u['unit_name'] ?? ''));
            $row = $byName[$key] ?? null;
            $subs = (int) ($row['submissions'] ?? 0);
            $approved = (int) ($row['approved'] ?? 0);
            if ($row === null || ($subs === 0 && $approved === 0)) {
                $missing[] = [
                    'unit_name' => $u['unit_name'],
                    'unit_type' => $u['unit_type'] ?? 'unit',
                    'institution' => $u['institution'] ?? null,
                    'content_examples' => $u['content_examples'] ?? null,
                    'submissions' => $subs,
                    'approved' => $approved,
                    'reason' => $row === null ? 'No record this month — zero input to Website Manager' : 'Recorded but zero submissions/approvals',
                ];
            }
        }

        return $missing;
    }

    /**
     * Units failing KPI for N consecutive months including $monthKey.
     *
     * @param list<array<string,mixed>> $historyRows flat list across months
     * @return list<array<string,mixed>>
     */
    public static function consecutiveFailures(array $historyRows, string $monthKey, int $months = self::ESCALATION_MONTHS): array
    {
        $needed = [];
        $dt = \DateTime::createFromFormat('Y-m-d', $monthKey . '-01') ?: new \DateTime('first day of this month');
        for ($i = 0; $i < $months; $i++) {
            $needed[] = $dt->format('Y-m');
            $dt->modify('-1 month');
        }

        $map = [];
        foreach ($historyRows as $r) {
            $name = (string) ($r['unit_name'] ?? '');
            $mk = (string) ($r['month_key'] ?? '');
            if ($name === '' || $mk === '') {
                continue;
            }
            $map[$name][$mk] = (int) ($r['approved'] ?? 0);
        }

        $escalated = [];
        foreach ($map as $name => $byMonth) {
            $failCount = 0;
            foreach ($needed as $mk) {
                $approved = $byMonth[$mk] ?? 0;
                if ($approved < self::KPI_APPROVED_PER_MONTH) {
                    $failCount++;
                }
            }
            if ($failCount >= $months) {
                $escalated[] = [
                    'unit_name' => $name,
                    'failed_months' => $failCount,
                    'window' => $needed,
                    'latest_approved' => $byMonth[$monthKey] ?? 0,
                ];
            }
        }

        return $escalated;
    }
}
