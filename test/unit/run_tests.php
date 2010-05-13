<?php
$dir = dirname(__FILE__);
$tests = glob("$dir/*Test.php");
foreach($tests as $test) {
    // Destructing the old lime_test() first.
    $t = null;

    echo "Running $test\n";
    include($test);
}
