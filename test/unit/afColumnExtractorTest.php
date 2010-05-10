<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(1, new lime_output_color());

$extractor = new afColumnExtractor('Server', array('name', 'alert_interval', 'updated_at'));

$server = new Server();
$server->setName('localhost');
$server->setAlertInterval(300);
$date = '2010-02-14 03:25:45';
$server->setUpdatedAt($date);
$objects = array($server);
$t->is($extractor->extractColumns($objects), array(
    array('name'=>'localhost', 'alert_interval'=>300, 'updated_at'=>$date)
));


