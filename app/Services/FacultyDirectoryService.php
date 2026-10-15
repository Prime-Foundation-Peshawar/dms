<?php

namespace App\Services;

use App\Libraries\HrmsClient;
use App\Models\FacultyProfileModel;

class FacultyDirectoryService
{
    protected HrmsClient $hrms;
    protected FacultyProfileModel $profiles;

    public function __construct(?HrmsClient $hrms = null, ?FacultyProfileModel $profiles = null)
    {
        helper('faculty');
        $this->hrms = $hrms ?? new HrmsClient();
        $this->profiles = $profiles ?? model(FacultyProfileModel::class);
    }

    /** @return list<string> */
    protected function hiddenDepts(): array
    {
        return ['DHPE & R', 'DHPE & Research'];
    }

    public function isPublicDept(string $depName): bool
    {
        $canon = faculty_canonical_dept($depName);
        return $canon !== '' && ! in_array($canon, $this->hiddenDepts(), true);
    }

    /**
     * @return list<array{empName:string,desTitle:string,depName:string,facPMDCNo:string,facFacRegNo:string,qualifications:string,photo?:string}>
     */
    public function publicDirectory(): array
    {
        static $memo = null;
        if (is_array($memo)) {
            return $memo;
        }

        $out = [];
        $seenEmpIds = [];
        $pack = $this->hrms->fetchAll();

        if (! empty($pack['ok'])) {
            foreach ($pack['employees'] as $row) {
                if (! is_array($row)) {
                    continue;
                }
                if (! faculty_is_directory_rank((string) ($row['desTitle'] ?? ''))) {
                    continue;
                }
                $emp = trim((string) ($row['empName'] ?? ''));
                if ($emp === '') {
                    continue;
                }
                if (! faculty_slug_is_public($emp)) {
                    continue;
                }
                $empId = trim((string) ($row['empId'] ?? ''));
                $depName = faculty_canonical_dept((string) ($row['depName'] ?? ''));
                $hrmsDept = $depName;
                // Match local CV/profile by HRMS empId only (never by name).
                $rec = $empId !== '' ? $this->profiles->findByEmpId($empId) : null;
                $cvDept = faculty_canonical_dept((string) ($rec['department_name'] ?? ''));
                // Only remap when ACP explicitly enables override (Default/Overridden switch).
                $overridden = ! empty($rec['dept_override']) && $cvDept !== '';
                if ($overridden) {
                    $depName = $cvDept;
                } elseif ($depName === 'Administration' && $cvDept !== '' && $cvDept !== 'Administration') {
                    $depName = $cvDept;
                }
                $depName = faculty_live_dept_name($emp, $depName);
                if (! $this->isPublicDept($depName)) {
                    continue;
                }
                if ($empId !== '') {
                    $seenEmpIds[$empId] = true;
                }
                $out[] = [
                    'empId' => $empId,
                    'empName' => $emp,
                    'desTitle' => trim((string) ($row['desTitle'] ?? '')),
                    'depName' => $depName,
                    'hrmsDepName' => $hrmsDept,
                    'dept_override' => $overridden,
                    'cv_only' => false,
                    'facPMDCNo' => trim((string) ($row['facPMDCNo'] ?? '')),
                    'facFacRegNo' => trim((string) ($row['facFacRegNo'] ?? '')),
                    'qualifications' => trim((string) ($row['qualifications'] ?? '')),
                    'photo' => (string) ($rec['photo'] ?? ''),
                    'profile_id' => isset($rec['id']) ? (int) $rec['id'] : null,
                ];
            }
        }

        foreach ($this->profiles->publicRows() as $rec) {
            if (! $this->cvHasWordProfile($rec)) {
                continue;
            }
            if (! faculty_is_directory_rank((string) ($rec['designation'] ?? 'Faculty'))) {
                continue;
            }
            if (! faculty_slug_is_public((string) ($rec['name'] ?? '')) || ! faculty_slug_is_public((string) ($rec['slug'] ?? ''))) {
                continue;
            }
            $profileEmpId = trim((string) ($rec['emp_id'] ?? ''));
            if ($profileEmpId !== '' && isset($seenEmpIds[$profileEmpId])) {
                continue; // already represented by HRMS row
            }
            // Profiles without emp_id are CV-only extras; skip if we cannot safely dedupe.
            if ($profileEmpId === '') {
                // Keep only when not already covered — no name matching against HRMS.
                // (Unlinked profiles still appear until emp_id is set in ACP.)
            }
            $depName = faculty_live_dept_name((string) $rec['name'], (string) ($rec['department_name'] ?? ''));
            if ($depName !== '' && ! $this->isPublicDept($depName)) {
                continue;
            }
            $qual = $rec['qualifications'] ?? [];
            if (is_string($qual)) {
                $qualText = $qual;
            } else {
                $qualText = implode(', ', array_map('strval', $qual));
            }
            $out[] = [
                'empId' => $profileEmpId,
                'empName' => trim((string) $rec['name']),
                'desTitle' => trim((string) ($rec['designation'] ?? 'Faculty')) ?: 'Faculty',
                'depName' => $depName,
                'hrmsDepName' => '',
                'dept_override' => ! empty($rec['dept_override']),
                'cv_only' => $profileEmpId === '',
                'facPMDCNo' => '',
                'facFacRegNo' => '',
                'qualifications' => trim($qualText),
                'photo' => (string) ($rec['photo'] ?? ''),
                'profile_id' => (int) ($rec['id'] ?? 0) ?: null,
            ];
            if ($profileEmpId !== '') {
                $seenEmpIds[$profileEmpId] = true;
            }
        }

        $hasFurqan = false;
        foreach ($out as $row) {
            if (faculty_slugs_match(faculty_slug((string) ($row['empName'] ?? '')), 'muhammad-furqan')) {
                $hasFurqan = true;
                break;
            }
        }
        if (! $hasFurqan) {
            $out[] = [
                'empId' => '',
                'empName' => 'Muhammad Furqan',
                'desTitle' => 'Director IT',
                'depName' => 'IT & MI',
                'hrmsDepName' => 'IT & MI',
                'dept_override' => false,
                'cv_only' => true,
                'facPMDCNo' => '',
                'facFacRegNo' => '',
                'qualifications' => 'MS Computer Science',
                'photo' => '',
                'profile_id' => null,
            ];
        }

        $memo = $out;
        return $memo;
    }

