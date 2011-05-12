<?php

class moreactionsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		actionsParser::parse($node,$parent,"moreactions");
		return true;
	}
	
}


?>
