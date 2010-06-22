<?php
error_reporting(E_ALL);

$formUrl = 'https://localhost/networkmonitor_snmp/editSnmpPackage';
$apikey = '44f42038bd8fc208f4e8fe269161e78a9da264c7,admin';

function debug($msg) {
    file_put_contents('php://stderr', $msg."\n");
}

function error($reason) {
    file_put_contents('php://stderr', $reason."\n");
    exit(1);
}

$url = $formUrl.'?af_format=json&af_apikey='.urlencode($apikey);
debug("Fetching: $url");
$response = file_get_contents($url);
if ($response === false) {
    error('Unable to fetch the URL: '.$url);
}

$json = json_decode($response, true);
if ($json === null) {
    error('Unable to decode the JSON response: '.$response);
}

debug('JSON response: '.var_export($json, true));

$submitUrl = $json['af_url'].'?af_apikey='.urlencode($apikey);
$data = $json;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $submitUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

debug('Sending data to: '.$submitUrl);
$result = curl_exec($ch);
if ($result === false) {
    error('Unable to post data: '.curl_error($ch));
}

curl_close($ch);

$json = json_decode($result, true);
if ($json === null) {
    error('Unable to decode the JSON result: '.$result);
}

debug('JSON result: '.var_export($json, true));
