<?php

class alternateDescriptionsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		
		$nodes = self::$parser->fetch("./i:description",$node);
		
		foreach($nodes as $node) {
			self::add("descriptions/".self::$parser->get($node,"condition"),self::$parser->get($node));
		}
		
		return true;
		
	}
	
}


?>