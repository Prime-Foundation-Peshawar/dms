<?php
/**
 * Accept faculty self-update form posts. Saves for admin review (not live).
 */
require_once __DIR__ . '/includes/faculty-lib.php';

function faculty_update_redirect(string $query): void {
  $base = 'faculty-update';
  header('Location: ' . $base . '?' . $query, true, 303);
  exit;
}

function faculty_clean_url(string $url): string {
  $url = trim($url);
  if ($url === '') {
    return '';
  }
  if (!preg_match('#^https?://#i', $url)) {
    $url = 'https://' . $url;
  }
  if (filter_var($url, FILTER_VALIDATE_URL) === false) {
    return '';
  }
  if (strlen($url) > 400) {
    $url = substr($url, 0, 400);
  }
  return $url;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  faculty_update_redirect('');
}

// Honeypot
if (trim((string) ($_POST['website'] ?? '')) !== '') {
  faculty_update_redirect('thanks=1');
}

$ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
if (faculty_submission_rate_limited($ip)) {
  faculty_update_redirect('error=' . rawurlencode('Please wait about a minute before submitting again.'));
}

$empName = trim((string) ($_POST['emp_name'] ?? ''));
$slug = faculty_slug((string) ($_POST['slug'] ?? $empName));
$desTitle = trim((string) ($_POST['des_title'] ?? ''));
$depName = trim((string) ($_POST['dep_name'] ?? ''));
$phone = trim((string) ($_POST['contact_phone'] ?? ''));
$research = faculty_parse_lines_field($_POST['research_preferences'] ?? '', 8);
$quals = faculty_parse_lines_field($_POST['qualifications'] ?? '', 12);
$skills = faculty_parse_lines_field($_POST['skills'] ?? '', 12);
$publicationsUrl = faculty_clean_url((string) ($_POST['publications_url'] ?? ''));

if ($empName === '' || $slug === '') {
  faculty_update_redirect('error=' . rawurlencode('Please select your name from the list.'));
}
if (count($research) < 1) {
  faculty_update_redirect('error=' . rawurlencode('Please add at least one research topic tag.'));
}
if (strlen($phone) > 40) {
  $phone = substr($phone, 0, 40);
}

$photo = $_FILES['photo'] ?? null;
$result = faculty_save_submission([
  'ip' => $ip,
  'college' => 'dms',
  'emp_name' => $empName,
  'slug' => $slug,
  'des_title' => $desTitle,
  'dep_name' => $depName,
  'research_preferences' => $research,
  'publications_url' => $publicationsUrl,
  'publications' => [],
  'qualifications' => $quals,
  'skills' => $skills,
  'contact_phone' => $phone,
], is_array($photo) ? $photo : null);

if (empty($result['ok'])) {
  faculty_update_redirect('error=' . rawurlencode($result['error'] ?? 'Could not save. Please try again.'));
}

faculty_update_redirect('thanks=1');
