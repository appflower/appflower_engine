<?php

class descriptionParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		if(self::$parser->name($parent,true) == "alternateDescriptions") {
			return true;
		}
		
		self::add("description",self::$parser->get($node));
		
		if(self::$parser->has($node,"image")) {
   			self::add("image",self::$parser->get($node,"image"));
		}
		
		return true;
		
	}
	
}


?>