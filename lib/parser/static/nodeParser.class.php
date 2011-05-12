<?php

class nodeParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
	
		$element = $node->parentNode;
		$name = "node".preg_replace("/[\. ]+/","",microtime());
		$kstr = array($name);
			
			while(get_class($element) == "DOMElement" && self::$parser->name($element) != "area") {	
				$kstr[] = self::$parser->get($element,"name");
				if(self::$parser->name($element) == "tree") {
					$kstr[] = "components";
				}
				
				$element = $element->parentNode;
			}
			
		$kstr[] = self::$parser->get($element,"type"); 
		$kstr = implode("/",array_reverse($kstr));
		$level = substr_count(substr($kstr,strpos($kstr,"rootnode")+strlen("rootnode")),"/")+1;
				
		attributeParser::parse($node,$parent,"areas/".$kstr);
		self::add("areas/".$kstr."/level",$level."");
		
		self::$parser->set("name",$name,$node);
		
		return true;
	}
	
}


?>