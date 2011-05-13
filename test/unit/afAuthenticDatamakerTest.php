<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(6, new lime_output_color());

$data = array('hello'=>'value1', 'hello2'=>123);

$encoded = afAuthenticDatamaker::encode($data);
$t->is(afAuthenticDatamaker::decode($encoded), $data);

$encoded = afAuthenticDatamaker::encode($data, 1);
$t->is(afAuthenticDatamaker::decode($encoded), null);

$t->is(afAuthenticDatamaker::decode('wrongInput'), null);
$t->is(afAuthenticDatamaker::decode(null), null);
$t->is(afAuthenticDatamaker::decode(''), null);

sfConfig::set('app_appFlower_siteSecret', 'CHANGE_ME');
$apikey = afApikeySecurityFilter::getApiKey(sfGuardUserPeer::retrieveByPk(1));
$t->is($apikey, 'RPQgOL2Pwgj06P4mkWHnip2iZMc~admin');

