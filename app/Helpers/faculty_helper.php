<?php

if (! function_exists('faculty_slug')) {
    function faculty_slug(string $name): string
    {
        $n = trim($name);
        $titles = '/^(associate professor|assistant professor|senior registrar|senior lecturer|assoc\.?\s*prof\.?|asst\.?\s*prof\.?|sr\.?\s*registrar|sr\.?\s*lecturer|professor|prof\.?|dr\.?)\s+/i';
        while (preg_match($titles, $n)) {
            $n = preg_replace($titles, '', $n, 1) ?? $n;
        }
        $n = strtolower($n);
        $n = preg_replace('/[^a-z0-9]+/', '-', $n) ?? $n;
        $n = trim($n, '-');
        $n = preg_replace('/^(mohammad|muhammed)-/', 'muhammad-', $n) ?? $n;
        return $n;
    }
}

if (! function_exists('faculty_slug_tokens')) {
    /** @return list<string> */
    function faculty_slug_tokens(string $slug): array
    {
        $parts = array_values(array_filter(explode('-', trim($slug))));
        foreach ($parts as $i => $part) {
            if (in_array($part, ['m', 'md', 'mohammad', 'muhammed'], true)) {
                $parts[$i] = 'muhammad';
            }
        }
        return $parts;
    }
}

if (! function_exists('faculty_tokens_subsequence')) {
    function faculty_tokens_subsequence(array $short, array $long): bool
    {
        if (! $short) {
            return true;
        }
        if (count($short) > count($long)) {
            return false;
        }
        $i = 0;
        foreach ($long as $token) {
            if ($i < count($short) && $short[$i] === $token) {
                $i++;
            }
        }
        return $i === count($short);
    }
}

if (! function_exists('faculty_slugs_match')) {
    function faculty_slugs_match(string $a, string $b): bool
    {
        if ($a === '' || $b === '') {
            return false;
        }
        if ($a === $b) {
            return true;
        }
        $ta = faculty_slug_tokens($a);
        $tb = faculty_slug_tokens($b);
        if (! $ta || ! $tb) {
            return false;
        }
        if (implode('-', $ta) === implode('-', $tb)) {
            return true;
        }
        if ($ta[0] !== $tb[0]) {
            return false;
        }
        $la = $ta[count($ta) - 1];
        $lb = $tb[count($tb) - 1];
        if ($la !== $lb && ! str_starts_with($la, $lb) && ! str_starts_with($lb, $la)) {
            return false;
        }
        $midA = array_slice($ta, 1, -1);
        $midB = array_slice($tb, 1, -1);
        if ($midA && $midB) {
            return faculty_tokens_subsequence($midA, $midB) || faculty_tokens_subsequence($midB, $midA);
        }
        return true;
    }
}

if (! function_exists('faculty_canonical_dept')) {
    function faculty_canonical_dept(string $name): string
    {
        $raw = trim($name);
        if ($raw === '') {
            return '';
        }
        $key = strtolower(str_replace('&', 'and', $raw));
        $key = trim(preg_replace('/\s+/', ' ', $key) ?? '');
        $aliases = [
            'community health sciences' => 'CHS',
            'dhpe and research' => 'DHPE & R',
            'gynaecology and obstetrics' => 'Gynae and Obstetrics',
            'gynaecology' => 'Gynae and Obstetrics',
            'orthopaedics' => 'Orthopedics',
            'oral patholgy' => 'Oral Pathology',
            'oral pathology' => 'Oral Pathology',
            'sciences of dental material' => 'Sciences of Dental Materials',
            'sciences of dental materials' => 'Sciences of Dental Materials',
            'accident and emergency' => 'Accident and Emergency',
            'medical oncology' => 'Oncology',
        ];
        return $aliases[$key] ?? $raw;
    }
}

