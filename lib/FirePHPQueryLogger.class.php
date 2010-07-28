<?php
/**
 * This class will log executed queries
 * It works with every HTTP request so it is ajax friendly.
 * For this to work switch to dev environment and make sure that Firebug and FirePHP extensions for firefox are installed and "Net" panel in firebug is enabled
 * If You want to use it in other environment You must also make sure that:
 *  * DebugPDO connection class is used instead of PropelPDO (databases.yml)
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
    private $bindings = array();
    private $lastStatementIndex = null;
    private $statementsIndexes = array();

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
            if (preg_match('/^(prepare|exec|query): (.*)$/s', $logMessage, $match))
            {
              if ($match[1] == 'prepare') {
                  $this->lastStatementIndex = count($this->queries);
                  $this->statementsIndexes[] = $this->lastStatementIndex;
              }
              $this->queries[] = $match[2];
            }
            else if (preg_match('/Binding (.*) at position (.+?) w\//', $logMessage, $match))
            {
              $this->bindings[$this->lastStatementIndex][$match[2]] = $match[1];
            }
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
        foreach ($this->statementsIndexes as $statementIndex)
        {
          if (isset($this->bindings[$statementIndex]) && count($this->bindings[$statementIndex]))
          {
            $this->bindings[$statementIndex] = array_reverse($this->bindings[$statementIndex]);
            foreach ($this->bindings[$statementIndex] as $search => $replace)
            {
              $this->queries[$statementIndex] = str_replace($search, $replace, $this->queries[$statementIndex]);
            }
          }
        }

        $fp = FirePHP::getInstance(true);
        $fp->group('Queries ('.count($this->queries).')', array('Collapsed'=>true));
        foreach ($this->queries as $index => $query) {
            $fp->log(($index+1).'. '.$query);
        }
        $fp->groupEnd();
    }

}
?>