<?php
					
class XmlParser extends XmlParserTools {

	private 
		$schema,
	    $schemaLocation,
		$schemaNamespace = "http://www.w3.org/2001/XMLSchema",
		$root,
		$application,
		$view,
		$widgets,
		$layout,
		$validator,
		$document,
		$xmlDefaults,
		$multi = false,
		$iteration = 0,
		$fields, $elements, $process = array(),
		$page,
		$step,
		$forms,
		$context,
		$attribute_holder,
		$tree,
		$tree_root,
		$datastore = false,
		$tree_item,
		$tree_tmp = array(),
		$current,
		$floated = null,
		$tabbedWizard = false,
		$currentGroup = 1,
		$openGroup = false,
		$wizardSets,
		$portalConfig,
		$portalIdXml,
		$portalStateObj,
		$is_floated,
		$portalColumns,
		$portalColumnsSize,
		$portalSizes = array(
			1=>array(100),
			array(50,50),
			array(25,75),
			array(75,25),
			array(33,33,33),
			array(50,25,25),
			array(25,50,25),
			array(25,25,25,25),
			array(40,20,20,20),
		),
		$extobjects,
		$user,
		$area_types = array
		(
		"content" => "center",
		"sidebar" => "west",
		"footer" => "south"
		),
		$defaultPanels,
		$manualMode = false,
		$multisubmit = false,
		$widgetHelpSettings;
		
	private static
		$dbschema,
		$instance;
		
	public 
		$dschecked = false;
			
	public static
		$masterLayout;
	
	const
		 PANEL = false,
		 PAGE = 1,
		 WIZARD = 2;
		 

	function __construct($type = self::PANEL, $dry_run = false, $step = false, $manual = false, $internal = false) {
		
		if(self::$instance) {
			return true;
		}
		
		if(!defined("NODES")) {
			define("NODES",1);	
		}
		if(!defined("VALUES")) {
			define("VALUES",2);	
		}
		if(!defined("ATTRIBUTES")) {
			define("ATTRIBUTES",3);	
		}
		if(!defined("PAIRS")) {
			define("PAIRS",4);	
		}
		
		$this->root = sfConfig::get("sf_root_dir");
		$this->schemaLocation = $this->root."/plugins/appFlowerPlugin/schema/appflower.xsd";
		
		// Context info
			
		$this->context = sfContext::getInstance();
		
		$this->application = $this->context->getConfiguration()->getApplication();
		
		// Parser type
		
		$this->type = $type;
		
		// Set user
		
		$this->user = $this->context->getUser();
		
		// Clear Validators		
		
		if(!$internal) {
			$this->context->getUser()->getAttributeHolder()->removeNamespace('parser/validation');	
		}
		
		$this->attribute_holder = $this->context->getActionStack()->getLastEntry()->getActionInstance()->getVarHolder()->getAll();
		
		try {
			if(!file_exists($this->schemaLocation) || !is_readable($this->schemaLocation)) {
				throw new XmlParserException("Was unable to read main schema document. Please check ".$this->schemaLocation."!");
			}  else if($this->type === self::WIZARD && !isset($this->attribute_holder["current"])) {
				throw new XmlParserException("The step parameter must be defined in case of wizard layout!");
			}
			 
			if(isset($this->attribute_holder["current"])) {
				$this->current = $this->attribute_holder["current"];	
			}
			
			if($this->type === self::WIZARD && !$this->current) {
				throw new XmlParserException("The value of step parameter must be either false or a positive number!");
			}  	
			
			$this->step = $step;
			
			if($this->type === self::WIZARD && !isset($this->attribute_holder["step"])) {
				throw new XmlParserException("Required variable step cannot be found in attribute holder!");
			}
			
		}
		catch(Exception $e) {
			throw $e;
		}
		
		if($dry_run) {
			return true;
		}
		
		// Reading default footer and sidebar data
		
		$this->parseDefaultPanels();
		
		// Reading Main XML Schema..
		
		$this->schema = new DOMDocument();
		$this->schema->load($this->schemaLocation);
		
		// Reading enum values..
		
		$this->fetchEnums();
		
		// Check widget access rights
		
		if($this->type === self::PANEL || $this->type === self::WIZARD) {
			$this->checkWidgetCredentials();	
		}
		
		// Is this a layout?
		
		$this->page = $type;
		
		// Assign DOM Document..
		
		$this->readXmlDocument();
		parent::__construct($this->document);
	
		$root = $this->document->getElementsByTagName("view")->item(0);
		$view_type = $root->getAttribute("type"); 
		
		$view_type = XmlBaseElementParser::parseValue($view_type,$root,true);
		$this->set("type",$view_type,$root);
		
		$this->enumCheck("i:viewType",$view_type);
			
		if($this->type === self::WIZARD && isset($this->attribute_holder["init"])) {
			if(!$this->fetch("//i:datastore")->length) {
				$this->datastore = false;	
			} else {
				$this->datastore = true;
			}
			
		} 
		
		// Create layout
		
		if($type === self::PANEL) {
			self::$masterLayout = null;
			$this->layout = new ImmExtjsPanelLayout();
		} else {
			if(self::$instance === null) {
				self::$instance = true;
			}
			if($type === self::WIZARD) {
				
				$this->isTabbed();
				
				if(!$this->tabbedWizard) {
					$wizattrs = array('id'=>'center_panel','title'=>"");
				} else {
					$wizattrs = array('id'=>'center_panel','title'=>"",'centerType'=>'group');
				}
				$this->layout = new ImmExtjsWizardLayout($wizattrs);
			} else {
				
			$this->portalIdXml = $this->context->getModuleName()."/".$this->context->getActionName();
			$this->portalStateObj = afPortalStatePeer::retrieveByIdXml($this->portalIdXml);
			
				if(!$this->portalStateObj)
				{	
				
					//default values for layout & columns
					$this->portalConfig = new stdClass();
					$this->portalConfig->layoutType = ($this->fetch("//i:tab")->length) ? afPortalStatePeer::TYPE_TABBED : afPortalStatePeer::TYPE_NORMAL;
					$this->portalConfig->content = array();
					$this->portalConfig->content[0]["portalLayoutType"] = sfConfig::get("app_parser_default_layout","[100]");					
					$this->portalConfig->idXml = $this->portalIdXml;					
				}
			}
			
			if($this->layout) {
				self::$masterLayout = $this->layout;	
			}
			
			
		}
		
		$this->manualMode = $manual;
		
		/**
		 * widget help settings
		 * 
		 * ticket #300 - radu
		 */
		$this->widgetHelpSettings=afWidgetHelpSettingsPeer::retrieveCurrent();
		
		if(!$manual) {
			$this->runParser(1);	
		}
		
	}
	
	
	/******************************************************************
	*	MAIN FUNCTION
	*	
	*	These is where we call all the parseElementXXX() methods
	* 	right after pre-processing.
	*******************************************************************/
	
	private function parseXmlDocument() {
        Console::profile('parseXmlDocument');
        
		
		$this->fields = array();
		
		try {
			foreach($this->elements as $element => $arguments) {
				$basename = substr($element,strrpos($element,"-")+1);	
				$classname = $basename."Parser";
				
				if(!class_exists($classname)) {
					throw new XmlParserException("Class ".$classname." doesn't exists!");
				} else if(!method_exists($classname,"parse")) {
					throw new XmlParserException("The required 'parse' method doesn't exists in class ".$classname."!");
				} else if(!method_exists($classname,"setParser")) {
					throw new XmlParserException("The required 'setParser' method doesn't exists in class ".$classname."!");
				}
				
				foreach($arguments as $args) {
					
					if($basename == "field" || $basename == "button" || $basename == "show" || $basename == "linkbutton" || 
					$basename == "link") {
						$this->fields[$this->get($args["node"],"name")] = $this->get($args["node"],"name");
					}
					
					// Set parser and parse the element..
					call_user_func(array($classname,"setParser"),$this);
					
					if(call_user_func(array($classname,"init"),$args["node"]) === true) {
						$ret = call_user_func(array($classname,"parse"),$args["node"],$args["parent"]);	
					} else {
						$ret = -1;
					}
					
					// Save data..
					if((int) $ret > 0) {
						$this->storeProcessed(call_user_func(array($classname,"getRetVal")));	
					} else if($ret === false) {
						throw new XmlParserException("Parser ".$classname." returned with false!");
					} else if($ret == -1) {
						call_user_func(array($classname,"clearRetVal"));
						//echo $classname."<br>";
					}
				}
			}	
		}
		catch(Exception $e) {
			throw $e;
		}
		
		
		$this->elements = array();
		
	}
	
	
	/******************************************************************
	*	PARSER PUBLIC FUNCTIONS
	*	
	*******************************************************************/
	
	public function getProcess() {
		return $this->process;
	}
	
	public function getSchema() {
		$xp = new DOMXPath($this->schema);
		$xp->registerNamespace("xs","http://www.w3.org/2001/XMLSchema");
		
		return $xp;

	}
	
	public function getEnums() {
		return $this->enums;
	}
	
	
	public function testConditions() {
		
		$ifs = $this->fetch("//i:if");
		
		if(!$ifs->length) {
			return true;
		}
		
		$root = $this->fetch("/i:view");
		
		$view = $this->get($root->item(0),"type");
		
		foreach($ifs as $if) {
			$condition = str_replace("&amp;","&",$this->get($if,"test"));
			
			$parent = $if->parentNode;
		
			if(!eval("return (".$condition.");")) {
				
				$childnodes = $this->fetch("child::*",$if);
				
				$this->remove($if);
				
				foreach($childnodes as $child) {
					
					$name = $this->name($child);
					
					if($name == "field") {
						$grouping = $this->fetch("//i:grouping/i:set/i:ref[@to='".$this->get($child,"name")."']");
						if($grouping->length == 1) {
							$this->remove($grouping->item(0));
						}
					} else if($name == "action") {
						if($this->childcount($parent,3) == 0) {
							$this->remove($parent);
						}
					} 	
				}	
			}
		}
		
		return true;
		
	}
	
	
	public function enumCheck($type,$value) {
		
		$enums = $this->getEnums();
		
		if(!in_array($value,$enums[$type])) {
			throw new Exception("Invalid ".$type." value \"".$value."\": \"".
			implode(", ",$enums[$type])."\" expected!");
		} 
		
		return true;
	}
	
	public function getLayout() {
		return $this->layout;
	}
	
	public function getView() {
		return $this->view;
	}

	public function getIteration() {
		return $this->iteration;
	}
	
	public function setFooter($input) {
		return $this->setPanel($input, false);
	}
	
	public function setSidebar($input) {
		return $this->setPanel($input);
	}
	
	public function run() {
		
		if($this->manualMode) {
			$this->runParser(1);	
		}
	}
	
	public function createTree($title) {
		$this->tree = new ImmExtjsTree(array('title'=>$title));
	}
	
	public function addRoot() {
		$this->tree_root = $this->tree->startRoot(array("title" => "Startroot"));
	}
	
	public function addItem($item,$root = false) {
		if($root) {
			$this->tree_item = $this->tree_root->addChild($item);
		} else {
			$this->tree_item = $this->tree_item->addChild($item);
		}
	}
	
	
	public function createFieldSets() {
		
		$nodes = $this->fetch("//i:grouping");
		
		if($nodes->length == 0) {
			$this->process["parses"][$this->iteration]["isgrouped"] = false;
			return true;
		}
		$def_isSetting = $this->get($nodes->item(0),"isSetting");
		$this->process["parses"][$this->iteration]["isSetting"] = $def_isSetting;
		$def_title = $this->get($nodes->item(0),"title");
		$def_collapsed = $this->get($nodes->item(0),"collapsed");		
		$nodes = $this->fetch("//i:set");
		$it = new nodeListIterator($nodes);
		$name = null;
		
		// Named sets..
		
		foreach($it as $node) {
			
			$nodes = $this->fetch("./i:ref",$node);
			$it_in = new nodeListIterator($nodes);
			$name = $this->get($node,"title");
			
			if($this->has($node,"tabtitle")) {
				$tabname = $this->get($node,"tabtitle");	
			} else {
				$tabname = null;
			}
			if($this->has($node,"tabHeight")) {
				$tabHeight = $this->get($node,"tabHeight");	
			}
			else {
				$tabHeight = null;
			}
			if($this->has($node,"iconCls")) {
				$tabIconCls = $this->get($node,"iconCls");	
			}
			else {
				$tabIconCls = null;
			}
			
			if($this->tabbedWizard && $tabname) {
				$name = $tabname;
			}
			
			$collapsed = $this->get($node,"collapsed");
			$columns = $this->get($node,"columns");
			$float = $this->get($node,"float");
			
			if(!$this->tabbedWizard) {
				
				if(!isset($this->process["parses"][$this->iteration]["fields"][$name])) {
					$this->process["parses"][$this->iteration]["fields"][$name] = array();
				}
				
				$this->process["parses"][$this->iteration]["fields"][$name]["attributes"] = array("title" => $name, 
				"collapsed" => ($collapsed == "false") ? false : true,"columns" => $columns, "float" => $float,"tabHeight"=>$tabHeight,"tabIconCls"=>$tabIconCls);
								
				if($tabname != null) {
					$this->process["parses"][$this->iteration]["fields"][$name]["attributes"]["tabtitle"] = $tabname;
				}	
			}
			
			foreach($it_in as $ref) {
				$to = $this->get($ref,"to");
				$group = $this->get($ref,"group");
				
				if($group === "false") {

					//$float = $this->get($ref,"float");
					$break = $this->get($ref,"break");
				
					if($this->tabbedWizard && $this->iteration == 0) {
						$tabtip = ($this->has($ref,"tip")) ? $this->get($ref,"tip") : "";
						$tabtitle = $this->get($ref,"title");
						$this->process["parses"][$this->iteration]["groups"][$name][] = array($to,$tabtitle,$tabtip); 	
					} else {
						$this->process["parses"][$this->iteration]["fields"][$name][$to] = $this->process["parses"][$this->iteration]["fields"][$to];
						//$this->process["parses"][$this->iteration]["fields"][$name][$to]["attributes"]["float"] = $float;
						$this->process["parses"][$this->iteration]["fields"][$name][$to]["attributes"]["break"] = $break;
						unset($this->process["parses"][$this->iteration]["fields"][$to]);
						unset($this->fields[$to]);	
					}
					
				} else {
					$fields = $this->getFieldsFor($to);
					foreach($fields as $id => $item) {
						$this->process["parses"][$this->iteration]["fields"][$name][$id] = $item;	
						//$this->process["parses"][$this->iteration]["fields"][$name][$id]["attributes"]["float"] = "false";
						unset($this->process["parses"][$this->iteration]["fields"][$id]);
						unset($this->fields[$id]);
					}
				}
			}
		}
		
		// Defaults..
		
		if(!empty($this->fields)) {
			
			foreach($this->fields as $key => $field) {
				$f = $this->process["parses"][$this->iteration]["fields"][$field];
				
				if($name && isset($f["attributes"]["type"]) && ($f["attributes"]["type"] == "hidden" || 
				$f["attributes"]["type"] == "link" || $f["attributes"]["type"] == "linkButton" || 
				$f["attributes"]["type"] == "button")) {
					unset($this->fields[$key]);	
					$this->process["parses"][$this->iteration]["fields"][$name][$field] = $this->process["parses"][$this->iteration]["fields"][$field];
					unset($this->process["parses"][$this->iteration]["fields"][$field]);	
				}
				
			}
					
			if(!empty($this->fields)) {
				if(!isset($this->process["parses"][$this->iteration]["fields"][$def_title])) {
					$this->process["parses"][$this->iteration]["fields"][$def_title] = array();
				}
								
				$this->process["parses"][$this->iteration]["fields"][$def_title]["attributes"] = array("title" => $def_title, 
				"collapsed" => ($def_collapsed == "true"),"columns" => 1, "float" => "false");
	
				foreach($this->fields as $field) {
					$this->process["parses"][$this->iteration]["fields"][$def_title][$field] = $this->process["parses"][$this->iteration]["fields"][$field];	
					unset($this->process["parses"][$this->iteration]["fields"][$field]);
					
				}	
			}	
		}
		
		$this->process["parses"][$this->iteration]["isgrouped"] = true;
	
		if($this->tabbedWizard && $this->iteration == 0) {
			$this->reArrangeComponents();
		}
		
	}
	
