<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(1, new lime_output_color());

$doc = afConfigUtils::getDoc('networkmonitor_snmp', 'listSnmpGroup');
$view = afDomAccess::wrap($doc, 'view');
$listView = new afListView($view);

$t->is($listView->getSelectedColumns(), array('name', 'body', 'id'));
