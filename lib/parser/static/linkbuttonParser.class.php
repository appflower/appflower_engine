<?php

class linkbuttonParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$name = self::$parser->get($node,"name");
		$attributes = self::$parser->attributes($node);
		
		try {
			foreach ($attributes as $attrName => $attrValue) {
				if($attrName == "icon" && (!file_exists(getcwd()."/".$attrValue) || !is_readable(getcwd()."/".$attrValue))) {
					throw new XmlParserException("The icon: ".$attrValue." doesnt exist or is not readable!");
				}
			}	
			
			attributeParser::parse($node,$parent,"fields/".$name."/attributes");
		}
		catch(Exception $e) {
			throw $e;
		}
		
		
		return true;
		
	}
	
}


?>