    /** @param array<string,mixed> $rec */
    protected function cvHasWordProfile(array $rec): bool
    {
        if (! empty($rec['photo'])) {
            return true;
        }
        foreach (['qualifications', 'experience', 'publications', 'skills'] as $key) {
            $val = $rec[$key] ?? [];
            if (is_array($val) && count(array_filter($val)) > 0) {
                return true;
            }
        }
        return false;
    }

    /** @return list<string> */
    public function deptNamesForSlug(string $slug, string $deptName = ''): array
    {
        $map = [
            'chs' => ['CHS', 'Community Health Sciences'],
            'dhpe' => ['DHPE & R', 'DHPE & Research'],
            'gynaecology' => ['Gynae and Obstetrics', 'Gynaecology & Obstetrics', 'Gynaecology'],
            'orthopaedics' => ['Orthopedics', 'Orthopaedics'],
            'pulmonology' => ['Pulmonology'],
            'accident-emergency' => ['Accident and Emergency'],
            'paeds-cardiology' => ['Paeds Cardiology'],
            'forensic-medicine' => ['Forensic Medicine'],
            'oncology' => ['Oncology', 'Medical Oncology'],
            'critical-care' => ['Critical Care'],
        ];
        $names = $map[$slug] ?? [];
        if ($deptName !== '') {
            $names[] = $deptName;
        }
        return array_values(array_unique(array_filter(array_map('trim', $names))));
    }

    /**
     * @param list<array<string,mixed>> $staticFaculty
     * @return list<array<string,mixed>>
     */
    public function forDepartment(string $slug, string $deptName, array $staticFaculty = [], string $hodName = ''): array
    {
        $wanted = [];
        foreach ($this->deptNamesForSlug($slug, $deptName) as $label) {
            $wanted[strtolower(faculty_canonical_dept($label))] = true;
            $wanted[strtolower(trim($label))] = true;
        }

        $matched = [];
        foreach ($this->publicDirectory() as $row) {
            $dep = strtolower((string) ($row['depName'] ?? ''));
            if ($dep === '' || empty($wanted[$dep])) {
                continue;
            }
            $matched[] = $row;
        }

        $rank = [
            'Professor' => 1, 'Professor & HOD' => 1, 'Associate Professor' => 2,
            'Assistant Professor' => 3, 'Senior Registrar' => 4, 'Senior Lecturer' => 5,
            'Lecturer' => 6, 'Registrar' => 7,
        ];
        usort($matched, static function ($a, $b) use ($rank) {
            $ra = $rank[$a['desTitle'] ?? ''] ?? 10;
            $rb = $rank[$b['desTitle'] ?? ''] ?? 10;
            if ($ra !== $rb) {
                return $ra <=> $rb;
            }
            return strcasecmp((string) ($a['empName'] ?? ''), (string) ($b['empName'] ?? ''));
        });

        $out = [];
        foreach ($matched as $row) {
            $out[] = [
                'name' => $this->displayName($row),
                'empName' => $row['empName'],
                'empId' => $row['empId'] ?? '',
                'qualification' => $row['qualifications'] ?? '',
                'reg' => $row['facPMDCNo'] ?? '',
                'desTitle' => $row['desTitle'] ?? '',
                'photo' => $row['photo'] ?? '',
                'depName' => $row['depName'] ?? '',
                'hrmsDepName' => $row['hrmsDepName'] ?? ($row['depName'] ?? ''),
                'dept_override' => ! empty($row['dept_override']),
                'profile_id' => $row['profile_id'] ?? null,
                'slug' => faculty_slug((string) ($row['empName'] ?? '')),
            ];
        }

        if ($hodName === '' || ! $out) {
            return $out;
        }
        usort($out, static function ($a, $b) use ($hodName) {
            $aHod = faculty_slugs_match(faculty_slug($a['empName'] ?? $a['name']), faculty_slug($hodName)) ? 0 : 1;
            $bHod = faculty_slugs_match(faculty_slug($b['empName'] ?? $b['name']), faculty_slug($hodName)) ? 0 : 1;
            return $aHod <=> $bHod;
        });
        return $out;
    }

    /** @param array<string,mixed> $row */
    public function displayName(array $row): string
    {
        $desTitle = trim((string) ($row['desTitle'] ?? ''));
        $name = trim((string) ($row['empName'] ?? ''));
        $prefixMap = [
            'Professor' => 'Prof.', 'Professor & HOD' => 'Prof.',
            'Associate Professor' => 'Assoc. Prof.', 'Assistant Professor' => 'Asst. Prof.',
            'Senior Lecturer' => 'Sr. Lecturer', 'Lecturer' => 'Lecturer',
            'Senior Registrar' => 'Sr. Registrar', 'Registrar' => 'Registrar',
        ];
        $prefix = $prefixMap[$desTitle] ?? '';
        $medical = array_keys($prefixMap);
        if (in_array($desTitle, $medical, true)) {
            return $prefix !== '' ? ($prefix . ' Dr. ' . $name) : ('Dr. ' . $name);
        }
        return $prefix !== '' ? trim($prefix . ' ' . $name) : $name;
    }
}
