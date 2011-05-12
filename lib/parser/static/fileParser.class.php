<?php

class fileParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$id = self::$parser->get($node,"name");
		$process = self::$parser->getProcess();
		
		if(isset($process["parses"][0]["datastore"]["files"][$id])) {
			throw new XmlParserException("Duplicate file name found: ".$id);
		}
		
		attributeParser::parse($node,$parent,"datastore/files/".$id."/attributes");
		
		return true;	
		
	}
	
}


?>