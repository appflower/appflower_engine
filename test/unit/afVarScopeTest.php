<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(3, new lime_output_color());

$scope = new afVarScope(array('title'=>'hello', 'start'=>5));
$t->is($scope->interpret('Title: {title}, other={other}, start={start}'),
    'Title: hello, other={other}, start=5');
$t->is($scope->interpret('normal text'), 'normal text');
$t->is($scope->interpret('/a regex[a-Z]{3}/'), '/a regex[a-Z]{3}/');

