<?php
$dir = dirname(__FILE__);
$tests = glob("$dir/*Test.php");
foreach($tests as $test) {
    echo "Running $test\n";
    include($test);
}
