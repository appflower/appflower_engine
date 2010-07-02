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
class afValidatorCacheTask extends sfPropelBaseTask
{
	
  private
  	$basedir, 
  	$dirs,
  	$log = "/usr/www/manager/log/validation.log",
  	$dbmanager,
  	$validator,
  	$errors = array(),
  	$count = 0,
  	$args;
  	
	
  /**
   * Configures task.
   */
  public function configure()
  {
  	
  	$this->addArguments(array(
  	  new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The name of symfony app'),
      new sfCommandArgument('reporting', sfCommandArgument::REQUIRED, 'Error reporting mode'),
      new sfCommandArgument('rebuild', sfCommandArgument::OPTIONAL, 'Wheter to rebuild cache','no'),
      new sfCommandArgument('src', sfCommandArgument::OPTIONAL, 'The xml file to validate',null),
    ));
    
  	
    $this->namespace = 'appflower';
    $this->name = 'validator-cache';
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
  	
  	
  	if($arguments["reporting"] != "full" && $arguments["reporting"] != "incremental" && $arguments["reporting"] != "cache" && $arguments["reporting"] != "file") {
  		throw new Exception("Invalid value for argument: reporting. The value 'incremental', 'cache', 'file' or 'full' are expected, but '".
  		$arguments["reporting"]."' is given!");
  	} else if($arguments["rebuild"] != "yes" && $arguments["rebuild"] != "no") {
  		throw new Exception("Invalid value for argument: rebuild. The value 'yes' or 'no' are expected, but '".
  		$arguments["rebuild"]."' is given!");
  	} else if($arguments["src"] != null && (!file_exists($arguments["src"]) || !is_readable($arguments["src"]))) {
  		throw new Exception("Invalid value for argument: src. The file '".$arguments["src"]."' doesn't exist or is not readable!");
  	}
  	
  	
  	$project = ProjectConfiguration::getActive();
  	$this->basedir = $project->getRootDir();
  	
  	// Add plugin dirs..
  	
  	$this->dirs = XmlParser::getPluginDirs($this->basedir."/plugins");
  	
  	// DB
  	$configuration = ProjectConfiguration::getApplicationConfiguration($arguments['application'], 'prod', true);
    $this->dbmanager = new sfDatabaseManager($configuration);
    // XML Validator
    $this->validator = new XmlValidator(null,false,true,($arguments["reporting"] == "cache"));
    // Truncating cache
   
    if($arguments["rebuild"] == "yes") {
    	afValidatorCachePeer::clearCache();	
    }
    
    $this->args = $arguments;
  
    $this->logSection("\nXML Config validation is starting.. (this may take a while)\n".
    "Validation type: ".$arguments["reporting"]."                                      \n".
    "Will flush cache: ".$arguments["rebuild"]."                                       ",null,null,"ERROR");
    
    if($arguments["reporting"] != "file" && $arguments["src"] == null) {
    	foreach($this->dirs as $dir) {
    		$scan = (substr($dir,0,1) == "/") ? $dir : $this->basedir."/apps/".$arguments['application']."/".$dir; 
    		$this->readConfigs($scan);	
    	}	
    } else {
    	$this->validator->readXmlDocument($arguments["src"],true);
  		$result = $this->validator->validateXmlDocument(true);
  		
  		$this->logSection(($result[1] === null) ? "valid: " : "error: ",$arguments["src"],null,$result[0]);
  		
  		if($result[1]) {
  			echo Util::arrayToString(array("file" => $tmp, "message" => $result[1]->getMessage()));
  			return true;
  		}
  			
    }
    
    if($arguments["reporting"] == "full") {
    	$this->printTotal();
    }
    
  }
  
  private function hasXmlFiles($data) {
  	
  	foreach($data as $tmp) {
  		if(strtolower(substr($tmp,strrpos($tmp,".")+1)) == "xml") {
  			return true;
  		}
  	}
  	
  	return false;
  	
  }
  
  private function readConfigs($dir) {
  	
  	$input = scandir($dir);
  	
  	if(substr($dir,strrpos($dir,"/")+1) == "config" || substr($dir,strrpos($dir,"/")+1) == "pages") {
  		$this->logSection("\n\nValidating configuration data in: ".$dir."\n",null);	
	  	if(!$this->hasXmlFiles($input)) {
	  			$this->logSection("","No config files found, skipping..");
	  	}
  	}
  	
  	foreach($input as $file) {
  		
  		$tmp = $dir."/".$file;
  		
  		if($file == "." || $file == ".." || $file == ".svn" || substr($file,0,1) == ".") {
  			continue;
  		}
  		
  		$xtmp = substr($dir,strrpos($dir,"/")+1);
  		
  		if(in_array($xtmp,$this->dirs) || $file == "config") {
  			$this->readConfigs($tmp);
  		}
  		else if(strtolower(substr($tmp,strrpos($tmp,".")+1)) == "xml") {
  			$this->validator->readXmlDocument($tmp,true);
  			$result = $this->validator->validateXmlDocument(true);
  			
  			if($this->args["reporting"] == "cache") {
  				$result[0] = "INFO";
  				$result[1] = null;
  			}
  			
  			$this->logSection(($this->args["reporting"] == "cache") ? "cached: " : (($result[1] === null) ? "valid: " : "error: "),$dir."/".$file,null,$result[0]);
  		
  			if($result[1]) {
  				$error = array("file" => $tmp, "message" => $result[1]->getMessage());
  				if($this->args["reporting"] == "full") {
  					$this->errors[] = $error;	
  				} else {
  					if($this->askConfirmation("Would you like to fix the error now? (yes / no)","QUESTION","yes") == "yes") {
  						echo Util::arrayToString($error);
						throw new Exception("An error has been found!");	
  					} else {
  						continue;
  					}
  					
  				}
  				
  			}
  			
  			$this->count++;
  			
  		}
  		
  	}
  	
  }
  
  private function printTotal() {
  	
  	$this->logSection("\nFinished!","The following result was generated:\n");
  	
  	$this->logSection("Total files",$this->count);
  	$this->logSection("Total valid files",$this->count - sizeof($this->errors));
  	$this->logSection("Total invalid files",sizeof($this->errors),null,"ERROR");
  	
  	if(!empty($this->errors)) {
  		
  		$handle = @fopen($this->log,"wr");
  		
  		if(!$handle) {
  			throw new Exception("Unable to write validation log file!");
  		}
  		
  		$buffer = "";
  		
  		foreach($this->errors as $error) {
  		  $buffer .= Util::arrayToString($error)."\n";	
  		}
  		
  		fwrite($handle,$buffer,strlen($buffer));
  		fclose($handle);
  		
  		$this->logSection("The log file has been successfully created!\n",null);
  		
  	}
  	
  }
  

}