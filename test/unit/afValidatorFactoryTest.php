<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(5, new lime_output_color());

function assertError($validator, $value, $expectedMsg) {
    global $t;
    try {
        $validator->clean($value);
        $t->fail();
    } catch(sfValidatorError $e) {
        $t->is($e->getMessage(), $expectedMsg);
    }
}

$validator = afValidatorFactory::createValidator('requiredValidator',
    array('required_error'=>'myRequiredError'));

$t->is($validator->clean('full'), 'full');
assertError($validator, '', 'myRequiredError');

$validator = afValidatorFactory::createValidator('immValidatorHostname', array());
assertError($validator, '', 'Required.');

$validator = afValidatorFactory::createValidator('immValidatorHostname',
    array('required_error'=>'myRequiredError'));
assertError($validator, '', 'myRequiredError');

$validator = afValidatorFactory::createValidator('immValidatorHostname',
    array('required'=>'false'));
$t->is($validator->clean(''), '');

