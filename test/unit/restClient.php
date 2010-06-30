<?php
error_reporting(E_ALL);

$formUrl = 'https://localhost/networkmonitor_snmp/editSnmpGroup?id=1';
$apikey = 'RPQgOL2Pwgj06P4mkWHnip2iZMc~admin';

$url = $formUrl.'&af_format=json&af_apikey='.urlencode($apikey);
$response = file_get_contents($url);
if ($response === false) {
    throw new Exception('Unable to fetch the URL: '.$url);
}

echo "JSON response: $response\n";

$json = json_decode($response, true);
if ($json === null) {
    throw new Exception('Unable to decode the JSON response: '.$response);
}

$submitUrl = $json['af_submitUrl'].'?af_apikey='.urlencode($apikey);
$data = $json;
$data['edit[name]'] = 'Changed name';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $submitUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$result = curl_exec($ch);
if ($result === false) {
    throw new Exception('Unable to post data: '.curl_error($ch));
}

curl_close($ch);

echo "JSON result: $result\n";

$json = json_decode($result, true);
if ($json === null) {
    throw new Exception('Unable to decode the JSON result: '.$result);
}

if (!isset($json['success']) || $json['success'] !== true) {
    throw new Exception('A form validation error: '.$result);
}

