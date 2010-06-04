<?php

class actionsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:action|./i:if/i:action",$node);
		$it = new nodeListIterator($nodes);
		
		if($key === null) {
			$key = "actions";
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
