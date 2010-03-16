<?php

class handlerParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
	
		$name = self::$parser->get($parent,"name");	
		$actions = array("actions","rowactions","moreactions");
		if(!in_array($name,$actions))
		self::add("fields/".$name."/handlers/".self::$parser->get($node,"type"),self::$parser->get($node,"action"));

		return true;
		
	}
	
}


?>