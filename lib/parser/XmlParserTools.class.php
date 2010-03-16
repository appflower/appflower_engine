<?php 

class XmlParserTools {

	private 
		$document,
		$namespace,
		$namespace_prefix;
	
	protected 
		$defaults = array(),
		$xpath;
	
	public $it;
	
	const
		DOUBLE_TREE = 1,
		DOUBLE_MULTI_COMBO = 2;
		
	function __construct($xml,$mode = 0) {
		
		// Set namespace
		
		$this->setNamespace($mode);
		
		// Set Document
		
		$this->setXmlDocument($xml);
		
		// Creating Iterator object..

		$this->it = new nodeListIterator();
				
		// Registering XPath object and our namespace

		$this->setXpath();
		
			
	}
		
		
	public static function buildOutput($ret,$filter = array(),$type = self::DOUBLE_TREE) {
		
		$options = array();
		
		$i = 0;
		$found = false;
		
		foreach($ret as $group => $item) {
			$found = false;
			foreach($filter as $tmp) {
				if($tmp["text"] == $group) {
					$found = true;
					break;
				}
			}
			if($found) {
				continue;
			}
			$options[$i] = array("text" => $group, "value" => rand(), "leaf" => false, "iconCls" => "folder");
			$options[$i]["children"] = array();
			foreach($item as $id => $sig) {
				$options[$i]["children"][] = array("text" => $sig, "value" => $id, "leaf"=> true, "iconCls" => "file");
			}
		$i++;
		}
		
		return $options;
		
	}
	
	protected function setXpath() {
			
			try {

				$this->xpath = new DOMXPath($this->document);

				if(!$this->xpath->registerNamespace($this->namespace_prefix,$this->namespace)) {
					throw XmlParserException("Unable to register immune namespace!");
				}

			}
			catch(Exception $e) {
				throw $e;
			}
			
		}
		

	public function getXml() {
		
		return $this->document;
	}
		
	protected function setXmlDocument($xml) {
			
		try {
			if(get_class($xml) != "DOMDocument") {
				throw new XmlParserException("The input parameter is missing or is not a DOMDocument object.");
			}
		}
		catch(Exception $e) {
			throw $e;
		}
				
		$this->document = $xml;
		
	}
	
	
	protected function normalizeDocument() {
		$this->document->normalize();
	}
	
	protected function setNamespace($mode = 0) {
		
		switch((int) $mode) {
			case 0:
				$this->namespace = "http://www.appflower.com/schema/";
				$this->namespace_prefix = "i";
				break;
			case 1:
				$this->namespace = "http://www.appflower.com/access/";
				$this->namespace_prefix = "s";
				break;
			default:
				$this->namespace = "http://www.appflower.com/schema/";
				$this->namespace_prefix = "i";
				break;
			
		}
		
	}
		
		protected function resetDefaults() {
			$this->defaults = array();
		}
		
		
		public function getNew($type,$name = null) {
			
			try {
				if($type == "elem") {
					return $this->document->createElementNS($this->namespace,$name);
				} else if($type == "doc") {
					return new DOMDocument();
				} else if($type == "attr") {
					return new DOMAttribute($name);
				} else {
					throw XmlParserException("Bad input parameter for type..");
				}
			}
			catch(Exception $e) {
				throw $e;
			}
		}
		
		
		public function container($element, $type = null) {
			
			$orig = $element;
			
			$element = $element->parentNode;
			
			if($type === null) {
				while(get_class($element) == "DOMElement" && !$this->has($element, "container")) {
				$element = $element->parentNode;	
				}	
			} else {
				while(get_class($element) == "DOMElement" && $this->name($element) != $type) {
					$element = $element->parentNode;	
				}
			}
			
			
			return (get_class($element) == "DOMElement") ? $element : null;
			
		}
		
		
		public function name($node,$local = true) {
			
			if(@get_class($node) == "DOMElement") {
				return ($local) ? $node->localName : $node->nodeName;
			} else {
				return $node;
			}
			
		}
		
