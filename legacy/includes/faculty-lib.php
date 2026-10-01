<?php
/**
 * Faculty profile helpers — slug matching against departmental CVs.
 */

if (!function_exists('str_starts_with')) {
  function str_starts_with($haystack, $needle): bool {
    $haystack = (string) $haystack;
    $needle = (string) $needle;
    return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
  }
}
if (!function_exists('str_ends_with')) {
  function str_ends_with($haystack, $needle): bool {
    $haystack = (string) $haystack;
    $needle = (string) $needle;
    if ($needle === '') {
      return true;
    }
    $len = strlen($needle);
    return $len <= strlen($haystack) && substr($haystack, -$len) === $needle;
  }
}
if (!function_exists('str_contains')) {
  function str_contains($haystack, $needle): bool {
    $haystack = (string) $haystack;
    $needle = (string) $needle;
    return $needle === '' || strpos($haystack, $needle) !== false;
  }
}

function faculty_slug(string $name): string {
  $n = trim($name);
  // Strip HRMS / department titles (full and abbreviated) so
  // "Sr. Registrar Dr. Abdul Rauf" and "Asst. Prof. Dr. Ihsan Ul Haq"
  // resolve to the same slug as CV JSON ("abdul-rauf", "ihsan-ul-haq").
  $titles = '/^(senior\s+registrar|sr\.?\s+registrar|associate\s+professor|assoc\.?\s+prof\.?|assistant\s+professor|asst\.?\s+prof\.?|professor|prof\.?|dr\.?)\s+/i';
  while (preg_match($titles, $n)) {
    $n = preg_replace($titles, '', $n, 1);
  }
  $n = strtolower($n);
  $n = preg_replace('/[^a-z0-9]+/', '-', $n);
  return trim($n, '-');
}

function faculty_slugs_match(string $a, string $b): bool {
  if ($a === '' || $b === '') {
    return false;
  }
  if ($a === $b) {
    return true;
  }
  $ta = array_values(array_filter(explode('-', $a)));
  $tb = array_values(array_filter(explode('-', $b)));
  if (!$ta || !$tb) {
    return false;
  }
  $fa = $ta[0];
  $fb = $tb[0];
  $la = $ta[count($ta) - 1];
  $lb = $tb[count($tb) - 1];
  $firstOk = $fa === $fb
    || (strlen($fa) >= 4 && strlen($fb) >= 4 && (str_starts_with($fa, substr($fb, 0, 4)) || str_starts_with($fb, substr($fa, 0, 4))));
  $lastOk = $la === $lb
    || str_starts_with($la, $lb)
    || str_starts_with($lb, $la)
    || (strlen($la) >= 4 && strlen($lb) >= 4 && substr($la, 0, 4) === substr($lb, 0, 4));
  return $firstOk && $lastOk;
}

function faculty_profiles_pack(): array {
  static $pack = null;
  if ($pack !== null) {
    return $pack;
  }

  $pack = ['profiles' => [], 'index' => [], 'source' => ''];

  // Base: master parity JSON under public/assets (CI4 layout).
  $candidates = [];
  if (defined('ROOTPATH')) {
    $candidates[] = ROOTPATH . 'public/assets/data/faculty-profiles.json';
  }
  $legacyRoot = dirname(__DIR__);
  $candidates[] = dirname($legacyRoot) . '/public/assets/data/faculty-profiles.json';
  $candidates[] = $legacyRoot . '/assets/data/faculty-profiles.json';

  foreach ($candidates as $path) {
    if (!is_file($path)) {
      continue;
    }
    $decoded = json_decode((string) file_get_contents($path), true);
    if (!is_array($decoded)) {
      continue;
    }
    $pack = [
      'profiles' => is_array($decoded['profiles'] ?? null) ? $decoded['profiles'] : [],
      'index' => is_array($decoded['index'] ?? null) ? $decoded['index'] : [],
      'source' => (string) ($decoded['source'] ?? 'json'),
    ];
    break;
  }

  // Overlay ACP / DB profiles (wins per slug).
  try {
    require_once __DIR__ . '/db.php';
    $pdo = dms_db();
    $rows = $pdo->query(
      'SELECT slug, aliases, name, department, designation, hod, photo,
              qualifications, experience, skills, publications, research_preferences,
              publications_url, contact_phone, source, updated_at
       FROM faculty_profiles'
    )->fetchAll();
  } catch (Throwable $e) {
    error_log('[faculty_profiles_pack] ' . $e->getMessage());
    $rows = [];
  }

  $decode = static function ($v) {
    if (is_array($v)) {
      return $v;
    }
    if (!is_string($v) || $v === '') {
      return [];
    }
    $d = json_decode($v, true);
    return is_array($d) ? $d : [];
  };

  foreach ($rows as $row) {
    $slug = (string) ($row['slug'] ?? '');
    if ($slug === '') {
      continue;
    }
    $aliases = $decode($row['aliases'] ?? '[]');
    $rec = [
      'slug' => $slug,
      'aliases' => $aliases,
      'name' => (string) ($row['name'] ?? ''),
      'department' => (string) ($row['department'] ?? ''),
      'designation' => (string) ($row['designation'] ?? ''),
      'hod' => !empty($row['hod']),
      'photo' => (string) ($row['photo'] ?? ''),
      'qualifications' => $decode($row['qualifications'] ?? '[]'),
      'experience' => $decode($row['experience'] ?? '[]'),
      'skills' => $decode($row['skills'] ?? '[]'),
      'publications' => $decode($row['publications'] ?? '[]'),
      'research_preferences' => $decode($row['research_preferences'] ?? '[]'),
      'publications_url' => (string) ($row['publications_url'] ?? ''),
      'contact_phone' => (string) ($row['contact_phone'] ?? ''),
      'source' => (string) ($row['source'] ?? 'db'),
    ];
    $photoUrl = faculty_photo_url($rec['photo']);
    if ($photoUrl === '') {
      unset($rec['photo']);
    } else {
      $rec['photo'] = $photoUrl;
    }
    if ($rec['publications_url'] === '') {
      unset($rec['publications_url']);
    }
    if ($rec['contact_phone'] === '') {
      unset($rec['contact_phone']);
    }
    $pack['profiles'][$slug] = $rec;
    $pack['index'][$slug] = $slug;
    foreach ($aliases as $alias) {
      $a = faculty_slug((string) $alias);
      if ($a !== '') {
        $pack['index'][$a] = $slug;
      }
    }
  }
  if ($rows !== []) {
    $pack['source'] = $pack['source'] !== '' ? $pack['source'] . '+db' : 'db';
  }
  return $pack;
}

function faculty_profile_lookup(string $slug): ?array {
  if ($slug === '') {
    return null;
  }
  $pack = faculty_profiles_pack();
  $canon = $pack['index'][$slug] ?? $slug;
  $rec = $pack['profiles'][$canon] ?? null;
  if (is_array($rec)) {
    return $rec;
  }
  foreach ($pack['profiles'] as $row) {
    $keys = array_merge([$row['slug'] ?? ''], $row['aliases'] ?? []);
    foreach ($keys as $key) {
      if (faculty_slugs_match($slug, (string) $key)) {
        return $row;
      }
    }
  }
  return null;
}

