<?php
// launcher.php
// 1. Check if already running
if (file_exists(__DIR__ . '/.port')) {
    $port = trim(file_get_contents(__DIR__ . '/.port'));
    $connection = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1);
    if (is_resource($connection)) {
        fclose($connection);
        echo "RUNNING:$port\n";
        exit;
    }
}

// 2. Find free port
$host = '127.0.0.1';
$port = 8000;
while ($port < 8100) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 1);
    if (is_resource($connection)) {
        fclose($connection);
        $port++;
    } else {
        break;
    }
}

// 3. Write port
file_put_contents(__DIR__ . '/.port', $port);
echo "START:$port\n";
