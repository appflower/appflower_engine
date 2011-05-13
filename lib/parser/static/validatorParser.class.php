<?php

class validatorParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
			
		$name = self::$parser->get($parent,"name");
		$validator = self::$parser->get($node,"name");
		
		if(!self::$parser->has($node)) {
			self::add("fields/".$name."/validators/".$validator,array());
		} else {
			$nodes = self::$parser->fetch("./i:param",$node);
			
			foreach($nodes as $n) {
				$v = self::$parser->get($n);
				if(substr($v,0,1) == "[") {
					$v = explode(",",preg_replace("/[\[\]]+/","",$v));
				}
				self::add("fields/".$name."/validators/".$validator."/params/".self::$parser->get($n,"name"),$v);				
			}
		}

		return true;
		
	}
	
}


?>
