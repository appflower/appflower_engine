<?php

class extrahelpParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		self::add("extra",self::$parser->get($node));
		
		return true;
		
	}
	
}


?>