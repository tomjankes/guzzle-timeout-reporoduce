<?php
require_once __DIR__.'/config.php';


$multi_handle = curl_multi_init();
$single_handle = curl_init(ENDPOINT_URL);
curl_setopt($single_handle, CURLOPT_NOSIGNAL, true);
curl_setopt($single_handle, CURLOPT_CONNECTTIMEOUT_MS, 200);
curl_setopt($single_handle, CURLOPT_TIMEOUT_MS, 300);

curl_multi_add_handle($multi_handle, $single_handle);

$selectTimeout = 0.001;
$active = false;

$time = microtime(true);

try {
    do {
        $running = null;
        while (($mrc = curl_multi_exec($multi_handle, $active)) == CURLM_CALL_MULTI_PERFORM) {
            //do nothing;
        }
        if ($mrc != CURLM_OK && $mrc != CURLM_CALL_MULTI_PERFORM) {
            throw new Exception('Curl exception: '.$mrc);
        }

        while ($done = curl_multi_info_read($multi_handle)) {
           $this_handle = $done['handle'];
           if (!(CURLM_OK == $done['result'] || CURLM_CALL_MULTI_PERFORM == $done['result'])) {
                if ($done['result'] == CURLE_OPERATION_TIMEOUTED) {
                    throw new Exception('Curl timeout');
                }
                throw new Exception("Curl exception: ".$done['result']);
            }
        }

        if ($active && curl_multi_select($multi_handle, $selectTimeout) === -1) {
            usleep(150);
        }
        //$selectTimeout = 1; //<-- this doesn't throw exception before 1 sec passes
        $selectTimeout = min($selectTimeout + 0.1, 1); //<-- this throws exception after CURL_TIMEOUT_MS passes
    } while ($active);
} catch (Exception $e) {
    echo $e->getMessage().PHP_EOL;
}

curl_multi_remove_handle($multi_handle, $single_handle);
curl_multi_close($multi_handle);

echo 'It took '.(microtime(true) - $time).PHP_EOL;
