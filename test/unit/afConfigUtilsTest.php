<?php
include(dirname(__FILE__).'/../bootstrap/dbunit.php');
$t = new lime_test(1, new lime_output_color());

$t->is(afConfigUtils::getPath('server', 'listServer'), '/usr/www/manager/apps/frontend/modules/server/config/listServer.xml');

