<?php

class rowactionsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		actionsParser::parse($node,$parent,"rowactions");
		
		return true;
		
	}
	
}


?>