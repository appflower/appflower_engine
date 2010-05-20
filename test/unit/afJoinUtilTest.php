<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(16, new lime_output_color());

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'MonitorService',
    array('name', 'port', 'server_id'), array());
$t->is($selectMethod, 'doSelectJoinServer');
$t->is(count($c->getJoins()), 0);

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'MonitorService',
    array('name', 'port', 'server_id', 'server_interface_id'), array());
$t->is($selectMethod, 'doSelectJoinAllExceptMonitorServiceSettings');
$t->is(count($c->getJoins()), 0);

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

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'EventInfo',
    array('name', 'confidentiality_id'), array());
$t->is($selectMethod, 'doSelectJoinEventImpactRelatedByConfidentialityId');
$t->is(count($c->getJoins()), 0);

// The event_impact joins are not unique,
// so we cannot use the doSelectJoinAll*() methods.
$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'EventInfo',
    array('name', 'availability_id', 'confidentiality_id', 'integrity_id', 'publisher_id', 'vendor_id', 'source_type', 'source_name_id', 'category_id'), array('event_source_name'));
$t->is($selectMethod, 'doSelect');
$t->is(count($c->getJoins()), 6);

$c = new Criteria();
$selectMethod = afJoinUtil::chooseJoins($c, 'EventInfo',
    array('name', 'availability_id', 'confidentiality_id', 'integrity_id', 'publisher_id', 'vendor_id', 'source_type', 'source_name_id', 'category_id', 'event_response_module_connector_id'), array('event_source_name'));
$t->is($selectMethod, 'doSelect');
$t->is(count($c->getJoins()), 7);

