<?php

class valueParser extends XmlBaseElementParser {
	
	public static function parse($node,$parent,$key = null) {
		
		$type = self::$parser->get($node,"type");
		$name = self::$parser->get($parent,"name");
		$process = self::$parser->getProcess();
		$iteration = self::$parser->getIteration();
		
		$default = self::$parser->fetch("./i:default|./i:static",$node);
		$defvalue = $selected = "";
		
		if($default->length) {
			$default = $default->item(0);
			$kname = self::$parser->name($default,true);
			
			$children = self::$parser->fetch("./i:node",$default);
			if($children->length) {
				parent::parseNodes($children,$defvalue,$selected);
				if($selected) {
					self::add("fields/".$name."/value/".$kname."/selected",$selected);
				}
			} else {
				$defvalue = self::$parser->get($default);	
			}
			
			self::add("fields/".$name."/value/".$kname."/value",$defvalue);
		}

		
		if(self::$parser->find($node,"source")) {

			self::add("fields/".$name."/value/type","1");
			self::add("fields/".$name."/value/class",$process["parses"][$iteration]["datasource"]["class"]);
			self::add("fields/".$name."/value/method",self::$parser->get(self::$parser->getnode("source",$node),"name"));
		
		} else if(self::$parser->find($node,"item")) {
			self::add("fields/".$name."/value/type","3");
			
			$nodes = self::$parser->fetch("./i:item",$node);
			$it = new nodeListIterator($nodes,PAIRS);
			$ls = $it->getList();
			$ls_arr = array();
			foreach($ls as $node) {
				$ls_arr[$node->getAttribute("value")] = $node->nodeValue;
			}
			$it->setList($ls);
			self::add("fields/".$name."/value/default/value",$ls_arr);
			
			//$it->setAttribute("value");
			//self::add("fields/".$name."/value/items",$it);
		
		} else if($node) {
            $class = self::$parser->get(self::$parser->getnode("class",$node));
            if ($class) {
                self::add("fields/".$name."/value/type","2");
                self::add("fields/".$name."/value/class",$class);
                $method = self::$parser->getnode("method",$node);
                if ($method) {
                    self::add("fields/".$name."/value/method/name",self::$parser->get($method,"name"));

                    $nodes = self::$parser->fetch("./i:param",$method);
                    $it = new nodeListIterator($nodes);

                    if($it->getListLength() > 0) {
                        self::parseValues($it);
                        $it->rewind();
                        //$it->setMode(VALUES);
                    }

                    $i = 0;
                    foreach($it as $v) {
                        $val = self::$parser->get($v);
                        if(!property_exists($v,"non_convertable") && substr($val,0,1) == "[") {
                            $val = explode(",",preg_replace("/[\[\]]+/","",$val));
                        }
                        self::add("fields/".$name."/value/method/params/".$i,$val);
                    $i++;
                    }
                }
            } else if ($type == 'orm') {
                // falling back to class defined in datasource node with guessed getter method name
                self::add("fields/".$name."/value/type","1");
                self::add("fields/".$name."/value/class",$process["parses"][$iteration]["datasource"]["class"]);
                $nameParts = explode('[', $name);
                $nameParts = array_reverse($nameParts);
                $originalName = str_replace(']', '', $nameParts[0]);
                $originalNameGetterMethod = 'get' . sfInflector::camelize($originalName);
                self::add("fields/".$name."/value/method",$originalNameGetterMethod);
            }
			
		}
		
		return true;
		
	}
	
}


?>
