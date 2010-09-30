<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(11, new lime_output_color());

function assertError($validator, $value, $expectedMsg) {
    global $t;
    try {
        $validator->clean($value);
        $t->fail('expected: '.$expectedMsg);
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

$validator = afValidatorFactory::createValidator('sfValidatorSchemaCompare',
    array('left_field'=>'password', 'right_field'=>'re_password',
    'operator'=>'==', 'invalid_error'=>'myInvalidError'));
$t->is($validator->getOption('left_field'), 'password');
$t->is($validator->getOption('right_field'), 're_password');
$t->is($validator->getOption('operator'), '==');

assertError($validator,
    array('password'=>'hello', 're_password'=>'hello2'), 'myInvalidError');

$paramHolder = new sfParameterHolder13();
$paramHolder->set('edit', array(array('password'=>'hello', 're_password'=>'hello2')));
$values = afValidatorFactory::prepareValue('edit[0][re_password]', $validator,
    $paramHolder);
$t->is($values, array('password'=>'hello', 're_password'=>'hello2'));

$paramHolder = new sfParameterHolder13();
$paramHolder->set('edit', array('password'=>'hello', 're_password'=>'hello2'));
$values = afValidatorFactory::prepareValue('edit[re_password]', $validator,
    $paramHolder);
$t->is($values, array('password'=>'hello', 're_password'=>'hello2'));

