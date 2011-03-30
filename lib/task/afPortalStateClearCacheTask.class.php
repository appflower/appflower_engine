<?php
/**
 * @author   Radu Topala <radu@appflower.com>
 */

/**
 * This task clears the portal state cache for a specific xml id
 */
class afPortalStateClearCacheTask extends sfBaseTask
{
  /**
   * Configures task.
   */
  public function configure()
  {
  	
  	$this->addArguments(array(
  	  new sfCommandArgument('idxml', sfCommandArgument::REQUIRED, 'The ID xml based on module/action')
    ));
    
    $this->addOptions(array(
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));
    
  	
    $this->namespace = 'appflower';
    $this->name = 'portal-state-cc';
    $this->briefDescription = 'Clears the portal\'s state cache for a specific xml id';

    $this->detailedDescription = <<<EOF
You can use it like: 
./symfony appflower:portal-state-cc idxml

where idxml is formed from module/action
EOF;

  }

  /**
   * @param   array   $arguments    (optional)
   * @param   array   $options      (optional)
   */
  public function execute($arguments = array(), $options = array())
  {
  	$databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
  	
    afPortalStatePeer::deleteAllByIdXml($arguments['idxml']);  	

    return 0;
  }
}