if (! function_exists('faculty_public_excluded_slugs')) {
    /** Dual-appointed / PDC / resigned staff who must not appear on the DMS directory. */
    function faculty_public_excluded_slugs(): array
    {
        return [
            'munaza-khattak' => true,
            'hina-hakim' => true,
            'amna-umer' => true,
            'ambereen-humayun' => true,
            'sadaf-alam' => true,
            'maryam-tahir' => true,
            'saima-afridi' => true,
            'iftikhar-akbar' => true,
            'muhammad-zain' => true,
            'hasan-ali-raza' => true,
            'shahab-adil' => true,
            'zafar-ul-islam' => true,
            'muhammad-raza' => true,
            'nasir-bakhtiar' => true,
            // Resigned
            'marina-hidayat' => true,
        ];
    }
}

if (! function_exists('faculty_profile_is_public')) {
    function faculty_profile_is_public(?array $rec): bool
    {
        if (! $rec) {
            return false;
        }
        if (array_key_exists('public', $rec) && $rec['public'] === false) {
            return false;
        }
        $keys = array_merge([(string) ($rec['slug'] ?? '')], $rec['aliases'] ?? []);
        $keys[] = (string) ($rec['name'] ?? '');
        foreach ($keys as $key) {
            if (! faculty_slug_is_public((string) $key)) {
                return false;
            }
        }

        return true;
    }
}

if (! function_exists('faculty_public_registrations')) {
    /**
     * Live profile JS always paints HRMS PM&DC / faculty numbers, then extra CV regs.
     *
     * @param array<string,mixed>|null $directory
     * @param array<string,mixed>|null $extra
     * @return list<string>
     */
    function faculty_public_registrations(?array $directory, ?array $extra): array
    {
        $out = [];
        $seen = [];
        $add = static function (string $label) use (&$out, &$seen): void {
            $label = trim($label);
            if ($label === '') {
                return;
            }
            $key = strtolower(preg_replace('/\W+/', '', $label) ?? '');
            if ($key === '' || isset($seen[$key])) {
                return;
            }
            $seen[$key] = true;
            $out[] = $label;
        };

        $pmdc = trim((string) ($directory['facPMDCNo'] ?? ''));
        $fac = trim((string) ($directory['facFacRegNo'] ?? ''));
        if (preg_match('/^0+$/', $fac) || strcasecmp($fac, 'n/a') === 0) {
            $fac = '';
        }
        if ($pmdc !== '') {
            $add('PM&DC No. ' . $pmdc);
        }
        if ($fac !== '') {
            $add('Faculty No. ' . $fac);
        }
        foreach (faculty_plain_list($extra['registrations'] ?? []) as $reg) {
            if ($pmdc !== '' && preg_match('~\bpm\s*&?\s*dc\b~i', $reg)) {
                continue;
            }
            if ($fac !== '' && preg_match('~\bfaculty\s*(no\.?|number|#)\b~i', $reg)) {
                continue;
            }
            $add($reg);
        }

        return $out;
    }
}

if (! function_exists('faculty_slug_is_public')) {
    function faculty_slug_is_public(string $slugOrName): bool
    {
        $slug = faculty_slug($slugOrName);
        if ($slug === '') {
            return true;
        }
        foreach (array_keys(faculty_public_excluded_slugs()) as $excluded) {
            if (faculty_slugs_match($slug, $excluded) || $slug === $excluded) {
                return false;
            }
        }

        return true;
    }
}

if (! function_exists('faculty_live_dept_name')) {
    /** Departments that live/master lists differently from current HRMS labels. */
    function faculty_live_dept_name(string $empName, string $depName): string
    {
        $depName = faculty_canonical_dept($depName);
        $map = [
            'fazli-wahab' => 'Critical Care',
            'muhammad-ishfaq' => 'Accident and Emergency',
            'shumaila-farman' => 'Accident and Emergency',
        ];

        return $map[faculty_slug($empName)] ?? $depName;
    }
}

