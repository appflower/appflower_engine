<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(2, new lime_output_color());

$source = new afPropelSource('SnmpGroup', array('name', 'body'));

$t->is($source->getTotalCount(), 3);
$t->is(count($source->getRows()), 3);

