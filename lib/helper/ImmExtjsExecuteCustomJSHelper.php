<?php
function parameterForEvent($event){
	$params = '';
	$handler = $event;
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
function setHandler(&$action){	
	foreach($action['handlers'] as $key=>$value){		
		$filename = $value['action'];
		//$filename = substr($filename,1,strlen($filename));
		//$action['attributes']['handlers'][$key] = array("parameters" => parameterForEvent($key),"source"=>"afApp.widgetPopup('$filename','')");
		$action['attributes']['handlers'][$key] = array("parameters" => parameterForEvent($key),"source"=>createSourceForEvent($value));
	}
}
function getHandler($action){
	$handler = array();
	foreach($action['handlers'] as $key=>$value){
		$handler[$key] = array("parameters" => parameterForEvent($key),"source"=>createSourceForEvent($value));
	}
	return $handler;
}
function createSourceForEvent($data){
	
	$filename = $data['action'];
	$filename = substr($filename,1,strlen($filename));
	$variables = '';
	
	if(isset($data['params']) && isset($data['params']['title'])){
		$variables .= 'var title = "'.$data['params']['title'].'";';
	}
	if(isset($data['params']) && isset($data['params']['custom']) && $data['params']['custom']){
		$count = 0;
		foreach($data['params'] as $key=>$param){
			$count++;
			$variables .= 'var immParam_'.$key.' = "'.$param.'";';
		}
	}
	
	$source = $variables;
	
	if(file_exists("appFlowerPlugin/js/custom/".$filename))
		$source .= getHtmlSource("appFlowerPlugin/js/custom/".$filename);
	else {
		
		//Test for widget js execution.................
		$popup = new XmlParser(XmlParser::PANEL,false,false,true,true);
		
		$path = createPath($filename);
		
		$popup->readXmlDocument($path);
		$object = $popup->runParser(1,"object");
		
		
		
		$source .= 'var popup_widget = '.$object->privateName.';';
		$source .= getHtmlSource("appFlowerPlugin/js/custom/popup_window.js");
		//...................................................................
	}		
	return $source;
}
function preExecuteSource($file,$callback){	
	$code = 'var callback = \''.$callback.'\'';
	$code .= "\n\r";
	$code .= file_get_contents($file);
	return $code;
}
function popup_widget($text,$widget,$title=''){
	return '<a href="javascript:void(0)" onclick="afApp.widgetPopup(\''.$widget.'\',\''.$title.'\')">'.$text.'</a>';
}

function ajaxlink($text,$url,$title=''){
	return '<a href="#'.$url.'" title="'.$title.'">'.$text.'</a>';
}


function createPath($uri,$security=false){
	$root = sfConfig::get("sf_root_dir");
	$app = sfContext::getInstance()->getConfiguration()->getApplication();
	if(!$security) {		
		return $root."/apps/".$app."/modules/".strtok($uri,"/")."/config/".strtok("/").".xml";	
	} else {			
		return $root."/apps/".$app."/modules/".$uri."/config/security.xml";
	}
}
function getHtmlSource($url) {
	return @file_get_contents ($url);
}


?>
