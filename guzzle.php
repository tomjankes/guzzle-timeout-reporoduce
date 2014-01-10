<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

use Guzzle\Http\Client as HttpClient;

$client = new HttpClient(ENDPOINT_URL);
//$client->setDefaultOption('timeout', 0.2);

$client->setConfig(
	array('curl.options' =>
	      array(
        	  'CURLOPT_NOSIGNAL' => true,
	          'CURLOPT_CONNECTTIMEOUT_MS' => 200,
        	  'CURLOPT_TIMEOUT_MS' => 300,
              //'CURLOPT_TCP_NODELAY' => true,
              //'TCP_NODELAY' => true,
      		)
	)
);


$time = microtime(true);
try {
    $response = $client->get()->send();
    echo $response->getStatusCode()."\n";
} catch (Exception $e) {
    echo get_class($e).' :: '.$e->getMessage().PHP_EOL;
}
$time = microtime(true) - $time;
echo 'it took '.$time.PHP_EOL;
