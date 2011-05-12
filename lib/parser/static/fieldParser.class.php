<?php

class fieldParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {

		return attributeParser::parse($node,$parent,$key);
		
		if(self::$parser->has($node,"width")) {
			self::add("width",self::$parser->get($node,"width"));		
		}
		
		if(self::$parser->has($node,"module")) {
			self::add("module",self::$parser->get($node,"module"));		
		}
		
		if(self::$parser->has($node,"action")) {
			self::add("action",self::$parser->get($node,"action"));		
		}
		
	}
	
}


?>