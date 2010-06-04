<?php

class datasourceParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$element = self::$parser->any("datasource");
		
		self::$parser->enumCheck("i:valueType",self::$parser->get($element,"type"));
		
		self::add("datasource/type",self::$parser->get($element,"type"));
		self::add("datasource/lister",self::$parser->get($element,"lister"));
		
		if(self::$parser->has($node,null,"statement")) {
			return true;
		}
		
		$class = self::$parser->get(self::$parser->getnode("class",$element));
		
		self::add("datasource/class",$class);
		
		$element = self::$parser->getnode("method",$element);
		$method = self::$parser->get($element,"name");
		$type = self::$parser->get($element,"type");

		self::$parser->enumCheck("i:fetchType",$type);
		
		$nodes = self::$parser->fetch("./i:param",$element);
		$it = new nodeListIterator($nodes);
		
		self::add("datasource/method/name",$method);
		self::add("datasource/method/type",$type);
		
		if($it->getListLength() > 0) {
			self::parseValues($it);
			$it->rewind();
			$it->setMode(VALUES);	
		}
		
		$i = 0;
		
		foreach($it as $v) {
			if(substr($v,0,1) == "[") {
				$v = explode(",",preg_replace("/[\[\]]+/","",$v));
			} 
			self::add("datasource/method/params/".$i,$v);	
			
		$i++;
		}
		
		return true;
		
		
		
		
		
	}
	
}


?>
