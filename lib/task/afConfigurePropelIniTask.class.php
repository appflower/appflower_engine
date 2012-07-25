<?php

class afConfigurePropelIniTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    $this->namespace        = 'af';
    $this->name             = 'configure-propel-ini';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [af:configure-propel-ini|INFO] task takes the values from databases.yml and configure propel.ini
Call it with:

  [php symfony af:configure-propel|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->log('Propel ini config started');  
      
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $databaseYml = sfYaml::load(sfConfig::get('sf_root_dir').'/config/databases.yml');    
    
    $propelIni = file_get_contents(sfConfig::get('sf_root_dir').'/config/propel.ini');
    
    $propelIni = str_replace('mysql:dbname=appflower_playground;host=localhost',$databaseYml['all']['propel']['param']['dsn'],$propelIni);
    $propelIni = str_replace('propel.database.user       = root','propel.database.user       = '.$databaseYml['all']['propel']['param']['username'],$propelIni);
    $propelIni = str_replace('propel.database.password       = root','propel.database.password       = '.$databaseYml['all']['propel']['param']['password'],$propelIni);
    $propelIni = str_replace('propel.output.dir              = /www/appflower_playground','propel.output.dir              = '.sfConfig::get('sf_root_dir'),$propelIni);
    
    file_put_contents(sfConfig::get('sf_root_dir').'/config/propel.ini',$propelIni);
    
    $this->log('Propel ini config ended');  
  }
}