if (! function_exists('faculty_is_directory_rank')) {
    function faculty_is_directory_rank(string $desTitle): bool
    {
        $hidden = [
            'Senior Lecturer' => true,
            'Lecturer' => true,
            'Junior Registrar' => true,
        ];
        return ! isset($hidden[trim($desTitle)]);
    }
}

if (! function_exists('dms_asset')) {
    function dms_asset(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path !== '' && ! str_starts_with($path, 'assets/')) {
            $path = 'assets/' . $path;
        }
        $full = FCPATH . $path;
        $v = is_file($full) ? (string) filemtime($full) : (string) time();

        return base_url($path) . '?v=' . rawurlencode($v);
    }
}

if (! function_exists('faculty_soft_space')) {
    function faculty_soft_space(string $text): string
    {
        $text = preg_replace('/([a-z])([A-Z])/', '$1 $2', $text) ?? $text;
        $text = preg_replace('/(Department)(Professor|Associate|Assistant)/', '$1 $2', $text) ?? $text;

        return trim(preg_replace('/\s+/', ' ', $text) ?? '');
    }
}

if (! function_exists('faculty_has_arabic')) {
    function faculty_has_arabic(string $text): bool
    {
        return (bool) preg_match('/\p{Arabic}/u', $text);
    }
}

if (! function_exists('faculty_plain_list')) {
    /** @param mixed $raw @return list<string> */
    function faculty_plain_list($raw): array
    {
        if (is_string($raw)) {
            $raw = preg_split('/[\n;|]+/', $raw) ?: [];
        }
        if (! is_array($raw)) {
            return [];
        }
        $out = [];
        $seen = [];
        foreach ($raw as $item) {
            $text = trim(faculty_soft_space((string) $item));
            if ($text === '' || isset($seen[strtolower($text)])) {
                continue;
            }
            $seen[strtolower($text)] = true;
            $out[] = $text;
        }

        return $out;
    }
}

if (! function_exists('faculty_research_preferences')) {
    /** @param array<string,mixed> $rec @return list<string> */
    function faculty_research_preferences(array $rec): array
    {
        foreach (['research_preferences', 'research_interests', 'research', 'preferences'] as $key) {
            if (empty($rec[$key])) {
                continue;
            }
            $list = faculty_plain_list($rec[$key]);
            if ($list) {
                return $list;
            }
        }

        return [];
    }
}

if (! function_exists('faculty_fix_runons')) {
function faculty_fix_runons(string $text): string {
  $text = str_ireplace([
    'Histopatholoyg',
    'Histopatholoy',
    'WORKING EXPERIENCE',
    'Assit. Prof',
    'Assit Prof',
  ], [
    'Histopathology',
    'Histopathology',
    '',
    'Assistant Professor',
    'Assistant Professor',
  ], $text);
  $text = str_replace(['â€”', 'â€“', 'âˆ’'], '-', $text);
  $text = preg_replace('/\bAT\b/', 'At', $text);
  $text = preg_replace('/([a-z])([A-Z])/', '$1 $2', $text);
  $text = preg_replace('/\bUo P\b/', 'UoP', $text);
  $text = preg_replace('/\bPh D\b/', 'PhD', $text);
  $text = preg_replace('/([A-Za-z])((?:19|20)\d{2})\b/', '$1 $2', $text);
  $text = preg_replace('/([A-Za-z])(\d{1,2}[-.\/]\d{1,2}[-.\/]\d{2,4})/', '$1 $2', $text);
  $text = preg_replace('/(\d)([A-Z][a-z]+)/', '$1 $2', $text);
  $text = preg_replace('/(\))([A-Z])/', '$1 $2', $text);
  $text = preg_replace('/\(([A-Za-z .]+)\&/', '($1) &', $text);
  $text = preg_replace('/\s+/', ' ', $text);
  return trim($text ?? '');
}
}