	public static function toggleAction($action,$info,$args = null) {
		
		$data = null;
		
		if(isset($info["conditions"][$action])) {
			$data = $info["conditions"][$action];	
		} else if(isset($info["attributes"]["condition"])) {
			$data = $info["attributes"]["condition"];
		} 
		
		if(!$data) {
			return true;
		}

		$tmp = explode(",",$data);
		$class = $tmp[0];
		$method = $tmp[1];

		unset($tmp[0],$tmp[1]);
		
		if(!$args) {
			$args = $tmp;
		}
		
		if(call_user_func(array($class,$method),$args) !== false) {
			return true;
		} else {
			return false;
		}
			
		
		
		
	}
	
	
	/******************************************************************
	*	PARSER PRIVATE FUNCTIONS
	*	
	*	No need to edit these!
	*******************************************************************/
	
	
	public function runParser($arg = 0,$region = "content") {
		if($region == "object") {
			$this->iteration = 99;
		}
		
		// Set view type..
		
		$this->setView();
		
		// Do pre-processing...
	
		$this->preProcess();
	
		// Calling the parser..
		
		$this->parseXmlDocument();
		
		// If layout is view, parse xmls
		
		if($region != "object" && $this->iteration == 0 && ($this->type == self::WIZARD || $this->type == self::PAGE)) {
			
			$this->readTemplates();
			
		}
		
		if(($this->view == "edit" || $this->view == "show") && !isset($this->process["parses"][$this->iteration]["isgrouped"])) {
			$this->createFieldSets();
		}
		
		// Cleaining extra attributes used by only the parser
		
		$this->clearAttributes();
		
		if($region == "object") {
			return $this->postProcess(true);
		}
		
		// Do post-processing...
		if(!$this->multi) {
			$this->process["parses"][$this->iteration]["area"] = $region;
			if(count($this->defaultPanels["sidebar"]["components"]) || count($this->defaultPanels["footer"]["components"])) {
				if(count($this->defaultPanels["sidebar"]["components"])) {
					$item = array_shift($this->defaultPanels["sidebar"]["components"]);
					$region = "sidebar";
				} else if(count($this->defaultPanels["footer"]["components"])) {
					$item = array_shift($this->defaultPanels["footer"]["components"]);
					$region = "footer";
				} 
				
				$path = $this->getPath($item["module"]."/".$item["name"]);
				$this->readXmlDocument($path);
				$this->iteration++;
				$this->runParser(1,$region);
				
				return true;
			
			} else {

				$this->postProcess();	
			
			}
				
		} 
		
	}
	
	
	private function fetchEnums() {
		
		$schema = $this->getSchema();
		
		$enums = array();
		$enumNodes = $schema->evaluate("//child::xs:simpleType[descendant::xs:enumeration]");
			
		foreach($enumNodes as $e) {
			$tmp = $schema->evaluate("descendant::xs:enumeration",$e);	
			$tmp_name = trim($e->getAttribute("name"));
			if(!$tmp_name) {
				continue;
			}
			foreach($tmp as $t) {
				$enums["i:".$tmp_name][] = $t->getAttribute("value");
			}
		}
		
		$this->enums = $enums;
	}
	
	private function checkWidgetCredentials($module = null,$action = null) {

		$action_name = ($action === null) ? $this->context->getActionName() : $action;	
		/*$class = $module."Actions";
		include_once("/usr/www/manager/apps/frontend/modules/".$module."/actions/actions.class.php");		
		$class = new $class($this->context,$module,$action);
		if(!$this->user->hasCredential($class->getCredential())) return false;*/
		$actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();
		$path = $this->getPath(($module === null) ? $this->context->getModuleName() : $module, true);
		
		if(!file_exists($path)) {
			return true;
		}
		
		$this->readXmlDocument($path,true);
		
		$permissions = $this->fetch("//s:permissions[@for='".$action_name."']");
		
		
		if(!$permissions->length) {
			return true;
		}
		
		$url = $this->getnode("url",$this->document->documentElement);
	
		$rights = $this->fetch("./s:right",$permissions->item(0));
		$credentials = "";
		
		foreach($rights as $right) {
			$r = $this->get($right,"name");
			
			if($r == "*") {
				return true;
			}
			
			$credentials .= $this->get($right,"name").",";
		}
		
		if($this->checkCredentials($credentials) === false) {
			if($this->type === self::PANEL || $this->type === self::WIZARD) {
				$actionInstance->redirect(($url) ? $this->get($url) : sfConfig::get("app_parser_denied"));	
			} else {
				return false;
			}
			
		}
			
		
		
	}
	
	private function setView() {
		
		$element = $this->getnode("view");
		$this->view = $this->get($element,"type");
		
		$this->process["parses"][$this->iteration]["view"] = $this->view;
		
	}

	private function isTabbed() {
		
		$element = $this->any("grouping");
		
		if($element) {
			$this->tabbedWizard = true;
		}
		
	}
	
	public function getPath($uri, $security = false) {
		if(!$security) {
			return $this->root."/apps/".$this->application."/modules/".strtok($uri,"/")."/config/".strtok("/").".xml";	
		} else {
			return $this->root."/apps/".$this->application."/modules/".$uri."/config/security.xml";
		}
		
	}
	
	
	private function setPanel($input, $sidebar = true) {
		
		$tmp = explode(",",$input);
		$data = array();
		$key = ($sidebar) ? "sidebar" : "footer";
		
		foreach($tmp as $item) {
			if(!strstr($item,"/")) {
				throw new XmlParserException("Invalid data: ".$item);
			}
		
			$module = trim(strtok($item,"/"));
			$action = trim(strtok("/"));
			
			$data[$key][] = array("module" => $module, "action" => $action); 
			
		}
		
		$this->parseDefaultPanels($data);
		
	}
	
	private function getCurrentView() {
		
		$element = $this->getnode("view");
		return $this->get($element,"type");
		
	}
	
	
	private function reArrangeComponents() {
		
		$groups = $this->process["parses"][0]["groups"];
		
		foreach($this->process["parses"][0]["areas"]["content"]["tabs"] as $k => $tab) {
			
			$components = $tab["components"];
			$order = $tmp = array();
			
			foreach($groups as $g) {
				foreach($g as $i) {
					$order[] = $i[0];
				}
			}
			
			foreach($order as $name) {
				foreach($components as $key => $component) {
					if(preg_match("/^".$name."[0-9]+$/",$key)) {
						$tmp[$key] = $component;
					}
				}
			}
			
			$this->process["parses"][0]["areas"]["content"]["tabs"][$k]["components"] = $tmp;
		}
	}
	
	private function parseDefaultPanels($data = null) {
		
		if($data === null) {
			$data = sfConfig::get("app_parser_panels");	
		}
		
		
		foreach($data as $key => $params) {
			
			if(isset($this->defaultPanels[$key]["components"])) {
				unset($this->defaultPanels[$key]["components"]);
			}
			
			foreach($params as $idx => $values) {
				$this->defaultPanels[$key]["attributes"] = array
				("type" => $key, 
				"container" => "true", 
				"parsable" => "true", 
				"priority" => 0);
				$this->defaultPanels[$key]["components"][$values["action"].preg_replace("/[\. ]+/","",microtime())] = array(
				"name" => $values["action"],
				"module" => $values["module"], 
				"container" => "true", 
				"parsable" => "true", 
				"priority" => 0);	
			}	
		}

		return true;
	}
	
	
	public static function readDocumentByPath($path,$xpath = true) {
		
		if(!file_exists($path) || !is_readable($path)) {
			throw new XmlValidatorException("The file ".$file." doesn't exist or isn't readable!");
		} 
		
		return self::buildDocument($path,$xpath);
		
	}
		
	public static function readDocumentByUri($uri,$xpath = true,$app = "frontend") {
		
		try {
		
			if(!trim($uri) || !is_string($uri)) {
				throw new XmlValidatorException("The uri argument is missing or invalid!");
			} else if(!strstr($uri,"/")) {
				throw new XmlValidatorException("The uri argument is invalid!");
			} 
			
			if(!class_exists("ProjectConfiguration")) {
				require_once(dirname(__FILE__).'/../../../../config/ProjectConfiguration.class.php');	
			}
			
			$module = strtok($uri,"/");
			$action = strtok("/");
			
			$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
			
			$file = $configuration->getRootDir()."/apps/".$app."/modules/".$module."/config/".$action.".xml";
			$alt_file = $configuration->getRootDir()."/plugins/appFlowerPlugin/modules/".$module."/config/".$action.".xml";
			
			if(!file_exists($file)) {
				if(!file_exists($alt_file)) {
					throw new XmlValidatorException("The input file: ".$file." or ".$alt_file." doesn't exist!");	
				} else {
					$file = $alt_file;
				}	
			}
			
			$xpath = self::buildDocument($file,$xpath);
							
		}	
		catch(Exception $e) {
			throw($e);
		}
		
		
		return $xpath;
		
	} 
	
	public static function buildDocument($path,$xpath = true) {
		
		$namespace = "http://www.appflower.com/schema/";
		$namespace_prefix = "i";
	
		$document = new DOMDocument();
		$document->load($path);
		
		if($xpath) {
			$xp = new DOMXPath($document);
	
			if(!$xp->registerNamespace($namespace_prefix,$namespace)) {
				throw XmlParserException("Unable to register immune namespace!");
			}	
		
			return $xp;
		} else {
			return $document;
		}
		
		
	}
	
	
	
