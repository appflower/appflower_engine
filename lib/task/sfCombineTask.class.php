<?php

class sfCombineTask extends sfBaseTask
{
  protected function configure()
  {
  	
  	$this->addOptions(array(
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));
  	
    $this->namespace        = 'sfCombine';
    $this->name             = 'cc';
    $this->briefDescription = 'Clears the cache stored for sfCombine plugin';
    $this->detailedDescription = <<<EOF
The [list|INFO] task does things.
Call it with:

  [php symfony list|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $nbAssets = DbFinder::from('sfCombine')->delete();
  
  	sfProcessCache::clear();
    
  }
}
