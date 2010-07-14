<?php

class handlerParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$parent = $node->parentNode;
		
		$name = self::$parser->get($parent,"name");	
		$handlerType = self::$parser->get($node,"type");

		self::$parser->enumCheck("i:handlerType",$handlerType);
		
		$actions = array("actions","rowactions","moreactions");
		
		$handlerParams = self::$parser->fetch("./i:param",$node);
		$data = array("script"=>self::$parser->get($node,"action"));

		//$parentNodeName = self::$parser->name($parent);
	
		$k = self::$parser->name($parent->parentNode)."/".$name."/handlers/".$handlerType;
	
		attributeParser::parse($node,$parent,$k);
		
		if(count($handlerParams)) {
			
			$params = array();
			
			foreach($handlerParams as $hp){										
				self::add($k."/params/".self::$parser->get($hp,"name"),self::$parser->get($hp));									
			}					
			
			
		}
		
		return true;
		
	}
	
}


?>