		public function shift($list,$index = 0, $type = NODES) {
			
			try {
				if(get_class($list) != "DOMNodeList") {
					throw new XmlParserException("The input parameter is missing or is not a DOMNodeList object.");
				}
			}
			catch(Exception $e) {
				throw $e;
			} 
			
			
			if($index < 0 || $index > $list->length-1) {
				return false;
			}
			
			$item = $list->item($index);
			
			if($type == NODES) {
				return $item;
			} else if($type == VALUES) {
				return $this->get($item);
			} else {
				return $this->get($item,$type);
			}
			
		}
		
		
		public function attributes($element) {
			
			try {
				
				if(get_class($element) != "DOMElement") {
					throw new XmlParserException("Bad input parameter, only DOMNodeElement objects are accepted!");
				}
				
			}
			catch(Exception $e) {
				throw $e;
			}
			
			$ret = $found = array();
			$nodename = $this->name($element);
			
			foreach ($element->attributes as $attrName => $attrNode) {
				$ret[$attrName] = $attrNode->nodeValue;
				$found[] = $attrName;
			}
			
			if($nodename == "field" || $nodename == "column" || $nodename == "show") {
				$key = $nodename."Attributes";
			} else {
				$key = $nodename;
			}
			
			// Adding default and fixed attributes..
			if(isset($this->defaults[$key])) {
				foreach($this->defaults[$key] as $attribute => $value) {
					if(!isset($ret[$attribute])) {
						$ret[$attribute] = $value;
					}
				}	
			}
			
			
			return $ret;
		
		}
		
		
		public function read($expression,$context) {
			
			try {
				$res = $this->xpath->evaluate($expression,$context);
				if(!$res || gettype($res) == "string") {
					return $res;
				} else {
					throw new XmlParserException("Result of expression must be string or null!");
				}
	
			}
			catch(Exception $e) {
				throw $e;
			}
			
		}
		
		public function getnode($element,$context = null) {
			
			return $this->parseSingleElement($element,$context);
		
		}
		
		public function any($element) {
			
			return $this->parseSingleElement($element,null,true);
		
		}
		
		public function textonly($node) {
			
			$list = $this->fetch("./child::*",$node);	
			if($list->length == 0) {
				return true;
			}  
			
			return false;
			
		}
	
		public function childcount($node,$type = 1) {
			
			if($type == 1) {
				$type = "text()";
			} else if($type == 2) {
				$type = "node()";
			} else {
				$type = "*";
			}
			
			$res = $this->fetch("./child::".$type,$node);
			
			return $res->length;
		}
		
		
		public function has($element, $attribute = null, $child = null) {
			
			if($attribute === null || $child != null) {
				if($child === null) {
					$child = "*";
				} else {
					$child = $this->namespace_prefix.":".$child;
				} 
				return $this->fetch("./".$child,$element)->length;
			} else {
				return (int) $element->hasAttribute($attribute);
			}
			
		}
		
		public function count($element) {
			
			return $this->has($element);
		
		}
		
		public function find($element,$child) {
			
			return (bool) $this->has($element,null,$child);
		
		}
		
		public function fetch($expression,$contextnode = null) {

			if(!$contextnode) {
				$contextnode = $this->document;
			}

			return $this->xpath->evaluate($expression,$contextnode);

		}


		public function get($node,$nodevalue = -1,$array = false) {

			try {
				if(is_object($node)) {
					if($nodevalue == -1) {
						if(!property_exists($node,"nodeValue")) {
							throw new XmlParserException("Bad input parameter, only strings and DOMElement objects are accepted!");
						}

						return trim(($array) ? explode($array,XmlBaseElementParser::parseValue($node->nodeValue,$node)) : XmlBaseElementParser::parseValue($node->nodeValue,$node));
					} else {
						if(!$node->hasAttribute($nodevalue)) {
							throw new XmlParserException("Bad input parameter, the ".$node->nodeName." object has no ".$nodevalue." attribute!");
						}

						return trim(($array) ? explode($array,XmlBaseElementParser::parseValue($node->getAttribute($nodevalue),$node,true)) : XmlBaseElementParser::parseValue($node->getAttribute($nodevalue),$node,true));
					}


				} else {
					return trim($node);
				}
			}
			catch(Exception $e) {
				throw $e;
			}
		}
		
		
		public function set($name = null,$value,$node) {

			try {
				
				if(get_class($node) != "DOMElement") {
					throw new XmlParserException("Bad input parameter, only DOMNodeElement objects are accepted!");
				}
				
				if($name === null) {
					if(!property_exists($node,"nodeValue")) {
						throw new XmlParserException("Bad input parameter, only strings and DOMNondeElement objects are accepted!");
					}

					$node->nodeValue = $value;
					
				} else {
					$node->setAttribute($name,$value);
				}

				
			}
			catch(Exception $e) {
				throw $e;
			}
		
			return true;
		
		}
		
		private function parseSingleElement($element,$context = null,$anywhere = false) {

			try {

				if(!$element) {
					throw new XmlParserException("Bad input parameter, the element name must be specified!");
				} else if($context != null && get_class($context) != "DOMElement") {
					throw new XmlParserException("Bad input parameter, only DOMElement objects are accepted!");
				}
			}
			catch(Exception $e) {
				throw $e;
			}

			if($context === null) {
				$context = $this->document;
			}

			$prefix = ($anywhere) ? "//".$this->namespace_prefix.":" : "./".$this->namespace_prefix.":"; 

			return $this->shift($this->fetch($prefix.$element,$context),0,NODES);

		}
		
		
}

?>