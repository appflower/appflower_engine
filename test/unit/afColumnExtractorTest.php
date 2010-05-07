<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(1, new lime_output_color());

$extractor = new afColumnExtractor('MonitorService', array('name', 'port', 'server_id'));

$service = new MonitorService();
$service->setName('myService');
$service->setPort(80);
$objects = array($service);
$t->is($extractor->extractColumns($objects), array(
    array('name'=>'myService', 'port'=>80, 'server_id'=>null)
));


