<?php
$url = '/server/listServer';
if(isset($argv[1])) {
    $url = $argv[1];
}

require_once(dirname(__FILE__).'/../bootstrap/dbunit.php');

Console::$profilingEnabled = true;
register_shutdown_function(array('Console', 'profile'), 'end');

class AuthenticatedBrowser extends sfBrowser {
    protected function doCall() {
        $this->context = $this->getContext(true);
        $admin = sfGuardUserPeer::retrieveByPk(1);
        $this->context->getUser()->signIn($admin);
        ob_start();
        $this->context->getController()->dispatch();
        $html = ob_get_clean();
    }
}

$browser = new AuthenticatedBrowser();
sfConfig::set('app_parser_panels', array());
sfConfig::set('app_parser_skip_toolbar', true);
$browser->get($url);
Console::profile('end');

Console::restartProfiling();
$browser->get($url);

