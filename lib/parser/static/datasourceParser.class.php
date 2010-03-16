<?php

class datasourceParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$element = self::$parser->any("datasource");
		
		self::add("datasource/type",self::$parser->get($element,"type"));
		self::add("datasource/lister",self::$parser->get($element,"lister"));
		
		if(self::$parser->has($node,null,"statement")) {
			return true;
		}
		
		$class = self::$parser->get(self::$parser->getnode("class",$element));
		
		if(!class_exists($class)) {
			throw new XmlParserException("The class ".$class." doesn't exits!");
		}
		
		self::add("datasource/class",$class);
		
		$element = self::$parser->getnode("method",$element);
		$method = self::$parser->get($element,"name");
		$type = self::$parser->get($element,"type");

		if(!method_exists($class,$method)) {
			throw new XmlParserException("The method ".$method." doesn't exist in class ".$class);
		}
		
		$nodes = self::$parser->fetch("./i:param",$element);
		$it = new nodeListIterator($nodes);
		
		self::add("datasource/method/name",$method);
		self::add("datasource/method/type",$type);
		
		if($type == "instance") {
			if($it->getListLength() == 0) {
				throw new XmlParserException("Datasource method's type is instance, but no arguments provided!");
			} else if(!is_numeric(self::$parser->get($it->current())) && self::$parser->get($it->current()) != "{id}") {
				//throw new XmlParserException("Datasource method's type is instance, but first argument is not an id!");
			} else if($method == "retrieveByPk" && $it->getListLength() != 1) {
				throw new XmlParserException("Datasource method's type is instance, and method is retrieveByPk, but there are more than 1 arguments provided!");
			}		
		}
		
		
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
