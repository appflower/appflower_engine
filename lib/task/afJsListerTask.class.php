<?php
/*
 * This file is part of the sfJobQueuePlugin package.
 * 
 * (c) 2007 Xavier Lacot <xavier@lacot.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * @author   Xavier Lacot <xavier@lacot.org>
 * @author   Tristan Rivoallan <tristan@rivoallan.net>
 * @see      http://www.symfony-project.com/trac/wiki/sfJobQueuePlugin
 */

/**
 * This task displays a list of exisiting job queues.
 */
class afJsListerTask extends sfBaseTask
{
	
  private 
  	$root,
  	$dirs,
  	$files,
  	$count = 0,
  	$result = "jslist";	
	
  /**
   * Configures task.
   */
  public function configure()
  {
  	
  	$this->addArguments(array(
  	  new sfCommandArgument('ext', sfCommandArgument::REQUIRED, 'List files with this extension'),
   	  new sfCommandArgument('verbose', sfCommandArgument::OPTIONAL, 'Wheter to print output',''),
   	  new sfCommandArgument('path', sfCommandArgument::OPTIONAL, 'Save results to this file',''),
  	));
    
    $this->namespace = 'appflower';
    $this->name = 'list-files';
    $this->briefDescription = 'Validates all config files and generates validator cache with optional error reporting';

    $this->detailedDescription = <<<EOF
This will generate validator cache and also displays validation errors upon request.
The task has 3 arguments: reporting, rebuild and src.

 * If error reporting is set to "full", it will validate all files, then will provide stats and and sum of errors found.
 * If error reporting is set to "incremental", it will stop as soon as it finds an error and prints the related error data.
 * When error reporting is set to 'cache', it will just generate the cache whenever it is possible and will report everything as valid.
 * Finally, i reporting is set to 'file', the  you must provide the 'src' arg as well, and only that one file will be validated.
 
 * The rebuild param should be set to "yes" or "no", and it determines wheter you want to rebuild the full cache or just add
   new items.  
   
 * The src attribute is always a path to an xml file. It must be used only if reporting is set to 'file', otherwise it is optional
   
EOF;

  }

  /**
   * Displays the list of existing job queues.
   * 
   * @param   array   $arguments    (optional)
   * @param   array   $options      (optional)
   */
  public function execute($arguments = array(), $options = array())
  {
  	
  	$project = ProjectConfiguration::getActive();
  	$this->root = $project->getRootDir();
  	
  	$this->arguments = $arguments;
  	
  	$this->dirs = array($this->root."/web");
  	
  	FileUtils::getPluginDirs($this->dirs,$this->root,"/web");
  	
    $this->logSection("\nXML Generating file list.. (this may take a while)\n".
    "File type: ".$arguments["ext"]."                                      \n",null,null,"ERROR");
    
    foreach($this->dirs as $dir) {
   		 FileUtils::scanDirs($dir,"js",$this->files,true); 	
    }
   
    $this->printResult();	
   	
    $this->finalize();
    
  }
  
  
  private function printResult() {
  	
	  	foreach($this->files as $dir => $items) {
		  		if($this->arguments["verbose"] && $this->arguments["verbose"] != "no") {
		  			$this->logSection("\n\nScanning files in: ".$dir."\n",null);
		  		}
			  	if(empty($items)) {
			  		if($this->arguments["verbose"] && $this->arguments["verbose"] != "no") {
			  			$this->logSection("","No matching files found, skipping..");	
			  		}
			  		unset($this->files[$dir]);
			  	} else {
			  		foreach($items as $file) {
			  			if($this->arguments["verbose"] && $this->arguments["verbose"] != "no") {
			  				$this->logSection("found: ",$file,null,"INFO");	
			  			}
			  			$this->count++;	
			  		}
			  	}		
	  	}	
   	
  }
  
  private function finalize() {
	
  	if($this->arguments["path"]) {
		$this->result = $this->arguments["path"];
	} else {
		$this->result = $this->root."/cache/".$this->result;
	}
	
	$handle = @fopen($this->result,"wr");
  		
	if(!$handle) {
		throw new Exception("Unable to write result file!");
	}
	
	$data = serialize($this->files);
  		
  	fwrite($handle,$data,strlen($data));
  	fclose($handle);
  		
  	$this->logSection("\nFinished!","The following result was generated:\n");
  	$this->logSection("Total files",$this->count);
  	$this->logSection("Created: ",$this->result);
  	
  }
  

}