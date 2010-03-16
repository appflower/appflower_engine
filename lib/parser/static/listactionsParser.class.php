<?php

class listactionsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		actionsParser::parse($node,$parent,"listactions");
		
		if(self::$parser->has($node,"onTop")) {
			self::add("onTop",self::$parser->get($node,"onTop"));
		}else self::add("onTop","true");
		if(self::$parser->has($node,"onBottom")) {
			self::add("onBottom",self::$parser->get($node,"onBottom"));
		}else self::add("onBottom","true");
		if(self::$parser->has($node,"forceSelection")) {
			self::add("forceSelection",self::$parser->get($node,"forceSelection"));
		}else self::add("forceSelection","true");
		
		/*if(self::$parser->has($node,"confirm")) {
			self::add("confirm",self::$parser->get($node,"confirm"));
		}else self::add("confirm","true");
		*/
		
		return true;
		
	}
	
}


?>