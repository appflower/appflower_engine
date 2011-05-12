<?php

class confirmParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		self::add("confirm/url",self::$parser->get($node,"url"));
		self::add("confirm/title",self::$parser->get($node,"title"));
		self::add("confirm/text",self::$parser->get($node));
		
		return true;
		
	}
	
}


?>