<?php

class paramsParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$nodes = self::$parser->fetch("./i:param",$node);	
		
		foreach($nodes as $node) {
			$value = self::$parser->get($node);
			if(strstr($value,"\n")) {
				self::$parser->set(null,str_replace("\n","<br />",$value),$node);
			}
		}
		
		$it = new nodeListIterator($nodes);
		$tmp = self::$parser->getProcess();
		$iteration = self::$parser->getIteration();
		
		if($parent) {
			
			$pid = self::$parser->name($parent);
			
			if($pid == "component") {
				
				$name = self::$parser->get($parent,"name");
				
				foreach($tmp["parses"][$iteration]["areas"] as $aname => $area) {
					if(!isset($area["tabs"])) {
						$area["tabs"][0] = array("attributes" => array(), "components" => $area["components"]);
					}
					foreach($area["tabs"] as $tk => $tab) {
						foreach($tab["components"] as $cname => $component) {
							if($name == $cname) {
								$area_name = $aname;
								$tabid = $tk;
								break;
							}
						}	
					}
					
				}
				
				foreach($nodes as $var) {
					$v = self::$parser->get($var);
					if(substr($v,0,1) == "[") {
						$v = explode(",",preg_replace("/[\[\]]+/","",$v));
					} 
					if(isset($tmp["parses"][$iteration]["areas"][$area_name]["tabs"])) {
						self::add("areas/".$area_name."/tabs/".$tabid."/components/".$name."/params/".self::$parser->get($var,"name"),$v);	
					} else {
						self::add("areas/".$area_name."/components/".$name."/params/".self::$parser->get($var,"name"),$v);
					}
						
				}
					
			} else if($pid == "area") {
				$name = self::$parser->get($parent,"type");
				$it->setMode(VALUES);
				
				$i = 0;
				foreach($it as $v) {
					if(substr($v,0,1) == "[") {
						$v = explode(",",preg_replace("/[\[\]]+/","",$v));
					} 
					self::add("areas/".$name."/params/".$i,$v);
				$i++;
				}
			}
		    
		} else {
			
			if($it->getListLength() > 0) {
				self::parseValues($it);
				$it->rewind();
			}
			
			
			$i = 0;
			foreach($it as $v) {
				$val = self::$parser->get($v);
				if(!property_exists($v,"non_convertable") && substr($val,0,1) == "[") {
					$val = explode(",",preg_replace("/[\[\]]+/","",$val));
				} 
				self::add("params/".$i,$val);
				//Params with key also
				self::add("params/".self::$parser->get($v,"name"),$val);
			$i++;
			}
				
		}
		
		return true;
		
	}
	
}


?>