/**
 * Resolve a stored photo path (e.g. assets/images/faculty/foo.jpg) to a real file under public/.
 * CI4 serves from public/; legacy scripts live in legacy/ — never check __DIR__ alone.
 */
function faculty_photo_fs(?string $rel): string {
  $rel = ltrim(str_replace('\\', '/', (string) $rel), '/');
  if ($rel === '' || preg_match('#^(?:https?:)?//#i', $rel)) {
    return '';
  }

  $candidates = [];
  if (defined('FCPATH')) {
    $candidates[] = FCPATH . $rel;
  }
  if (defined('ROOTPATH')) {
    $candidates[] = ROOTPATH . 'public/' . $rel;
    $candidates[] = ROOTPATH . 'legacy/' . $rel;
  }
  // faculty-lib.php lives in legacy/includes/
  $legacyRoot = dirname(__DIR__);
  $candidates[] = dirname($legacyRoot) . '/public/' . $rel;
  $candidates[] = $legacyRoot . '/' . $rel;

  foreach ($candidates as $path) {
    if ($path !== '' && is_file($path)) {
      return $path;
    }
  }

  return '';
}

/**
 * Public URL for a faculty photo, or '' if the file is missing.
 */
function faculty_photo_url(?string $rel): string {
  $rel = ltrim(str_replace('\\', '/', (string) $rel), '/');
  if ($rel === '') {
    return '';
  }
  if (preg_match('#^(?:https?:)?//#i', $rel)) {
    return $rel;
  }
  if (faculty_photo_fs($rel) === '') {
    return '';
  }
  if (function_exists('base_url')) {
    return base_url($rel);
  }
  if (defined('base_url')) {
    return rtrim((string) constant('base_url'), '/') . '/' . $rel;
  }

  return $rel;
}

function faculty_profile_has_cv(?array $rec): bool {
  if (!$rec) {
    return false;
  }
  if (!empty($rec['photo'])) {
    return true;
  }
  foreach (['qualifications', 'experience', 'publications', 'skills', 'research_preferences', 'research_interests', 'research'] as $key) {
    if (!empty($rec[$key]) && is_array($rec[$key])) {
      return true;
    }
  }
  return false;
}

/**
 * Research preferences / interests for public faculty profiles.
 *
 * @return list<string>
 */
function faculty_research_preferences(array $rec): array {
  foreach (['research_preferences', 'research_interests', 'research', 'preferences'] as $key) {
    if (empty($rec[$key])) {
      continue;
    }
    $raw = $rec[$key];
    if (is_string($raw)) {
      $raw = preg_split('/[\n;|]+/', $raw) ?: [];
    }
    if (!is_array($raw)) {
      continue;
    }
    $out = [];
    $seen = [];
    foreach ($raw as $item) {
      $text = trim(faculty_soft_space((string) $item));
      if ($text === '') {
        continue;
      }
      $keyNorm = strtolower($text);
      if (isset($seen[$keyNorm])) {
        continue;
      }
      $seen[$keyNorm] = true;
      $out[] = $text;
    }
    if ($out) {
      return $out;
    }
  }
  return [];
}

function faculty_profile_lookup_cv(string $nameOrSlug): ?array {
  try {
    $rec = faculty_profile_lookup(faculty_slug($nameOrSlug));
    return faculty_profile_has_cv($rec) ? $rec : null;
  } catch (Throwable $e) {
    return null;
  }
}

function faculty_profile_link_html(string $label, string $qual = ''): string {
  $cv = faculty_profile_lookup_cv($label);
  $nameHtml = '<span>' . htmlspecialchars($label) . '</span>';
  $qualHtml = $qual !== '' ? '<span class="pg-qual">' . htmlspecialchars($qual) . '</span>' : '';
  if ($cv) {
    $href = 'faculty-profile?n=' . rawurlencode((string) ($cv['slug'] ?? faculty_slug($label)));
    return '<a class="pg-staff-link" href="' . htmlspecialchars($href) . '">' . $nameHtml . $qualHtml . '</a>';
  }
  return $nameHtml . $qualHtml;
}

function faculty_soft_space(string $text): string {
  $text = preg_replace('/([a-z])([A-Z])/', '$1 $2', $text);
  $text = preg_replace('/(Department)(Professor|Associate|Assistant)/', '$1 $2', $text);
  return trim(preg_replace('/\s+/', ' ', $text) ?? '');
}

