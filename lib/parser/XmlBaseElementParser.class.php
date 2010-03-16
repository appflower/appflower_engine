<?php

class XmlBaseElementParser  {
	
	public static 
		$parser,
		$ret = array(),
		$tree,
		$tree_root,
		$tree_item;
	
	public static final function init($node) {
		
		$val = self::$parser->get($node);
		
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
		
		if($m) {
			$tmp = explode("/",$key);
			$key = "";
			foreach($tmp as $v) {
				$key .= "['".trim($v)."']";
			}
		}
		
		
		if(is_string($value) || @get_class($value) == "DOMElement") {
			self::$ret[] = array("key" => $key, "value" => $value);
		} else {
			self::$ret[] = array("key" => $key, "value" => array());
			if((is_object($value) && $value->getListLength() > 0) || !empty($value)) {
				$k = sizeof(self::$ret)-1;
				foreach($value as $v) {
					if(is_array($v)) {
						self::$ret[$k]["value"][key($v)] = $v;
					} else {
						self::$ret[$k]["value"][] = $v;	
					}
				}	
			}		
		}	
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
		
		if(strstr($value,"{")) {
			preg_match_all("/(\{[^\}]+\})/",$value,$matches);
			foreach($matches[1] as $tmp) {
				$match = true;
				$tmp = preg_replace("/[\{\}]+/","",$tmp);
				if(isset($attribute_holder[$tmp]) || is_null($attribute_holder[$tmp])) {
					$ret[$tmp] = $attribute_holder[$tmp];
					$node->parsed = true;	
				} else {
					throw new XmlParserException("The variable ".$tmp." cannot be found in attribute holder!");
				}	
			}	
			
		}	
		
		return ($void) ? array() : $ret;
		
		
	}
	
	
	public static function parseValue($value,$node,$mode = false) {
		
		$vars = self::parseVariables($value,$node,$mode);
		
		if(strstr($value,"{")) {
			
			foreach($vars as $varname => $varvalue) {
				$value = str_replace("{".$varname."}",$varvalue,$value);
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

				if(!$var->parsed) {
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