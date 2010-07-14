<?php

class ExtEvent {
	
	public static function getEventArguments($event_type){
		$params = '';
		$handler = $event_type;
		if($handler == "beforestaterestore" || $handler == "beforestatesave" ||	$handler == "staterestore" || $handler == "statesave") {
			$params = "component,state";
		} else if($handler == "change") {
			$params = "field,newValue,oldValue";
		} else if($handler == "blur" || $handler == "focus" || $handler == "valid") {
			$params = "field";
		} else if($handler == "invalid") {
			$params = "field,msg";
		} else if($handler == "move") {
			$params = "component,x,y";
		} else if($handler == "resize") {
			$params = "component,adjWidth,adjHeight,rawWidth,rawHeight";
		} else if($handler == "specialkey") {
			$params = "field,e";
		} else if($handler == "check") {
			$params = "field,checked";
		} else if($handler == "autosize") {
			$params = "field,width";
		} else if($handler == "beforequery") {
			$params = "queryEvent";
		} else if($handler == "beforeselect" || $handler == "select") {
			$params = "combo,record,index";
		} else if($handler == "collapse" || $handler == "expand") {
			$params = "combo";
		} else if($handler == "click" || $handler == "mouseover" || $handler == "mouseout" || $handler == "keydown" || $handler == "keypress" || $handler == "keyup") {
			$params = "field,e";
		} else if($handler == "beforepush" || $handler == "beforesync" || $handler == "push" || $handler == "sync") {
			$params = "HtmlEditor,html";
		} else if($handler == "editmodechange") {
			$params = "HtmlEditor,sourceEdit";
		} else if($handler == "activate" || $handler == "initialize") {
			$params = "HtmlEditor";
		} else {
			$params = "component";
		}
		return $params;
	}
	
	
	public static function getEventSource($event) {
		
		$variables = '';

		if(isset($event['params'])){

			if(isset($event['params']['title'])){
				$variables .= 'var title = "'.$event['params']['title'].'";';
			}
			foreach($event['params'] as $key=>$param){
				$variables .= (isset($event['params']['custom']) && $event['params']['custom']) ?  'var immParam_'.$key.' = '.$param.';' : 
				'var '.$key.' = '.$param.';';
			}
			
		}
		
		return $variables.$event["action"];
		
	}
	
	
	public static function attachAll(Array &$holder) {
		
		if(!array_key_exists("handlers", $holder)) {
			return false;
		}
		
		$holder["attributes"]["handlers"] = array();
		
		foreach($holder["handlers"] as $type => $event) {
			$holder["attributes"]["handlers"][$type] = array("parameters" => self::getEventArguments($type), "source" => self::getEventSource($event));	
		}
		
	}
	
	public static function attach(Array &$holder, Array $event) {
		
		if(!array_key_exists("handlers", $holder["attributes"])) {
			$holder["attributes"]["handlers"] = array();
		}
		
		if(!array_key_exists($event["type"], $holder["attributes"]["handlers"])) {
			$holder["attributes"]["handlers"][$event["type"]] = array("parameters" => self::getEventArguments($event["type"]), "source" => self::getEventSource($event));	
		} else {
			$holder["attributes"]["handlers"][$event["type"]]["source"] .= self::getEventSource($event);
		}
		
		
	}
	
	public static function detach(Array &$holder,Array $event) {
		
		if(!array_key_exists("handlers",$holder["attributes"]) || !array_key_exists($event["type"],$holder["attributes"]["handlers"])) {
			return false;
		}
		
		$holder["attributes"]["handlers"][$event["type"]];
		
	}
	
	
}