<?php

class scriptsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		
		sfLoader::loadHelpers(array('afUrl'));
	
		$value = self::$parser->get($node);
		
		$tmp = explode(",",$value);
		
		foreach($tmp as $k => $file) {
			if(substr($file,0,1) == "/") {
				$uri = uri_for($file);
			}
		}
		
		self::add("scripts",self::$parser->get($node));
			
		return true;
		
	}
	
}


?>