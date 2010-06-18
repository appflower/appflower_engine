<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(5, new lime_output_color());

$data = array('hello'=>'value1', 'hello2'=>123);

$encoded = afAuthenticDatamaker::encode($data);
$t->is(afAuthenticDatamaker::decode($encoded), $data);

$encoded = afAuthenticDatamaker::encode($data, 1);
$t->is(afAuthenticDatamaker::decode($encoded), null);

$t->is(afAuthenticDatamaker::decode('wrongInput'), null);
$t->is(afAuthenticDatamaker::decode(null), null);
$t->is(afAuthenticDatamaker::decode(''), null);

