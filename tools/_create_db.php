<?php
$hosts = ['127.0.0.1', 'localhost'];
$users = [['root', ''], ['root', 'root'], ['root', 'password']];
foreach ($hosts as $h) {
    foreach ($users as [$u, $p]) {
        try {
            mysqli_report(MYSQLI_REPORT_OFF);
            $m = @new mysqli($h, $u, $p);
            if ($m && !$m->connect_error) {
                $m->query('CREATE DATABASE IF NOT EXISTS dms CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');
                echo "OK host={$h} user={$u} db=dms\n";
                file_put_contents(
                    dirname(__DIR__) . '/writable/db_credentials.json',
                    json_encode(['hostname' => $h, 'username' => $u, 'password' => $p, 'database' => 'dms'], JSON_PRETTY_PRINT)
                );
                $m->close();
                exit(0);
            }
            echo 'fail ' . $h . '/' . $u . ': ' . ($m ? $m->connect_error : 'null') . "\n";
        } catch (Throwable $e) {
            echo "err {$h}/{$u}: {$e->getMessage()}\n";
        }
    }
}
exit(1);
