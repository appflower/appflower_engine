<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(4, new lime_output_color());

$rows = array(
    array(1997,'Ford','E350','ac, abs, moon',3000.00),
    array(1999,'Chevy','Venture "Extended Edition"','',4900.00),
    array(1999,'Chevy','Venture "Extended Edition, Very Large"','',5000.00),
    array(1996,'Jeep','Grand Cherokee','MUST SELL!
    air, moon roof, loaded',4799.00));

$expected = array(
    '"1997","Ford","E350","ac, abs, moon","3000"',
    '"1999","Chevy","Venture ""Extended Edition""","","4900"',
    '"1999","Chevy","Venture ""Extended Edition, Very Large""","","5000"',
    '"1996","Jeep","Grand Cherokee","MUST SELL!
    air, moon roof, loaded","4799"');

for($i = 0; $i < count($rows); $i++) {
    $t->is(afOutput::asCsv($rows[$i]), $expected[$i]);
}

