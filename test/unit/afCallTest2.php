<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(11, new lime_output_color());

$t->is(afCall::evaluate('5 + 3', array()), 8);
$t->is(afCall::evaluate('"good"." day"', array()), 'good day');
$t->is(afCall::evaluate('strtolower("BIG")', array()), 'big');

$t->is(afCall::evaluate('$id + 3', array('id'=>100)), 103);
$t->is(afCall::evaluate('$values["name"]', array(
    'values'=>array('id'=>1, 'name'=>'my name'))), 'my name');
$t->is(afCall::evaluate('strtoupper($name)', array('name'=>'my name')),
    'MY NAME');

$t->is(afCall::evaluate('StringUtil::startsWith("hello", "hell")', array()),
    true);
$t->is(afCall::evaluate('StringUtil::startsWith("hello", "heaven")', array()),
    false);


$t->is(afCall::rewriteIfOldCondition(
    'MyPeer,isEnabled,extra1,extra2', array('id')),
    'MyPeer::isEnabled(array($id,$extra1,$extra2))');

$t->is(afCall::rewriteIfOldCondition(
    'MyPeer,isEnabled,123', array()),
    'MyPeer::isEnabled(array(123))');

$t->is(afCall::rewriteIfOldCondition(
    'MyPeer,isEnabled', array()),
    'MyPeer::isEnabled(array())');
