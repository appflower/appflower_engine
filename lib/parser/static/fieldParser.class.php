<?php

class fieldParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		return attributeParser::parse($node,$parent,$key);
	}
	
}


?>