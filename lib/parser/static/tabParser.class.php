<?php

class tabParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$process = self::$parser->getProcess();
		
		if(!isset($process["parses"][0]["areas"]["content"]["tabs"])) {
			$tabid = 0;
		} else {
			$tabid = count($process["parses"][0]["areas"]["content"]["tabs"]);
		}
		
		$key = "areas/".self::$parser->get($parent,"type")."/tabs/".$tabid."/attributes";
		
		attributeParser::parse($node,$parent,$key);
		self::add($key."/id","tab".$tabid);
		self::$parser->set("id","tab".$tabid,$node);
		
		return true;
		
	}
	
}


?>