if (! function_exists('faculty_normalize_designation')) {
    function faculty_normalize_designation(string $raw): string
    {
        $d = trim($raw);
        $d = preg_replace('/^head of department\s*/i', '', $d) ?? $d;
        $d = preg_replace('/\band head of department\b/i', '', $d) ?? $d;
        $d = preg_replace('/\bhod\b/i', '', $d) ?? $d;
        $d = faculty_soft_space($d);
        $d = trim($d, " ,&");
        if (preg_match('/^assit\.?\s*prof\.?$/i', $d) || preg_match('/^asst\.?\s*prof\.?$/i', $d) || preg_match('/^assistant\s*prof\.?$/i', $d)) {
            return 'Assistant Professor';
        }
        if (preg_match('/^assistant professor(?:\s+pediatrics|\s+paediatrics)?$/i', $d)) {
            return 'Assistant Professor';
        }
        if (preg_match('/^(hod|head of department|unit incharge)\s*(pediatrics|paediatrics)?$/i', $d)) {
            return 'Professor';
        }
        if ($d === '' || strcasecmp($d, 'hod') === 0 || strcasecmp($d, 'head of department') === 0) {
            return '';
        }
        if (preg_match('/^(Associate Professor|Assistant Professor|Professor|Senior Lecturer|Lecturer|Senior Registrar|Registrar)\s+(?!\()(.+)$/i', $d, $m)) {
            return $m[1] . ' (' . trim($m[2], " ()") . ')';
        }

        return $d;
    }
}

