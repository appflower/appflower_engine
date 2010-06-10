<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(2, new lime_output_color());

$validator = afValidatorFactory::createValidator('requiredValidator',
    array('required_error'=>'myRequiredError'));

$t->is($validator->clean('full'), 'full');
try {
    $validator->clean('');
    $t->fail();
} catch(sfValidatorError $e) {
    $t->is($e->getMessage(), 'myRequiredError');
}

