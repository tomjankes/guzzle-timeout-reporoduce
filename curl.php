<?php

require_once __DIR__.'/config.php';

// Client
$ch = curl_init(ENDPOINT_URL);
curl_setopt($ch, CURLOPT_NOSIGNAL, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 200);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 300);

$time = microtime(true);
$result = curl_exec($ch);

$curl_errno = curl_errno($ch);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_errno > 0) {
    echo "cURL Error ($curl_errno): $curl_error\n";
} else {
    echo "No timeout\n";
}
echo "It took: ".(microtime(true) - $time).PHP_EOL;
?>
