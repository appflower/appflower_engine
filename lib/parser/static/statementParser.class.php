<?php

class statementParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		self::add("datasource/statement",self::$parser->get($node));
		
		return true;
		
	}
	
}


?>