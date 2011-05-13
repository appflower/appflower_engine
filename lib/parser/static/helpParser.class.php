<?php

class helpParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$name = self::$parser->get($parent,"name");
		self::add("fields/".$name."/comment",self::$parser->get($node));
			
		return true;
		
	}
	
}


?>