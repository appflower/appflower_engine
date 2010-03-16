<?php

class proxyParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		self::add("proxy",self::$parser->get($node,"url"));
		
		if(self::$parser->has($node,"start")) {
			self::add("proxystart",self::$parser->get($node,"start"));	
		} else {
			self::add("proxystart","0");
		}
		
		if(self::$parser->has($node,"stateId"))
		{
			self::add("stateId",self::$parser->get($node,"stateId"));
		}
		
		return true;
		
	}
	
}


?>