	public function readXmlDocument($path = null,$security = false) {
		
		try {
			if($path !== null && !file_exists($path)) {
				throw new XmlParserException("The input xml document couldn't be read! Path: ".$path);
			} 
		}
		catch(Exception $e) {
			throw $e;
		}	
		
		$page = false;
		
		if($this->page && $path === null) {
			$path = $this->root."/apps/".$this->application."/config/pages/".sfContext::getInstance()->getActionName().".xml";
			if(!file_exists($path)) {
				$path = $this->root."/plugins/appFlowerPlugin/config/pages/".sfContext::getInstance()->getActionName().".xml";
			}
			$page = true;
		}
		
		if($path === null) {
            $path = $this->root."/apps/".$this->application."/modules/".sfContext::getInstance()->getModuleName()."/config/".sfContext::getInstance()->getActionName().".xml";
			if(!file_exists($path)) {
				$path = $this->root."/plugins/appFlowerPlugin/modules/".sfContext::getInstance()->getModuleName()."/config/".sfContext::getInstance()->getActionName().".xml";
			}
		}
        
		$hash = sha1_file($path);
		$obj = afValidatorCachePeer::inCache($path);
		
		if(!$obj || $obj->getSignature() != $hash) {
			$doc = new XmlValidator($path,$security,false,false,($page) ? $this->context->getModuleName()."/".$this->context->getActionName() : null);
			$doc->validateXmlDocument();	
			$this->document = $doc->getXmlDocument();
			$this->validator = $doc;
		} else {
			$this->document = new DOMDocument();
			$this->document->load($path);
		}
		
		parent::setNamespace($security);
		parent::setXmlDocument($this->document);
		parent::setXpath();
		
		XmlBaseElementParser::clearRetVal();
		
	}
	
	
	private function buildTree($tree,$cycle = false) { 
		
		if(!in_array($cycle,$this->tree_tmp)) {
			if($cycle === 1) {
				$this->tree_item[$tree["level"]][] = $this->tree_root->addChild(array('text'=>$tree["text"], 'href' => (isset($tree["href"])) ? $tree["href"] : null)); 
			} else {
				$this->tree_item[$tree["level"]][] = $this->tree_item[$tree["level"]-1][sizeof($this->tree_item[$tree["level"]-1])-1]->addChild(array('text'=>$tree["text"], 'href' => (isset($tree["href"])) ? $tree["href"] : null));			
			}	
		}
		
		foreach($tree as $key => $item) {
			if(is_array($item)) {
				$this->buildTree($item,++$cycle);
			}	
		}
		
	}
	
	
	private function clearAttributes() {
		
		
		$attrs = array
		(
		"session",
		"parsable",
		"priority",
		"assignid",
		"container"
		);
		
		
		foreach($this->process["parses"] as $pk => $parse) {
			if(isset($parse["fields"])) {
				foreach($parse["fields"] as $name => $set) {
					foreach($set as $fname => $field) {
						foreach($attrs as $attribute) {
							if(isset($field["attributes"][$attribute])) {
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"][$attribute]);						
							}	
						}
						
						if(isset($field["attributes"])) {
	
							if(isset($field["attributes"]["help"]) && $field["attributes"]["help"] == "") {
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["help"]);							
							}
							
							if(!isset($field["attributes"]["type"]) || ($field["attributes"]["type"] != "combo" && 	$field["attributes"]["type"] != "multicombo" && $field["attributes"]["type"] != "doublemulticombo" && $field["attributes"]["type"] != "itemSelectorAutoSuggest")) {
								if(isset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["options"])) {
									unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["options"]);	
								}
								
								if(isset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["clear"])) {
									unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["clear"]);	
								}
								
								if(isset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["selected"])) {
									unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["selected"]);
								}
							
							}
							
							if(isset($field["attributes"]["type"]) && $field["attributes"]["type"] != "doubletree") {
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["fromLegend"]);
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["toLegend"]);
							}
			
							if(isset($field["attributes"]["type"]) && $field["attributes"]["type"] != "textarea") {
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["rich"]);
							}
							
							if(isset($field["attributes"]["button"])) {
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["button"]["parsable"]);
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["button"]["priority"]);
								
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["window"]["parsable"]);
								unset($this->process["parses"][$pk]["fields"][$name][$fname]["attributes"]["window"]["priority"]);
							}
						}
					}	
				}	
			}
		}
	}
	
	
	private function getPortalSizes($data,$js = false) {		
		$value = $this->portalSizes[$data["layout"]];
	
			return array("[".implode(",",$value)."]",count($value));
		
	} 
	
	
	public static function getComponentKey($name) {
		return $name.preg_replace("/[\. ]+/","",microtime());
	}
	
	
	private function getWidgets() {
		
			$page = is_array($this->page) ? $this->page : $this->process["parses"][0]; 
		
			if(isset($page["categories"])) {
				$widget_ids = array_keys($page["categories"]);
			} else {
				$widget_ids = array();
			}
			
			return afWidgetSelectorPeer::getWidgetsByCategory($widget_ids,false);
		
	}
	
	private function readTemplates() {
		
		$this->widgets = $this->getWidgets();
		
		$attribute_holder = sfContext::getInstance()->getActionStack()->getLastEntry()->getActionInstance()->getVarHolder();
		
		
		$this->multi = true;
		
		if($this->type !== self::WIZARD) {
			if(!isset($this->process["parses"][0]["areas"]["sidebar"])) {
				$this->process["parses"][0]["areas"]["sidebar"] = $this->defaultPanels["sidebar"];
			}
			
			if(!isset($this->process["parses"][0]["areas"]["footer"])) {
				$this->process["parses"][0]["areas"]["footer"] = $this->defaultPanels["footer"];
			}	
		}
	
		// Processing afWidgetSelector settings, adding extra widgets.
		// TODO: Some parts must be changed in 1165!!
		
		if($this->portalStateObj) {					
			$layoutType = $this->portalStateObj->getLayoutType();
			$content = $this->portalStateObj->getContent();
			
			$tabs = $this->process["parses"][$this->iteration]["areas"]["content"]["tabs"];	
			$tabbed = ($layoutType == afPortalStatePeer::TYPE_TABBED);
			
			foreach($content as $item) {
				
				if($tabbed) {
					$title = isset($item["tabTitle"]) ? $item["tabTitle"] : $item["portalTitle"];	
				} else {
					$title = null;
				}
				
				foreach($tabs as $tkk => &$tab) {
					$tab_id = ($tabbed) ? $tab["attributes"]["id"] : null;
					$newtab = true;
					if(!$title || $tab["attributes"]["title"] == $title) {
						if(isset($item["portalColumns"]))
						foreach($item["portalColumns"] as $column) {
							foreach($column as $component) {
								
								
								
								$tmp[0] = strtok($component->idxml,"/");
								$tmp[1] = strtok("/");
								$found = false;
								foreach($tab["components"] as $data) {
									if($data["module"] == $tmp[0] && $data["name"] == $tmp[1]) {
										$found = true;
										break;
									}
								}
								if(!$found) {
									$this->process["parses"][$this->iteration]["areas"]["content"]["tabs"][$tkk]["components"][self::getComponentKey($tmp[1])] = 
									array(
									"name" => $tmp[1],
									"module" => $tmp[0],
									"column" => 0,
									"refresh" => 0,
									"container" => "true",
									"parsable" => "true",
									"permissions" => "*",
									"priority" => 0,
									"params" => null
									); 
								}
							}
						}
						$newtab = false;
						break;
					} 
				}
				
				if(!$newtab) {
					continue;
				}
				
				if($newtab) {
					$tk = count($this->process["parses"][$this->iteration]["areas"]["content"]["tabs"]);
					$this->process["parses"][$this->iteration]["areas"]["content"]["tabs"][$tk] = array(
					"attributes" => array(
						"title" => $title,
						"name" => "",
						"container" => "true",
						"parsable" => "true",
						"permissions" => "*",
						"priority" => 0,
						"id" => "tab".(((int) preg_replace("/[^0-9]+/","",$tab_id)+1))
						)
					);
				
					if($item["portalColumns"][0] != array()) {
						foreach($item["portalColumns"] as $ck => $column) {
							foreach($column as $component) {
								$tmp[0] = strtok($component->idxml,"/");
								$tmp[1] = strtok("/");
								$this->process["parses"][$this->iteration]["areas"]["content"]["tabs"][$tk]["components"][self::getComponentKey($tmp[1])] = 
								array(
									"name" => $tmp[1],
									"module" => $tmp[0],
									"column" => $ck,
									"refresh" => 0,
									"container" => "true",
									"parsable" => "true",
									"permissions" => "*",
									"priority" => 0,
									"params" => null
								); 	
							}
						}	
					} else {
						$this->process["parses"][$this->iteration]["areas"]["content"]["tabs"][$tk]["components"] = array();
					}
				}
			}
			
		}		
		foreach($this->process["parses"][$this->iteration]["areas"] as $area_type => $area) {
			
			$z = 0;
			
			if(!isset($area["tabs"])) {
				$area["tabs"][0] = array("attributes" => array(), "components" => $area["components"]);
			}
			
			foreach($area["tabs"] as $k => $tab) {
				
			if($area_type == "content" && $this->portalConfig) {
				$sizedata = $this->getPortalSizes($area["attributes"]);						
				$this->portalConfig->content[$k]["portalLayoutType"] = $sizedata[0];				
				if(isset($tab["attributes"]["title"])) {
					$this->portalConfig->content[$k]["tabTitle"] = $tab["attributes"]["title"];	
				}
				
			}
			
			if(count($tab["components"])>0){
				foreach($tab["components"] as $name => $component) {				
					$arg = 0;
					
					if(substr($name,0,4) != "tree") {						
						if(isset($component["post"])) {
							$this->multisubmit = $component["post"];
						}
						
						// Checking widget permissions
						
						if($this->checkWidgetCredentials($component["module"],$component["name"]) === false) {
							continue;
						}
						
						//adds all the widgets by default to first column
						if($area_type == "content" && !$this->portalStateObj) {	
							
							if($this->type !== self::WIZARD) {
								$idx = $component["column"];							
								if($idx >= $sizedata[1]) {
									throw new XmlParserException("Invalid column reference!");
								}
							} else {
								$idx = 0;
							}
						
							$this->portalConfig->content[$k]["portalColumns"][$idx][$z] = new stdClass();
							$this->portalConfig->content[$k]["portalColumns"][$idx][$z]->idxml = $component["module"]."/".$component["name"];	
						}
						
						$z++;
						
						if(!class_exists($class = $component["module"]."Actions",true)) {
							$file = $this->root."/apps/".$this->application."/modules/".$component["module"]."/actions/actions.class.php";
							if(file_exists($file))require_once($file);
							$file = $this->root."/plugins/appFlowerPlugin/modules/".$component["module"]."/actions/actions.class.php";
							if(file_exists($file))require_once($file);	
						}
						
						$class = new $class(sfContext::getInstance(),null,null);
						$class->isPageComponent = true;
						call_user_func(array($class,"execute".ucfirst($component["name"])),true);
						
						foreach($class->getVarHolder()->getAll() as $propname => $propvalue) {
								$attribute_holder->add(array($propname => $propvalue));	
						}
						
						unset($class);
							
						$file = $this->root."/apps/".$this->application."/modules/".$component["module"]."/config/".$component["name"].".xml";
						$alt_file = $this->root."/plugins/appFlowerPlugin/modules/".$component["module"]."/config/".$component["name"].".xml";
						
						if(!file_exists($file)) {
							
							if(!file_exists($alt_file)) {
								throw new XmlParserException("The config file ".$file." or ".$alt_file." doesn't exist!");	
							} else {
								$file = $alt_file;
							}
							
						}
						 //else
						  {
							if(isset($component["params"])) {
								foreach($component["params"] as $pname => $pvalue) {
									$attribute_holder->add(array($pname => $pvalue));
								}
							}
							
							$this->readXmlDocument($file);
							$this->iteration++;
							$this->process["parses"][$this->iteration]["component"] = $name;
							$this->process["parses"][$this->iteration]["component_name"] = $component["name"];
							$this->process["parses"][$this->iteration]["module"] = $component["module"];
							$this->process["parses"][$this->iteration]["area"] = $area_type;
							$this->process["parses"][$this->iteration]["refresh"] = isset($component['refresh']) ? $component['refresh'] : false;
							
							if(($this->getCurrentView() == "edit" || $this->getCurrentView() == "show") && 
							$this->fetch("//i:grouping")->length == 0) {
								$arg = 1;
							}
							
							$this->runParser($arg);
							
						}
					} else {
						
						
						if($area_type == "content" && !$this->portalStateObj) {
							
							if($this->type !== self::WIZARD) {
								$idx = $component["column"];
							
								if($idx >= $sizedata[1]) {
									throw new XmlParserException("Invalid column reference!");
								}
							} else {
								$idx = 0;
							}
						
							$idxml = "tree".$z;
							$this->portalConfig->content[$k]["portalColumns"][$idx][$z] = new stdClass();
							$this->portalConfig->content[$k]["portalColumns"][$idx][$z]->idxml = $idxml;	
						}
						
						$z++;
						
						$this->tree = new ImmExtjsTree(array('title'=>$component["title"]));
						$this->tree_root = $this->tree->startRoot(array("title" => "Startroot"));
						$this->buildTree($component["rootnode"],1);
					
						foreach(array_reverse($this->tree_item) as $level) {
							foreach($level as $f) {
								$f->end();
								
							}
						}
						
						$this->tree->endRoot($this->tree_root);
						$this->tree->end();		

						$this->extobjects[$idxml] = $this->tree;
						
					} 
					
				}
			}
			
		}
			
			
		}
		
		if($this->type == self::PAGE) {
			
			if(!$this->portalStateObj) {
				$this->portalStateObj = afPortalStatePeer::createOrUpdateState($this->portalConfig);
			}
						
			$layoutType = $this->portalStateObj->getLayoutType();
			$portalLayoutType = $this->portalStateObj->getPortalLayoutType();
			
			$this->portalContent = $this->portalStateObj->getContent();
			
			$this->portalColumns = $this->portalStateObj->getColumns();
			ksort($this->portalColumns);
			
			$this->portalColumnsSize = $this->portalStateObj->getColumnsSize();
			
			$portalTools  = new ImmExtjsTools();
			if($layoutType == afPortalStatePeer::TYPE_NORMAL) {
				$portalWidgets=$this->widgets;
				$portalTools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,portal','source'=>"
						var layouts=[[100],[50,50],[25,75],[75,25],[33,33,33],[50,25,25],[25,50,25],[25,25,25,25],[40,20,20,20]]; 
						var menu=new Ext.menu.Menu({items:[
							{text: 'Layout Selector', handler:function(){
									portal.showLayoutSelector(target,'Layout Selector',layouts);
								},icon: '/images/famfamfam/application_tile_horizontal.png' 
							},
							{text: 'Widget Selector', handler:function(){
									portal.showWidgetSelector(target,'Widget Selector');									
								},icon: '/images/famfamfam/application_side_boxes.png'
							},
							{text: 'Reset to Default', handler:function(){
									new Portals().reset(target,portal);											
								},icon: '/images/famfamfam/application_lightning.png'
							}
						   ]});						 
						menu.showAt(e.getXY());
						menu.showAt([menu.getEl().getBox().x-menu.getEl().getBox().width,menu.getEl().getBox().y]);")));
			} else {
				
				// TODO: Thise must be changed in 1165!!
				
		
				
				$newTabPortalWidgets=$this->filterWidgets(null,$this->portalContent,$this->widgets);
				$portalWidgets = null;
				
				$portalTools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"
						var layouts=[[100],[50,50],[25,75],[75,25],[33,33,33],[50,25,25],[25,50,25],[25,25,25,25],[40,20,20,20]];
						var tabpanel=panel.items.items[0];
						var menu=new Ext.menu.Menu({items:[
							{text: 'Layout Selector', handler:function(){
									tabpanel.getActiveTab().items.items[0].showLayoutSelector(target,'Layout Selector for '+tabpanel.getActiveTab().title,layouts);
								},icon: '/images/famfamfam/application_tile_horizontal.png' 
							},
							{text: 'Widget Selector', handler:function(){
									tabpanel.getActiveTab().items.items[0].showWidgetSelector(target,'Widget Selector for '+tabpanel.getActiveTab().title);									
								},icon: '/images/famfamfam/application_side_boxes.png'
							},
							{text: 'Add New Tab', handler:function(){
									var newTabPortalWidgets=".json_encode($newTabPortalWidgets).";
									new Portals().createNewTab(target,tabpanel,newTabPortalWidgets);											
								},icon: '/images/famfamfam/application_add.png'
							},
							{text: 'Remove Tab', handler:function(){
									new Portals().removeTab(target,tabpanel);											
								},icon: '/images/famfamfam/application_delete.png'
							},
							{text: 'Change Title', handler:function(){
									new Portals().changeTitle(target,tabpanel);											
								},icon: '/images/famfamfam/application_edit.png'
							},
							{text: 'Reset to Default', handler:function(){
									new Portals().reset(target,tabpanel.getActiveTab().items.items[0]);
								},icon: '/images/famfamfam/application_lightning.png'
							}
						   ]});						  
						menu.showAt(e.getXY());
						menu.showAt([menu.getEl().getBox().x-menu.getEl().getBox().width,menu.getEl().getBox().y]);")));
			}
				
			$this->layout = new ImmExtjsPortalLayout(array('id'=>'center_panel','tools'=>$portalTools,'idxml'=>$this->portalIdXml,'layoutType'=>$layoutType, 'portalLayoutType' => $portalLayoutType, 'portalWidgets' => $portalWidgets));
			self::$masterLayout = $this->layout;
			
		}
		
		
		$this->postProcess();
		
	}
	
	
	private function storeProcessed($input) {
		
		
		try {
			
			if(!is_array($input) || empty($input)) {
				throw new XmlParserException("Invalid input parameter, a multi-dimensonal array expected!");
			}
			
			
			foreach($input as $item) {
				if(!is_array($item) || !isset($item["key"]) || !isset($item["value"])) {
					throw new XmlParserException("Invalid input parameter, array structure is unexpected! Please refer the wiki!");
				} 
				
				if(!strstr($item["key"],"[")) {
					$this->process["parses"][$this->iteration][$item["key"]] = $item["value"];	
				} else {
					if(is_array($item["value"])) {
						$value = "array('".trim(implode("','",$item["value"]),",")."')";						
						eval("return \$this->process['parses'][".$this->iteration."]".$item["key"]." = ".$value.";");
					}  else {
						if(eval("return !isset(\$this->process['parses'][".$this->iteration."]".$item["key"].");")) {
						eval("\$tmp =& \$this->process['parses'][".$this->iteration."]".$item["key"].";");
							$tmp = $item["value"];	
						}
						
					}
				}
			}
		}
		catch(Exception $e) {
			throw $e;
		}
		
	}
	
	
	private function parseDefaults() {
		if(!isset($this->xmlDefaults)) {
			$this->xmlDefaults = new XmlDefaults($this->getSchema());
		}
		$this->xmlDefaults->setTreeDefaults($this->document);
	}

	private function buildParserData() {
		
		$elements = $this->fetch("//*[@parsable]|//*[@assignid]");
		$profile = $this->user->getProfile();
		$sortables = array();
		
		try {
			foreach($elements as $e) { 
				if($profile) {
					if($this->name($e) == "comment" && $this->getWidgetId() != "appFlower/editHelpSettings" && ($this->widgetHelpSettings->getHelpType() == 0 || 
					$this->widgetHelpSettings->getHelpType() == 2)) {
						continue;		
					}	
				}
				if($this->has($e,"assignid")) {
					if(!$this->has($e,"name")) {
						throw new XmlParserException("The element: ".$this->name($e)." should have a name attribute!");
					}
					$this->set("id",$this->get($e,"name"),$e);	
				}	
				if($this->has($e,"parsable")) {
					
					$index = $this->get($e,"priority");
					$name = $index."-".$this->name($e);
					
					if($this->has($e,"container")) {
						$parent = $this->container($e);
					} else {
						$parent = $this->container($e);
					}
					
					$this->elements[$name][] = array("node" => $e,"parent" => $parent);
					if((int) substr($name,0,strpos($name,"-")) != 0) {
						$sortables[preg_replace("/[^0-9]+/","",$name)] = $name;
					}	
				}
			}	
		}
		catch(Exception $e) {
			throw $e;
		}
		
		ksort($sortables);
		
		foreach($sortables as $key) {
			$item = $this->elements[$key];
			unset($this->elements[$key]);
			$this->elements = array($key => $item) + $this->elements;
			
		}
		
		// Validating document, looking for duplicate IDs..
		
		/*if($this->validator) {
			$this->validator->setXmlDocument($this->document);
			// TODO: turned off validation for now.
			//$this->validator->validateIDs($this->xpath);	
		}
		*/
		
	}
	
	
	private function preProcess() {
		// Remove trash data
		
		$this->document->normalize();
		
		
		// Set unique names for elements.
		
		$this->setUnique();
	
		// Parse the schema, store and assign default / fixed values of all attributes 
		
		$this->parseDefaults();
		
		// Parse ifs..
		
		
		$this->testConditions();
		
		
		// Find parsable elements, assign unique ids, build parser data
				
		$this->buildParserData();
		
		return true;
		
	}
	
	
	private function setUnique() {
		
		$elements = $this->fetch("//i:field[@name]|//i:button[@name]|//i:linkbutton[@name]|//i:link[@name]");
		
		foreach($elements as $elem) {
			
			$name = $this->get($elem,"name");
			$value = $this->view."[".$this->iteration."][".$name."]";
			$this->set("name",$value,$elem);
			$ref = $this->any("grouping/i:set/i:ref[@to='".$name."']");
			
			if($ref) {
				$this->set("to",$value,$ref);	
			}
			
		}
		
		parent::setXmlDocument($this->document);
		
	}
	
	private function isInGroup($item) {
		
		if($this->tabbedWizard) {
			return false;
		}
		
		if(isset($this->process["parses"][$this->iteration]["groups"])) {
			foreach($this->process["parses"][$this->iteration]["groups"] as $group) {
				foreach($group["members"] as $member) {
					if($member == $item) {
						return true;
					}
				}
			}	
		}
		
		return false;
		
	}
	
	
	private function shiftItem($item, $default = true) {
		
		foreach($this->process["parses"] as $it) {
			if(!$default) {
				foreach($it["fields"] as $element => $data) {
					if($element == $item) {
						return $data;
					}
				}	
			} else {
				foreach($it["fields"] as $setname => $setvalue) {
					foreach($setvalue as $element => $data) {
						if($element == $item) {
							return $data;
						}
					}
				}	
			}
				
		}
		
		throw new XmlParserException("Invalid element reference in radiogroup!");
		
	}
	
	private function mapKeys($keys,$values) {
		
		$tmp_value = array();
		foreach($keys as $k) {
			$tmp_value[$k] = $values[$k];
		}
		
		return $tmp_value;
		
	}
	
	
	public function checkCredentials($credentials, $node = null) {
		
		$barriers = array("i:fields","i:actions","i:rowactions","i:moreactions");
		
		if($node) {
			$attributes = $this->attributes($node);
			
			if(!isset($attributes["permissions"])) {
				return true;
			}
			
			$credentials = $attributes["permissions"];
		}
		
		if($node && in_array($this->name($node->nodeName),$barriers)) {
			$node = null;
		}
		
		$c = explode(",",$credentials);
		
		if($c[0] == "*") {
			if($node && !in_array($this->name($node->parentNode->nodeName),$barriers)) {
				return $this->checkCredentials(null,$node->parentNode);	
			}
			return true;	
			
		}
		
		foreach($c as $cr) {
			if(!$this->user->hasCredential($cr)) {
				return false;	
			}
		}	
		
		if($node && !in_array($this->name($node->parentNode->nodeName),$barriers)) {
			return $this->checkCredentials(null,$node->parentNode);
		}
		
		return true;
		
	}
	
	
	private function childToAttribute(&$data,$key) {
				
		if(isset($data["help"])) {
			if(!isset($data["attributes"]["help"])) {
				$data["attributes"]["help"] = $data["help"];	
			}			
		} else {
			$data["attributes"]["help"] = "";
		}
					
		if(isset($data["comment"])) {
			if(!isset($data["attributes"]["comment"])) {
				$data["attributes"]["comment"] = $data["comment"];	
			}	
		} else {
				$data["attributes"]["comment"] = "";
		}
		
		
		// Values..
		
		if(isset($data["attributes"]["content"])) {
			$data["attributes"]["value"] = $data["attributes"]["content"];
		} else if(isset($data["value"])) {
			
			
			
			try {
				
				if(!class_exists($data["value"]["class"])) {
					throw new XmlParserException("Class ".$data["value"]["class"]." doesn't exists!");
				} 
				
				
				if($data["value"]["type"] == 1 && $data["value"]["class"] == $this->process["parses"][$key]["datasource"]["class"]) {
					if($this->process["parses"][$key]["datasource"]["method"]["type"] == "instance") {
						$params = isset($this->process["parses"][$key]["datasource"]["method"]["params"])?$this->process["parses"][$key]["datasource"]["method"]["params"]: array(null);
						$class = call_user_func_array(array($data["value"]["class"],$this->process["parses"][$key]["datasource"]["method"]["name"]),$params);
						if(!$class || !is_object($class)) {
							if($this->view == "show") {
								throw new XmlParserException("Invalid id was specified, non-object value has been returned!");	
							} else {
								$class = "";
							}
						}
					$params = array();
					}
				} 
								
				
				if(!isset($class)) { 
					$class = $data["value"]["class"];
					$params = (isset($data["value"]["method"]["params"])) ? $data["value"]["method"]["params"] : array();
					
					
					if(isset($data["attributes"]["selected"]) && $data["attributes"]["selected"]) {
						$params[] = $data["attributes"]["selected"];
					}
				}
				
				
				$method = (is_array($data["value"]["method"])) ? $data["value"]["method"]["name"] : $data["value"]["method"];
				
				if($class && !method_exists($class,$method)) {
					throw new XmlParserException("The method ".$method." doesn't exist in class ".((is_string($class)) ? $class : get_class($class))."!");
				}
				
				if($class) {
				
					$value = call_user_func_array(array($class,$method),$params);	
					if(isset($data["attributes"]["content"])) {
						$value = $data["attributes"]["content"];
					}
				} else {
					$value = "";
				}
				
				if(!is_numeric($value) && !is_array($value) && !is_string($value) && !is_bool($value) && get_class($value) != 
				"Collection" && $value !== null && !($value instanceof sfExtjs2Var)) {
					throw new XmlParserException("Invalid value has been returned by ".$data["value"]["class"]."->".
					$data["value"]["method"].", number, array, object, booelan or string expected, but ".gettype($value)." given!");
				}	
				
				if(isset($data["attributes"]["type"]) && $data["attributes"]["type"] == "checkbox") {
					if(!isset($data["attributes"]["checked"])) {
						if($value) {
							$data["attributes"]["checked"] = true;
						} else {
							$data["attributes"]["checked"] = false;
						}	
					}
					
				}
				
			}
			catch(Exception $e) {
				throw $e;
			}
			
			if(@get_class($value) != "Collection") {
				if(isset($data["attributes"]["type"])) {
					
					if($data["attributes"]["type"] == "itemSelectorAutoSuggest" || $data["attributes"]["type"] == "doublemulticombo"  || $data["attributes"]["type"] == "doubletree") {
						
						if(isset($value[1])) {
							$data["attributes"]["selected"] = $value[1];
							foreach($value[1] as $k => $v) {
								if($data["attributes"]["type"] == "doubletree") {
									foreach($v["children"] as $kk => $c) {
										if(($idx = array_search($c,$value[0][$k]["children"])) !== false) {
											unset($value[0][$k]["children"][$idx]);
											$value[0][$k]["children"] = array_merge($value[0][$k]["children"],array());
										}	
									}
									if(empty($value[0][$k]["children"])) {
										unset($value[0][$k]);
										$value[0] = array_merge($value[0],array());
									}
								} else {
									if(isset($value[0][$k])) {
										unset($value[0][$k]);
									}	
								}
								
							}
						}
						$data["attributes"]["options"] = $value[0];	
					} else {
						$data["attributes"][(is_array($value)) ? "options" : "value"] = $value;	
					}
					
				} 
					
			} else {
				$data["attributes"]["options"] = $value->getArray();
				$data["attributes"]["selected"] = $value->getSelected();
			}
			  
		}
		
		if($this->type === self::WIZARD) {
			
			$session = $this->context->getUser()->getAttributeHolder()->getAll("parser/wizard");
			
			if(isset($session[$this->current]["fields"])) {
				$step = $session[$this->current]["fields"];
			} else if(isset($session[$this->current]) && isset($session[$this->current][sizeof($session[$this->current])-1]["fields"])) {
				$step = $session[$this->current][sizeof($session[$this->current])-1]["fields"];
			} else {
				$step = null;
				// An exception for PDF reports.. tmp solution, must be properly addressed after 4.0
				if(isset($session[2]["fields"]["report_type_value"]) && $this->current == 4 && isset($session[3]["fields"])) {
					$step = $session[3]["fields"];
				}
			}
			
			if($step && isset($data["attributes"]["name"])) {
		
				$name = substr($data["attributes"]["name"],strpos($data["attributes"]["name"],"2")+3,-1);
				if($data["attributes"]["type"] == "combo") {
					$name .= "_value";
				} 
				
				if(isset($step[$name])) {
					if(isset($data["attributes"]["type"]) && $data["attributes"]["type"] != "password") {
						if(($data["attributes"]["type"] == "checkbox" || $data["attributes"]["type"] == "radio")) {
							$data["attributes"]["checked"] = true;	
						} else if($data["attributes"]["type"] == "combo") {
							$data["attributes"]["selected"] = $step[$name];
						} else if($data["attributes"]["type"] == "itemSelectorAutoSuggest" || $data["attributes"]["type"] == "doublemulticombo" || $data["attributes"]["type"] == "multicombo") {
							$tmp = explode(",",$step[$name]);
							$data["attributes"]["selected"] = $this->mapKeys($tmp,$value[0]);
						} else {
							$data["attributes"]["value"] = $step[$name];
						}
					}
				}
			}			
			unset($session);
		}
		
		// Handlers
		
	
					
		if(isset($data["handlers"])) {
			
	
			
			$data["attributes"]["handlers"] = array();
						
			foreach($data["handlers"] as $handler => $value) {
				
				if($handler == "beforestaterestore" || $handler == "beforestatesave" ||
				$handler == "staterestore" || $handler == "statesave") {
					$params = "component,state";
				} else if($handler == "change") {
					$params = "field,newValue,oldValue";
				} else if($handler == "blur" || $handler == "focus" || $handler == "valid") {
					$params = "field";
				} else if($handler == "invalid") {
					$params = "field,msg";
				} else if($handler == "move") {
					$params = "component,x,y";
				} else if($handler == "resize") {
					$params = "component,adjWidth,adjHeight,rawWidth,rawHeight";
				} else if($handler == "specialkey") {
					$params = "field,e";
				} else if($handler == "check") {
					$params = "field,checked";
				} else if($handler == "autosize") {
					$params = "field,width";
				} else if($handler == "beforequery") {
					$params = "queryEvent";
				} else if($handler == "beforeselect" || $handler == "select") {
					$params = "combo,record,index";
				} else if($handler == "collapse" || $handler == "expand") {
					$params = "combo";
				} else if($handler == "keydown" || $handler == "keypress" || $handler == "keyup") {
					$params = "field,e";
				} else if($handler == "beforepush" || $handler == "beforesync" || $handler == "push" || $handler == "sync") {
					$params = "HtmlEditor,html";
				} else if($handler == "editmodechange") {
					$params = "HtmlEditor,sourceEdit";
				} else if($handler == "activate" || $handler == "initialize") {
					$params = "HtmlEditor";
				} else {
					$params = "component";
				}
				
				// Adding params..
				
				if(isset($value["params"])) {;
					if(!isset($params)) {
						$params = "";
					} else {
						$params .= ",";
					}
					foreach($value["params"] as $p) {
						$params .= "'".$p."',";
					}
					$params = trim($params,",");
				} 
				
				$data["attributes"]["handlers"][$handler] = array("parameters" => $params, "source" => $value["action"]);

			}
											
		}
						
	}

	private function isSSL(){
	
	  if(isset($_SERVER['https']) && $_SERVER['https'] == 1) /* Apache */ {
	     return TRUE;
	  } elseif (isset($_SERVER['https']) && $_SERVER['https'] == 'on') /* IIS */ {
	     return TRUE;
	  } elseif ($_SERVER['SERVER_PORT'] == 443) /* others */ {
	     return TRUE;
	  } else {
	  return FALSE; /* just using http */
	  }
	
	}
	
	public static function updateSession($step = false,$key = "parser/wizard",$data = null,$datastore = null,$process = null) {
		
		$context = sfContext::getInstance();
		$session = $context->getUser()->getAttributeHolder()->getAll($key);
		$add = $context->getRequest()->getParameter("add");
		$actionInstance = $context->getActionStack()->getLastEntry()->getActionInstance();
		$attribute_holder = $actionInstance->getVarHolder()->getAll();
		
		// Put xml data
	  
		if($key == "parser/wizard") {
			
			if(!isset($session["skip"])) {
				$session["skip"] = array(); 
			}
			
			if(isset($attribute_holder["init"]) && $datastore) {
				$session["datastore"] = $process["parses"][0]["datastore"];	
			}
				
			if($context->getRequest()->getMethod() === sfRequest::POST) {
			
				$post = $context->getRequest()->getParameterHolder()->getAll();
				
				$empty = true;
				
				// Is empty post?
				if(isset($post["edit"][2])) {
					foreach($post["edit"][2] as $k => $value) { 
						if($k == "id") {
							continue;
						}
						if(trim($value)) {
							$empty = false;
							break;
						}
					}	
				} else {
					
					$post["edit"][2] = array();
					
					if(isset($_FILES["edit"])) {
						$empty = false;
					}
				}
				
				
				
				if($step === false) {
					$step = $attribute_holder["step"];	
				}
				
		  		// Put post data
				
		  		if($add === "true") {
		  						
		  			// Is a duplicate?
		  			
		  			$duplicate = false;
		  			if(isset($session[$step]))
		  			foreach($session[$step] as $item) {
		  				$cnt = 0;
		  				foreach($post["edit"][2] as $k => $v) {
		  					if(isset($item["fields"][$k]) && $v == $item["fields"][$k]) {
		  						$cnt++;
		  					}
		  					
		  					if($cnt == sizeof($post["edit"][2])) {
		  						$duplicate = true;
		  						break;
		  					}
		  				}
		  			}
		  			
		  			
		  			if(!$empty && !$duplicate) {
		  				$session[$step][] = array();
			  			$sk = max(array_keys($session[$step]));	
			  			
			  			foreach($post["edit"][2] as $k => $value) { 
			 				$session[$step][$sk]["fields"][$k] = $value;	
			 				
			  			}	
		  			}
		  			
		  		} else {
		  			
		  			if(isset($post["edit"][2])) {
			  			foreach($post["edit"][2] as $k => $value) {	
			  				if($k != "associated_widgets") {
			  					$session[$step]["fields"][$k] = $value;	
			  				} else {
			  					$session = PdfReportsPeer::updateWidgets($value);
			  				}		
			  			}	
		  			}
		  			
		  			if(isset($_FILES["edit"])) {
		  				
		  				$session[$step]["file"] = true;
		  				
							  			
		  				
			  			foreach($_FILES["edit"] as $k => $value) {		  		
			  				
			  				if($k == "error") {
			  					if($value[2][key($value[2])] != 0) {
			  						return $value[2][key($value[2])];	
			  					}
			  					
			  				}
			  				
			  				if($k == "type" || $k == "error") {
			  					continue;
			  				}
			  				
			  				if($k == "tmp_name") {
			  					$tx = substr($value[2][key($value[2])],strrpos($value[2][key($value[2])],"/")+1);
			  					copy($value[2][key($value[2])],"/usr/www/tmp/".$tx);
			  					$txval = "/usr/www/tmp/".$tx;
			  				} else {
			  					$txval = $value[2][key($value[2])];
			  				}
			  				
			  				$k = "file_".$k;
			  				$session[$step]["fields"][$k] = $txval;
			  			}
			  			
		  			}
		  			
		  		}
		  		
			}	
		} else if($key == "parser/validation") {
			
			$session = array_merge($session,$data);
			
		}
			
		$context->getUser()->getAttributeHolder()->removeNamespace($key);
	  	$context->getUser()->getAttributeHolder()->add($session, $key);
	  	
	  	return true;
	  	
	}
	
	private function escapeJString($str) {
		
		if(strstr($str,"\r\n")) {
			$tmp = explode("\r\n",$str);	
		} else if(strstr($str,"\n")) {
			$tmp = explode("\n",$str);
		} else {
			return $str;
		}
		
		$ret = "";
		
		foreach($tmp as $s) {
			$ret .= "'".$s."\\n'+\n";
		}
		
		return trim($ret,"\n+'");
		
	}
	
	private function getFieldsFor($group) {
		
		$ret = array();
		
		foreach($this->process["parses"] as $it) {
			if(!isset($it["groups"])) {
				continue;
			}
			foreach($it["groups"] as $name => $item) {
				if($name == $group) {
					foreach($item["members"] as $member) {
						$ret[$member] = $this->shiftItem($member,false);	
					}
				}
			}
		}
			
		return $ret;
		
	}
	
	
	/**
	 * modified by radu
	 *
	 * @author tamas
	 * @author radu
	 */
	private function addPortal($ext,$name) {
		
		$name = preg_replace("/[0-9]+/","",$name);
		
		if($this->portalConfig) {
			$content = $this->portalConfig->content;
		} else {
			$content = $this->portalContent;
		}
		
		foreach($content as $k => $data) {
			if(isset($data["portalColumns"]))
			foreach($data["portalColumns"] as $col) {
				foreach ($col as $widget){
					if($widget->idxml == $name) {
						$this->extobjects[$name] = $ext;
						break;
					}	
				}
			}	
		}
		
	}
	
	
	private function isFloatedSet($set,$start = 0) {
		
		$cnt = $i = 0;
		$tmp = array();
		
		if($set["attributes"]["float"] == "false") {
			return 0;
		}
		
		if($start) {
			foreach($set as $k => $d) {
				if($i <= $start) {
					$i++;
					continue;
				} else {
					$tmp[$k] = $d;
				}
			
			}
		}
		
		if(!empty($tmp)) {
			$set = $tmp;
		}
		
		foreach($set as $k => $d) {
			if($k == "attributes") {
				continue;
			}
		
			$cnt++;
			
			if(isset($d["attributes"]["break"]) && $d["attributes"]["break"] == "true") {
				break;
			}
			
		}
		
		return $cnt;
		
	}
	
	
	private function isLastGroupMember($component) {
		
		foreach($this->page["groups"] as $group) {
			$i = 0;
			foreach($group as $item) {
				$res = array_search($component,$item);
				if($res !== false && $i == sizeof($group)-1) {
					return true;
				}
				$i++;	
			}
		}
		
		return false;
		
	}
	
	
	private function getTabData($component) {
		
		foreach($this->page["groups"] as $name => $group) {
			foreach($group as $item) {
				if(in_array($component,$item)) {
					return $item;
				}	
			}
		}
		
		return "";
		
	}
	
	
	private function showAttributes(&$attributes) {
		
		$filter = array
		(
		'name',
		'label',
		'value',
		'help',
		'comment',
		);
		
		$selected = isset($attributes["selected"]) ? $attributes["selected"] : null;
		
		if($attributes["type"] == "date") {
			$attributes["value"] = substr($attributes["value"],0,strrpos($attributes["value"]," "));
		}
		
		if(isset($attributes["options"])) {
			
			if($selected && (!is_array($selected) || array_shift($selected))) {
				if($attributes["type"] == "combo") {
					$attributes["value"] = $attributes["options"][$attributes["selected"]];	
				} else {
					$attributes["value"] = "";
					foreach($attributes["selected"] as $sel) {
						if($attributes["type"] != "doublemulticombo") {
							$attributes["value"] .= $attributes["options"][$sel]."<br />";
						} else {
							$attributes["value"] .= $sel."<br />";
						}
					}
				}
				
			} else {
				$attributes["value"] = implode("<br />",$attributes["options"]);
			}
		
		}
		
		foreach($attributes as $key => $value) {
			if(!in_array($key,$filter)) {
				unset($attributes[$key]);
			}
		}
		
		$attributes["submitValue"] = false;
		
		return true;
		
	}

	
	private function postProcess($build = false) {

		Console::profile('postProcess');
		
		/*
		 * Create widgets in advanced for the text link script (widget launcher)
		 */
	
		sfLoader::loadHelpers(array("Helper","Url"));
		
		// Update session if needed..
		
		$module = $this->context->getModuleName();
		$action = $this->context->getActionName();
		
		$this->context->getUser()->getAttributeHolder()->removeNamespace('parser/grid');
		
		if($this->type === self::WIZARD) {
			
			if(isset($this->attribute_holder["init"])) {
				$this->context->getUser()->getAttributeHolder()->removeNamespace('parser/wizard');	
			}
			
			self::updateSession(false,"parser/wizard",null,$this->datastore,$this->process);
		}
		
		$host = ($this->isSSL()? 'https' : 'http')."://".$this->context->getRequest()->getHost();
		$pageHelp = ($this->type !== self::WIZARD && isset($this->process["parses"][0]["extra"]) && $this->widgetHelpSettings->getWidgetHelpIsEnabled());
			
		
		if($this->multi) {
			$this->page = $this->process["parses"][0];
			unset($this->process["parses"][0]);
			
			if($pageHelp) {
				$this->layout->addHelp($this->page["extra"]);
			}
		}		
		
		
		if($this->multi) {
			$this->layout->setTitle($this->page["title"].(class_exists('ImmExtjsWidgetConfig')?ImmExtjsWidgetConfig::getPostfixTitle():''));
		}
		
		if($this->tree) {
			$this->layout->addItem('west',$this->tree);
			
		}
		
		foreach($this->process["parses"] as $it => $parse) {
			/*
			 * Moved the tools in this loop to have different tools on different portlets depending upon their types.
			 */
			
			$tools=new ImmExtjsTools();
			if(isset($parse['params']) && isset($parse['params']['settings'])){
				$tools->addItem(array('id'=>'gear','qtip'=>'Setting','handler'=>array('parameters'=>'e,target,panel','source'=>"ajax_widget_popup('".$parse['params']['settings']."','Settings',panel,'width:800,overflow:\'hidden\'')")));
			}		
			//$tools->addItem(array('id'=>'start-reload','handler'=>array('parameter'=>'e,target,panel','source'=>'this.id="stop-reload"')));	
			$tools->addItem(array('id'=>'close','qtip'=>'Close','handler'=>array('parameters'=>'e,target,panel','source'=>"var portal=panel.ownerCt.ownerCt;panel.ownerCt.remove(panel, true);portal.onWidgetDrop();")));
		
			/***********************************************************/
			$unique_id = md5(microtime());
			$widgetHelp = ($this->type !== self::WIZARD && isset($parse["description"]) && $this->widgetHelpSettings->getWidgetHelpIsEnabled());
			
			if($widgetHelp) {
				if($this->type === self::PANEL) {
					$this->layout->addHelp($parse["description"]);	
				}
			}	
			
			
			if($this->multi) {
				$current_area = $this->page["areas"][$parse["area"]];
			} else {
				$current_area = (isset($parse["area"])) ? $parse["area"] : null;
			}
			
			$view = $parse["view"];
			
			$parsedgroups = array();
			
			if(!isset($parse["multipart"])) {
				$parse["multipart"] = false;
			}
			
			if(isset($this->page["confirm"])) {
				$this->layout->attributes['listeners']['beforerender']=$this->layout->immExtjs->asMethod(array('parameters'=>'el','source'=>"Ext.Msg.confirm('".$this->page["confirm"]["title"]."','".$this->page["confirm"]["text"]."', function(btn){if (btn=='yes'){ return true; }else{ window.location.href='".$this->page["confirm"]["url"]."';return false;} });"));
			}
			//echo "<pre>";print_r($parse);exit;
			$formoptions = ($this->multi) ? array("title" => $parse["title"], "fileUpload" => $parse["multipart"], "portal" => true, "tools" => $tools) : array("fileUpload" => $parse["multipart"]);

			if($it == 1 && $this->multi && $this->type === self::WIZARD) {
				$panel = $this->layout->startColumn(array('columnWidth'=>(isset($current_area["attributes"]["width"])) ? $current_area["attributes"]["width"] : '0.98'));	
			}
			
			if($this->type == self::PAGE) {
				$action_name = preg_replace("/[0-9]+/","",$parse["component"]);
			}
			
			// Determine group - Wizards
			
			if(!$this->openGroup && $this->tabbedWizard) {
				$this->openGroup = true;
				$wizard_group = $this->layout->startGroup();
			}
			
			if($view == "edit" || $view == "show") {
								
				$formoptions["action"] = $host.url_for($parse["form"]);
				$formoptions["name"] = "form".$it;
				$formoptions["classic"] = ($parse["classic"] !== "false");
				$formoptions["labelWidth"] = isset($parse['labelWidth'])?$parse['labelWidth']:'75';
				if($this->type == self::PANEL) {
					$widget = $this->context->getActionName();	
				} else if($this->type == self::PAGE) {
					$widget = $action_name;
					$formoptions["idxml"] = $parse["module"]."/".$action_name;
				} 
				 
				
				if($this->type === self::WIZARD) {
					//$formoptions["classic"] = true;
				}
				
				$form = new ImmExtjsForm($formoptions);

				if($widgetHelp) {
					if($this->multi) {
						$form->addHelp($parse["description"]);	
					}
				}	
				
				foreach($parse["fields"] as $setname => $set) {
					
					$this->is_floated = array();
					
					if(!isset($set["attributes"]["tabtitle"])) {
						
						if(isset($tabx)) {
							unset($tabx);
						}
						
					} else if(!isset($tabs)) {
						$set["attributes"]["isSetting"] = $parse['isSetting'];											
						$set["attributes"]["description"] = $parse['description'];
						$set["attributes"]["title"] = $parse['title'];
						$tabs = $form->startTabs($set["attributes"]);
					}
					
					// Fieldset
					
					if($parse["isgrouped"]) {
						
						if(!$this->tabbedWizard) {
							$attributes = array("legend" => $set["attributes"]["title"], "collapsed" => $set["attributes"]["collapsed"]);	
						} else {
							$attributes = array();	
							
						}
						
						$this->is_floated[] = (!$this->tabbedWizard) ? $this->isFloatedSet($set) : false;
						
						if($this->is_floated[0]) {
							$attrs = array("columnWidth" => sprintf("%1.2f",(float) 1 / $this->is_floated[0]), 'labelAlign'=> 'top');
						} else {
							$attrs = array('columnWidth' => 1,'labelAlign'=> 'left');
						}	

						
					} else {
						$attributes = array();
						$fieldset = $form;
						$set = $parse["fields"];
						$attrs = array('columnWidth' => 1,'labelAlign'=> 'left');
					}
					
					
					if(isset($set["attributes"]["tabtitle"])) {
						$tabx = $tabs->startTab(array('title'=>$set["attributes"]["tabtitle"],'height'=>$set["attributes"]["tabHeight"],'iconCls'=>$set["attributes"]["tabIconCls"]));
						$fieldset = $tabx->startFieldset($attributes);
									
					} else {
						$fieldset = $form->startFieldSet($attributes);				
					}

					if($parse["isgrouped"]) {
						$columns = $fieldset->startColumns();	
					}
					
					// Field data
					
					foreach($set as $name => $data) {
						$rcf = new ReConfigureFields($data);
						$data = $rcf->getField();
						if($parse["isgrouped"]) {
							$columnx = $columns->startColumn($attrs);
						}
						
						if($name == "attributes" || !is_array($data)) {
							continue;
						}
						
						
						
						// Convert to attributes field's children's default values here!
						
						$this->childToAttribute($data,$it);
						
						$attributes = $data["attributes"];	
						
						$attributes['labelStyle'] = 'width:'.$parse['labelWidth'].'px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;';
						
						// Put validators into session.
						
						if(isset($data["validators"])) {
							self::updateSession(false,"parser/validation",array($name => $data["validators"]),$this->datastore,$this->process);
						}
						
						$classname = $attributes["type"];
					
						if($setname == $name) {
							$this->process["parses"][$it]["fields"][$name]["attributes"] = $attributes;
						} else {
							$this->process["parses"][$it]["fields"][$setname][$name]["attributes"] = $attributes;
						}
							
						
						if($attributes["type"] == "link") {	
							continue;
							
						} else if($attributes["type"] == "radio" || ($attributes["type"] == "checkbox" && 
						$this->isInGroup($attributes["name"]))) {
							
							$groupname = $attributes["group"];
							
							if(!in_array($groupname,$parsedgroups)) {
								
								// Radio and Checkbox groups
								
								$radiogroup = $fieldset->startGroup($attributes["type"],$parse["groups"][$groupname]["attributes"]);	
								
								foreach($parse["groups"][$groupname]["members"] as $key => $member) {
								
									$elem = $this->shiftItem($member);
									$this->childToAttribute($elem,$it);
									
									$attributes = $elem["attributes"];
									$attributes["name"] = $groupname;
									
									$tmp_name = "ImmExtjsField".ucfirst($classname);
									
									if($view == "edit") {
										$obj = new $tmp_name($radiogroup,$attributes);		
									} else {
										$this->showAttributes($attributes);
										$obj = new ImmExtjsFieldStatic($radiogroup,$attributes);
									}
								
								}	

								$fieldset->endGroup($radiogroup);	
								
								$parsedgroups[] = $groupname;
									
							}
							
						} else if($attributes["type"] == "linkButton" || $attributes["type"] == "button") {
							
							$attributes["url"] = (strstr($attributes["action"],"://")) ? $attributes["action"] : 
							$host.url_for($attributes["action"]);
							$tmp_name = "ImmExtjs".ucfirst($classname); 
							$obj = new $tmp_name($form,$attributes);	
							
						} else {
							
							if($attributes["type"] == "date" || $attributes["type"] == "datetime") {
								$classname = "dateTime";
								$attributes["dateFormat"] = "Y-m-d";
								
							}
							
							if($attributes["type"] == "multicombo" || $attributes["type"] == "doublemulticombo" || $attributes["type"] == "itemSelectorAutoSuggest" || $attributes["type"] == "doubletree") {
								
								switch($attributes["type"]) {
									case "multicombo":
										$classname = "multiCombo";
										break;
									case "doublemulticombo":
										$classname = "doubleMultiCombo";
										break;
									case "doubletree":
										$classname = "doubleTree";
										break;
								}
								
								$attributes["clear"] = true;
								
								if(!isset($attributes["selected"])) {
									$attributes["selected"] = array();	
								} else {
									if(!is_array($attributes["selected"]) && $classname != "doubleTree") {
										$attributes["selected"] = explode(",",$attributes["selected"]);	
									}
								}
							}
							
							
							if($attributes["type"] == "textarea" && $attributes["rich"] == "false") {
								$attributes["rich"] = false;
							}
							
							$tmp_name = "ImmExtjsField".ucfirst($classname);
							
							if($view == "edit" || $attributes["type"] == "doubletree") {
						
								
								$obj = new $tmp_name((isset($columnx)) ? $columnx : $form,$attributes);
								
								/*
								if(isset($attributes["window"])) {
									//$obj = new ImmExtjsFieldCombo($fieldset,array('name'=>'my_combo_button','label'=>'My combo button','help'=>"combo box with button",'comment'=>'comment for combo w button','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b','button'=>array('text'=>'Trigger','icon'=>'/images/famfamfam/cancel.png'),'window'=>array('title'=>'Window Title','component'=>$this->getForm(),'className'=>'ServerPeer','methodName'=>'getAllAsOptions')));
									//$columnx = $columns->startColumn(array('columnWidth'=>5,'labelAlign'=> 'left'));
									//$obj = new ImmExtjsFieldCombo($columnx,array('name'=>'my_combo_button','label'=>'My combo button','help'=>"combo box with button",'comment'=>'comment for combo w button','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b','button'=>array('text'=>'Trigger','icon'=>'/images/famfamfam/cancel.png'),'window'=>array('title'=>'Window Title','component'=>$this->getForm(),'className'=>'ServerPeer','methodName'=>'getAllAsOptions')));
									//$obj = new ImmExtjsFieldCombo($columnx,$attributes);							
								} else {
									
									;	
								
								}
								*/
									
							} else {
								if($attributes["type"] != "file") {
									$this->showAttributes($attributes);
									$obj = new ImmExtjsFieldStatic((isset($columnx)) ? $columnx : $form,$attributes);	
								}
								
							}
							
						}
												
						if($parse["isgrouped"]) {
							$columns->endColumn($columnx);	
						}
							
						if(isset($attributes["break"]) && $attributes["break"] == "true") {
							
							$attrs["columnWidth"] = 1;
							$columnx = $columns->startColumn($attrs);	
							$columns->endColumn($columnx);
							$this->is_floated[] = $this->isFloatedSet($set,Util::arraySum($this->is_floated));	
								
							if(!$this->is_floated[sizeof($this->is_floated)-1]) {
								$this->is_floated[sizeof($this->is_floated)-1] = 1;
							}
							
							$attrs["columnWidth"] = sprintf("%1.2f",(float) 1 / $this->is_floated[sizeof($this->is_floated)-1]);	
						
						}
						
							
					}
					
					if($parse["isgrouped"]) {
						
						$fieldset->endColumns($columns);
						
						if(isset($tabx)) {
							$tabx->endFieldSet($fieldset);
							$tabs->endTab($tabx);	
						} else {
							$form->endFieldSet($fieldset);
						}

					} else {
						break;
					}

				}
				
				if($view == "edit") {					
					if($this->type !== self::WIZARD && $parse["submit"] !== "false") {
						if(!$this->multisubmit) {
							
							if(isset($parse["redirect"])) {
								if($parse["redirect"])
								$form_params = array("redirect" => $parse["redirect"], "message" => "");	
							} else {
								if(isset($widget)) {
									if(substr($widget,0,4) == "edit") {
										$tmp_func = "executeList".substr($widget,4);
										$tmp_url = "list".substr($widget,4);
										if(!class_exists($this->context->getModuleName()."Actions")) {
											require_once("/apps/".$this->application."/modules/".$this->context->getModuleName()."/actions/actions.class.php");
										}
										if(method_exists($this->context->getModuleName()."Actions",$tmp_func)) {
											$form_params = array("redirect" => "/".$this->context->getModuleName()."/".$tmp_url, "message" => "");
										}
									}
								}
								if(!isset($form_params)) {
									$form_params = null;	
								}
							}
							$obj = new ImmExtjsSubmitButton($form,array("action"=>$formoptions["action"], "params" => $form_params));	
						} else {
							$this->formaction = $formoptions["action"];
						}
						if(!isset($parse['isSetting']) || $parse['isSetting']==="false")
						$obj = new ImmExtjsResetButton($form,array("action"=>$formoptions["action"]));	
					}
					
				}
				
				if(isset($parse["actions"]) && $this->type !== self::WIZARD) {					
					foreach($parse["actions"] as $aname => $action) {
						
						if(!self::toggleAction($aname,$action)) {
							continue;
						}
						
						$action["attributes"]["label"] = ucfirst($action["attributes"]["name"]);
						$action["attributes"]["name"] = $this->view.$this->iteration."_".$action["attributes"]["name"];
						$action["attributes"]["url"] = url_for($action["attributes"]["url"]);
						
						$temp_grid = new ImmExtjsGrid();
						$params = $temp_grid->getListenerParams($action,"action");
						
						if($action["attributes"]["post"] === "true"){
							$this->prepareButton($form, $action['attributes']);
						}else if($action["attributes"]["updater"] === "true") {
							$updater = new ImmExtjsUpdater(array('url'=>$action["attributes"]["url"],'width' => 500));
							$action["attributes"]["handlers"]["click"] = array('parameters'=>'field,event','source'=>$updater->privateName.'.start();');
							$obj = new ImmExtjsButton($form,$action['attributes']);
						} else {
							$obj = $this->prepareButton($form, $action['attributes']);
						}
					}	
				}

				
				if(isset($tabs)) {
					$form->endTabs($tabs);	
				}
				
				if(!$this->multisubmit) {
					$form->end();
				}
				
				$this->forms[] = $form;
				
				if($this->type == self::PAGE && $current_area["attributes"]["type"] == "content") {
					$this->addPortal($form,$parse["module"]."/".$parse["component"]);
				}
				
				if($build) {
					return $form;
				}
				
				if(!$this->multi) {
					$this->layout->addItem($this->area_types[$current_area],$form);	
					
					if($this->area_types[$current_area] == "center") {
						$this->layout->addCenterComponent($tools,array('title'=> $parse["title"].(class_exists('ImmExtjsWidgetConfig')?ImmExtjsWidgetConfig::getPostfixTitle():'')));	
					}
				} else {
					
					if($current_area["attributes"]["type"] == "content") {
						if($this->type != self::PAGE) {
							$panel->addItem($form);	
						}
					} else {
						$this->layout->addItem($this->area_types[$current_area["attributes"]["type"]],$form);
					}	
					
				}
			
	
			} else if($view == "html") {				
				if(!isset($parse["options"])) {
					$parse["options"]["autoScroll"] = true;
					$parse["options"]["border"] = false;
					$parse["options"]["header"] = true;
					$parse["options"]["autoHeight"] = true;
					$parse["options"]["autoEnd"] = false;
				}
				$plugins = ImmExtjsWidgets::getReloadPlugin($parse);
				if($this->multi) {
					
					if (isset($action_name)) {
						$idxml = $parse["module"]."/".$action_name;
					} else {
						$idxml = false;
					}
					
					$pn=new ImmExtjsPanel(array('plugins'=>$plugins,'title'=>$parse["title"],
					'autoScroll'=>$parse["options"]["autoScroll"],
					'border'=>$parse["options"]["border"],
					'header'=>$parse["options"]["header"],
					'style'=>'',
					'autoHeight'=>$parse["options"]["autoHeight"],
					'autoEnd'=>$parse["options"]["autoEnd"],
					'portal'=>true,
					'tools'=>$tools,
					'idxml'=>$idxml));
					
					if(isset($parse["description"])) {
						$html = $parse["description"];	
						if($this->widgetHelpSettings->getWidgetHelpIsEnabled()) {
							$pn->addHelp($html);	
						}
					}
					
					$pn->addMember(array('html'=>$parse["params"][0]));
					$pn->end();
					
					if($this->type != self::PAGE) {
						$panel->addItem($pn);	
					} 
					if($build) {
						return $pn;
					}
					//radu
					if($this->type == self::PAGE && $current_area["attributes"]["type"] == "content") {
						$this->addPortal($pn,$parse["module"]."/".$action_name);
					}
						
				} else {
					
						$pn=new ImmExtjsPanel(array('plugins'=>$plugins,'title'=>$parse["title"],
						'autoScroll'=>$parse["options"]["autoScroll"],
						'border'=>$parse["options"]["border"],
						'header'=>$parse["options"]["header"],
						'style'=>'',
						'tools'=>$tools,
						'autoHeight'=>$parse["options"]["autoHeight"],
						'autoEnd'=>$parse["options"]["autoEnd"],
						'portal'=>true));
						$pn->addMember(array('html'=>$parse["params"][0]));
						$pn->end();
						if($build) {
							return $pn;
						}
					$this->layout->addItem("center",$pn);
					$this->layout->addCenterComponent($tools,array('title'=> $parse["title"].(class_exists('ImmExtjsWidgetConfig')?ImmExtjsWidgetConfig::getPostfixTitle():'')));	
				}
				
			} else if($view == "info") {
			
				$form = new ImmExtjsForm($formoptions);

				if(isset($parse["settitle"])) {
					$legend = $parse["settitle"];
				} else {
					$legend = "Default";
				}
				
				$attributes = array("legend" => $legend, "collapsed" => false);
				$fieldset = $form->startFieldSet($attributes);
				
				if(isset($parse["actions"])) {
					foreach($parse["actions"] as $aname => $action) {
						
						if(!self::toggleAction($aname,$action)) {
							continue;
						}
						
						$action["attributes"]["label"] = ucfirst($action["attributes"]["name"]);
						$action["attributes"]["name"] = $this->view.$this->iteration."_".$action["attributes"]["name"];
						$action["attributes"]["url"] = url_for($action["attributes"]["url"]);
						$obj = $this->prepareButton($form, $action['attributes']);
					}	
				}	
				
				$attributes = array
				(
				'name'=>'msg',
				'label'=>'Message:',
				'value'=>$parse["message"],
				'comment'=>'',
				'submitValue'=>false
				);
				
				$obj = new ImmExtjsFieldStatic($fieldset,$attributes);
				
				$form->endFieldSet($fieldset);
				$form->end();
				
				if($build) {
					return $form;
				}
				
				if(!$this->multi) {
					$this->layout->addItem('center',$form);	
					$this->layout->addCenterComponent($tools,array('title'=> $parse["title"].(class_exists('ImmExtjsWidgetConfig')?ImmExtjsWidgetConfig::getPostfixTitle():'')));
				} else {
					
					if($this->type != self::PAGE) {
						$panel->addItem($form);	
					} else {
						if($current_area["attributes"]["type"] == "content") {
							$this->addPortal($form,$parse["module"]."/".$action_name);
						}
						
					}
					
				}
				
				
			} else if($view == "list") {
						
				$invisibles = array();
				
				// Add all non-hidden columns to visible..
				
				foreach($parse["fields"] as $column) {
					if(!in_array($column["attributes"]["name"], $parse["display"]["visible"])) {
						$parse["display"]["visible"][] = $column["attributes"]["name"];
						if($column["attributes"]["hidden"] == "false")
							$invisibles[] = $column["attributes"]["name"]; 
					}
				}
			
				
				if(isset($parse["params"]["name"])){
					$formoptions['name'] = $parse["params"]["name"];
				}
				if(isset($parse["remoteSort"]))
				{
					$formoptions["remoteSort"] = ($parse["remoteSort"]=="false") ? false : true;
				}
				if(isset($parse['id'])) $formoptions["id"] = $parse['id'];
				$formoptions["autoHeight"] = true;
				$formoptions["clearGrouping"] = false;
				$formoptions["frame"] = false;
				$formoptions["tree"] = ($parse["tree"] == "false") ? false : true;
				$formoptions["select"] = ($parse["select"] == "false") ? false : true;
				$formoptions["pager"] = ($parse["pager"] == "false") ? false : true;
				$formoptions["border"] = ($parse["border"] == "false") ? false : true;
				$formoptions["portal"] = ($parse["portal"] == "false") ? false : true;
				$formoptions["display_visible"] = $parse['display']['visible'];
				//$formoptions["id"] = strtolower(str_replace(" ","_",$parse['title']));				
				$formoptions["remoteLoad"] = ArrayUtil::isTrue($parse, 'remoteLoad');
				$formoptions["remoteFilter"] = isset($parse['remoteFilter'])?true:false;
				$formoptions["expandButton"] = isset($parse['expandButton'])?true:false;				
				$formoptions["path"] = $this->context->getModuleName()."/".$this->context->getActionName();
				if($parse['action'] != '') $formoptions['action'] = $parse['action'];
				
				if($this->type == self::PAGE) {
					$formoptions["idxml"] = $parse["module"]."/".$action_name;
				}
				if(isset($parse["plugin"])) {
					$formoptions["plugin"] = $parse["plugin"];
				}
				if(isset($parse["iconCls"])) {
					$formoptions["iconCls"] = $parse["iconCls"];
				}
				
				if(isset($parse["bodyStyle"])) {
					$formoptions["bodyStyle"] = $parse["bodyStyle"];
				} 
					
				if((is_array($current_area) && $this->area_types[$current_area["attributes"]["type"]] == "south") ||
				$current_area == "footer") {
					$formoptions["title"] = "";
				}
				
				if(!isset($formoptions["title"])) {
					$formoptions["title"] = ($current_area == "footer") ? " " : $parse["title"];
				}
				
				
				if($formoptions["tree"]) {
					$formoptions["root_title"] = (isset($parse["title"])) ? $parse["title"] : "root";
				}
				
				
				
				if((is_array($current_area) && $this->area_types[$current_area["attributes"]["type"]] == "west") ||
				$current_area == "sidebar") {
					$formoptions["tools"] = null;
				}
				
				$formoptions['datasource'] = $parse['datasource'];
				
				// Add the select fields in grid if it has moreactions..............
				if(isset($parse["moreactions"])) $formoptions['select'] = "true";
				//..................................................................
				
				$formoptions['plugins'][] = ImmExtjsWidgets::getReloadPlugin($parse);
				$grid = new ImmExtjsGrid($formoptions);
				
				if($widgetHelp) {
					if($this->multi) {
						$grid->addHelp($parse["description"]);	
					}
				}	
				
				foreach($parse["fields"] as $colname => $column) {
					
					unset($column["attributes"]["id"]);
					
					if($column["attributes"]["hidden"] == "true" || in_array($column["attributes"]["name"],$invisibles)) {	
						$column["attributes"]["hidden"] = true;
					}
					
					if(isset($column["attributes"]["qtip"]) && $column["attributes"]["qtip"] == "false") {
						$column["attributes"]["qtip"] = false;
					}					
					$grid->addColumn($column["attributes"]);
					
				}
				
				// Reads the maxperpage param.
				if(isset($parse["params"]['maxperpage'])) {
					$limit = $parse["params"]['maxperpage'];
				} else {
					$limit = 20;
				}
				if(isset($parse["params"]['proxystart'])) {
					$parse["proxystart"] = $parse["params"]['proxystart'];
				}
				
				if(isset($parse["stateId"]) && $parse["stateId"] == "true") {
					$args = array('url'=>$host."/".$parse["proxy"].((strstr($parse["proxy"],"?")) ? "&" : "?")."uid=".$unique_id,'limit'=>$limit, 'start' => $parse["proxystart"],'stateId'=>true);
				}
				else
				{
					$args = array('url'=>$host."/".$parse["proxy"].((strstr($parse["proxy"],"?")) ? "&" : "?")."uid=".$unique_id,'limit'=>$limit, 'start' => $parse["proxystart"]);
				}
				$grid->setProxy($args);
				
				
				
				if(isset($parse["rowactions"])) {
					
					$actions = $grid->startRowActions(array('header'=>'Actions'));
					$default_actions_tooltip = array(
						"Delete"=>array("delete","remove","erase"),
						"Edit"=>array("edit","modify","update"),
						"View"=>array("view","show","display","detail","details"),
						"Expand"=>array("expand")
					);
					
					$cnt = 1;
					foreach($parse["rowactions"] as $action_name => $action) {						
						self::addConfirmation($action_name, $action['attributes']);
						if(!isset($action["attributes"]["tooltip"])){ 
							foreach($default_actions_tooltip as $key=>$value){
								foreach($value as $v){
									if(stristr($action_name,$v)){
										$action["attributes"]["tooltip"] = $key;
										break;
									}
								}
								if(isset($action["attributes"]["tooltip"])) break;
							}
						}
				
						if(isset($parse["form"]) && $action["attributes"]["url"] === "/#") {
							$parse["rowactions"][$action_name]["attributes"]["url"] = $parse["form"]."#";
						}
						
						if(isset($action["attributes"]["condition"])) {
							$parse["conditions"]["rowaction".$cnt] = $action["attributes"]["condition"]; 
						}
						
						$parameterForRowAction = $grid->getListenerParams($action,"rowactions",$this->view.$this->iteration,$parse["select"]);	
						$actions->addAction($action["attributes"]);	
						$cnt++;
					}
					$grid->endRowActions($actions);
				}
				
				/**
				 * ticket 1140
				 */
				//Check for more actions....................................................
				
				$export_config = sfConfig::get('app_parser_export');
				
				/**
				* default select & unselect, export page, export all to csv in all actions
				*/
				
				if($formoptions["select"]) {
					//$grid->addMenuActionsItem(array('label'=>'Select All','icon'=>'/images/famfamfam/flag_green.png','listeners'=>array('click'=>array('parameters'=>'','source'=>$grid->privateName.".getSelectionModel().selectAll()"))));
					//$grid->addMenuActionsItem(array('label'=>'Deselect All','icon'=>'/images/famfamfam/flag_red.png','listeners'=>array('click'=>array('parameters'=>'','source'=>$grid->privateName.".getSelectionModel().clearSelections()"))));
				}
				
				if(isset($export_config["enabled"]) && $export_config["enabled"] === true && $parse["exportable"] == "true") {
					
					if(isset($parse["pager"]) && $parse["pager"] === "true") {
						$grid->addMenuActionsItem(array('label'=>'Export Page as CSV', 'icon'=>'/images/famfamfam/database_save.png','url' => '#','listeners'=>array('click'=> array('parameters'=>'','source'=>"window.location.href='/parser/csvexport?uid=".$unique_id."&export=page&start='+".$grid->privateName.
						".store.lastOptions.params.start+'&limit='+".$grid->privateName.".store.lastOptions.params.limit+'&sort='+((".
						$grid->privateName.".store.sortInfo) ? ".$grid->privateName.".store.sortInfo.field : '')+'&dir='+((".
						$grid->privateName.".store.sortInfo) ? ".$grid->privateName.".store.sortInfo.direction : '')+'&xsort='+((".
						$grid->privateName.".store.lastOptions.params.xsort) ? ".$grid->privateName.".store.lastOptions.params.xsort : '');"))));
					}
					
					if($parse["tree"] === "false") {
						$confirmFunction = '
								Ext.Msg.show({
								   title:"Confirmation Required",
								   msg: "Are you sure you want export '. sfConfig::get("app_parser_max_items").' items? This may take a while..",
								   buttons: Ext.Msg.YESNO,
								   fn: function(buttonId){if(buttonId == "yes"){'.
								   "window.location.href='/parser/csvexport?uid=".$unique_id."&export=all&sort='+((".
								   $grid->privateName.".store.sortInfo) ? ".$grid->privateName.
								   ".store.sortInfo.field : '')+'&dir='+((".$grid->privateName.".store.sortInfo) ? ".
								   $grid->privateName.".store.sortInfo.direction : '')+'&xsort='+((".
									$grid->privateName.".store.lastOptions.params.xsort) ? ".$grid->privateName.".store.lastOptions.params.xsort : '');".'}},
								   icon: Ext.MessageBox.QUESTION								   
								});
							';
												
						$grid->addMenuActionsItem(array('label'=>'Export first '.sfConfig::get("app_parser_max_items").' rows as CSV', 'confirmMsg' => 'foo', 'icon'=>'/images/famfamfam/database_save.png','url' => '#','listeners'=>array('click'=> array('parameters'=>'','source'=>$confirmFunction))));
					
						
					} 					
					
					if($formoptions["select"]) {
						
						$noItemsSelectedFunction = '								
							if(!'.$grid->privateName.'.getSelectionModel().getCount()){
								Ext.Msg.alert("No items selected","Please select at least one item");
								return;
							}
						';
						
						$fs = "true";
						
							if(isset($parse['remoteLoad']) && $parse['remoteLoad'] == "true") 
								$fs = "false";		
						
						$grid->addMenuActionsItem(array('label'=>'Export '.(($fs === "true") ? "Selected" :  "All").' as CSV', 'forceSelection' => "true", 
						'icon'=>'/images/famfamfam/database_save.png','url' => '#','listeners'=>array('click'=> array('parameters'=>'','source'=>
						"frm = document.createElement('form'); field = document.createElement('input'); field.setAttribute('type','hidden'); field.setAttribute('name','selections'); field.value = ".
						$grid->privateName.".getSelectionModel().getSelectionsJSON(); frm.appendChild(field); frm.action = '/parser/csvexport?uid=".
						$unique_id."&_csrf_token=".$this->context->getRequest()->getAttribute("_csrf_token")."&export=".(($fs === "true") ? "selected" : "all")."&sort='+((".$grid->privateName.".store.sortInfo) ? ".$grid->privateName.".store.sortInfo.field : '')+'&dir='+((".
						$grid->privateName.".store.sortInfo) ? ".$grid->privateName.".store.sortInfo.direction : '')+'&xsort='+((".
						$grid->privateName.".store.lastOptions.params.xsort) ? ".$grid->privateName.".store.lastOptions.params.xsort : ''); frm.method='POST'; frm.name='frm1'; document.body.appendChild(frm); ".$noItemsSelectedFunction." frm.submit();"))));
						
						
					}
					
					
				}
				
				if(isset($parse["moreactions"])) {												
					
					$parse["select"] = "true";
					$items = array();										
					
					foreach($parse["moreactions"] as $aname => $action) {	
						
						if(!self::toggleAction($aname,$action)) {
							continue;
						}
						
						$parameterForButton = $grid->getListenerParams($action,"moreactions",$this->view.$this->iteration,$parse["select"]);						
						$grid->addMenuActionsItem($parameterForButton);															
					}
				}
				//More actions End............................................................
				//...........................................................................
				
				if(isset($parse["actions"])) {
					
					foreach($parse["actions"] as $aname => $action) {
						
						if(!self::toggleAction($aname,$action)) {
							continue;
						}
						$parameterForButton = $grid->getListenerParams($action,"actions",$this->view.$this->iteration,$parse["select"]);
						$obj = new ImmExtjsButton($grid,$parameterForButton);
					}
				}
				
				if($parse["select"] != "false") {
					$post_url = ($parse["action"] != "false" && $parse["action"] != "n/a") ? "/".$parse["action"] :  "/".$this->context->getModuleName()."/". $this->context->getActionName();
					if($parse["action"] != "false" && $parse["action"] != "n/a"){
						if(!$this->multisubmit) {
							new ImmExtjsButton($grid,array('label'=>$parse["label"],'icon' => $parse["icon"], 'handlers'=>array('click'=>
							array('parameters'=>'field,event','source'=>'Ext.Ajax.request({ url: "'.$post_url.'", 
							method:"post", params:{"selections":'.$grid->privateName.'.getSelectionModel().getSelectionsJSON()}, success:function(response, options){response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message,function(){if(response.redirect){window.location.href=response.redirect;}});}},failure: function(response,options) {if(response.message){Ext.Msg.alert("Failure",response.message);}}});'))));
						}
					}					
				}
				$grid->end();
				
				if($this->type == self::PAGE && $current_area["attributes"]["type"] == "content") {
					$this->addPortal($grid,$parse["module"]."/".$parse["component"]);
				}
					
				if(is_array($current_area) && $this->area_types[$current_area["attributes"]["type"]] == "center") {
					$this->multigrid = $grid;
				}
				
				if($build === false) {
					
					if(!$this->multi) {
						$this->layout->addItem($this->area_types[$current_area],$grid);
						if($this->area_types[$current_area] == "center") {
							$this->layout->addCenterComponent($tools,array('title'=> $parse["title"].(class_exists('ImmExtjsWidgetConfig')?ImmExtjsWidgetConfig::getPostfixTitle():'')));		
						}
					} else {
						
						if($current_area["attributes"]["type"] == "content") {
							if($this->type != self::PAGE) {
								$panel->addItem($grid);	
							}
						} else {
							$this->layout->addItem($this->area_types[$current_area["attributes"]["type"]],$grid);
						}	
						
					}
				}
				
				
				// Getting DB results..
				if(!isset($parse["datasource"]["statement"])) {
					
					if($parse["datasource"]["type"] != "file") {
						if(ArrayUtil::isTrue($parse, 'remoteLoad')) {
							$criteria = array();
						} else {
							$criteria = call_user_func_array(array($parse["datasource"]["class"],$parse["datasource"]["method"]["name"]),
								(isset($parse["datasource"]["method"]["params"])?$parse["datasource"]["method"]["params"]:array()));	
						}
					}
					
					if($parse["datasource"]["type"] == "orm") {
						if(get_class($criteria) != "Criteria") {
							throw new XmlParserException("Bad return value, in case of orm source, peer methods must return Criteria object!");
						}	
					} else if($parse["datasource"]["type"] == "static") {
						if(!is_array($criteria)) {
							throw new XmlParserException("Bad return value, in case of static source, peer methods must return Array!");
						}
						$result = $criteria;
						$criteria = null;
					} else if($parse["datasource"]["type"] == "file") {
						$criteria = $result = null;
					}
					
					$stmt = null;
					$data_class = $parse["datasource"]["class"];	
					
					
				} else {
				
					preg_match("/from ([a-zA-Z0-9_\-]+)/",$parse["datasource"]["statement"],$m);

					if(!$m || sizeof($m) != 2) {
						throw new XmlParserException("Invalid SQL statement, name of table cannot be determined!");
					} 
					$phpName = self::getPhpName('propel', trim($m[1]));
					$con = Propel::getConnection("propel");

					$stmt = $con->prepare($parse["datasource"]["statement"]);
					$stmt->execute();
					
					$data_class = $phpName.'Peer';
					
					preg_match_all("/([a-zA-Z0-9_\-\.]+) as '([a-zA-Z0-9_\-]+)'/",$parse["datasource"]["statement"],$as_columns);
					
					$criteria = null;
					
				}
				
				if($data_class) {
					if($parse["datasource"]["type"] == "orm") {
						$cols = call_user_func(array($data_class,"getTableMap"));	
						$current_table = $cols->getPhpName();	
					}
				}
				
				if(isset($as_columns) && !empty($as_columns)) {
					foreach($as_columns[1] as $i => $table) {
						$table = substr($table,0,strpos($table,"."));
						foreach($cols->getColumns() as $colobject) {
							if($colobject->getRelatedTableName() == $table) {
								$as_columns[3][strtolower($colobject->getName())] = $as_columns[2][$i];
							}
						}
					}
				}
				
				$foreign_keys = $selected_foreign_keys = $fks = $fk_count = $data_types = array();
				
				if($parse["datasource"]["type"] == "orm") {
					
					foreach($cols->getColumns() as $colobject) {
						$method = "";
						$dbconn = constant($colobject->getTable()->getPhpName()."Peer::DATABASE_NAME");
						$related_column = strtolower($colobject->getRelatedColumnName());
						$column = strtolower($colobject->getColumnName());
						$data_types[$column] = $colobject->getType();
				
						if($related_column) {
							$class = self::getPhpName($dbconn, $colobject->getRelatedTableName());
							
							if(!class_exists($class)) {
								throw new XmlParserException("PhpName of table: ".$colobject->getRelatedTableName()." cannot be determined!");
							} else if(!method_exists($current_table,"get".$class)) {
								$method = $class."RelatedBy".sfInflector::camelize($column);
								if(!method_exists($current_table,"get".$method)) {
									throw new XmlParserException("The getter method cannot be determined for column: ".$column);
								}
							}
							
							$foreign_keys[] = array("pointer" => $column, "class" => $class, "method" => $method);
							if(in_array($column,$parse["display"]["visible"])) {
								$selected_foreign_keys[$column] = array("pointer" => $column, "class" => $class, "method" => $method);
								$fks[] = ($method) ? $method : $class;
								if(!isset($fk_count[$class])) {
									$fk_count[$class] = 0;
								}
								$fk_count[$class]++;
							}
						}
					}	
				
					
					$group_field = "";
					
					foreach($parse["display"]["visible"] as $ik => $item) {
					
						if(isset($parse["fields"][$item]["attributes"]["groupField"]) && 
						 $parse["fields"][$item]["attributes"]["groupField"] === "true") {
							$group_field = $item;
						}
						
						unset($parse["display"]["visible"][$ik]);
						$parse["display"]["visible"][$ik]["column"] = $item;
						if(isset($selected_foreign_keys[$item])) {
							$parse["display"]["visible"][$ik]["phpname"] = ($selected_foreign_keys[$item]["method"]) ? $selected_foreign_keys[$item]["method"] : $selected_foreign_keys[$item]["class"];
						} else {
							$parse["display"]["visible"][$ik]["phpname"] = sfInflector::camelize($item);
						}
						
						if(!empty($data_types) && isset($data_types[$item])) {
							$parse["display"]["visible"][$ik]["type"] = $data_types[$item]; 	
						}
					}
					
					if(!empty($fk_count)) {
						$duplicates = (max($fk_count) > 1);		
					} else {
						$duplicates = false;
					}
					
					if(!isset($parse["datasource"]["statement"])) {
						if(!$duplicates && sizeof($selected_foreign_keys) == 0) {
							$select_method = "doSelect";
						} else if(!$duplicates && sizeof($selected_foreign_keys) == 1) {
							$select_method = "doSelectJoin".$selected_foreign_keys[key($selected_foreign_keys)]["class"];
						} else if(!$duplicates && sizeof($foreign_keys)-1 == sizeof($selected_foreign_keys)) {
							foreach($foreign_keys as $value) {
								if(!array_key_exists($value["pointer"],$selected_foreign_keys)) {
									$diff = $value;
									break;
								}
							}
							$select_method = "doSelectJoinAllExcept".$diff["class"];
						} else {
							$select_method = "doSelect";
							foreach($selected_foreign_keys as $fk) {
								if($fk_count[$fk["class"]] == 1) {
									$criteria->addJoin(strtolower(constant($current_table."Peer::".
									strtoupper($fk["pointer"]))),constant($fk["class"]."Peer::ID"),Criteria::LEFT_JOIN);	
								}
							}
						}	
						$result = array();
					} else {
						$select_method = null;
					}
				
					
					$i = 0;
					
					if($stmt) {
						$result = array();
						while($row = $stmt->fetch(PDO::FETCH_BOTH)) {
							$keys = array_keys($row);
							foreach($parse["display"]["visible"] as $k => $r) {
								if(isset($row[$r["column"]])) {
									if(!$as_columns || !isset($as_columns[3][$r["column"]])) {
										$result[$i][$r["column"]] = $row[$keys[array_search($r["column"],$keys,true)+1]];	
									} else if(isset($as_columns[3][$r["column"]])) {
										$result[$i][$r["column"]] = $row[$as_columns[3][$r["column"]]];
									}
								}
							}
						$i++;
						}	
					}
				
				} else {
					$select_method = null;
				}
				
				if(!isset($group_field)) {
					$group_field = "";
				}
				
				
				// Add column labels (CSV export needs it)
				
				$headers = array();
				
				
				if(is_array($parse["display"]["visible"][0])) {

					foreach($parse["fields"] as $f) {
						foreach($parse["display"]["visible"] as &$col_data) {	
							if($f["attributes"]["name"] == $col_data["column"]) {
								$col_data["label"] = $f["attributes"]["label"];			
							}	
						}
					}
				} else {
					
					foreach($parse["fields"] as $f) {
						if(in_array($f["attributes"]["name"],$parse["display"]["visible"])) {
								$parse["headers"][$f["attributes"]["name"]] = $f["attributes"]["label"];
						}
					}	
						
				}
				
	  			$data = array
	  			(
	  			"uid" => $unique_id,
	  			"title" => $parse["title"],
	  			"group_field" => $group_field,
	  			"datafile" => (isset($this->attribute_holder["datafile"])) ? $this->attribute_holder["datafile"] : null,
	  			"realtime" => (isset($this->attribute_holder["realtime"])) ? $this->attribute_holder["realtime"] : false, 
	  			"wizard" => $this->step,
	  			"tree" => ($parse["tree"] == "false") ? false : true,
	  			"levels" => (isset($parse["levels"])) ? $parse["levels"] : false,
	  			"type" => $parse["datasource"]["type"],	  			
	  			"datasource" => (isset($parse["datasource"])) ? $parse["datasource"] : null,
	  			"result" => $result,
	  			"total_count"=>(isset($this->attribute_holder["total_count"])) ? $this->attribute_holder["total_count"] : null,
	  			"sql" => isset($parse["datasource"]["statement"]) ? true : false,
	  			"foreign_keys" => $fks,
	  			"select_method" => $select_method,
	  			"criteria" => $criteria, 
	  			"class" => str_replace("Peer","",$data_class),
	  			"columns" => $parse["display"]["visible"],
	  			"headers" => (isset($parse["headers"])) ? $parse["headers"] : null,
	  			"selectors" => (isset($parse["display"]["selectors"])) ? $parse["display"]["selectors"] : null,
	  			"proxy" => $parse["proxy"],
	  			"limit" => $limit,
	  			"rowactions" => isset($parse["rowactions"]) ? $parse["rowactions"] : null,
	  			"actions" => isset($parse["actions"]) ? $parse["actions"] : null,
	  			"remoteLoad" => ArrayUtil::isTrue($parse, 'remoteLoad'),
	  			"remoteSort" => (isset($parse["remoteSort"]) && $parse["remoteSort"] == "true") ? true : false,
	  			"remoteFilter" => (isset($parse["remoteFilter"]) && $parse["remoteFilter"] == "true") ? true : false,
	  			"exportFrom" => isset($parse["exportFrom"]) ? $parse["exportFrom"] : false,
	  			"conditions" => isset($parse["conditions"]) ? $parse["conditions"] : false,
	  			"static_real_pagination"=>isset($parse["params"]["static_real_pagination"]) ? $parse["params"]["static_real_pagination"] : false,
	  			"reload_topic" => ArrayUtil::get($parse, 'params', 'reload_topic', null),
	  			);
				if($data['remoteLoad']) {
					// The anode fetching would not work with the overwritten session state.
					$this->context->getResponse()->setHttpHeader('Cache-Control', 'Cache-Control: no-cache, no-store');
				}
				/*if($data['static_real_pagination']) {
					// The anode fetching would not work with the overwritten session state.
					$this->context->getResponse()->setHttpHeader('Cache-Control', 'Cache-Control: no-cache, no-store');
				}*/
	
	  			$current =  $this->context->getUser()->getAttributeHolder()->getAll('parser/grid');

	  			if(is_array($current)) {
	  				$current[] = $data;
	  			} else {
	  				$current = array($data);
	  			}
	  			
	  			$this->context->getUser()->getAttributeHolder()->add($current, 'parser/grid');
	  			
	  		}
	  		
	  		if($this->tabbedWizard) {
	  			
	  			switch($view) {
	  				case "show": case "edit": case "info":
	  					$obj = $form;
	  					break;
	  				case "list":
	  					$obj = $grid;
	  					break;
	  				case "html":
	  					$obj = $pn;
	  					break;
	  			}
	  	
	  			$wiztab = $this->getTabData($parse["component_name"]);
	  			$wizard_group->addItem($obj,array("title" => $wiztab[1], "tabTip" => $wiztab[2], "iconCls" => ""));
	  
		  		if($this->isLastGroupMember($parse["component_name"])) { 			
		  			$this->openGroup = false;
		  			$this->layout->endGroup($wizard_group);	
		  		}
	  		
	  		}
	  		
		}
		
		
		if($this->multi) {
			if($this->type === self::WIZARD && !$this->tabbedWizard) {
				$this->layout->endColumn($panel);	
			}
				
	  	}
	  	
	  	if($this->multisubmit && $this->type !== self::WIZARD) {
	  		new ImmExtjsSubmitButton($this->forms[0],array('action'=>$this->formaction,
				'afterSuccess'=>'
				var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Saving additional information... <br>Please wait..."});
				myMask.show();
				Ext.Ajax.request({ url: "'.$this->multisubmit.'", method:"post", params:{"selections":'.$this->multigrid->privateName.'.getSelectionModel().getSelectionsJSON()}, success:function(response, options){response=Ext.decode(response.responseText);
if(response.message) {
	Ext.Msg.alert("Success", response.message, function(){
		if(response.redirect) {
			window.location.href = response.redirect;
		}
	});
} else {
	if(response.redirect) {
		window.location.href = response.redirect;
	}
}
	  	myMask.hide();
	  	},failure: function(response,options) {if(response.message){Ext.Msg.alert("Failure",response.message);myMask.hide();}}});'));
	  		$form->end();
	  	}
	  	
	  	// Adding layout buttons
	  	
	  	if($this->type == self::WIZARD) {
	  		
	  		$actions = array
			(
			"Previous" => 1,
			"Next" => 1,
			"Cancel" => 0,
			"Add new" => 1,
			"Finish" => 1
			);
			
			$add = "false";
			$finish = false;
			
			foreach($actions as $key => $value) {

				if(isset($this->attribute_holder["last"]) && $key == "Next") {
					continue;
				}
				
				if($key == "Finish" && isset($this->page["actions"][$key])) {
					$finish = true;
				}
				
				if($key == "Add new" && isset($this->page["actions"][$key])) {
					$add = "true";
				}
				
				if($key == "Next") {
					$icon = "/images/famfamfam/arrow_right.png";
				} else if($key == "Previous") {
					$icon = "/images/famfamfam/arrow_left.png";
				} else {
					$icon = null;
				}
				
				if(isset($this->page["actions"][$key])) {
					/*
					 * Find if grid data save request
					 */		

					$preExecute = '';					
					//$file = 'appFlowerPlugin/js/custom/'.self::isRequest($this->page["actions"][$key]["attributes"]["url"],'js').".js";					
				
					if(isset($this->page["actions"][$key]["attributes"]["script"])) {
						$file = 'appFlowerPlugin/js/custom/'.$this->page["actions"][$key]["attributes"]["script"].".js";	
					} else {
						$file = "";
					}
					
					if(file_exists($file) && !is_dir($file)){
						$preExecute = $file;
					}					
					if($this->page["actions"][$key]["attributes"]["post"] === "false" || !$value) {
						new ImmExtjsLinkButton($this->layout,array('preExecute'=>$preExecute,'label'=>$key,'url'=>$this->page["actions"][$key]["attributes"]["url"], 'icon' => (isset($icon)) ? $icon : $this->page["actions"][$key]["attributes"]["icon"]));
								
					} else {
						new ImmExtjsSubmitButton($this->layout,array('preExecute'=>$preExecute,'label'=>$key,'icon' => (isset($icon)) ? $icon : 
						"/images/famfamfam/accept.png", 'action'=>$this->page["actions"][$key]["attributes"]["url"]),$this->forms[0]);
					}	
				}	
				
			}
			
			if(!$finish && isset($this->attribute_holder["last"])) {
				$id = '';
				if(isset($this->attribute_holder["id"])) $id = $this->attribute_holder["id"];
				new ImmExtjsSubmitButton($this->layout,array('label'=>'Finish','action'=>"/wizard/finalize?last=".$this->current.
				"&end=".$this->attribute_holder["end"]."&id=".$id."&add=".$add),$this->forms[0]);
			}

			
	  	}
	  	
	  	if($this->type == self::PAGE) {
	  		
	  		if($this->portalStateObj->getLayoutType() == afPortalStatePeer::TYPE_TABBED) {
	  			
	  			$content = $this->portalStateObj->getContent();
	  		
	  			foreach ($content as $item=>$itemDetails)	{
	
					$portalLayoutType = $this->portalStateObj->getPortalLayoutType($item);
					$portalColumns = $this->portalStateObj->getColumns($item);
					$portalColumnsSize = $this->portalStateObj->getColumnsSize($item);
					
		  			${'tab'.$item}=$this->layout->startTab(array('title'=>(isset($itemDetails["tabTitle"])) ? $itemDetails["tabTitle"] : $itemDetails["portalTitle"],'portalLayoutType' => $portalLayoutType,'portalWidgets'=>$this->filterWidgets($item,$content,$this->widgets)));
						  			
		  			if(is_array($portalColumns))
					foreach ($portalColumns as $k=>$widgets)
					{
						//instanciate a column
						${'column'.$k}=${'tab'.$item}->startColumn(array('columnWidth'=>($portalColumnsSize[$k]/100)));
						
						foreach ($widgets as $widget)
						{
							${'column'.$k}->addItem($this->extobjects[$widget->idxml]);
						}
						
						//end the instanciation of a column
						${'tab'.$item}->endColumn(${'column'.$k});
					}
					
					$this->layout->endTab(${'tab'.$item});
				
	  			}
	  			
	  			
	  		} else {

		  		foreach ($this->portalColumns as $k=>$widgets)
				{
					
					//instanciate a column
					${'column'.$k} = $this->layout->startColumn(array('columnWidth'=>($this->portalColumnsSize[$k]/100)));
					
					foreach ($widgets as $widget)
					{				
						${'column'.$k}->addItem($this->extobjects[$widget->idxml]);
					}
					
					//end the instanciation of a column
					$this->layout->endColumn(${'column'.$k});
					
				}
	  			
	  		}
	  		
	  		
	  	}

	  	
	  	if($this->multi) {
	  		self::$instance = null;
	  	}
	  	
	  	
		if($build) return $grid;
		return true;	
		
	}
	
	
	private function filterWidgets($current_tab = null,$all_tabs,$all_widgets) {
		
		foreach($all_tabs as $item => $details) {
			if($item !== $current_tab) {
				if(isset($details["portalColumns"]))
				foreach($details["portalColumns"] as $col => $widgets) {
					foreach($widgets as $widget) {
						foreach($all_widgets as $ck => $category) {
							foreach($category["widgets"] as $wk => $entry) {
								if("/".$widget->idxml == $entry) {
									unset($all_widgets[$ck]["widgets"][$wk]);
									sort($all_widgets[$ck]["widgets"]);
								}
							}
						}
					}
				}
			}	
		}
		
		return $all_widgets;
		
	}
	
	
	private static function isRequest($url,$request){
		if(preg_match('/\?/',$url)){
			$a = explode("?",$url);
			$b = $a[1];
		}else{
			$b = $url;
		}
		$c = explode("&",$b);
		if(is_array($c)){
			foreach($c as $val){
				$d = explode("=",$val);				
				if($d[0] == $request){
					return $d[1];
				}
			}
		}else{
			$d = explode("=",$c);	
			if($d[0] == $request){
				return $d[1];
			}
		}
		return false;
	}
	
	private static function addConfirmation($action_name, &$attributes) {
		$lower_name = strtolower($action_name);
		if(strstr($lower_name,'delete') || strstr($lower_name,'remove')) {
			$attributes['post'] = true;
			$attributes['confirm'] = true;
			$attributes['message'] = 'Are you sure you would like to delete this item?';
		}
	}

	/**
	 * Returns the matching PhpName or throws an XmlParserException.
	 */
	public static function getPhpName($dbName, $tableName) {
		return PublicCache::cache(array('XmlParser', '_getPhpName'), array($dbName, $tableName));
	}

	public static function _getPhpName($dbName, $tableName) {
		if(!isset(self::$dbschema)) {
			$root = sfConfig::get("sf_root_dir");
			self::$dbschema = sfYaml::load($root.'/config/schema.yml');
		}
		if(isset(self::$dbschema[$dbName][$tableName]['_attributes']['phpName'])) {
			$phpName = self::$dbschema[$dbName][$tableName]['_attributes']['phpName'];
		} else {
			$phpName = sfInflector::camelize($tableName);
		}
		return $phpName;
	}

	public static function layoutExt($actionInstance, $type = XmlParser::PANEL,$msg = null) {
					
		ImmExtjsAjaxWidgetsPopup::checkWidgetPopupRequest($actionInstance,$type);
		sfLoader::loadHelpers("Helper");
		$parser = new XmlParser($type);
		$actionInstance->layout = $parser->getLayout();		
		$actionInstance->setLayout("layoutExtjs");
		self::setTemplateAppFlower($actionInstance);
		return sfView::SUCCESS;
	}
	
	
	public static function saveSessionData($data) {
		
		$data_dir = substr(__FILE__,0,strpos(__FILE__,"appFlowerPlugin/")+15)."/data/parser";
		$str_data = serialize($data);
		
		$data_file = FileUtil::saveTmpFile($str_data,$data_dir,"grid_");
		
		return $data_file;
		
	}
	

    /**
     * set the extSuccess.php template
     */
	private static function setTemplateAppFlower($actionInstance)
	{
		$name = sfConfig::get('sf_plugins_dir').'/appFlowerPlugin/modules/appFlower/templates/ext';		
		sfConfig::set('symfony.view.'.$actionInstance->getModuleName().'_'.$actionInstance->getActionName().'_template', $name);
	}

	/**
	 * Prepares a button for POST or GET.
	 */
	private function prepareButton($form, &$attributes)
	{
		$defaultIcon = "/images/famfamfam/application_view_list.png";
		if($attributes["post"] === "true"){
			$defaultIcon = "/images/famfamfam/accept.png";
		}
		$attributes['icon'] = (isset($attributes["icon"]) && $attributes["icon"]) ? $attributes["icon"] : $defaultIcon;

		if(!isset($attributes['popupSettings']))
		{
			$attributes['popupSettings']="";
		}
		
		if(isset($attributes['popup']) && $attributes['popup'] && $attributes['popup'] !=="false") 
		{
			$attributes['handlerSource'] = 'ajax_widget_popup("'.$attributes["url"].'","","","'.$attributes['popupSettings'].'");';
		}
		
		unset($attributes['popupSettings']);
		unset($attributes['popup']);
		
		if($attributes["post"] === "true"){
			$obj = new ImmExtjsSubmitButton($form,array_merge($attributes,array('label'=>$attributes["label"], 'action'=>$attributes["url"])),$this->forms[0]);
		} else {
			$obj = new ImmExtjsLinkButton($form,$attributes);
		}
		return $obj;
	}
}

?>
