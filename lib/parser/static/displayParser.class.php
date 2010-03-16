<?php

class displayParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:visible|./i:hidden",$node);
		$it = new nodeListIterator($nodes);
		
		foreach($it as $n) {
			self::add("display/".self::$parser->name($n),explode(",",self::$parser->get($n)));
		}
		
		return true;
		
	}
	
}


?>