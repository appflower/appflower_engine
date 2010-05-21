<?php

class radiogroupParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$attributes = self::$parser->attributes($node);
		
		$children = self::$parser->fetch("./i:field",$node);
		
		$group = self::$parser->get($node,"name");
		
		foreach ($attributes as $attrName => $attrValue) {
			self::add("groups/".$group."/attributes/".$attrName,$attrValue);
		}
		
		$members = array();
				
		foreach($children as $n => $child) {
			
			if(self::$parser->checkCredentials(null,$child) === true) {
				$name = self::$parser->get($child,"name");
				
				if(self::$parser->has($child,"checked") && self::$parser->get($child,"checked") == "true") {
					self::add("fields/".$name."/attributes/checked","true");	
				}
				
				self::add("fields/".$name."/attributes/group",$group);
				self::add("fields/".$name."/attributes/islast",($n == $children->length-1) ? "true" : "false");
				$members[] = $name;	
			}
			
		}
		
		self::add("groups/".$group."/members",$members);
		
		return true;
		
	}
	
}


?>