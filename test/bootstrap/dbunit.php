<?php
require_once(dirname(__FILE__).'/../../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
new sfDatabaseManager($configuration);
sfContext::createInstance($configuration);
error_reporting(E_ALL);
 
require_once($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');
