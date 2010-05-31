<?php

class fieldsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$url = self::$parser->get($node,"url");
		$context = sfContext::getInstance();
		
		if($url == "n/a") {
			$url = "/".$context->getModuleName()."/".$context->getActionName();
		}
		
		self::add("form",$url);
		self::add("label",self::$parser->get($node,"label"));
		self::add("multipart",self::$parser->get($node,"multipart"));
		self::add("tree",self::$parser->get($node,"tree"));
		self::add("selectable",self::$parser->get($node,"selectable"));
		
		$exportable = explode(",",self::$parser->get($node,"exportable"));
	
		self::add("exportable",$exportable);
		self::add("title",self::$parser->get($node,"title"));
		self::add("pager",self::$parser->get($node,"pager"));
		self::add("select",self::$parser->get($node,"select"));
		self::add("submit",self::$parser->get($node,"submit"));
		self::add("classic",self::$parser->get($node,"classic"));
		self::add("border",self::$parser->get($node,"border"));
		self::add("portal",self::$parser->get($node,"portal"));
		self::add("permissions",self::$parser->get($node,"permissions"));
		self::add("remoteSort",self::$parser->get($node,"remoteSort"));
		self::add("labelWidth",self::$parser->get($node,"labelWidth"));
				
		$action = self::$parser->get($node,"action");
		self::add("action",$action);
		
		if(self::$parser->has($node,"expandButton")) {
			self::add("expandButton",self::$parser->get($node,"expandButton"));		
		}		
		if(self::$parser->has($node,"plugin")) {
			self::add("plugin",self::$parser->get($node,"plugin"));		
		}
		if(self::$parser->has($node,"remoteFilter")) {
			self::add("remoteFilter",self::$parser->get($node,"remoteFilter"));		
		}
		
		if(self::$parser->has($node,"remoteLoad")) {
			self::add("remoteLoad",self::$parser->get($node,"remoteLoad"));		
		}
		if(self::$parser->has($node,"exportFrom")) {
			self::add("exportFrom",self::$parser->get($node,"exportFrom"));		
		}
		
		if(self::$parser->has($node,"iconCls")) {
			self::add("iconCls",self::$parser->get($node,"iconCls"));		
		}
		
		if(self::$parser->has($node,"icon")) {
			self::add("icon",self::$parser->get($node,"icon"));		
		}
		
		if(self::$parser->has($node,"bodyStyle")) {
			self::add("bodyStyle",self::$parser->get($node,"bodyStyle"));		
		}
		
		if(self::$parser->has($node,"redirect")) {
			self::add("redirect",self::$parser->get($node,"redirect"));		
		}

		return true;
		
	}
	
}


?>