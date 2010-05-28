<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(8, new lime_output_color());

$t->is(afCall::evalute('5 + 3', array()), 8);
$t->is(afCall::evalute('"good"." day"', array()), 'good day');
$t->is(afCall::evalute('strtolower("BIG")', array()), 'big');

$t->is(afCall::evalute('$id + 3', array('id'=>100)), 103);
$t->is(afCall::evalute('$values["name"]', array(
    'values'=>array('id'=>1, 'name'=>'my name'))), 'my name');
$t->is(afCall::evalute('strtoupper($name)', array('name'=>'my name')),
    'MY NAME');

$t->is(afCall::evalute('StringUtil::startsWith("hello", "hell")', array()),
    true);
$t->is(afCall::evalute('StringUtil::startsWith("hello", "heaven")', array()),
    false);
