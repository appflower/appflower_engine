<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(7, new lime_output_color());

$source = new afStaticSource(array('ServerSnmpGraphPeer', 'getGraphTree'), array(1));

$numGraphs = 24;
$numGroups = 3;
$totalCount = $numGraphs + 1 + $numGroups;
$t->is($source->getTotalCount(), $totalCount);
$t->is(count($source->getRows()), $totalCount);

$source->setStart(2);
$t->is($source->getTotalCount(), $totalCount);
$t->is(count($source->getRows()), $totalCount - 2);

$source->setLimit(3);
$t->is($source->getTotalCount(), $totalCount);
$t->is(count($source->getRows()), 3);

$source->setStart(0);
$source->setLimit(0);
$t->is(count($source->getRows()), $totalCount);
