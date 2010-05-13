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
		$schema;
	
	public function getAttribute($attr) {
		
			$k = "default";
			if($attr->hasAttribute("fixed")) {
				$k = "fixed";
			}
		
			return array("name" => $attr->getAttribute("name"), $k => $attr->getAttribute($k));
		
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
		
		if($is_text_enabled) {
			$ret[$element]["children"][] = "Any text";
		}
		
		return $ret;
	}
	
	public function executeStructinfo() {
		
		// Read Schema
		
		$root = sfConfig::get("sf_root_dir");
		$this->schema = XmlParser::readDocumentByPath($root."/plugins/appFlowerPlugin/schema/appflower.xsd",true,array("prefix" => "xs", "uri" => "http://www.w3.org/2001/XMLSchema"));
		
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
		
		// Params of call
		
		$expression = $this->getRequestParameter("node",null);
		$type = $this->getRequestParameter("type","edit");
		$lang = $this->getRequestParameter("lang","en");
	
		if($expression === null) {
			$result = $this->getElement("i:view",$type,$lang);
		} else {
			$result = $this->getElement($expression,$type,$lang);
		}
		
		$result = json_encode($result);
		return $this->renderText($result);
	}
	
	
}
