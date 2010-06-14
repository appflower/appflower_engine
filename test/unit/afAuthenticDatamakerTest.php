<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(3, new lime_output_color());

$data = array('hello'=>'value1', 'hello2'=>123);
$key = 'mykey';

$encoded = afAuthenticDatamaker::encode($data, $key);
$t->is(afAuthenticDatamaker::decode($encoded, $key), $data);

$encoded = afAuthenticDatamaker::encode($data, $key, 1);
$t->is(afAuthenticDatamaker::decode($encoded, $key), null);

$t->is(afAuthenticDatamaker::decode('wrongInput', $key), null);

