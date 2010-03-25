<?php

class areaParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		self::$parser->enumCheck("i:areaType",self::$parser->get($node,"type"));
		
		$key = "areas/".self::$parser->get($node,"type")."/attributes";
		
		attributeParser::parse($node,$parent,$key);
		
		if(self::$parser->has($node,null,"tab")) {
			self::add($key."/tabbed","true");
		} else {
			self::add($key."/tabbed","false");
			$x = array();
			self::add("areas/".self::$parser->get($node,"type")."/tabs/0/attributes",array());
		}
		
		
		$process = self::$parser->getProcess();
		
		
	}
	
}


?>