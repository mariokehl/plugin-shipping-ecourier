<?php

$options = [];
$options['headers'] = [
    'Content-Type' => 'application/json',
    'apiKey' => SdkRestApi::getParam('apiKey')
];
$options['body'] = SdkRestApi::getParam('payload');

if (SdkRestApi::getParam('echo')) {
    $options['query']['modus'] = 'echo';
}

$client = new \GuzzleHttp\Client();
$res = $client->request(
    'POST', 
    SdkRestApi::getParam('uri'), 
    $options
);

/** @return array */
return json_decode($res->getBody(), true);