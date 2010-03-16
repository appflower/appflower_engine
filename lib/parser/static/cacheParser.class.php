<?php

class cacheParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:ref",$node);
		$it = new nodeListIterator($nodes);
		
		$ret = array();
		
		foreach($it as $n) {
			$ret[] = self::$parser->get($n,"to");
		}
		
		self::add("cache",$ret);
				
		return true;
		
	}
	
}


?>