<?php

class bodyParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		self::add("message",self::$parser->get($node));
		
		if(self::$parser->has($node,"title")) {
			self::add("settitle",self::$parser->get($node,"title"));
		}
		
		return true;
		
	}
	
}


?>