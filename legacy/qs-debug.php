<?php
header('Content-Type: text/plain');
echo "GET="; var_export($_GET); echo "\n";
echo "QS=" . ($_SERVER['QUERY_STRING'] ?? '') . "\n";
echo "URI=" . ($_SERVER['REQUEST_URI'] ?? '') . "\n";