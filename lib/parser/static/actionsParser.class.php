<?php

class actionsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:action",$node);
		$it = new nodeListIterator($nodes);
		
		if($key === null) {
			$key = "actions";
		}
		if(self::$parser->has($node,"forceSelection")) {
			self::add("forceSelection",self::$parser->get($node,"forceSelection"));
		}
		else self::add("forceSelection","true");
		
		if(self::$parser->has($node,"confirmMsg")) {
			self::add("confirmMsg",self::$parser->get($node,"confirm"));
		}
		if(self::$parser->has($node,"popup")) {
			self::add("popup",self::$parser->get($node,"popup"));
		}	
		
		self::$parser->set("name",$key,$node);	
		
		foreach($it as $n) {
			
			if(self::$parser->checkCredentials(null,$n)) {				
				
				$action = self::$parser->get($n,"name");
				
				attributeParser::parse($n,$node,$key."/".$action."/attributes");	
				
			}
		
		}		
		return true;		
	}
	
}


?>