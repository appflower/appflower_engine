<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(3, new lime_output_color());

$doc = afConfigUtils::getDoc('networkmonitor_snmp', 'editSnmpPackage');
$view = afDomAccess::wrap($doc, 'view');
$fields = $view->wrapAll('fields/field');
$t->is(count($fields), 3);
$t->is(afEditView::getParams($fields[1], 'validator/param'),
    array('class'=>'SnmpPackage', 'column'=>'name',
    'unique_error'=>'This name is already used. Please choose another one!'));

$validators = json_decode('{"edit[name]":{"immValidatorUnique":{"params":{"class":"SnmpPackage","column":"name","unique_error":"This name is already used. Please choose another one!"}}},"edit[template]":{"immValidatorRequired":[]}}', true);
$t->is(afEditView::getValidators($fields), $validators);

