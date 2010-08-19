<?php

class XmlBaseElementParser  {
	
	private static
		$ret = array();
	public static 
		$parser,
		$tree,
		$tree_root,
		$tree_item;
	
	public static final function init($node) {
		
		// Value
		
		$val = self::$parser->get($node);
		
		// Replacing non-XML firendly chars..
		
		self::entityReplace($val);
		
		if($val && (is_string($val) || is_numeric($val)) && property_exists($node,"nodeValue") && 
		self::$parser->textonly($node)) {
			$str = self::parseValue($val,$node);		
			self::$parser->set(null,$str,$node);
		}
		
		$attributes = self::$parser->attributes($node);
		
		// Parse variables in attributes..
		
		foreach($attributes as $attrname => $attrvalue) {
			$attrvalue = self::parseValue($attrvalue,$node);
			self::$parser->set($attrname,$attrvalue,$node);
		}
		
		if(isset($attributes["permissions"])) {
			return self::$parser->checkCredentials(null,$node);
		} else {
			return true;
		}
	}
		
	public static final function setParser($parser) {
		try {
			if(!$parser || !is_object($parser)) {
				throw new XmlValidatorException("Invalid input parameter, XmlParser object expected, ".gettype($parser)." given!");
			} else if(get_class($parser) != "XmlParser") {
				throw new XmlValidatorException("Invalid input parameter, XmlParser object expected, ".get_class($parser)." given!");
			}
		}
		catch(Exception $e) {
			throw $e;
		}
		self::$parser = $parser;
	}
	
	private static function entityReplace(&$val) {
		
		$entities = array("&" => "&amp;","<" => "&lt;",">" => "&gt;", "\"" => "&quot;", "'" => "&#39;");
		
		foreach($entities as $char => $ent) {
			$val = str_replace($char,$ent,$val);
		}
		
	} 
	
	
	public static function parseNodes(DOMNodeList $nodes,&$result,&$selected) {
		
		foreach($nodes as $child) {
			
			$children =  self::$parser->fetch("./i:node",$child);
			
			if($children->length) {
				self::parseNodes($children, $result, $selected);
			} else {
				$key = self::$parser->get($child);
				$value = self::$parser->get($child,"label");
				$result[$key] = $value;
				if(self::$parser->get($child,"selected") === "true") {
					$selected[$value] = $key;
				}	
			}
		}
		
	}
	
	public static final function add($key,$value) {
		
		try {
			if(!$key || !is_string($key) || !($m = preg_match("/^[a-zA-Z0-9\/_\]\[ \-=]+$/",$key))) {
				throw new XmlValidatorException("Invalid input parameter 1, string expected, ".gettype($key)." given!");
			} else if((!is_string($value) && !is_bool($value) && !is_array($value) && get_class($value) != "nodeListIterator" && get_class($value) != "DOMElement")) {
				throw new XmlValidatorException("Invalid input parameter 2, string, array or nodeListIterator object expected, ".get_class($value)." given!");
			}
		}
		catch(Exception $e) {
			throw $e;
		}
		
		$key = explode('/',$key);
		self::$ret[] = array("key" => $key, "value" => $value);
	}
	
	public static final function getParser() {
		return self::$parser;
	}
	
	public static final function getRetVal() {
		return self::$ret;
	}
	
	public static final function clearRetVal() {
		self::$ret = array();
	}
	
	
	public static function parseVariables(&$value,$node,$mode = false) {
		
		$ret = array();
		$void = false; 
		$skip = array("i:field","i:fields");
		
		if(preg_match("/\/[^\/]+\//",$value) && !$mode) {
			return $ret;
		}	
		
		$attribute_holder = sfContext::getInstance()->getActionStack()->getLastEntry()->getActionInstance()->getVarHolder()->getAll();
		
		if(self::$parser) {
			$action_holder = self::$parser->vars[self::$parser->currentUri];
			$uri = self::$parser->currentUri;			
		} 
		else {
			$action_holder = $attribute_holder;
			$uri = "action's";
		}
		
		if(strstr($value,"{")) {
			preg_match_all("/(\{[^\}]+\})/",$value,$matches);
			foreach($matches[1] as $tmp) {
				$match = true;
				$tmp = preg_replace("/[\{\}]+/","",$tmp);
				
				if(is_array($action_holder) && array_key_exists($tmp,$action_holder)) {
					$ret[$tmp] = $attribute_holder[$tmp];
					$node->parsed = true;	
				} else {
					throw new XmlParserException("The variable \"".$tmp."\" cannot be found among ".$uri." variables!");
				}	
			}	
			
		}	
		
		return ($void) ? array() : $ret;
		
		
	}
	
	
	public static function parseValue($value,$node,$mode = false,$vars = null) {
		
		if(is_null($vars)) {
			$vars = self::parseVariables($value,$node,$mode);	
		}
		
		if(strstr($value,"{")) {
			
			foreach($vars as $varname => $varvalue) {
				if(!is_array($varvalue)) {
					$value = str_replace("{".$varname."}",$varvalue,$value);
				}
				
			}
			
		} 
		
		return $value;
		
	}
	
	public static final function parseValues(&$vars) {
		
		$nodelist = false;
		
		try {
			if(!$vars && !is_array($vars) && get_class($vars) != "nodeListIterator") {
				throw new XmlValidatorException("Invalid input parameter 1, array or nodeListIterator expected, ".gettype($vars)." given!");
			} else if(get_class($vars) == "nodeListIterator") {
				if($vars->getMode() != NODES) {
					throw new XmlValidatorException("nodeListIterator mode must be ".NODES.", ".$vars->getMode()." given!");	
				} else {
					$nodelist = true;
				}
				
			}
			 
		}
		catch(Exception $e) {
			throw $e;
		}
		
		foreach($vars as $key => $var) {
			$item = ($nodelist) ? self::$parser->get($var) : $var;
			if(strstr($item,"{")) {

				if(!property_exists($var,"parsed") || !$var->parsed) {
					$tmp = self::parseValue($item,$var);	
				} else {
					$tmp = $item;
				}
				
				if($nodelist) {
					self::$parser->set(null,$tmp,$var);
				} else {
					$vars[$key] = $tmp;	
				}	
					
			} else {
				if($nodelist) {
					self::$parser->set(null,$item,$var);
				} else {
					$vars[$key] = $item;	
				}
			}
		}		
	}
	
	protected static function parse($node,$parent,$key = null) {}
	
	
	
}


?>
