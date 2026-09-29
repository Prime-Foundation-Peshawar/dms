<?php
/**
 * faculty-proxy.php
 * Server-side proxy to fetch faculty data – no CORS issues.
 */

header('Content-Type: application/json');
header('Cache-Control: no-store');

$api_url = 'https://biometric.prime.edu.pk/hrms/apis/getEmployeeInfo.php';

// Use cURL if available (most reliable)
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // if SSL issues
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        http_response_code(500);
        echo json_encode(['error' => 'cURL error: ' . $curl_error]);
        exit;
    }

    if ($http_code !== 200) {
        http_response_code($http_code);
        echo json_encode(['error' => "API returned HTTP $http_code"]);
        exit;
    }

    echo $response;
    exit;
}

// Fallback to file_get_contents (if allow_url_fopen enabled)
$response = @file_get_contents($api_url);
if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not fetch API data']);
    exit;
}

echo $response;