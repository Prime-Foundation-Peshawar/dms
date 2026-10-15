<?php
/**
 * Public faculty directory JSON — shared by Faculty and Departments pages.
 * Built once in faculty_public_directory() (HRMS + CV-only + IT).
 * Use ?raw=1 for the unfiltered upstream HRMS payload (debug only).
 */

require_once __DIR__ . '/includes/faculty-lib.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if (isset($_GET['raw'])) {
  $pack = faculty_hrms_fetch_all(0);
  if (empty($pack['ok'])) {
    http_response_code(502);
    echo json_encode(['error' => $pack['error'] ?? 'HRMS fetch failed']);
    exit;
  }
  echo json_encode($pack['employees']);
  exit;
}

echo json_encode(faculty_public_directory());
