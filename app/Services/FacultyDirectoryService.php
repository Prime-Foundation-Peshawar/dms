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
        $slugs = [];
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
                $depName = faculty_canonical_dept((string) ($row['depName'] ?? ''));
                if (! $this->isPublicDept($depName)) {
                    continue;
                }
                $out[] = [
                    'empName' => $emp,
                    'desTitle' => trim((string) ($row['desTitle'] ?? '')),
                    'depName' => $depName,
                    'facPMDCNo' => trim((string) ($row['facPMDCNo'] ?? '')),
                    'facFacRegNo' => trim((string) ($row['facFacRegNo'] ?? '')),
                    'qualifications' => trim((string) ($row['qualifications'] ?? '')),
                    'photo' => '',
                ];
                $slugs[] = faculty_slug($emp);
            }
        }

        foreach ($this->profiles->publicRows() as $rec) {
            if (! $this->cvHasWordProfile($rec)) {
                continue;
            }
            if (! faculty_is_directory_rank((string) ($rec['designation'] ?? 'Faculty'))) {
                continue;
            }
            $keys = array_filter([(string) ($rec['slug'] ?? '')]);
            foreach ($rec['aliases'] ?? [] as $alias) {
                $keys[] = (string) $alias;
            }
            $already = false;
            foreach ($slugs as $memberSlug) {
                foreach ($keys as $key) {
                    $key = trim($key);
                    if ($key === '') {
                        continue;
                    }
                    if (faculty_slugs_match($memberSlug, faculty_slug($key)) || faculty_slugs_match($memberSlug, $key)) {
                        $already = true;
                        break 2;
                    }
                }
            }
            if ($already) {
                continue;
            }
            $depName = faculty_canonical_dept((string) ($rec['department_name'] ?? ''));
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
                'empName' => trim((string) $rec['name']),
                'desTitle' => trim((string) ($rec['designation'] ?? 'Faculty')) ?: 'Faculty',
                'depName' => $depName,
                'facPMDCNo' => '',
                'facFacRegNo' => '',
                'qualifications' => trim($qualText),
                'photo' => (string) ($rec['photo'] ?? ''),
            ];
            $slugs[] = faculty_slug((string) $rec['name']);
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
                'qualification' => $row['qualifications'] ?? '',
                'reg' => $row['facPMDCNo'] ?? '',
                'desTitle' => $row['desTitle'] ?? '',
                'photo' => $row['photo'] ?? '',
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
