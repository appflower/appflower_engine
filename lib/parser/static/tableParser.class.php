<?php

class tableParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:ref",$node);
		$id = (self::$parser->has($node,"alias")) ? self::$parser->get($node,"alias") : self::$parser->get($node,"name");
		$process = self::$parser->getProcess();
		
		if(isset($process["parses"][0]["datastore"]["tables"][$id])) {
			throw new XmlParserException("Duplicate table name found: ".$id);
		}
		
		attributeParser::parse($node,$parent,"datastore/tables/".$id."/attributes");
		self::add("datastore/tables/".$id."/attributes/class",self::$parser->get($node,"name"));	
		
		$i = 0;
		foreach($nodes as $n) {
			attributeParser::parse($n,$node,"datastore/tables/".$id."/fields/".$i);	
		$i++;
		}
		
		return true;	
		
	}
	
}


?>