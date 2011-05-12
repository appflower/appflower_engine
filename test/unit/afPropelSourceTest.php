<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(13, new lime_output_color());

$extractor = new afColumnExtractor('MonitorService',
	array('name', 'port', 'server_id'));
$source = new afPropelSource($extractor);

$t->is($source->getTotalCount(), 8);
$t->is(count($source->getRows()), 8);

$source->setStart(2);
$t->is($source->getTotalCount(), 8);
$t->is(count($source->getRows()), 6);

$source->setLimit(3);
$t->is($source->getTotalCount(), 8);
$t->is(count($source->getRows()), 3);

$source->setStart(0);
$source->setLimit(0);
$t->is(count($source->getRows()), 0);
$source->setLimit(null);
$t->is(count($source->getRows()), 8);

$source->setSort('name', 'DESC');
$rows = $source->getRows();
$t->is(count($rows), 8);
$t->is($rows[0]['name'], 'syslog');
$t->is($rows[7]['name'], 'ftp');

$source->setSort('name', 'ASC');
$rows = $source->getRows();
$t->is($rows[0]['name'], 'ftp');
$t->is($rows[7]['name'], 'syslog');
