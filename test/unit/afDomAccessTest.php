<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(9, new lime_output_color());

$doc = afConfigUtils::getDoc('server', 'listServer');
$view = afDomAccess::wrap($doc, 'view');

$t->is($view->get('datasource@type'), 'orm');
$t->is($view->get('datasource/method@name'), 'getAllServer');
$t->is($view->getBool('datasource/no_such_element'), false);
$t->is($view->getBool('datasource/no_such_element', true), true);

$columns = $view->wrapAll('fields/column');
$t->is(count($columns), 10);
$t->is($columns[0]->get('@name'), 'name');
$t->is($columns[1]->get('@name'), 'is_enabled');
$t->is($columns[9]->get('@name'), 'c_i_a');

$actions = $view->wrapAll('actions/action');
$t->is(count($actions), 4);
