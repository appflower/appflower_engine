<?php

class componentParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		if(self::$parser->name($parent,true) == "area") {
			$tabid = 0;
			$type = self::$parser->get($parent,"type");
		} else {
			$tabid=  str_replace("tab","",self::$parser->get($parent,"id"));
			$type = self::$parser->get($parent->parentNode,"type");
		}
		
		$component = self::$parser->get($node,"name");
		$component_name = $component.preg_replace("/[\. ]+/","",microtime());
		
		attributeParser::parse($node,$parent,"areas/".$type."/tabs/".$tabid."/components/".$component_name);
		self::$parser->set("name",$component_name,$node);
		
		return true;
	}
	
}


?>