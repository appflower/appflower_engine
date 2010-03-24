<?php

class attributeParser extends XmlBaseElementParser {
	
	
	public static function toArray($str,$key) {
		
		$str = trim($str);
		
		if(substr($str,0,1) == "[") {
			$tmp = explode(",",$str);
			foreach($tmp as $item) {
				$item = trim($item,"[");
				$tmp2 = explode(":",$item);
				if(substr(trim($tmp2[1]),0,1) != "[") {
					self::add($key."/".trim($tmp2[0]),trim($tmp2[1],"[]"));	
				} else {
					$ok = $tmp2[0];
					unset($tmp2[0]);
					self::toArray(implode(":",$tmp2),$key."/".trim($ok,"[]"));	
				}
			}	
		}
	}
	
	public static function parse($node,$parent,$key = null) {
		
		$attribute_holder = sfContext::getInstance()->getActionStack()->getLastEntry()->getActionInstance()->getVarHolder()->getAll();
		$iteration = self::$parser->getIteration();
		$view = self::$parser->getView();
		
		if(!$key) {
			$name = self::$parser->get($node,"name");	
		}
		
		$attributes = self::$parser->attributes($node);
		
		foreach ($attributes as $attrName => $attrValue) {
			
			if(strstr($attrValue,"{")) {
				$attrValue = self::parseValue($attrValue,$node,true);
				self::$parser->set($attrName,$attrValue,$node);
			}
			
			if(!$key) {
				$k = "fields/".$name."/attributes/".$attrName;
			} else {
				$k = $key."/".$attrName;
			}
			
			if(substr($attrValue,0,1) == "[") {
				if($attrName == "filter") {
					if(strstr($attrValue,"options:")) {

						$pos = strpos($attrValue,"options:")+8;
						$pos2 = strpos($attrValue,"]",$pos);
						$options = substr($attrValue,$pos,$pos2-$pos+1);
						
						$attrValue = str_replace("options:".$options.",","",$attrValue);
						
						$options = explode(",",preg_replace("/[\[\]]+/","",$options));
						self::add($k."/options",$options);
					}
						
					self::toArray($attrValue,$k);
					
					
				}
					
			}
			
			
			if(($attrName == "url" || $attrName == "action") && substr($attrValue,0,1) != "/" && substr($attrValue,0,1) != "#" && !strstr($attrValue,"://"))  {
				if(!self::$parser->has($node,"is_script") || self::$parser->get($node,"is_script") === "false") {
					$attrValue = "/".$attrValue;
					
				}
				
			}
			
		if(self::$parser->has($node,"is_script")) {
			
				
			}
			
			if($attrName == "confirmMsg" && trim($attrValue)) {
				$attrValue = str_replace("\\n","<br />",$attrValue);
			}
			
			self::add($k,$attrValue);	
			
			
		}
		
		return true;
		
	}
	
}


?>