if (! function_exists('faculty_normalize_qualifications')) {
    function faculty_normalize_qualifications($items): array {
        if (is_string($items)) {
            $items = preg_split('/[\n;,|]+/', $items) ?: [];
        }
        if (! is_array($items)) {
            return [];
        }
      $joined = [];
      $carry = '';
      foreach ($items as $q) {
        $q = trim(preg_replace('/\s+/', ' ', (string) $q) ?? '');
        if ($q === '') {
          continue;
        }
        if ($carry !== '') {
          $q = trim($carry . ' ' . $q);
          $carry = '';
        }
        if (preg_match('/\($/', $q) || preg_match('/^(Diploma in|Post\s*Graduate|Postgraduate|Master.?s in|Bachelors? of)$/i', $q)) {
          $carry = $q;
          continue;
        }
        $joined[] = $q;
      }
      if ($carry !== '') {
        $joined[] = $carry;
      }

      $text = faculty_fix_runons(implode(' Â· ', $joined));
      $repl = [
        'M.B.B.S' => 'MBBS', 'M.B.B.S.' => 'MBBS', 'F.C.P.S' => 'FCPS', 'F.C.P.S.' => 'FCPS',
        'M.C.P.S' => 'MCPS', 'C.H.P.E' => 'CHPE', 'M.PHIL' => 'M.Phil', 'M.Phil.' => 'M.Phil',
        'Ph. D' => 'PhD', 'Ph.D' => 'PhD', 'Ph.D.' => 'PhD', 'Bachelors of Medicine and Bachelors of Surgery' => 'MBBS',
        'Bachelor of Medicine & Bachelor of Surgery' => 'MBBS', 'Bachelor of Medicine and Bachelor of Surgery' => 'MBBS',
        'Bachelor of Dental Surgery' => 'BDS',
        'Certificate in Health Professional Education in Health Research' => 'CHR',
        'Certificate Course in Health Profession and Education' => 'CHPE',
        'Certificate in Health Professional Education' => 'CHPE',
        'Certificate in Health Professions Education' => 'CHPE',
      ];
      $text = str_ireplace(array_keys($repl), array_values($repl), $text);

      $out = [];
      $seen = [];
      $add = static function (string $label) use (&$out, &$seen): void {
        $label = trim(preg_replace('/\s+/', ' ', $label) ?? '', " ,;Â·|");
        $label = preg_replace('/^MBBS\s*\(\s*MBBS\s*\)/i', 'MBBS', $label) ?? $label;
        if ($label === '' || strlen($label) < 2) {
          return;
        }
        $key = strtolower(preg_replace('/\W+/', '', $label) ?? '');
        if ($key === '' || isset($seen[$key])) {
          return;
        }
        $seen[$key] = true;
        $out[] = $label;
      };

      $patterns = [
        '/\bMBBS\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bBDS\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bMD\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bFCPS(?:-I|-l)?(?:\s*\([^)]{0,80}\)|\s+(?:Histopathology|Hematology|Haematology|Pathology|Psychiatry|Pediatrics|Paediatrics|Surgery and Allied|Urology|Anesthesiology|Anaesthesia|Anesthesia|Cardiology|Interventional Cardiology|Dermatology|Gastroenterology|Medical Oncology|Neurosurgery|Ophthalmology|Vitreoretina|Vitreo Retina|VR|Retina))?/i',
        '/\bMCPSHPE\b/i',
        '/\bMCPS(?:\s*\([^)]{0,50}\)|\s+(?:Anesthesiology|Anaesthesia|Anesthesia|Ophthalmology))?\b/i',
        '/\bPOA fellowship in Arthroplasty\b/i',
        '/\bFellowship in Pead(?:\x{2019}|\x27)s Urology\b/iu',
        '/\bFellowship in Paediatric Orthopaedic\b/i',
        '/\bMS(?:\s*[-â€“]\s*Mental Health Policy(?:\s+&\s+Services)?)?\b/i',
        '/\bIMM(?:\s*\/\s*MCPS|\s*\([^)]{0,50}\))?/i',
        '/\bFRCPCH(?:\s*\([^)]{0,40}\))?/i',
        '/\bMRCPCH(?:\s*\([^)]{0,40}\))?/i',
        '/\bMRCPS(?:\s*\([^)]{0,40}\))?/i',
        '/\bFRCP\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bSCE-MRCP(?:\s+UK)?(?:\s*\([^)]{0,50}\))?/i',
        '/\bMRCP-II(?:\s*\([^)]{0,40}\))?/i',
        '/\bMRCOG\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bMRCPI(?:\s*\([^)]{0,40}\))?/i',
        '/\bMHR\b/i',
        '/\bMRCP(?:-UK)?\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bFellowship in EUS and ERCP(?:\s*\([^)]{0,40}\))?/i',
        '/\bMACG\b/i',
        '/\bFACC\b/i',
        '/\bFESC\b/i',
        '/\bFSCAI\b/i',
        '/\bMEAPCI\b/i',
        '/\bCertificate in Interventional Cardiology\b/i',
        '/\bFellowship in Interventional Cardiology\b/i',
        '/\bMRCS\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bFRCS\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bFACS\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bFMAS\b/i',
        '/\bATLS(?:Â®)?\b/i',
        '/\bM\.?\s*Phil\.?(?:\s*\([^)]{0,50}\)|\s+(?:Histopathology|Chemical Pathology|Hematology|Microbiology|Physiology|Oral Pathology|Community Medicine|FMT)(?:\s+Scholar)?)?/i',
        '/\bPhD(?:\s*\([^)]{0,80}\))?(?:\s*[â€”â€“-]\s*[^Â·|]{8,90})?(?:\s+(?:Physiology|Microbiology))?/i',
        '/\b(?:Master of Public Health|MPH)\b(?:\s*\([^)]{0,50}\))?/i',
        '/\bCHPE\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bCertificate Of Palliative Care\b/i',
        '/\bPalliative Care Course(?:\s*\([^)]{0,80}\))?/i',
        '/\bEHA Exam\b/i',
        '/\bCHR\b/i',
        '/\bDHPE\b/i',
        '/\bMHPE\b/i',
        '/\bPGD\b(?:\s*\([^)]{0,40}\)|\s+in\s+[^,.(]{8,60})?/i',
        '/\bPGT(?:\s+Pharmacy)?\b/i',
        '/\bDCP\b/i',
        '/\bDMJ\b/i',
        '/\bMSHCM\b/i',
        '/\bASCD\b/i',
        '/\bESBD\b/i',
        '/\bEDISC\b/i',
        '/\b(?:D\.C\.H\.?|DCH)\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bDOMS\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bDIHL\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bDCEH\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bADHPE\b(?:\s*\([^)]{0,40}\))?/i',
        '/\bAdvanced Diploma in Health Professions Education(?:\s*\([^)]{0,40}\))?/i',
        '/\bDiploma in Community Eye Health(?:\s*\([^)]{0,120}\))?/i',
        '/\bAttended a course of Executive MBA(?:\s*\([^)]{0,80}\))?/i',
        '/\bDiploma in (?:Anaesthesia|Anesthesia|Gynae and Obs|Tuberculosis and Chest Diseases|Dermatological Sciences|Dermatology and Venereology|Aesthetic [Mm]edicine)\b/i',
        '/\bFACHARTZ(?:\s*\/\s*Academy Fellowship(?:\s*\([^)]{0,40}\))?)?/i',
        '/\bAmerican Board of Aesthetic Medicine and Surgery(?:\s*\([^)]{0,40}\))?/i',
        '/\bFICO(?:\s*\([^)]{0,40}\))?/i',
        '/\bICO(?:\s*\([^)]{0,40}\))?/i',
        '/\bAD-HPE\b/i',
        '/\bFellowship Retina(?:\s*\([^)]{0,40}\))?/i',
        '/\bFellowship in Oculoplastic surgery(?:\s*\([^)]{0,40}\))?/i',
        '/\bMCPS-?\s*HPE\b/i',
        '/\bDA\b/',
        '/\bDip(?:loma)?(?:\s+in)?\s+CBT\b/i',
      ];
      foreach ($patterns as $re) {
        if (preg_match_all($re, $text, $ms)) {
          foreach ($ms[0] as $hit) {
            $hit = preg_replace('/^M\.?\s*Phil\.?/i', 'M.Phil', $hit) ?? $hit;
            $hit = preg_replace('/^FCPS-l\b/i', 'FCPS-I', $hit) ?? $hit;
            $hit = preg_replace('/^Master of Public Health\b/i', 'MPH', $hit) ?? $hit;
            $hit = preg_replace('/^MS\s*[-â€“]\s*(.+)$/i', 'MS ($1)', $hit) ?? $hit;
            $add($hit);
          }
        }
      }
      if (!$out) {
        foreach ($joined as $q) {
          $add(faculty_fix_runons($q));
        }
      }
      return $out;
    }
}

