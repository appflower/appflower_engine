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
class afUpdateWidgetSelectorTask extends sfPropelBaseTask
{
	
  private
  	$basedir,
  	$pagedir,
  	$pages,
  	$context,
  	$classes = array(),
  	$dbmanager,
  	$widgets,
  	$yml_path,
  	$count = 0,
  	$fixtures;
  	
	
  /**
   * Configures task.
   */
  public function configure()
  {
  	
  	$this->addArguments(array(
  	  new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The name of symfony app'),
  	  new sfCommandArgument('clear', sfCommandArgument::OPTIONAL, 'Wheter to clear all data before job','no'),
  	  new sfCommandArgument('all', sfCommandArgument::OPTIONAL, 'Wheter to process all modules','no'),
    ));
    
  	
    $this->namespace = 'appflower';
    $this->name = 'update-widget-selector';
    $this->briefDescription = 'Updates or rebuilds widget selector settings and takes care about db cleanup.';

    $this->detailedDescription = <<<EOF
This task updates the widget selector db data or rebuilds it. The task processes all modules in an app, with all of 
their widgets. It adds new modules / widgets and removes old, unsused ones from the db. It also parses compoenent params
in page configs and allows you to update corresponding widget settings. 

By default, whenever a new module is found, a prompt will be shown to provide a name for the module. The corresponding 
widgets will be listed under this category in the widget selector. If you don't provide this, the module's SF name
will be used.

It also allows the exclusion of certain modules from processing, these (and their widgets) won't be listed in ws.
By default it will ask for confirmation upon finding any new module. If you set the "all" argument to true, these
confirmations will be supressed.

It has three params:

* appication - The SF appname, required.
* clear      - If true, it truncates db tables and removes fixture file before opration (i.e: rebuild).
* all        - If this switch is set to "yes", it will parse all modules and widgets without user intervention,
               confirmation dialogues won't be displayed. It will also skip the prompts for user input providing
               a custom names and the module's SF name will be used.

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
  	
  	// Paths..
  	
  	$this->basedir = $project->getRootDir();
  	$this->modules_dir = $project->getRootDir()."/apps/".$arguments['application']."/modules";
  	$this->fixtures["category"]["data"] = $this->fixtures["selector"]["data"] = "";	
  	$this->yml_path = $this->basedir."/data/fixtures/widget_selector.yml";
  	
  	$this->args = $arguments;
  
  	// Init DB
  	$configuration = ProjectConfiguration::getApplicationConfiguration($this->args["application"], 'prod', true);
    
  	@sfContext::createInstance($configuration);
  	$this->context = sfContext::getInstance();
  	
  	$this->dbmanager = new sfDatabaseManager($this->configuration);

    
    
    if($arguments["clear"] == "yes") {
    	
    	$this->logSection("\nClearing database..\n",null,60,"ERROR");
    	
    	afWidgetSelectorPeer::clear();
    	afWidgetCategoryPeer::clear();
    	
    	$this->logSection("Done..",null);
    	
    	$this->logSection("\nRemoving fixture file..\n",null,60,"ERROR");
    	
    	$this->logSection("File: ",$this->yml_path);
    	
    	if(file_exists($this->yml_path)) {
    		if(!@unlink($this->yml_path)) {
    			throw new Exception("Unable to delete file: ".$this->yml_path);	
    		} 
    	}

    }
    
    
    // Parse page configs for widget params.
    
    $this->logSection("\nScanning page configs..\n",null,60,"ERROR");
    
    $this->parsePages();
    
    $this->logSection("Done..",null);
    
    // Doing job..
    
    $this->logSection("\nScanning modules..\n",null,60,"ERROR");
     
    // Create / Update DB and file data..
    
    $this->doUpdate($this->modules_dir);
    
    // Save YML fixture file..
    
  	$this->saveFixture();
    
  	// Clearing unused widget data..
  	
  	$this->doDBCleanup();
  		
  	
  }
  
  
  private function doDBCleanup() {
  	
  	$c = new Criteria();
  	$c->add(afWidgetSelectorPeer::ID,$this->widgets,Criteria::NOT_IN);
  	$res = afWidgetSelectorPeer::doSelect($c);
  	
  	$this->logSection("\nClearing unused data..\n",null,null,"ERROR");
  	
  	if($res) {
  		foreach($res as $item) {
  			$this->logSection("Deleting: ",$item->getUrl(),null);
  			$item->delete();
  		}
  	} else {
  		$this->logSection("No outdated records..",null,null,"COMMENT");
  	}
  	
  }
  
  
  private function parsePages() {
  	
  	$this->pagedir = $this->basedir."/apps/".$this->args['application']."/config/pages";
  	$data = array();
  	
  	$files = scandir($this->pagedir);
  	
  	foreach($files as $file) {
  		if(strtolower(substr($file,strrpos($file,".")+1)) == "xml") {
  			$xp = XmlParser::readDocumentByPath($this->pagedir."/".$file);
  			$nodes = $xp->evaluate("//i:component[child::i:params]");
  			
  			if(!$nodes->length) {
  				continue;
  			} else {
  				foreach($nodes as $node) {
  					$key = $node->getAttribute("module")."/".$node->getAttribute("name");
  					$params = $xp->evaluate("./i:params/i:param",$node);
  					$param_arr = array();
  					foreach($params as $k => $param) {
  						$param_arr[($param->getAttribute("name")) ? $param->getAttribute("name") : "noname".$k] = trim($param->nodeValue);
  					}
  					$data[$key]["combos"][$file] = $param_arr;  	
  							
  				}
  			}
  		}	
  	}
  	
  	$this->pages = $data;
  	
  }
  
  
  private function saveFixture() {
  	
  	$this->logSection("\nWriting data file..\n",null,null,"ERROR");
  	
  	ksort($this->fixtures);
  	
  	$fp = @fopen($this->yml_path,"wt");
  	
  	if(!$fp) {
  		throw new Exception("Cannot read or create file: ".$this->yml_path);
  	}
  	
  	foreach($this->fixtures as $key => $content) {
  		$content["data"] = "afWidget".ucfirst($key).":\n".$content["data"];
  		fwrite($fp,$content["data"]);
  	}
  	
  	fclose($fp);
  	$this->logSection("File: ",$this->yml_path,null);
  	
  }
  
