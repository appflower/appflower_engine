<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(4, new lime_output_color());

$doc = afConfigUtils::getDoc('server', 'listServer');
$view = afDomAccess::wrap($doc, 'view');

$t->is($view->get('datasource@type'), 'orm');
$t->is($view->get('datasource/method@name'), 'getAllServer');
$t->is($view->getBool('datasource/no_such_element'), false);
$t->is($view->getBool('datasource/no_such_element', true), true);

