<?php

class XmlValidator {

	private 
		$schema,
		$document,
		$file,
		$application,
		$is_valid = false,
		$cached = false,
		$cache_only,
		$idxml,
		$hash;

	function XmlValidator($path = null,$security = false,$cli = false,$cache_only = false, $idxml = null) {
		
		$this->root = sfConfig::get("sf_root_dir");
		
		if(!$security) {
			$this->schema = $this->root."/plugins/appFlowerPlugin/schema/appflower.xsd";	
		} else {
			$this->schema = $this->root."/access/access.xsd";
		}
		
		if(!$cli) {
			
			$this->application = sfContext::getInstance()->getConfiguration()->getApplication();
			
			$sf = sfContext::getInstance();
			$action = $sf->getActionName();
			$module = $sf->getModuleName();	
		}
	
		try {
			if(!$cli) {
				if(!$path && !trim($action)) {
					throw new Exception("Unbale to create class, action name argument is missing!");
				} else if(!$path && !trim($module)) {
					throw new Exception("Unbale to create class, module name argument is missing!");
				}
			
				if(!$path) {
                    $fileCU = new afConfigUtils($module);
					$file = $fileCU->getConfigFilePath($action.'.xml', true);
				} else {
					$file = $path;
				}
			
			}
			
		
			if(!file_exists($this->schema)) {
				throw new XmlValidatorException("The schema file doesn't exist!");
			} else if(!is_readable($this->schema)) {
				throw new XmlValidatorException("Unbale to read the schema file!");
			}
			
		}
		catch(Exception $e) {
			throw($e);
		}
		
		libxml_use_internal_errors(true);
		$this->cache_only = $cache_only;
		
		if(trim($idxml)) {
			$this->idxml = $idxml;
		}
		
		//appflower:validator-cache cache yes
		
		if(!$cli) {
			$this->hash = sha1_file($file);
			$this->readXmlDocument($file);	
		}
		
		
	}
	
	public function readXmlDocument($file,$cli = false) {
		
		try {
			
			if($cli) {
				if(!$file) {
					throw new XmlValidatorException("The file argument is missing!");
				} else if(!file_exists($file)) {
					throw new XmlValidatorException("The input file: ".$file." does't exist!");
				} else if(!is_readable($file)) {
					throw new XmlValidatorException("Unbale to read input file: ".$file."!");
				}

				$this->hash = sha1_file($file);
				$this->file = $file;
				
				if($this->cache_only) {
					return true;
				}
				
			}
			
			$this->document = new DOMDocument();
			$this->document->load($file);
			$this->file = $file;
	
		}
		catch(Exception $e) {
			throw($e);
		}
		
	} 
	
	
	public function validateXmlDocument($cli = false) {
		
		if($this->cache_only) {
			$this->putCache();
			return array("INFO",null);
		}
		
		$obj = $this->inCache();
		
		if(!$obj || $obj->getSignature() != $this->hash) {

			try {
				if (!@$this->document->schemaValidate($this->schema)) {
					$errors = libxml_get_errors();
					if(!empty($errors)) {
						throw new XmlValidatorException($errors,0,$cli);
					}
				}
			}
			catch(Exception $e) {
				if($cli) {
					return array("ERROR",$e);
				}
				throw($e);
			}
			
			$this->putCache($obj);
			if($this->idxml) {
				afPortalStatePeer::deleteByIdXml($this->idxml);
			}
			
		} 
		
		$this->is_valid = true;
		
		return ($cli) ? array("INFO",null) : true;
	}
	
	
	private function inCache() {
		
		$this->cached = true;
		return afValidatorCachePeer::inCache($this->file);
		
	}
	
	
	private function putCache($obj = null) {
		
		afValidatorCachePeer::putCache($this->hash,$this->file,$obj);
	}
	
	public function validateIDs($xpath) {

		if($this->cached) {
			return true;
		}
		
		try {

			if(!$xpath || get_class($xpath) != "DOMXPath") {
				throw new XmlValidatorException("Invalid argument: DOMXPath object expected, ".gettype($xpath)." given!");
			}
			
			$list = $xpath->evaluate("//*[@id]");
			$ids = array();
			
			foreach($list as $node) {
				$id = $node->getAttribute("id");
				if(!in_array($id,$ids)) {
					$ids[] = $id;
				} else {
					throw new XmlValidatorException("Duplicate id found: ".$id."!"); 
				}
			}
			
		}
		catch(Exception $e) {
			throw $e;
		}
		
	}
	
	public function validateActions($xpath) {

		if($this->cached) {
			return true;
		}
		
		try {

			if(!$xpath || get_class($xpath) != "DOMXPath") {
				throw new XmlValidatorException("Invalid argument: DOMXPath object expected, ".gettype($xpath)." given!");
			}
			
			$list = $xpath->evaluate("//i:action");
			
			foreach($list as $node) {
				if($node->getAttribute("icon") && $node->getAttribute("iconCls")) {
					throw new XmlValidatorException("The action attributes iconCls and icon cannot be defined at the same time!");
				} 
				
			}
			
		}
		catch(Exception $e) {
			throw $e;
		}
		
	}
	
	public function validateParsingOrder($xpath) {

		if($this->cached) {
			return true;
		}
		
		try {

			if(!$xpath || get_class($xpath) != "DOMXPath") {
				throw new XmlValidatorException("Invalid argument: DOMXPath object expected, ".gettype($xpath)." given!");
			}
			
			$list = $xpath->evaluate("//*[@priority]");
			
			foreach($list as $node) {
				$attr = $node->getAttribute("priority");
				if($attr != 0) {
					if(!isset($found[$attr])) {
						$found[$attr][] = $node->nodeName;
					} else {
						if($node->nodeName != $found[$attr][0]) {
							throw new XmlValidatorException("Invalid parsing order! Elements of same order must have the same type!<br /><br />Type of priority ".
							$attr." item should be ".$found[$attr][0].", but ".$node->nodeName." given!");
						} else {
							$found[$attr][] = $node->nodeName;
						}
					}
				}
			}

		}
		catch(Exception $e) {
			throw $e;
		}

		return true;
					
	}
	
	public function getXmlDocument() {
		
		// TODO: turned off validation for now.
		
		$this->is_valid = true;
		
		try {
			if(!$this->is_valid) {
				throw new XmlValidatorException("The XML document hasn't been validated yet! Call the validateXmlDocument() method!");
			} else if(!$this->document) {
				throw new XmlValidatorException("Was unable to read XML document, no document object!");
			}
		}
		catch(Exception $e) {
			throw($e);
		}
		
		return $this->document;
	}
	
	public function setXmlDocument($document) {
		
		try {
			if(!$document) {
				throw new XmlValidatorException("The document argument is missing!");
			}else if(get_class($document) != "DOMDocument") {
				throw new XmlValidatorException("Invalid parameter, only DOMDocument object is allowed!");
			} 
		}
		catch(Exception $e) {
			throw($e);
		}
	
		$this->document = $document;
	} 


}

?>