if (! function_exists('faculty_normalize_skills')) {
    function faculty_normalize_skills($items): array {
        if (is_string($items)) {
            $items = preg_split('/[\n;|]+/', $items) ?: [];
        }
        if (! is_array($items)) {
            return [];
        }
      $out = [];
      $seen = [];
      foreach ($items as $raw) {
        $s = faculty_fix_runons((string) $raw);
        $s = preg_replace('/\b(Former|Member of|Incharge|In charge)\b/i', '|$1', $s) ?? $s;
        foreach (preg_split('/[|;]+/', $s) ?: [] as $bit) {
          $bit = trim($bit, " ,:-");
          if (strlen($bit) < 8 || strlen($bit) > 88) {
            continue;
          }
          if (preg_match('/^\d{4}/', $bit) || preg_match('/^(college|university|hospital|institute|peshawar medical college)$/i', $bit)) {
            continue;
          }
          $key = strtolower(preg_replace('/\W+/', '', $bit) ?? '');
          if ($key === '' || isset($seen[$key])) {
            continue;
          }
          $seen[$key] = true;
          $out[] = $bit;
        }
      }
      return $out;
    }
}

if (! function_exists('faculty_public_current_post')) {
    /** Live profile JS uses the HRMS post title as-is, except owner/CEO rows. */
    function faculty_public_current_post(?array $directory, ?array $extra): string
    {
        $hrms = trim((string) ($directory['desTitle'] ?? ''));
        $cv = trim((string) ($extra['designation'] ?? ''));
        $probe = $hrms !== '' ? $hrms : $cv;
        if (preg_match('/owner|patron|founder|\bceo\b|chairman of (the )?prime/i', $probe)) {
            return faculty_normalize_designation($cv !== '' ? $cv : 'Professor');
        }
        if ($hrms !== '') {
            return $hrms;
        }

        return faculty_normalize_designation($cv);
    }
}

