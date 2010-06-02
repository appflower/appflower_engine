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
class afSecurityScanTask extends sfBaseTask
{
	
  private
  	$basedir, 
  	$dirs = array("modules"),
  	$result,
  	$dbmanager,
  	$file,
  	$count = 0,
  	$args;
  	
	
  /**
   * Configures task.
   */
  public function configure()
  {
  	
  	$this->addArguments(array(
  	  new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The name of symfony app'),
      new sfCommandArgument('sendto', sfCommandArgument::REQUIRED, 'An email address or a file name the results should be delivered or saved to'),
    ));
    
  	
    $this->namespace = 'appflower';
    $this->name = 'security-scan';
    $this->briefDescription = 'Scan all modules and extracts security.yml info as CSV. Mails the results or saves it to a local file';

    $this->detailedDescription = <<<EOF
This task scans all modules in your SF app, finds security.yml files and exports their contents as one CSV file, which can be loaded into
Excel or a similar application. The results can be either saved to a file or mailed to an email address. 

It has two parameters:

* application  -  The SF app to be scanned
* sendto       -  This is either an email address or absolute path. The results will be mailed or saved using this.

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
  	
  	
  	if(!strstr($arguments["sendto"],"@") && substr($arguments["sendto"],0,1) != "/") {
  		throw new Exception("Invalid value for argument: sendto. Either an absolute path or email address is expected, but '".
  		$arguments["sendto"]."' is given!");
  	}
  	
  	$mail = false;
  	
  	if(strstr($arguments["sendto"],"@")) {
  		$msg = "Report will be mailed to ".$arguments["sendto"];
  		$mail = true;
  	} else {
  		$msg = "Report will be saved to ".$arguments["sendto"];
  		$this->file = $arguments["sendto"];
  	}
  	
  	
  	$project = ProjectConfiguration::getActive();
  	$this->basedir = $project->getRootDir();
  	
  	$configuration = ProjectConfiguration::getApplicationConfiguration($arguments['application'], 'prod', true);
  	$this->dbmanager = new sfDatabaseManager($configuration);
   
    $this->args = $arguments;
  
    $this->logSection("\nScan has been started.. (this may take a while)\n".
    $msg."                                      \n",null,null,"ERROR");
    
    $this->result .= "Module;Widget;Credentials;Tested by;Date;In Build;Approved / Disapproved\n";
    
    foreach($this->dirs as $dir) {
    	$this->readConfigs($this->basedir."/apps/".$arguments['application']."/".$dir);	
    }	
    
    if(!$this->saveFile($mail)) {
    	$this->logSection("Unable to save results to file!",null,null,"ERROR");
    	exit();
    }
    
    if($mail) {
    	
    	$mailobj = myMail::createMail();
    	
    	$mailobj->addAddress($arguments["sendto"]);
		$mailobj->setFrom('AppFlower Bot <no-reply@appflower.com>');
		$mailobj->setSubject($arguments["application"]." Security Settings");
		
		$mailobj->setDomain('appflower.com');
		$mailobj->setSender('AppFlower Bot <no-reply@appflower.com>');
		$mailobj->setBody("Hello!\nThese are the widgets found in ".$this->basedir."/apps/".$arguments["application"].".\nPlease see attached CSV file.\n\nRegards\nBot");
		
		$mailobj->addAttachment($this->basedir."/data/security_tmp.csv","widget_data.csv");
		
		$mailobj->setPriority(1);
    	
		$mailobj->send();
		
		$this->logSection("Sending message..","(please wait..)");
		
		$this->printTotal(true);
    	
    } else {
    	
    	$this->printTotal(false);
    }
    
    
  }
  
  
  private function saveFile($mail = true) {
  	
  	$fp = fopen(($mail) ? $this->basedir."/data/security_tmp.csv" : $this->args["sendto"],"wt");
    
    if(!$fp) {
    	return false;
    } else {
    	fwrite($fp,$this->result);
    	fclose($fp);
    	return true;
    }
  	
  }
  
  
  private function addCredentials($module,$widget,$data) {
  	
  	  if(isset($data["is_secure"]) && (!$data["is_secure"] || $data["is_secure"] === "off")) {
  	  	$this->result .= $module.";".$widget.";-\n";	
  	  } else {
	  	  if(is_array($data)) {
	  		$this->result .= $module.";".$widget.";";
	  		$str = "";
	  		foreach($data[0] as $c) {
	  			$str .= $c.",";
	  		}
	  		$this->result .= trim($str,",")."\n";	
		  } else {
		  	$this->result .= $module.";".$widget.";".$data."\n";	
		  }	
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
  	
  	if(substr($dir,strrpos($dir,"/")+1) == "config") {
  		$module = substr(str_replace("/config","",$dir),strrpos(str_replace("/config","",$dir),"/")+1);
  		$this->logSection("\n\nScanning module: ".$module."\n",null);	
	  	if(!$this->hasXmlFiles($input)) {
	  			$this->logSection("","No widgets file found, skipping..");
	  	} else {
	  		$yml = sfYaml::load($dir."/security.yml");;
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

  			$widget = str_replace(".xml","",$file);
  			$this->logSection("found:",ucfirst($widget),null,"INFO");
  		
  			if(isset($yml[$widget]["credentials"])) {
  				$this->addCredentials($module,$widget,$yml[$widget]["credentials"]);
  			} else if(isset($yml["all"]["credentials"])) {
  				$this->addCredentials($module,$widget,$yml["all"]["credentials"]);
  			} else {
  				$this->addCredentials($module,$widget,"-");
  			}
  			
  			$this->count++;
  			
  		}
  		
  	}
  	
  }
  
  private function printTotal($mail = true) {
  	
  	$this->logSection("\nFinished!","The following result was generated:\n");
  	$this->logSection("Total widgets",$this->count);
  	
  	if($mail) {
  		$this->logSection("Results have been successfuly mailed to:".$this->args["sendto"],"");
  	} else {
  		$this->logSection("Results have been successfuly saved to: ",$this->args["sendto"]);
  	}
  	
  }
  

}