  private function isUnAllowedWidget($uri) {
  	
  	 $xp = XmlParser::readDocumentByUri($uri);
  	 $ret = $xp->evaluate("//i:view[@type='edit']|//i:view[@type='show']|//i:view[@type='wizard']")->length;
	 if($ret){
	 	return false;
	 }	
  	 $module = substr($uri,0,strrpos($uri,"/"));
  	 $method = "execute".ucfirst(substr($uri,strrpos($uri,"/")+1));
  	 $class = $module."Actions";
  	 	
  	 if(!class_exists($class) && !in_array($class,$this->classes)) {
  	 	require_once($this->modules_dir."/".$module."/actions/actions.class.php");
  	 	$this->classes[] = $class;
  	 }
  	 	
  	 $obj = new $class($this->context,$module,$method);	
	 $ret = !method_exists($obj,$method);  	 	
	 if($ret) {
	 	afWidgetSelectorPeer::removeWidgetByUrl($uri);
	 	return false;
	 }
  	 $permission = $obj->getCredential();
  	 if(is_null($permission)){
  	 	return false; 	
  	 }
  	 if(is_array($permission)){
  	 	$permission = json_encode($permission);
  	 }
  	 
  	 return $permission;
  }
  
  private function hasXmlFiles($data) {
  	
  	foreach($data as $tmp) {
  		if(strtolower(substr($tmp,strrpos($tmp,".")+1)) == "xml") {
  			return true;
  		}
  	}
  	
  	return false;
  	
  }
  
