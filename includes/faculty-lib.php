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
  $titles = '/^(associate professor|assistant professor|professor|prof\.?|dr\.?)\s+/i';
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
  $path = dirname(__DIR__) . '/assets/data/faculty-profiles.json';
  if (!is_file($path)) {
    $pack = ['profiles' => [], 'index' => [], 'source' => ''];
    return $pack;
  }
  $decoded = json_decode((string) file_get_contents($path), true);
  $pack = is_array($decoded) ? $decoded : ['profiles' => [], 'index' => [], 'source' => ''];
  $pack['profiles'] = $pack['profiles'] ?? [];
  $pack['index'] = $pack['index'] ?? [];
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

function faculty_profile_has_cv(?array $rec): bool {
  if (!$rec) {
    return false;
  }
  if (!empty($rec['photo'])) {
    return true;
  }
  foreach (['qualifications', 'experience', 'publications', 'skills'] as $key) {
    if (!empty($rec[$key]) && is_array($rec[$key])) {
      return true;
    }
  }
  return false;
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
