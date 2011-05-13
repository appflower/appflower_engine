<?php

class showParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$name = self::$parser->get($node,"name");
		$attributes = self::$parser->attributes($node);
		
		foreach ($attributes as $attrName => $attrValue) {
			self::add("fields/".$name."/attributes/".$attrName,$attrValue);
		}
		
		return true;
		
	}
	
}


?>