if (! function_exists('faculty_explode_publications')) {
    /** @param list<mixed>|mixed $pubs @return list<string> */
    function faculty_explode_publications($pubs): array
    {
        if (is_string($pubs)) {
            $pubs = preg_split('/\n+/', $pubs) ?: [];
        }
        if (! is_array($pubs)) {
            return [];
        }
        $out = [];
        $seen = [];
        foreach ($pubs as $raw) {
            $raw = trim(preg_replace('/\s+/', ' ', (string) $raw) ?? '');
            if ($raw === '' || strlen($raw) < 40) {
                continue;
            }
            $parts = [$raw];
            if (preg_match('/\d{4}[A-Z]/', $raw) || strlen($raw) > 550) {
                if (preg_match('/(?:^|\s)\d{1,2}[\.\)]\s+\S.{20,}/', $raw)) {
                    $parts = preg_split('/(?:^|\s)\d{1,2}[\.\)]\s+/', $raw) ?: [$raw];
                } else {
                    $parts = preg_split('/(?<=\d{4})(?=[A-Z])/', $raw) ?: [$raw];
                }
            }
            foreach ($parts as $p) {
                $p = trim($p, " \t.;•●-–—");
                if (strlen($p) < 40) {
                    continue;
                }
                $key = strtolower(substr(preg_replace('/\W+/', '', $p) ?? '', 0, 90));
                if ($key === '' || isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $out[] = $p;
            }
        }

        return $out;
    }
}

if (! function_exists('faculty_pub_year')) {
    function faculty_pub_year(string $cite): string
    {
        if (preg_match('/\b((?:19|20)\d{2})\b/', $cite, $m)) {
            return $m[1];
        }

        return '';
    }
}

if (! function_exists('faculty_pub_url')) {
    function faculty_pub_url(string $cite): string
    {
        if (preg_match('#https?://[^\s<>"]+#i', $cite, $m)) {
            return rtrim($m[0], '.,);');
        }
        if (preg_match('/\b(10\.\d{4,}\/[^\s<>"]+)/', $cite, $m)) {
            return 'https://doi.org/' . rtrim($m[1], '.,);');
        }

        return '';
    }
}

if (! function_exists('faculty_profile_has_cv')) {
    function faculty_profile_has_cv(array $rec): bool
    {
        if (! empty($rec['photo'])) {
            return true;
        }
        foreach (['qualifications', 'experience', 'publications', 'skills'] as $key) {
            if (! empty($rec[$key]) && is_array($rec[$key])) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('faculty_profile_link_html')) {
    /**
     * Linked faculty name for PG / department pages (legacy faculty_profile_link_html).
     */
    function faculty_profile_link_html(string $label, string $qual = ''): string
    {
        $nameHtml = '<span>' . esc($label) . '</span>';
        $qualHtml = $qual !== '' ? '<span class="pg-qual">' . esc($qual) . '</span>' : '';

        try {
            $cv = (new \App\Services\FacultyProfileService())->lookup($label);
        } catch (\Throwable $e) {
            $cv = null;
        }

        if ($cv && faculty_is_directory_rank((string) ($cv['designation'] ?? '')) && faculty_profile_has_cv($cv)) {
            $slug = (string) ($cv['slug'] ?? faculty_slug($label));
            $href = site_url('faculty-profile') . '?n=' . rawurlencode($slug);

            return '<a class="pg-staff-link" href="' . esc($href) . '">' . $nameHtml . $qualHtml . '</a>';
        }

        return $nameHtml . $qualHtml;
    }
}
