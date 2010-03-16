<?php

class triggerParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$name = self::$parser->get($parent,"name");
		attributeParser::parse($node,$parent,"fields/".$name."/attributes/button");
		
		return true;
		
	}
	
}


?>