<?php

class treeParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		if(self::$parser->name($parent,true) == "area") {
			$tabid = 0;
			$type = self::$parser->get($parent,"type");
		} else {
			$tabid=  str_replace("tab","",self::$parser->get($parent,"id"));
			$type = self::$parser->get($parent->parentNode,"type");
		}
		
		$component_name = "tree".preg_replace("/[\. ]+/","",microtime());
		
		attributeParser::parse($node,$parent,"areas/".$type."/tabs/".$tabid."/components/".$component_name);
		self::add("areas/".$type."/tabs/".$tabid."/components/".$component_name."/attributes/tree","true");
		
		self::$parser->set("name",$component_name,$node);
		
		return true;
	}
	
}


?>