function faculty_month_names(): array {
  return [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
}

function faculty_month_num(string $m): int {
  $key = strtolower(substr($m, 0, 3));
  $map = ['jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12];
  return $map[$key] ?? 0;
}

function faculty_year4(int $y): int {
  if ($y < 100) {
    return $y >= 50 ? 1900 + $y : 2000 + $y;
  }
  return $y;
}

function faculty_pretty_ymd(?int $y, ?int $m = null, ?int $d = null): string {
  if (!$y) {
    return '';
  }
  $months = faculty_month_names();
  if ($m && $d) {
    return (int) $d . ' ' . ($months[$m] ?? '') . ' ' . $y;
  }
  if ($m) {
    return ($months[$m] ?? '') . ' ' . $y;
  }
  return (string) $y;
}

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
  $text = str_replace(['—', '–', '−'], '-', $text);
  $text = preg_replace('/\bAT\b/', 'At', $text);
  $text = preg_replace('/([a-z])([A-Z])/', '$1 $2', $text);
  $text = preg_replace('/\bPh D\b/', 'PhD', $text);
  $text = preg_replace('/([A-Za-z])((?:19|20)\d{2})\b/', '$1 $2', $text);
  $text = preg_replace('/([A-Za-z])(\d{1,2}[-.\/]\d{1,2}[-.\/]\d{2,4})/', '$1 $2', $text);
  $text = preg_replace('/(\d)([A-Z][a-z]+)/', '$1 $2', $text);
  $text = preg_replace('/(\))([A-Z])/', '$1 $2', $text);
  $text = preg_replace('/\(([A-Za-z .]+)\&/', '($1) &', $text);
  $text = preg_replace('/\s+/', ' ', $text);
  return trim($text ?? '');
}

function faculty_normalize_designation(string $raw): string {
  $d = trim($raw);
  $d = preg_replace('/^head of department\s*/i', '', $d) ?? $d;
  $d = preg_replace('/\bhod\b/i', '', $d) ?? $d;
  $d = faculty_soft_space($d);
  $d = trim($d, " ,&");
  if (preg_match('/^assit\.?\s*prof\.?$/i', $d) || preg_match('/^asst\.?\s*prof\.?$/i', $d)) {
    return 'Assistant Professor';
  }
  if ($d === '' || strcasecmp($d, 'hod') === 0 || strcasecmp($d, 'head of department') === 0) {
    return '';
  }
  if (preg_match('/^(Associate Professor|Assistant Professor|Professor|Senior Lecturer|Lecturer|Senior Registrar|Registrar)\s+(?!\()(.+)$/i', $d, $m)) {
    return $m[1] . ' (' . trim($m[2], " ()") . ')';
  }
  return $d;
}

function faculty_normalize_qualifications(array $items): array {
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

  $text = faculty_fix_runons(implode(' · ', $joined));
  $repl = [
    'M.B.B.S' => 'MBBS', 'M.B.B.S.' => 'MBBS', 'F.C.P.S' => 'FCPS', 'F.C.P.S.' => 'FCPS',
    'M.C.P.S' => 'MCPS', 'C.H.P.E' => 'CHPE', 'M.PHIL' => 'M.Phil', 'M.Phil.' => 'M.Phil',
    'Ph. D' => 'PhD', 'Ph.D' => 'PhD', 'Ph.D.' => 'PhD', 'Bachelors of Medicine and Bachelors of Surgery' => 'MBBS',
    'Bachelor of Medicine & Bachelor of Surgery' => 'MBBS', 'Bachelor of Medicine and Bachelor of Surgery' => 'MBBS',
    'Bachelor of Dental Surgery' => 'BDS',
    'Certificate in Health Professional Education in Health Research' => 'CHR',
    'Certificate Course in Health Profession and Education' => 'CHPE',
    'Certificate in Health Professional Education' => 'CHPE',
  ];
  $text = str_ireplace(array_keys($repl), array_values($repl), $text);

  $out = [];
  $seen = [];
  $add = static function (string $label) use (&$out, &$seen): void {
    $label = trim(preg_replace('/\s+/', ' ', $label) ?? '', " ,;·|");
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
    '/\bFCPS(?:-I|-l)?(?:\s*\([^)]{0,50}\)|\s+(?:Histopathology|Hematology|Haematology|Pathology|Psychiatry|Pediatrics|Paediatrics|Surgery and Allied))?/i',
    '/\bMCPS(?:\s*\([^)]{0,50}\))?/i',
    '/\bMS(?:\s*[-–]\s*Mental Health Policy(?:\s+&\s+Services)?)?\b/i',
    '/\bIMM(?:\s*\([^)]{0,50}\))?/i',
    '/\bMRCS\b/i',
    '/\bM\.?\s*Phil\.?(?:\s*\([^)]{0,50}\)|\s+(?:Histopathology|Chemical Pathology|Hematology|Microbiology|Physiology|Oral Pathology)(?:\s+Scholar)?)?/i',
    '/\bPhD(?:\s*\([^)]{0,80}\))?(?:\s*[—–-]\s*[^·|]{8,90})?(?:\s+(?:Physiology|Microbiology))?/i',
    '/\b(?:MPH|Master of Public Health)\b/i',
    '/\bCHPE\b/i',
    '/\bCHR\b/i',
    '/\bMHPE\b/i',
    '/\bPGD(?:\s+in\s+[^,.(]{8,60})?/i',
    '/\bPGT(?:\s+Pharmacy)?\b/i',
    '/\bDCP\b/i',
    '/\bDCH\b/i',
    '/\bDOMS\b/i',
    '/\bDiploma in Gynae and Obs\b/i',
    '/\bDip(?:loma)?(?:\s+in)?\s+CBT\b/i',
  ];
  foreach ($patterns as $re) {
    if (preg_match_all($re, $text, $ms)) {
      foreach ($ms[0] as $hit) {
        $hit = preg_replace('/^M\.?\s*Phil\.?/i', 'M.Phil', $hit) ?? $hit;
        $hit = preg_replace('/^FCPS-l\b/i', 'FCPS-I', $hit) ?? $hit;
        $hit = preg_replace('/^Master of Public Health\b/i', 'MPH', $hit) ?? $hit;
        $hit = preg_replace('/^MS\s*[-–]\s*(.+)$/i', 'MS ($1)', $hit) ?? $hit;
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

function faculty_normalize_skills(array $items): array {
  $out = [];
  $seen = [];
  foreach ($items as $raw) {
    $s = faculty_fix_runons((string) $raw);
    $s = preg_replace('/\b(Former|Member of|Incharge|In charge)\b/i', '|$1', $s) ?? $s;
    foreach (preg_split('/[|.;]+/', $s) ?: [] as $bit) {
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

function faculty_exp_role_pattern(): string {
  return '(?:Head of Department|Head of Lab|Associate Professor|Assistant Professor|Assistant Dental Surgeon|Senior Consultant|Senior Lecturer|Senior Registrar|Senior Medical Officer|Theme Facilitator|House Job Officer|House Officer|House job|House Surgeon|Medical Superintendent|Medical Officer|Junior Registrar|District Pathologist|District Specialist|Post Graduate Trainee|Postgraduate Trainee|Postgraduate Resident|Trainee Medical Officer|Consultant Histopathologist|Consultant Pathologist|Consultant Psychiatrist|Dental Surgeon|Dental Assistant|M\.?\s*Phil\.? Trainee|In charge|Professor|Consultant|Supervisor|Examiner|Instructor|Resident|Demonstrator|Lecturer|Director|Registrar|Pathologist|Obstetrician|Internship|Section Head|Chair|Deputation)';
}

function faculty_exp_role_break_pattern(): string {
  return 'Head of Department|Associate Professor|Assistant Professor|Assistant Dental Surgeon|Senior Consultant|Senior Lecturer|Senior Registrar|Senior Medical Officer|Theme Facilitator|House Job Officer|House Officer|House Surgeon|District Pathologist|Post Graduate Trainee|Postgraduate Trainee|Postgraduate Resident|Trainee Medical Officer|Junior Registrar|Medical Superintendent|Medical Officer|Consultant Histopathologist|Consultant Pathologist|Consultant Psychiatrist|Dental Surgeon|Dental Assistant|M\.?\s*Phil\.? Trainee|Lecturer|Demonstrator|Professor of|Obstetrician|Section Head';
}

function faculty_split_exp_line(string $text): array {
  $text = faculty_fix_runons($text);
  if ($text === '') {
    return [];
  }

  $pieces = [];
  if (preg_match('/^(working|work|teaching|clinical)\s+experience:?\s*(.+)$/i', $text, $m)) {
    if (preg_match('/^(teaching|clinical)$/i', $m[1])) {
      $pieces[] = ucwords(strtolower(trim($m[1] . ' experience')));
    }
    $rest = trim($m[2], " \t.:");
    if ($rest !== '') {
      $pieces[] = $rest;
    }
  } else {
    $pieces[] = $text;
  }

  $role = faculty_exp_role_pattern();
  $break = faculty_exp_role_break_pattern();
  $out = [];
  foreach ($pieces as $piece) {
    $chunks = preg_split('/(?=\bAt\s+.{2,90})/', $piece) ?: [$piece];
    foreach ($chunks as $chunk) {
      $chunk = trim($chunk, " \t.;");
      if ($chunk === '' || $chunk === ':') {
        continue;
      }
      $afterDate = preg_split('/(?<=\d{4})\s+(?=(?:Senior|Associate|Assistant|Junior|' . $role . ')\b)/i', $chunk) ?: [$chunk];
      foreach ($afterDate as $seg) {
        $seg = trim($seg, " \t.;");
        if ($seg === '') {
          continue;
        }
        $hits = preg_match_all('/\b(?:' . $break . ')\b/i', $seg);
        if ($hits >= 2) {
          $roles = preg_split('/(?=\b(?:' . $break . ')\b)/i', $seg) ?: [$seg];
          foreach ($roles as $roleLine) {
            $roleLine = trim($roleLine, " \t.;");
            if ($roleLine !== '') {
              $out[] = $roleLine;
            }
          }
        } else {
          $out[] = $seg;
        }
      }
    }
  }
  return $out ?: [$text];
}

function faculty_exp_should_merge(string $cur, string $next): bool {
  $low = strtolower(rtrim($cur, " ,"));
  $nextLow = strtolower(trim($next));
  if (in_array($low, ['associate', 'assistant', 'senior', 'junior', 'professor', 'professor and', 'head', 'in charge', 'teaching experience as', 'have been working as'], true)) {
    return true;
  }
  if (preg_match('/^(till date|till now|present)$/i', $nextLow) && preg_match('/\d{4}/', $cur)) {
    return true;
  }
  if (str_ends_with(rtrim($cur), '&') || str_ends_with(rtrim($cur), '/') || preg_match('/\b(associate|assistant|senior|junior|and|as)$/i', $cur)) {
    return true;
  }
  return substr_count($cur, '(') > substr_count($cur, ')');
}

function faculty_exp_is_heading(string $text): bool {
  if (preg_match('/^(teaching|clinical)\s+experience:?$/i', $text)) {
    return true;
  }
  if (preg_match('/^at\s+.+/i', $text) && strlen($text) < 110) {
    return true;
  }
  if (str_ends_with($text, ':') && strlen($text) < 110 && !preg_match('/\d{4}/', $text) && !preg_match('/^(working|work)\s+experience/i', $text)) {
    return true;
  }
  return false;
}

function faculty_exp_collect_dates(string $text): array {
  $month = 'Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:t(?:ember)?)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?';
  $present = (bool) preg_match('/\b(till\s*(?:date|now)|to\s+date|present|retirement|to\s+till\s+date)\b/i', $text);
  $hits = [];

  $push = static function (int $off, int $len, string $pretty) use (&$hits): void {
    $end = $off + $len;
    foreach ($hits as $h) {
      if ($off < $h[1] && $end > $h[0]) {
        return;
      }
    }
    $hits[] = [$off, $end, $pretty];
  };

  if (preg_match_all('/\b(\d{1,2})(?:st|nd|rd|th)?\s+(' . $month . ')\.?\s+(\d{4})\b/i', $text, $ms, PREG_OFFSET_CAPTURE)) {
    foreach ($ms[0] as $i => $full) {
      $push((int) $full[1], strlen($full[0]), faculty_pretty_ymd((int) $ms[3][$i][0], faculty_month_num($ms[2][$i][0]), (int) $ms[1][$i][0]));
    }
  }
  if (preg_match_all('/\b(\d{1,2})[-.\/](\d{1,2})[-.\/](\d{2,4})\b/', $text, $ms, PREG_OFFSET_CAPTURE)) {
    foreach ($ms[0] as $i => $full) {
      $d = (int) $ms[1][$i][0];
      $m = (int) $ms[2][$i][0];
      $y = faculty_year4((int) $ms[3][$i][0]);
      if ($m > 12 && $d <= 12) {
        [$d, $m] = [$m, $d];
      }
      if ($m >= 1 && $m <= 12 && $d >= 1 && $d <= 31) {
        $push((int) $full[1], strlen($full[0]), faculty_pretty_ymd($y, $m, $d));
      }
    }
  }
  if (preg_match_all('/\b(' . $month . ')\.?\s*-?\s*(\d{4})\b/i', $text, $ms, PREG_OFFSET_CAPTURE)) {
    foreach ($ms[0] as $i => $full) {
      $push((int) $full[1], strlen($full[0]), faculty_pretty_ymd((int) $ms[2][$i][0], faculty_month_num($ms[1][$i][0])));
    }
  }
  if (preg_match_all('/\b(\d{1,2})\/(\d{4})\b/', $text, $ms, PREG_OFFSET_CAPTURE)) {
    foreach ($ms[0] as $i => $full) {
      $m = (int) $ms[1][$i][0];
      if ($m >= 1 && $m <= 12) {
        $push((int) $full[1], strlen($full[0]), faculty_pretty_ymd((int) $ms[2][$i][0], $m));
      }
    }
  }
  if (preg_match_all('/\b((?:19|20)\d{2})\b/', $text, $ms, PREG_OFFSET_CAPTURE)) {
    foreach ($ms[1] as $m) {
      $push((int) $m[1], strlen($m[0]), $m[0]);
    }
  }

  usort($hits, static fn($a, $b) => $a[0] <=> $b[0]);
  $dates = [];
  foreach ($hits as $h) {
    $dates[] = $h[2];
  }
  $dates = array_values(array_unique($dates));
  return ['dates' => $dates, 'present' => $present];
}

function faculty_exp_dates(string $text): string {
  $pack = faculty_exp_collect_dates($text);
  $dates = $pack['dates'];
  if (!$dates) {
    return $pack['present'] ? 'Present' : '';
  }
  $start = $dates[0];
  $end = count($dates) > 1 ? $dates[count($dates) - 1] : '';
  if ($pack['present']) {
    return $start . ' – Present';
  }
  if ($end !== '' && $end !== $start) {
    return $start . ' – ' . $end;
  }
  return $start;
}

function faculty_exp_strip_dates(string $text): string {
  $month = 'Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:t(?:ember)?)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?';
  $text = preg_replace('/\d{1,2}(?:st|nd|rd|th)?\s+(?:' . $month . '|January|February|March|April|June|July|August|September|October|November|December)\.?\s+\d{4}/i', ' ', $text);
  $text = preg_replace('/\d{1,2}[-.\/]\d{1,2}[-.\/]\d{2,4}/', ' ', $text);
  $text = preg_replace('/\d{1,2}\/\d{4}/', ' ', $text);
  $text = preg_replace('/(?:' . $month . ')\.?\s*-?\s*\d{4}/i', ' ', $text);
  $text = preg_replace('/\b(?:19|20)\d{2}\b/', ' ', $text);
  $text = preg_replace('/\b(?:till\s*(?:date|now)|to\s+date|present|retirement|from|to)\b/i', ' ', $text);
  $text = preg_replace('/[()]/', ' ', $text);
  return trim(preg_replace('/\s+/', ' ', $text) ?? '', " ,.;:-");
}

function faculty_exp_parse_role(string $text, string $fallbackTitle = ''): array {
  $dates = faculty_exp_dates($text);
  $core = faculty_exp_strip_dates($text);
  $core = preg_replace('/\b(in year|year)\b/i', '', $core) ?? $core;
  $core = trim($core, " ,.;:-");

  if (preg_match('/^(teaching|clinical)\s+experience:?$/i', $text) || preg_match('/^(teaching|clinical)\s+experience:?$/i', $core)) {
    $kind = preg_match('/clinical/i', $text . ' ' . $core) ? 'Clinical work' : 'Teaching';
    return ['kind' => 'heading', 'text' => $kind];
  }
  if (preg_match('/^(have been working as|as|till date)$/i', $core)) {
    return ['kind' => 'skip'];
  }

  if (faculty_exp_is_heading($text) || faculty_exp_is_heading($core . (str_ends_with($text, ':') ? ':' : ''))) {
    $label = preg_replace('/^at\s+/i', '', rtrim($text, ':'));
    $label = faculty_exp_strip_dates((string) $label);
    $label = trim(preg_replace('/\b(.{10,70}?)\s+\1\b/u', '$1', $label) ?? $label);
    if ($label !== '' && !preg_match('/^(working|work)\s+experience/i', $label)) {
      return ['kind' => 'heading', 'text' => $label];
    }
  }

  $role = faculty_exp_role_pattern();
  $spec = 'Histopathology|Chemical Pathology|Pathology|Microbiology|Hematology|Haematology|Physiology|Psychiatry|Behavioural Sciences|Behavioral Sciences|Public Health|Anatomy|Medicine|Surgery|Radiology|Gynaecology|Gynecology|Obstetrics|Pediatrics|Paediatrics|Endocrinology|Orthopaedics|Orthopedic Surgery|General Surgery|Plastic Surgery|Community [Mm]edicine|Medical Education';
  $title = '';
  $detail = $core;
  if (preg_match('/^(Head(?:,?\s+Department of [A-Za-z &]+))/i', $core, $hm)) {
    $title = trim($hm[1], ' ,');
    $detail = trim(substr($core, strlen($hm[1])), ' ,');
  } elseif (preg_match('~^((?:Senior |Junior |Trainee )?(?:' . $role . '))(?:\s*\(([^)]{0,40})\))?(?:\s*(?:in|of|-)?\s*(' . $spec . '))?~i', $core, $m)) {
    $title = trim($m[1]);
    if (!empty($m[2])) {
      $title .= ' (' . trim($m[2]) . ')';
    } elseif (!empty($m[3])) {
      $title .= ', ' . trim($m[3]);
    }
    $detail = trim(substr($core, strlen($m[0])), ' ,');
  } elseif (preg_match('/^(Supervisor|Examiner|Director)\b(.{0,70}?)(?=\s+(?:at\s+)?(?:Peshawar|College|Hospital|University|Institute|\z)|$)/i', $core, $m)) {
    $title = trim($m[1] . ' ' . $m[2]);
    $detail = trim(substr($core, strlen($m[0])), ' ,');
  }

  while ($detail !== '' && preg_match('/^\/\s*((?:Senior |Junior |Trainee )?(?:' . $role . '))/i', $detail, $sm)) {
    $title = trim($title . ' / ' . trim($sm[1]));
    $detail = trim(substr($detail, strlen($sm[0])), ' ,');
  }
  if ($detail !== '' && preg_match('/^and Head of Department\b/i', $detail, $hm)) {
    $title = trim($title . ' ' . $hm[0]);
    $detail = trim(substr($detail, strlen($hm[0])), ' ,');
  }
  if (preg_match('/^Director$/i', $title) && preg_match('/^(?:of\s+)?Research\b/i', $detail)) {
    $title = 'Director of Research';
    $detail = trim(preg_replace('/^(?:of\s+)?Research,?/i', '', $detail) ?? $detail, ' ,');
  }
  if ($detail !== '' && preg_match('/^(and Program Coordinator\b[^,]*)/i', $detail, $hm)) {
    $title = trim($title . ' ' . $hm[1]);
    $detail = trim(substr($detail, strlen($hm[1])), ' ,');
  }
  if ($detail !== '' && preg_match('/^Histopathologist\b/i', $detail) && preg_match('/Consultant$/i', $title)) {
    $title .= ' Histopathologist';
    $detail = trim(preg_replace('/^Histopathologist,?/i', '', $detail) ?? $detail);
  }
  if ($detail !== '' && preg_match('/^(Department of [A-Za-z &]+)/i', $detail, $dm)) {
    $title = trim($title . ', ' . $dm[1]);
    $detail = trim(substr($detail, strlen($dm[1])), ' ,');
  }
  if ($title !== '' && $detail !== '' && strncasecmp($detail, $title, strlen($title)) === 0) {
    $detail = trim(substr($detail, strlen($title)), ' ,');
  }

  if ($title === '') {
    $roleRe = faculty_exp_role_pattern();
    $placeOnly = !preg_match('/^(?:' . $roleRe . ')\b/i', $core)
      && preg_match('/\b(college|university|hospital|institute|academy|cpsp|pgmi)\b/i', $core)
      && strlen($core) < 120;
    if ($placeOnly && $dates === '') {
      return ['kind' => 'heading', 'text' => $core];
    }
    if ($core !== '' && !preg_match('/^\d/', $core)) {
      $title = $core;
      $detail = '';
    } elseif ($dates !== '' && $fallbackTitle !== '') {
      $title = $fallbackTitle;
      $detail = 'Peshawar Medical College';
    } else {
      return ['kind' => 'skip'];
    }
  }

  $title = trim(preg_replace('/\s+/', ' ', $title) ?? $title);
  $title = preg_replace_callback('/\b(associate professor|assistant professor|senior lecturer|senior registrar|senior consultant|assistant dental surgeon|house officer|house job|medical officer|professor|lecturer|demonstrator|consultant|resident|director|supervisor|examiner|pathologist)\b/i', static function ($m) {
    return ucwords(strtolower($m[0]));
  }, $title) ?? $title;
  $title = preg_replace('/\b(from|to)\b/i', '', $title) ?? $title;
  $title = trim($title, " ,;-");
  $detail = preg_replace('/\b(from|to)\b/i', '', $detail) ?? $detail;
  $detail = trim($detail, " ,;-");
  $detail = preg_replace('/^(in|at|of|:|&)\s+/i', '', $detail) ?? $detail;
  $detail = preg_replace('/^have been working as\s+/i', '', $detail) ?? $detail;
  $detail = trim($detail, " ,;-:&");
  $detail = trim(preg_replace('/\b(.{10,60}?)\s+\1\b/u', '$1', $detail) ?? $detail);
  if (strcasecmp($detail, $title) === 0 || strlen($detail) < 3) {
    $detail = '';
  }
  if ($detail !== '' && str_contains(strtolower($title), strtolower($detail)) && strlen($detail) < 20) {
    $detail = '';
  }

  return [
    'kind' => 'role',
    'title' => $title,
    'dates' => $dates,
    'detail' => $detail,
  ];
}

function faculty_exp_sort_ymd(string $pretty): int {
  $pretty = trim($pretty);
  if ($pretty === '') {
    return 0;
  }
  if (preg_match('/present/i', $pretty)) {
    return 99999999;
  }
  $months = 'Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:t(?:ember)?)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?';
  if (preg_match('/^(\d{1,2})\s+(' . $months . ')\.?\s+(\d{4})$/i', $pretty, $m)) {
    return ((int) $m[3] * 10000) + (faculty_month_num($m[2]) * 100) + (int) $m[1];
  }
  if (preg_match('/^(' . $months . ')\.?\s+(\d{4})$/i', $pretty, $m)) {
    return ((int) $m[2] * 10000) + (faculty_month_num($m[1]) * 100) + 28;
  }
  if (preg_match('/^(\d{4})$/', $pretty, $m)) {
    return ((int) $m[1] * 10000) + 1231;
  }
  return 0;
}

function faculty_exp_date_sort_value(string $dates): int {
  $dates = trim($dates);
  if ($dates === '') {
    return 0;
  }
  if (preg_match('/present/i', $dates)) {
    return 99999999;
  }
  $parts = preg_split('/\s+[–—-]\s+/u', $dates) ?: [$dates];
  $end = trim((string) end($parts));
  return faculty_exp_sort_ymd($end);
}

function faculty_sort_experience(array $rows): array {
  $groups = [];
  $current = ['heading' => null, 'roles' => []];
  foreach ($rows as $row) {
    if (($row['kind'] ?? '') === 'heading') {
      if ($current['heading'] !== null || $current['roles']) {
        $groups[] = $current;
      }
      $current = ['heading' => $row, 'roles' => []];
      continue;
    }
    $current['roles'][] = $row;
  }
  if ($current['heading'] !== null || $current['roles']) {
    $groups[] = $current;
  }

  $key = static fn(array $role): int => faculty_exp_date_sort_value((string) ($role['dates'] ?? ''));
  $groupMax = static function (array $group) use ($key): int {
    $max = 0;
    foreach ($group['roles'] as $role) {
      $max = max($max, $key($role));
    }
    return $max;
  };

  foreach ($groups as &$group) {
    usort($group['roles'], static fn($a, $b) => $key($b) <=> $key($a));
  }
  unset($group);

  usort($groups, static fn($a, $b) => $groupMax($b) <=> $groupMax($a));

  $out = [];
  foreach ($groups as $group) {
    if ($group['heading']) {
      $out[] = $group['heading'];
    }
    foreach ($group['roles'] as $role) {
      $out[] = $role;
    }
  }
  return $out;
}

function faculty_normalize_experience(array $items, string $fallbackTitle = ''): array {
  $fallbackTitle = preg_replace('/\s*\(.*\)$/', '', faculty_normalize_designation($fallbackTitle)) ?? $fallbackTitle;
  $flat = [];
  foreach ($items as $item) {
    foreach (faculty_split_exp_line((string) $item) as $piece) {
      $flat[] = $piece;
    }
  }

  $merged = [];
  $n = count($flat);
  for ($i = 0; $i < $n; $i++) {
    $cur = $flat[$i];
    $parenMerges = 0;
    while (isset($flat[$i + 1]) && faculty_exp_should_merge($cur, $flat[$i + 1])) {
      $unclosed = substr_count($cur, '(') > substr_count($cur, ')');
      if ($unclosed) {
        if ($parenMerges >= 1) {
          $cur .= ')';
          break;
        }
        $parenMerges++;
      }
      $next = ltrim($flat[$i + 1]);
      $cur = preg_match('/^[a-z ]+$/i', rtrim($cur, ' ,'))
        ? ucfirst(strtolower(rtrim($cur, ' ,'))) . ' ' . $next
        : rtrim($cur) . ' ' . $next;
      $i++;
    }
    $merged[] = $cur;
  }

  $out = [];
  foreach ($merged as $text) {
    $text = faculty_fix_runons($text);
    if ($text === '') {
      continue;
    }
    $row = faculty_exp_parse_role($text, $fallbackTitle);
    if (($row['kind'] ?? '') === 'skip') {
      continue;
    }
    $out[] = $row;
  }
  return faculty_sort_experience($out);
}

function faculty_explode_publications(array $pubs): array {
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

function faculty_pub_year(string $cite): string {
  if (preg_match('/\b((?:19|20)\d{2})\b/', $cite, $m)) {
    return $m[1];
  }
  return '';
}

function faculty_pub_url(string $cite): string {
  if (preg_match('#https?://[^\s<>"]+#i', $cite, $m)) {
    return rtrim($m[0], '.,);');
  }
  if (preg_match('/\b(10\.\d{4,}\/[^\s<>"]+)/', $cite, $m)) {
    return 'https://doi.org/' . rtrim($m[1], '.,);');
  }
  return '';
}

/** HRMS employee directory (PMC / DMS). */
function faculty_hrms_api_url(): string {
  return 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php';
}

function faculty_hrms_cache_path(): string {
  return sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'dms_hrms_faculty_cache.json';
}

/**
 * @return array{ok:bool,employees:array<int,array>,error:?string}
 */
function faculty_hrms_fetch_all(int $ttlSeconds = 900): array {
  static $memo = null;
  if (is_array($memo)) {
    return $memo;
  }

  $cache = faculty_hrms_cache_path();
  if (is_file($cache) && (time() - (int) filemtime($cache)) < $ttlSeconds) {
    $decoded = json_decode((string) file_get_contents($cache), true);
    if (is_array($decoded)) {
      $memo = ['ok' => true, 'employees' => $decoded, 'error' => null];
      return $memo;
    }
  }

  $url = faculty_hrms_api_url();
  $raw = false;
  $error = null;

  if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 15,
      CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $raw = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($raw === false || $code !== 200) {
      $error = curl_error($ch) ?: ('HTTP ' . $code);
      $raw = false;
    }
    curl_close($ch);
  } else {
    $raw = @file_get_contents($url);
    if ($raw === false) {
      $error = 'file_get_contents failed';
    }
  }

  if ($raw === false) {
    $memo = ['ok' => false, 'employees' => [], 'error' => $error ?: 'fetch failed'];
    return $memo;
  }

  $decoded = json_decode($raw, true);
  if (!is_array($decoded)) {
    $memo = ['ok' => false, 'employees' => [], 'error' => 'invalid JSON'];
    return $memo;
  }

  @file_put_contents($cache, json_encode($decoded));
  $memo = ['ok' => true, 'employees' => $decoded, 'error' => null];
  return $memo;
}

function faculty_hrms_norm_dept(string $name): string {
  $n = strtolower(trim($name));
  $n = str_replace(['&', '/'], ['and', ' '], $n);
  $n = preg_replace('/\s+/', ' ', $n) ?? $n;
  return trim($n);
}

/**
 * HRMS depName values that map to a site department slug.
 *
 * @return list<string>
 */
function faculty_hrms_dept_names_for_slug(string $slug, string $deptName = ''): array {
  $map = [
    'chs' => ['CHS', 'Community Health Sciences'],
    'dhpe' => ['DHPE & R', 'DHPE & Research'],
    'gynaecology' => ['Gynae and Obstetrics', 'Gynaecology & Obstetrics', 'Gynaecology'],
    'orthopaedics' => ['Orthopedics', 'Orthopaedics'],
    'pulmonology' => ['Pulmonology'],
    'accident-emergency' => ['Accident and Emergency'],
    'paeds-cardiology' => ['Paeds Cardiology'],
  ];
  $names = $map[$slug] ?? [];
  if ($deptName !== '') {
    $names[] = $deptName;
  }
  return array_values(array_unique(array_filter(array_map('trim', $names))));
}

function faculty_hrms_desig_rank(string $desTitle): int {
  $rank = [
    'Professor' => 1,
    'Associate Professor' => 2,
    'Assistant Professor' => 3,
    'Senior Lecturer' => 4,
    'Lecturer' => 5,
    'Senior Registrar' => 6,
    'Registrar' => 7,
    'CEO' => 8,
    'Director IT' => 9,
  ];
  return $rank[$desTitle] ?? 10;
}

function faculty_hrms_display_name(array $row): string {
  $desTitle = trim((string) ($row['desTitle'] ?? ''));
  $name = trim((string) ($row['empName'] ?? ''));
  $prefixMap = [
    'Professor' => 'Prof.',
    'Associate Professor' => 'Assoc. Prof.',
    'Assistant Professor' => 'Asst. Prof.',
    'Senior Lecturer' => 'Sr. Lecturer',
    'Lecturer' => 'Lecturer',
    'Senior Registrar' => 'Sr. Registrar',
    'Registrar' => 'Registrar',
    'CEO' => 'CEO',
    'Director IT' => 'Director',
  ];
  $prefix = $prefixMap[$desTitle] ?? '';
  $medical = [
    'Professor', 'Associate Professor', 'Assistant Professor',
    'Senior Lecturer', 'Lecturer', 'Senior Registrar', 'Registrar',
  ];
  if (in_array($desTitle, $medical, true)) {
    return $prefix !== '' ? ($prefix . ' Dr. ' . $name) : ('Dr. ' . $name);
  }
  return $prefix !== '' ? trim($prefix . ' ' . $name) : $name;
}

function faculty_is_hod_name(string $memberName, string $hodName): bool {
  if ($hodName === '' || $memberName === '') {
    return false;
  }
  if (strcasecmp(trim($memberName), trim($hodName)) === 0) {
    return true;
  }
  return faculty_slugs_match(faculty_slug($memberName), faculty_slug($hodName));
}

/**
 * Faculty for a department page from HRMS (name / qualification / reg).
 * Falls back to $staticFaculty only when the HRMS request fails.
 * HoD (when provided) is sorted first.
 *
 * @param list<array{name?:string,qualification?:string,reg?:string}> $staticFaculty
 * @return list<array{name:string,qualification:string,reg:string}>
 */
/**
 * Designations excluded from public faculty / department listings.
 *
 * @return array<string,true>
 */
function faculty_public_hidden_desigs(): array {
  return [
    'Senior Lecturer' => true,
    'Lecturer' => true,
    'Junior Registrar' => true,
  ];
}

function faculty_public_desig_allowed(string $desTitle): bool {
  return !isset(faculty_public_hidden_desigs()[trim($desTitle)]);
}

function faculty_public_static_row_allowed(array $row): bool {
  $name = trim((string) ($row['name'] ?? ''));
  if ($name === '') {
    return false;
  }
  if (preg_match('/^(Senior\s+Lecturer|Junior\s+Registrar|Sr\.?\s+Lecturer|Lecturer)\b/i', $name)) {
    return false;
  }
  return true;
}

function faculty_for_department_page(string $slug, string $deptName, array $staticFaculty = [], string $hodName = ''): array {
  $pack = faculty_hrms_fetch_all();
  if (!$pack['ok']) {
    $out = array_values(array_filter($staticFaculty, 'faculty_public_static_row_allowed'));
    return faculty_sort_hod_first($out, $hodName);
  }

  $wanted = [];
  foreach (faculty_hrms_dept_names_for_slug($slug, $deptName) as $label) {
    $wanted[faculty_hrms_norm_dept($label)] = true;
  }

  $matched = [];
  foreach ($pack['employees'] as $row) {
    if (!is_array($row)) {
      continue;
    }
    if (!faculty_public_desig_allowed((string) ($row['desTitle'] ?? ''))) {
      continue;
    }
    $dep = faculty_hrms_norm_dept((string) ($row['depName'] ?? ''));
    if ($dep === '' || empty($wanted[$dep])) {
      continue;
    }
    $matched[] = $row;
  }

  usort($matched, static function ($a, $b) {
    $ra = faculty_hrms_desig_rank((string) ($a['desTitle'] ?? ''));
    $rb = faculty_hrms_desig_rank((string) ($b['desTitle'] ?? ''));
    if ($ra !== $rb) {
      return $ra <=> $rb;
    }
    return strcasecmp((string) ($a['empName'] ?? ''), (string) ($b['empName'] ?? ''));
  });

  $out = [];
  foreach ($matched as $row) {
    $out[] = [
      'name' => faculty_hrms_display_name($row),
      'qualification' => trim((string) ($row['qualifications'] ?? '')),
      'reg' => trim((string) ($row['facPMDCNo'] ?? '')),
    ];
  }
  return faculty_sort_hod_first($out, $hodName);
}

/**
 * @param list<array{name?:string,qualification?:string,reg?:string}> $faculty
 * @return list<array{name?:string,qualification?:string,reg?:string}>
 */
function faculty_sort_hod_first(array $faculty, string $hodName): array {
  if ($hodName === '' || !$faculty) {
    return array_values($faculty);
  }
  $indexed = [];
  foreach (array_values($faculty) as $i => $row) {
    $indexed[] = ['i' => $i, 'row' => $row];
  }
  usort($indexed, static function ($a, $b) use ($hodName) {
    $aHod = faculty_is_hod_name((string) ($a['row']['name'] ?? ''), $hodName) ? 0 : 1;
    $bHod = faculty_is_hod_name((string) ($b['row']['name'] ?? ''), $hodName) ? 0 : 1;
    if ($aHod !== $bHod) {
      return $aHod <=> $bHod;
    }
    return $a['i'] <=> $b['i'];
  });
  return array_map(static function ($item) {
    return $item['row'];
  }, $indexed);
}

function faculty_hrms_count_for_department(string $slug, string $deptName, ?int $staticCount = null): int {
  $pack = faculty_hrms_fetch_all();
  if (!$pack['ok']) {
    return $staticCount !== null ? $staticCount : 0;
  }
  $wanted = [];
  foreach (faculty_hrms_dept_names_for_slug($slug, $deptName) as $label) {
    $wanted[faculty_hrms_norm_dept($label)] = true;
  }
  $n = 0;
  foreach ($pack['employees'] as $row) {
    if (!is_array($row)) {
      continue;
    }
    if (!faculty_public_desig_allowed((string) ($row['desTitle'] ?? ''))) {
      continue;
    }
    $dep = faculty_hrms_norm_dept((string) ($row['depName'] ?? ''));
    if ($dep !== '' && !empty($wanted[$dep])) {
      $n++;
    }
  }
  return $n;
}

/** Faculty self-update submissions (not published until applied by admin). */
function faculty_submissions_dir(): string {
  if (defined('ROOTPATH')) {
    return rtrim(ROOTPATH, '/\\') . '/data/faculty-submissions';
  }
  // legacy/includes → project root is two levels up
  return dirname(__DIR__, 2) . '/data/faculty-submissions';
}

function faculty_submissions_photos_dir(): string {
  return faculty_submissions_dir() . '/photos';
}

/**
 * Split textarea / multi-line field into clean list items.
 *
 * @return list<string>
 */
function faculty_parse_lines_field($raw, int $maxItems = 12): array {
  if (is_array($raw)) {
    $parts = $raw;
  } else {
    $parts = preg_split('/[\r\n]+/', (string) $raw) ?: [];
  }
  $out = [];
  $seen = [];
  foreach ($parts as $part) {
    $text = trim(faculty_soft_space((string) $part));
    $text = trim($text, " \t•●\-–—");
    if ($text === '') {
      continue;
    }
    if (strlen($text) > 220) {
      $text = substr($text, 0, 220);
    }
    $key = strtolower($text);
    if (isset($seen[$key])) {
      continue;
    }
    $seen[$key] = true;
    $out[] = $text;
    if (count($out) >= $maxItems) {
      break;
    }
  }
  return $out;
}

function faculty_submission_rate_limited(string $ip, int $seconds = 45): bool {
  $dir = faculty_submissions_dir() . '/.rate';
  if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
    return false;
  }
  $file = $dir . '/' . hash('sha256', $ip) . '.txt';
  $now = time();
  if (is_file($file)) {
    $last = (int) trim((string) @file_get_contents($file));
    if ($last > 0 && ($now - $last) < $seconds) {
      return true;
    }
  }
  @file_put_contents($file, (string) $now);
  return false;
}

/**
 * @param array<string,mixed> $payload
 * @return array{ok:bool,id?:string,error?:string}
 */
function faculty_save_submission(array $payload, ?array $photoFile = null, ?array $publicationsFile = null): array {
  $dir = faculty_submissions_dir();
  $photoDir = faculty_submissions_photos_dir();
  $docsDir = $dir . '/docs';
  if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
    return ['ok' => false, 'error' => 'Could not create submissions folder.'];
  }
  if (!is_dir($photoDir) && !@mkdir($photoDir, 0755, true) && !is_dir($photoDir)) {
    return ['ok' => false, 'error' => 'Could not create photo folder.'];
  }
  if (!is_dir($docsDir) && !@mkdir($docsDir, 0755, true) && !is_dir($docsDir)) {
    return ['ok' => false, 'error' => 'Could not create documents folder.'];
  }

  $id = date('Ymd-His') . '-' . bin2hex(random_bytes(4));
  $photoRel = null;
  $publicationsFileRel = null;

  if ($photoFile && !empty($photoFile['tmp_name']) && (int) ($photoFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if ((int) ($photoFile['error'] ?? 0) !== UPLOAD_ERR_OK) {
      return ['ok' => false, 'error' => 'Photo upload failed. Try a smaller JPG or PNG.'];
    }
    if ((int) ($photoFile['size'] ?? 0) > 2.5 * 1024 * 1024) {
      return ['ok' => false, 'error' => 'Photo must be under 2.5 MB.'];
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($photoFile['tmp_name']) ?: '';
    $map = [
      'image/jpeg' => 'jpg',
      'image/png' => 'png',
      'image/webp' => 'webp',
    ];
    if (!isset($map[$mime])) {
      return ['ok' => false, 'error' => 'Photo must be JPG, PNG, or WEBP.'];
    }
    $photoName = $id . '.' . $map[$mime];
    $dest = $photoDir . '/' . $photoName;
    if (!@move_uploaded_file($photoFile['tmp_name'], $dest)) {
      return ['ok' => false, 'error' => 'Could not save photo.'];
    }
    @chmod($dest, 0644);
    $photoRel = 'photos/' . $photoName;
  }

  if ($publicationsFile && !empty($publicationsFile['tmp_name']) && (int) ($publicationsFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if ((int) ($publicationsFile['error'] ?? 0) !== UPLOAD_ERR_OK) {
      return ['ok' => false, 'error' => 'Publications file upload failed. Try PDF or Word again.'];
    }
    if ((int) ($publicationsFile['size'] ?? 0) > 8 * 1024 * 1024) {
      return ['ok' => false, 'error' => 'Publications file must be under 8 MB.'];
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($publicationsFile['tmp_name']) ?: '';
    $orig = strtolower((string) ($publicationsFile['name'] ?? ''));
    $extFromName = pathinfo($orig, PATHINFO_EXTENSION);
    $map = [
      'application/pdf' => 'pdf',
      'application/msword' => 'doc',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
      'text/plain' => 'txt',
      'text/rtf' => 'rtf',
      'application/rtf' => 'rtf',
    ];
    $ext = $map[$mime] ?? '';
    if ($ext === '' && in_array($extFromName, ['pdf', 'doc', 'docx', 'txt', 'rtf'], true)) {
      // Some hosts report odd MIME for Office files; allow by extension.
      $ext = $extFromName;
    }
    if ($ext === '') {
      return ['ok' => false, 'error' => 'Publications file must be PDF, Word, or TXT.'];
    }
    $docName = $id . '-publications.' . $ext;
    $dest = $docsDir . '/' . $docName;
    if (!@move_uploaded_file($publicationsFile['tmp_name'], $dest)) {
      return ['ok' => false, 'error' => 'Could not save publications file.'];
    }
    @chmod($dest, 0644);
    $publicationsFileRel = 'docs/' . $docName;
  }

  $record = [
    'id' => $id,
    'submitted_at' => date('c'),
    'ip' => (string) ($payload['ip'] ?? ''),
    'college' => (string) ($payload['college'] ?? 'dms'),
    'emp_name' => (string) ($payload['emp_name'] ?? ''),
    'slug' => (string) ($payload['slug'] ?? ''),
    'des_title' => (string) ($payload['des_title'] ?? ''),
    'dep_name' => (string) ($payload['dep_name'] ?? ''),
    'research_preferences' => array_values($payload['research_preferences'] ?? []),
    'publications_url' => (string) ($payload['publications_url'] ?? ''),
    'publications_file' => $publicationsFileRel,
    'publications' => array_values($payload['publications'] ?? []),
    'qualifications' => array_values($payload['qualifications'] ?? []),
    'skills' => array_values($payload['skills'] ?? []),
    'contact_phone' => (string) ($payload['contact_phone'] ?? ''),
    'photo' => $photoRel,
    'status' => 'pending',
  ];

  // Keep a local JSON backup of each submission.
  $path = $dir . '/' . $id . '.json';
  $json = json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  if ($json === false || @file_put_contents($path, $json . "\n") === false) {
    return ['ok' => false, 'error' => 'Could not save your form. Please try again.'];
  }
  @chmod($path, 0644);

  // Primary store: MySQL
  try {
    require_once __DIR__ . '/db.php';
    $pdo = dms_db();
    $stmt = $pdo->prepare(
      'INSERT INTO faculty_profile_submissions (
        submission_key, submitted_at, ip, college, emp_name, slug, des_title, dep_name,
        research_preferences, publications_url, publications_file, publications,
        qualifications, skills, contact_phone, photo, status
      ) VALUES (
        :submission_key, :submitted_at, :ip, :college, :emp_name, :slug, :des_title, :dep_name,
        :research_preferences, :publications_url, :publications_file, :publications,
        :qualifications, :skills, :contact_phone, :photo, :status
      )'
    );
    $submittedAt = date('Y-m-d H:i:s');
    $stmt->execute([
      ':submission_key' => $id,
      ':submitted_at' => $submittedAt,
      ':ip' => $record['ip'] !== '' ? $record['ip'] : null,
      ':college' => $record['college'] !== '' ? $record['college'] : 'dms',
      ':emp_name' => $record['emp_name'],
      ':slug' => $record['slug'],
      ':des_title' => $record['des_title'] !== '' ? $record['des_title'] : null,
      ':dep_name' => $record['dep_name'] !== '' ? $record['dep_name'] : null,
      ':research_preferences' => json_encode($record['research_preferences'], JSON_UNESCAPED_UNICODE),
      ':publications_url' => $record['publications_url'] !== '' ? $record['publications_url'] : null,
      ':publications_file' => $publicationsFileRel,
      ':publications' => json_encode($record['publications'], JSON_UNESCAPED_UNICODE),
      ':qualifications' => json_encode($record['qualifications'], JSON_UNESCAPED_UNICODE),
      ':skills' => json_encode($record['skills'], JSON_UNESCAPED_UNICODE),
      ':contact_phone' => $record['contact_phone'] !== '' ? $record['contact_phone'] : null,
      ':photo' => $photoRel,
      ':status' => 'pending',
    ]);
  } catch (Throwable $e) {
    error_log('[faculty_save_submission] DB error: ' . $e->getMessage());
    return ['ok' => false, 'error' => 'Could not save to database. Please try again later.'];
  }

  return ['ok' => true, 'id' => $id];
}
