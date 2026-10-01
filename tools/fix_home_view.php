<?php
$p = dirname(__DIR__) . '/app/Views/pages/home.php';
$s = file_get_contents($p);
$s = preg_replace('/\$slideCount = count\(\$heroSlides\);.*?^\?>\s*/ms', '', $s);
file_put_contents($p, $s);
echo substr($s, 0, 250), "\n";
