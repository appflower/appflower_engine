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
class afWidgetBuilderTask extends sfPropelGenerateAdminTask
{
	
  private 
  	$path,
  	$genpath,
  	$schema = array(),
  	$generator,
  	$document,
  	$defaultGenerator;	
  	
	
  /**
   * Configures task.
   */
  public function configure()
  {
  	
   $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
      new sfCommandArgument('route_or_model', sfCommandArgument::REQUIRED, 'The route name or the model class'),
    ));

    $this->addOptions(array(
      new sfCommandOption('module', null, sfCommandOption::PARAMETER_REQUIRED, 'The module name', null),
      new sfCommandOption('singular', null, sfCommandOption::PARAMETER_REQUIRED, 'The singular name', null),
      new sfCommandOption('plural', null, sfCommandOption::PARAMETER_REQUIRED, 'The plural name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('no-forms', null, sfCommandOption::PARAMETER_REQUIRED, 'Skip forms and filters generation', null),
    ));
    
    $this->namespace = 'appflower';
    $this->name = 'generate-admin';
    $this->briefDescription = 'Generates PHP action code for widgets';

    $this->detailedDescription = <<<EOF
This builds the Propel / AppFlower CRUD for any model class or route, and creates widget XML configs automatically using the Schema and
generator.yml settings.

This is an extension of [propel:generate-admin|INFO] task, but some extra functionality has been added.

Therefore the task takes the same options and arguments as [propel:generate-admin|INFO], except the "theme" option, which you cannot define. 

Here we'll list only AppFlower specific arguments and options.
For a detailed description of the underlying Propel Admin generator, please see:

[symfony help propel:generate-admin|COMMENT]

EOF;

  }

  /**
   * Displays the list of existing job queues.
   * 
   * @param   array   $arguments    (optional)
   * @param   array   $options      (optional)
   */
  protected  function execute($arguments = array(), $options = array())
  {
  	
  	$project = ProjectConfiguration::getActive();
  	$root = $project->getRootDir();
  	
  	// Setting basic vars..
  	
  	$model = $arguments['route_or_model'];
    $name = strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), '\\1_\\2', $model));
  	$module = $options['module'] ? $options['module'] : $name;
  	
  	$this->path = $root."/apps/".$arguments["application"]."/modules/".$module;
  	
  	// Theme is always AppFlower..
  	
  	$options["theme"] = "appFlower";
    
  	
  	if(!is_dir($this->path)) {
  		
  		// Reading Schema (from all plugins and the app)
  	
	  	if(!file_exists($root."/config/schema.yml")) {
	  		 throw new sfCommandException("Couldn't read schema.yml!");
	  	}
	  	
	  	$this->schema = SchemaUtil::readSchema(true);	
  		
  		// Check if routing is used, and determine the model name.
  	
	  	$route = $this->getRouteFromName($model);
	  	
	  	if (false !== $route)
	    {
	      
	      $options = $route->getOptions(); 
	      $model = $options["model"];
	    
	    }
	  	
	    // Generate CRUD..
	    
	    if(!$options["no-forms"]) {

		    $classes = array(
		    	"sfPropelBuildFormsTask" => "Generating Forms & Filters..",
		    	"sfPropelBuildFiltersTask" => "",
		 	);
		 	
		 	foreach($classes as $class => $message) {
		 		$this->runTask(array($class,$message));
		 	}
	    	
	    }
	    
  		$this->logBlock("Generating CRUD for ".$arguments["application"]."/".$module,"QUESTION");
	
	    // is it a model class name
	    if (!class_exists($model))
	    {
	      throw new sfCommandException(sprintf('The route "%s" does not exist and there is no "%s" class.', $model, $model));
	    }
	
	    $r = new ReflectionClass($model);
	    if (!$r->isSubclassOf('BaseObject'))
	    {
	      throw new sfCommandException(sprintf('"%s" is not a Propel class.', $model));
	    }
  		
	    $arguments["model"] = $model;
	    $arguments["module"] = $module;
	    
	    $this->generate($arguments, $options);
  		
  		$this->logBlock("Generating Widget XML data..","QUESTION");
	
	    // Get schema data..
	    
	    $data = SchemaUtil::getSchemaDataForModel($model,$this->schema);
	    
	    // Generate widget XMLs..
	    
  		$this->generateWidgets($data,$model,$module);
  		
  		$this->logBlock("Clearing cache..","QUESTION");
  		
  		$this->runTask(array("sfCacheClearTask",null));
  		
  		$this->logBlock("All done!","QUESTION");
  		$this->logSection("Module has been successfuly initialized!",null,null,"INFO");
  		$this->logSection("Please fill apps/".$arguments["application"]."/modules/".$module."/config/generator.yml to fine-tune..",null,null,"INFO");
  		
  		
  	} else {
  		
  		 throw new sfCommandException("Module \"".$module."\" alerady exists! Please fill generator.yml!");
  		
  	}
  
  }
  
  protected function runTask(Array $data,$arguments = array(), $options = array()) {
  	
  	if($data[1]) {
  		$this->logBlock($data[1],"QUESTION");	
  	}
  	
    $task = new $data[0]($this->dispatcher, $this->formatter);
    $task->setCommandApplication($this->commandApplication);

    $task->run($arguments, $options);
  	
  }
  
  
  protected function generate($arguments, $options)
  {
    $module = $arguments['module'];
    $model = $arguments['model'];

    // execute the propel:generate-module task
    $task = new sfPropelGenerateModuleTask($this->dispatcher, $this->formatter);
    $task->setCommandApplication($this->commandApplication);

    $taskOptions = array(
      '--theme='.$options['theme'],
      '--env='.$options['env'],
      '--generate-in-cache',
      '--route-prefix=~',
      '--non-verbose-templates',
    );

    if (!is_null($options['singular']))
    {
      $taskOptions[] = '--singular='.$options['singular'];
    }

    if (!is_null($options['plural']))
    {
      $taskOptions[] = '--plural='.$options['plural'];
    }

    $this->logSection('app', sprintf('Generating admin module "%s" for model "%s"', $module, $model));

    return $task->run(array($arguments['application'], $module, $model), $taskOptions);
    
  }
  
  
  public static function getFieldType($name,$type) {
  	
  	$types = array
  	(
  	"timestamp" => "datetime",
  	"date" => "date",
  	"longvarchar" => "textarea",
  	"boolean" => "checkbox",
  	"fk" => "combo",
  	"pk" => "hidden"
  	);
  	
  	$ret = new stdClass();
  	
  	if(is_array($type)) {
  		foreach($type as $prop => $value) {
  			$ret->$prop = $value;
  		}
  		if(property_exists($ret, "foreignTable")) {
  			$ret->type = "fk";
  		} else if(property_exists($ret, "primaryKey")) {
  			$ret->type = "pk";
  		}
  		
  	}  else {
  		if($name == "id") {
  			$ret->type = "pk";
  		} else {
  			$ret->type = $type;	
  		}
  		
  	}
  	
  	$ret->type = ($name == "updated_at" || $name == "created_at") ? "datetime" : ((isset($types[$ret->type])) ? $types[$ret->type] : "input"); 

  	return $ret;
  	
  }
  
  private function generateWidgets(Array $data,$model,$module) {
    
    // Replace basic tokens. for edit / show..
    
  	if(isset($data["_attributes"])) {
  		unset($data["_attributes"]);	
  	}
  	
  	$visible = trim(implode(",",array_keys($data)),",");
  	
    $tokens = array
  	(
  	"TITLE" => "Edit / Create ".$model,
  	"DATACLASS" => $model."Peer",
  	"UPDATE_URL" => $module."/update",
  	"LIST_URL" => $module."/list",
  	"DELETE_URL" => $module."/delete",
  	"ADD_URL" => $module."/edit",
  	"ADD_TITLE" => "Add new item",
  	"VISIBLE" => $visible,
  	);
   
  	$templates = array
  	(
  	"list","edit"
  	);
  	
  	foreach($templates as $key => $template) {

  		$path = $this->path."/config/".$template.".xml";
  		
  		$this->getFilesystem()->replaceTokens($path, '##', '##', $tokens);
    
	    // Add fields and columns..
	    
	    $xpath = XmlParser::readDocumentByPath($path);
	    $document = $xpath->document;
	    $document->formatOutput = true;
	    $document->normalizeDocument();
	    
	   	$ns = $document->lookupNamespaceUri("i");
	   
	   	$fields = $xpath->evaluate("//i:fields")->item(0);
	   	
	   	foreach($data as $column => $type) {
	   		
	   		$coltype = $this->getFieldType($column, $type);
	   		
   			if(!$key) {
   				$elem = $document->createElementNS($ns,"column");	
   			} else {
   				
   				$elem = $document->createElementNS($ns,"field");
   				$elem->setAttribute("type",$coltype->type);
   				
	   			if(property_exists($coltype, "default") && $coltype->type == "checkbox") {
	   				$elem->setAttribute("checked","true");
	   			} else {
	   				
	   				if(property_exists($coltype, "foreignTable")) {
	   					
	   					$table = $this->schema["propel"][$coltype->foreignTable];
	   					$peer = $table["_attributes"]["phpName"]."Peer";
	   					unset($table["_attributes"]);
	   					
	   					$id_column = $coltype->foreignReference;
	   					
	   					if(isset($table["name"])) {
	   						$name_column = "name";
	   					} else if(isset($table["title"])) {
	   						$name_column = "title";
	   					} else {
	   						foreach($table as $colname => $thecol) {
	   							$c = (is_array($thecol)) ? $thecol["type"] : $thecol;
	   							if(strstr($c,"varchar")) {
	   								$name_column = $colname;
	   								break;
	   							}
	   						}
	   						if(!$name_column) {
	   							$name_column = $id_column;	
	   						}
	   						
	   					}
	   					
	   					$child = $document->createElementNS($ns,"value");
	   					$child->setAttribute("type","orm");
	   					$vchild = $document->createElementNS($ns,"class","afGenerator");
	   					$child->appendChild($vchild);
	   					$vchild = $document->createElementNS($ns,"method");
	   					$vchild->setAttribute("name","asCombo");
	   					$child->appendChild($vchild);
	   					
	   					$values = array
	   					(
	   					1 => array($peer,"peer"),
	   					array($name_column,"name"),
	   					array($id_column,"id"),
	   					);
	   					
	   					$i = 1;
	   					while($i < 4) {
	   						
	   						$vchild = $document->createElementNS($ns,"param",$values[$i][0]);
	   						$vchild->setAttribute("name",$values[$i][1]);
	   						$child->lastChild->appendChild($vchild);
	   						$i++;
	   					}
	   					
	   					$elem->appendChild($child);
	   					$elem->setAttribute("selected","{".$column."}");
	   					
	   				}
	   				
	   			}
	   			
	   			if(!property_exists($coltype, "foreignTable")) {
		   			$value = $document->createElementNS($ns,"value");
		   			$value->setAttribute("type","orm");
		   			$source = $document->createElementNS($ns,"source");
		   			$source->setAttribute("name","get".sfInflector::camelize($column));
		   			$value->appendChild($source);
		   			$elem->appendChild($value);
	   			}
	   			
   			}
   		
   			$elem->setAttribute("label",$column);	
   			$elem->setAttribute("name",$column);
   			
   			$fields->appendChild($elem);	
	   		
	   	}
	   
	   	$document->save($path);
  		
  	}
  	
    
  }
  

}