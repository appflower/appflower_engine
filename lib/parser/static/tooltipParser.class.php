<?php

class tooltipParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$name = self::$parser->get($parent,"name");
		self::add("fields/".$name."/help",self::$parser->get($node));
		
		return true;
		
	}
	
}


?>