<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(3, new lime_output_color());

$extractor = new afColumnExtractor('Server', array('name', 'alert_interval', 'updated_at', 'location_id', 'server_type_id', 'is_enabled'));

$location = new Location();
$location->setName('myLocation');

$server = new Server();
$server->setName('localhost');
$server->setAlertInterval(300);
$date = '2010-02-14 03:25:45';
$server->setUpdatedAt($date);
$server->setLocation($location);
$server->setIsEnabled(true);
$objects = array($server);

$rows = $extractor->extractColumns($objects);
$row = $rows[0];
$t->like($row['is_enabled'], '@^<img src=\'/images/famfamfam/accept.png\' .*@');
unset($row['is_enabled']);

$expected = array('name'=>'localhost', 'alert_interval'=>300,
    'updated_at'=>$date, 'location_id'=>'myLocation',
    'server_type_id'=>null);
$t->is($row, $expected);
$t->is(json_encode($row), json_encode($expected));


