<?php

class buttonParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$name = self::$parser->get($node,"name");
		$attributes = self::$parser->attributes($node);
		
		attributeParser::parse($node,$parent,"fields/".$name."/attributes");
		
		return true;
		
	}
	
}


?>