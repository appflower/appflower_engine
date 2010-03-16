<?php

class rootParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$process = self::$parser->getProcess();
		
	
		$name = self::$parser->get($parent,"name");
		$type = self::$parser->get(self::$parser->container($node,"area"),"type");
		$component_name = "rootnode";

		if(isset($process["parses"][0]["areas"][$type]["tabs"])) {
			foreach($process["parses"][0]["areas"][$type]["tabs"] as $key => $tab) {
				foreach($tab["components"] as $cname => $c) {
					if(substr($cname,0,4) == "tree") {
						$tabid = $key;
						break;
					}
				}
			}	
		} else {
			$tabid = 0;
		}
		
		
		attributeParser::parse($node,$parent,"areas/".$type."/tabs/".$tabid."/components/".$name."/".$component_name);
		self::add("areas/".$type."/tabs/".$tabid."/components/".$name."/".$component_name."/level","1");
		self::$parser->set("name",$component_name,$node);
		
		return true;
	}
	
}


?>