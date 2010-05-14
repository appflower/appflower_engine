<?php

/**
 * studio actions.
 *
 * @package    manager
 * @subpackage studio
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class studioActions extends sfActions
{
 
	private
	    $element_groups,
		$attribute_groups,
		$imported_namespaces,
		$data_types,
		$schema;
	
	public function getAttribute($attr) {
		
			if($attr->getAttribute("name") == "parsable" || $attr->getAttribute("name") == "container" || $attr->getAttribute("name") == "assignid") {
				return false;
			}
		
			$k = "default";
			if($attr->hasAttribute("fixed")) {
				$k = "fixed";
			}
			
			$dtype = $attr->getAttribute("type");
			if(strstr($dtype,"i:")) {
				$type = str_replace("i:","",$dtype);
			} else {
				$type = str_replace("xs:","",$dtype);
			}
			
		
			return array("name" => $attr->getAttribute("name"), $k => $attr->getAttribute($k), "datatype" => ($type == "token") ? "string" : $type);
		
	}
	
	public function getElement($element,$type = "edit",$lang = "en") {
		
		$element = str_replace("i:","",$element);
		
		$ret[$element] = array(
			"docs" => null,
			"children" => array(),
			"attributes" => array(),
			);
		$result = $this->schema->evaluate("//xs:element[@name='".$element."']");
		
		if(!$result->length) {
			$ret[$element]["error"] = "Tag doesn't exsist!";
			return $ret;
		}
		
		$node = $result->item(0);
		
		// Checking type
		
		$result = $this->schema->evaluate("./descendant::*[@i:view]",$node);
		$is_typed = ($result->length);
		
		// Fetch docs
		
		$result = $this->schema->evaluate("./xs:annotation/xs:documentation",$node);
		
		$ret[$element]["docs"] = $result->item(0)->nodeValue;
		
		// May contian text?
		
		$is_text_enabled = ($this->schema->evaluate("./xs:complexType[@mixed='true']",$node)->length);
		
		// Fetch children and attributes
		
		for($i = 1; $i < 3; $i++) {
			if($i == 1) {
				$prefix = "element";
				$key = "children";
				if($is_typed) {
					$xpath = "./descendant::xs:*[@i:view='".$type."']/descendant::xs:".$prefix."[@ref]|./descendant::xs:*[@i:view='".$type."']/descendant::xs:group[@ref]|./descendant::xs:*[@i:view='".$type."']/descendant::xs:any[@namespace]";	
				} else {
					$xpath = "./descendant::xs:".$prefix."[@ref]|./descendant::xs:group[@ref]|./descendant::xs:any[@namespace]";	
				}
			} else {
				$prefix = "attribute";
				$key = "attributes";
				$xpath = "./descendant::xs:".$prefix."|./descendant::xs:".$prefix."Group[@ref]";
			}
			
			$result = $this->schema->evaluate($xpath,$node);
			
			foreach($result as $attr) {
				
				if($attr->nodeName == "xs:".$prefix) {
					$ret[$element][$key][] = ($i == 1) ? $attr->getAttribute("ref") : $this->getAttribute($attr);	
				}else if($attr->nodeName == "xs:any" && $i == 1) {
					$ret[$element][$key][] = "Any ".$this->imported_namespaces[$attr->getAttribute("namespace")];
				} else {
					$ret[$element][$key] = array_merge($ret[$element][$key],($i == 1) ? $this->element_groups[str_replace("i:","",$attr->getAttribute("ref"))] : 
					$this->attribute_groups[str_replace("i:","",$attr->getAttribute("ref"))]);
				}
			}
		
		}
		
		$ret[$element]["children"] = array_unique($ret[$element]["children"]);
		$ret[$element]["attributes"] = array_filter($ret[$element]["attributes"]);
		
		if($is_text_enabled) {
			$ret[$element]["children"][] = "Any text";
		}
		
		return $ret;
	}
	
	public function executeStructinfo() {
		
		//Console::profile('struct start');
		
		// Params of call
		
		$expression = $this->getRequestParameter("node",null);
		$type = $this->getRequestParameter("type","edit");
		$lang = $this->getRequestParameter("lang","en");
		$do = $this->getRequestParameter("do","nodes");
		
		// Read Schema
		
		$root = sfConfig::get("sf_root_dir");
		$this->schema = XmlParser::readDocumentByPath($root."/plugins/appFlowerPlugin/schema/appflower.xsd",true,array("prefix" => "xs", "uri" => "http://www.w3.org/2001/XMLSchema"));
		
		// Data types
		
		if($do == "types") {

			$result = $this->schema->evaluate("//xs:simpleType");
			
			foreach($result as $dtype) {
				$data = $this->schema->evaluate("./descendant::xs:restriction|./descendant::xs:union|./descendant::xs:documentation[@xml:lang='".$lang."']",$dtype);
				$type_name = $dtype->getAttribute("name");
				
				if(!$type_name) {
					continue;
				}
				
				foreach($data as $info) {	
					
					if($info->nodeName == "xs:documentation") {
						$this->data_types[$type_name]["docs"] = $info->nodeValue;
						continue;
					} else if($info->nodeName == "xs:union") {
						$this->data_types[$type_name]["union"] = str_replace("i:","",$info->getAttribute("memberTypes"));
						continue;
					}
					
					$base = $info->getAttribute("base");
					
					foreach($info->childNodes as $child) {
					  
						if(!($child instanceof DOMElement) || ($child->nodeName != "xs:enumeration" && $child->nodeName != "xs:maxInclusive" &&
						$child->nodeName != "xs:minInclusive" && $child->nodeName != "xs:pattern")) {
	                		continue;
	            		}
	            	  
	            		if($child->nodeName == "xs:maxInclusive" || $child->nodeName == "xs:minInclusive") {
	            	  		$this->data_types[$type_name]["type"] = "range";
	            	  		$this->data_types[$type_name]["base"] = str_replace("xs:","",$base);
	            	  	} else if($child->nodeName == "xs:enumeration") {
	            	  		$this->data_types[$type_name]["type"] = "enum";
	            	  	} else {
	            	  		$this->data_types[$type_name]["type"] = "pattern";
	            	  		continue;
	            	  	}
	            	  
	            	  	$this->data_types[$type_name]["values"][] = $child->getAttribute("value");  
	            	  
					}	
				}
			}

			$result = json_encode($this->data_types);
			return $this->renderText($result);
			
		}
	
		// Imported namespaces
		
		$result = $this->schema->evaluate("//xs:import");
		
		foreach($result as $ns) {
			$this->imported_namespaces[$ns->getAttribute("namespace")] = $ns->getAttribute("i:name"); 
		}
		
		// Attribute and element groups
		
		$groups = array("element","attribute");
		
		foreach($groups as $k => $prefix) {
			$result = $this->schema->evaluate(($prefix == "element") ? "//xs:group" :  "//xs:attributeGroup");
			
			foreach($result as $group) {
				$attrs = $this->schema->evaluate("./descendant::xs:".$prefix,$group);
				foreach($attrs as $attr) {
					if(!$k) {
						$this->element_groups[$group->getAttribute("name")][] = $attr->getAttribute("ref");		
					} else {
						$this->attribute_groups[$group->getAttribute("name")][] = $this->getAttribute($attr);	
					}
				}
			}	
		}
		
		if($expression === null) {
			$result = $this->getElement("i:view",$type,$lang);
		} else {
			$result = $this->getElement($expression,$type,$lang);
		}
		
		$result = json_encode($result);
		//Console::profile('struct end');
		return $this->renderText($result);
	}
	
	
}
