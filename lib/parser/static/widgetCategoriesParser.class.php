<?php

class widgetCategoriesParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:category",$node);
		
		foreach($nodes as $node) {	
			self::add("categories/".self::$parser->get($node,"catid")."/id",self::$parser->get($node,"catid"));
			
			if(self::$parser->has($node,"name")) {
				self::add("categories/".self::$parser->get($node,"catid")."/name",self::$parser->get($node,"name"));
			}
		}
		
		return true;
		
	}
	
}


?>