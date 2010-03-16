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
			
			// There might be more error modes in the future.. For now, there is only one message.
			
			$nodes = self::$parser->fetch("./i:error",$node);
			
			foreach($nodes as $n) { 
				self::add("fields/".$name."/validators/".$validator."/error",self::$parser->get($n));				
			}
	
		}

		return true;
		
	}
	
}


?>