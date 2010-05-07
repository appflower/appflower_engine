<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(6, new lime_output_color());

$source = new afPropelSource('MonitorService', array('name', 'port', 'server_id'));

$t->is($source->getTotalCount(), 8);
$t->is(count($source->getRows()), 8);

$source->setStart(2);
$t->is($source->getTotalCount(), 8);
$t->is(count($source->getRows()), 6);

$source->setLimit(3);
$t->is($source->getTotalCount(), 8);
$t->is(count($source->getRows()), 3);

