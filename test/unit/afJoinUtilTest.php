<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(10, new lime_output_color());

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'MonitorService',
    array('name', 'port', 'server_id'), array());
$t->is($selectMethod, 'doSelectJoinServer');
$t->is(count($c->getJoins()), 0);

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'MonitorService',
    array('name', 'port', 'server_id', 'server_interface_id'), array());
$t->is($selectMethod, 'doSelect');
$t->is(count($c->getJoins()), 2);

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'MonitorService',
    array('name', 'port', 'server_id', 'server_interface_id', 'monitor_service_settings_id'), array());
$t->is($selectMethod, 'doSelectJoinAll');
$t->is(count($c->getJoins()), 0);

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'MonitorService',
    array('name', 'port'), array());
$t->is($selectMethod, 'doSelect');
$t->is(count($c->getJoins()), 0);

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'MonitorService',
    array('name', 'port'), array('mgmt_server_interface'));
$t->is($selectMethod, 'doSelectJoinServerInterface');
$t->is(count($c->getJoins()), 0);

