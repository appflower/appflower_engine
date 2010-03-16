<?php

class titleParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$arr = array(self::$parser->get(self::$parser->any("title")));
		self::add("title",$arr[0]);
		
		return true;
		
	}
	
}


?>