 private function doUpdate($dir,$module_name = null) {
  	
  	$input = scandir($dir);
  	
  	foreach($input as $file) {
  		
  		$tmp = $dir."/".$file;
  		
  		if($file == "." || $file == ".." || $file == ".svn" || substr($file,0,1) == ".") {
  			continue;
  		}
  		
  		if($file != "config" && is_dir($tmp)) {
  			
  			if(!($obj = afWidgetCategoryPeer::getCategoryByModule($file))) {
  				$this->logSection("\n\nFound new module: ".$file."\n",null);
	  			if(!file_exists($tmp."/config") || !is_dir($tmp."/config") || !$this->hasXmlFiles(scandir($tmp."/config"))) {
	  				$this->logSection("No widgets found, skipping..",null,null,"COMMENT");
	  				continue;
	  			}
  				if($this->args["all"] == "no" && !$this->askConfirmation("Would you like the module to be processed (y / n)","QUESTION","y")) {
  					continue;
	  			}
	  			$tmp_name = ucwords(sfInflector::humanize($file));
  				$longname = trim($this->ask("Please provide a name for this module (".$tmp_name." by default):","INFO"));
  				echo "\n\n";
  				if($longname == "") {
  					$longname = $tmp_name;
  				}
  				$cat_id = afWidgetCategoryPeer::addNewItem($file,$longname);
  			} else {
  				$longname = $obj->getName();
  				$cat_id = $obj->getId();
  				$this->logSection("\n\nFound existing module: ".$file."\n",null);
	  			if(!file_exists($tmp."/config") || !is_dir($tmp."/config")) {
	  				$this->logSection("No widgets found, skipping..",null,"COMMENT");
	  				continue;
	  			}
  			}
  			
  			$this->fixtures["category"]["data"] .= 
"  category".$cat_id.":
    module: ".$file."\n    name: ".$longname."\n";
  			
  			$this->doUpdate($tmp."/config",$file);
  			
  		} else {
  			if(strtolower(substr($tmp,strrpos($tmp,".")+1)) == "xml") {
  				$base = str_replace(".xml","",$file);
  				$url = $module_name."/".$base; 
  				$params = null;
  				$permission = $this->isUnAllowedWidget($url);
  				if($permission == FALSE) {
  					$this->logSection("Found unallowed widget (skipping): ",ucfirst($base),null,"COMMENT");
  					continue;
  				}else{
  					
  				}
  				
  				if(!($obj =afWidgetSelectorPeer::getWidgetByUrl($url))) {
  					$new = true;
  					$msg = "new";
  				} else {
  					$new = false;
  					$msg = "existing";
  					$wid_id = $obj->getId();
  					$cid = $obj->getCategoryId();
  				}
  				
  				if(isset($this->pages[$url]) && ($new ||(!$new && !$obj->getParams()))) {
  					$this->logSection("Found ".$msg." widget, requires params: ",ucfirst($base),null);
  					$item = $this->pages[$url];
  					$no = 0;
		  			foreach($item["combos"] as $file => $set) {
		  				$this->logSection("\n\nOption [".($no+1)."]","(page: ".$file.")",60,"QUESTION");
		  				foreach($set as $name => $value) {
		  					$this->logSection(null,$name." = ".$value,null);
		  				}
		  				$no++;
		  			}
		  			echo "\n\n";
		  			while(1) {
		  				$selected = trim($this->ask("Please choose one of the above configs or hit enter to add custom one:","QUESTION"));	
		  				if($selected === "" || ($selected > 0 && $selected <= count($item["combos"]))) {
		  					if($selected === "") {
		  						$selected = trim($this->ask("Please provide parameters as a query string:","QUESTION"));
		  					} else {
		  						$no = 0;
		  						foreach($item["combos"] as $tmp) {
		  							if($no == $selected-1) {
		  								$selected = $tmp;
		  								break;
		  							}
		  							$no++;
		  						}	
		  					}
		  					break;
		  				}
		  			}
		  			if(is_array($selected)) {
		  				$params = ArrayUtil::arrayToQueryString($selected);	
		  			} else {
		  				$params = $selected;
		  			}
		  			
  				}
  				
  				if($new) {
  					$cid = afWidgetCategoryPeer::getCategoryByModule($module_name)->getId();
  					$wid_id = afWidgetSelectorPeer::addNewItem($url,$cid,$params,$permission);
  				} else {
  					$params = $obj->getParams();
  				}
  				
  				$this->widgets[] = $wid_id;
  				
  				$this->fixtures["selector"]["data"] .= 
"  widget".$wid_id.":
    url: ".$url."\n    category_id: category".$cid."\n    params: ".$params."\n    permission: ".$permission."\n";
  				
  				if(!$params) {
  					$this->logSection("Found ".$msg." widget: ",ucfirst($base),null);	
  				}
  				 
  			}
  		}
  		
  		$this->count++;
  		
  	}	
  	
  }
  

}