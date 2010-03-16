<?php

class moreactionsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		actionsParser::parse($node,$parent,"moreactions");
		
		if(self::$parser->has($node,"forceSelection")) {
			self::add("forceSelection",self::$parser->get($node,"forceSelection"));
		}
		else self::add("forceSelection","true");
		
		if(self::$parser->has($node,"confirmMsg")) {
			self::add("confirmMsg",self::$parser->get($node,"confirm"));
		}
		return true;
		
	}
	
}


?>