<?php

class groupingParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:by",$node);
		
		$it = new nodeListIterator($nodes);
		
		if($it->getListLength() > 0) {
			foreach($it as $n) {
				self::add("grouping/".self::$parser->get($n,"ref"),self::$parser->get($n,"callback"));
			}	
		} else {
			
			self::$parser->createFieldSets();
			return -1;
		}
				
		return true;
		
	}
	
}


?>