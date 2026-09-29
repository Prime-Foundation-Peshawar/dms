<?php
/**
 * Faculty profiles API (replaces assets/data/faculty-profiles.json).
 */
require_once __DIR__ . '/includes/faculty-lib.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

$pack = faculty_profiles_pack();
echo json_encode($pack, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
