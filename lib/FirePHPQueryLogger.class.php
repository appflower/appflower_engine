<?php
/**
 * This class will log executed queries
 * It works with every HTTP request so it is ajax friendly.
 * For this to work switch to dev environment and make sure that Firebug and FirePHP extensions for firefox are installed and "Net" panel in firebug is enabled
 * If You want to use it in other environment You must also make sure that:
 *  * DebugPDO connection class is used instead of PropelPDO (databases.yml)
 *  * You are using debug mode: $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
 *  * You have enabled it in app.yml like below:
all:
  enable_firephp_query_logger: true
 *
 * When You do everything correct You should notice queries being logged to Firebug console.
 *
 * You can also force this class NOT to log queries even in dev environment by switching above app.yml setting to false
 *
 * @author Łukasz Wojciechowski <luwo@appflower.com>
 */
class FirePHPQueryLogger
{
    static $instance;

    private $queries = array();

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function applicationLog(sfEvent $event)
    {
        $subject = $event->getSubject();

        if ($subject instanceof sfPropelLogger) {
            $parameters = $event->getParameters();
            $logMessage = $parameters[0];
            $this->queries[] = $logMessage;
        } else if ($subject instanceof sfResponse) {
            $parameters = $event->getParameters();
            $logMessage = $parameters[0];
            if (preg_match('/^Send content/s', $logMessage, $match)) {
                $this->sendLogs();
            }
        }
    }

    private function sendLogs()
    {
        $fp = FirePHP::getInstance(true);
        $fp->group('Queries ('.count($this->queries).')', array('Collapsed'=>true));
        foreach ($this->queries as $index => $query) {
            $fp->log(($index+1).'. '.$query);
        }
        $fp->groupEnd();
    }
}
?>