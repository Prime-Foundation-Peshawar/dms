<?php
$host = strtolower($_SERVER['HTTP_HOST'] ?? '');
$is_staging = ($host === 'staging.riphahpsh.edu.pk' || $host === 'www.staging.riphahpsh.edu.pk');

header('Content-Type: text/plain; charset=UTF-8');
header('X-Robots-Tag: noindex');

if ($is_staging) {
  echo "User-agent: *\n";
  echo "Disallow: /\n";
  exit;
}

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /faculty-proxy\n";
echo "\n";
echo "Sitemap: https://dms.riphahpsh.edu.pk/sitemap.xml\n";
