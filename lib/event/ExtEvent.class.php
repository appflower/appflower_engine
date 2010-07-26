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
	
	public static function getButtonParams(&$action,$type,$iteration='',$select="false",$grid = null){		
		
		if($type == "moreactions"){
			if(!isset($action['attributes']['confirmMsg']))$action['attributes']['confirmMsg'] = "";
			if(!isset($action['attributes']['forceSelection']))$action['attributes']['forceSelection'] = "true";
			if(!isset($action['attributes']['confirm']))$action['attributes']['confirm'] = "true";
			if(!isset($action['attributes']['post']))$action['attributes']['post'] = "true";
		}else{
			if(!isset($action['attributes']['confirmMsg']))$action['attributes']['confirmMsg'] = "";
			if(!isset($action['attributes']['forceSelection']))$action['attributes']['forceSelection'] = "false";
			if(!isset($action['attributes']['confirm']))$action['attributes']['confirm'] = "false";
			if(!isset($action['attributes']['post']))$action['attributes']['post'] = "false";
		}
		if(!isset($action['attributes']['icon']))$action['attributes']['icon'] = ""; 
		if(!isset($action['attributes']['iconCls']))$action['attributes']['iconCls'] = "";
		if(!isset($action['attributes']['url']))$action['attributes']['url'] = "#";
		
		if(!isset($action["attributes"]["label"]))
		$action["attributes"]["label"] = ucfirst($action["attributes"]["name"]);						
		$action["attributes"]["name"] = $iteration."_".$action["attributes"]["name"];
		
		$confirmMsg = $action["attributes"]["confirmMsg"] == ""?"Are you sure to perform this operation?":$action["attributes"]["confirmMsg"];
		$noItemsSelectedFunction='';
		$noDataInGridFunction = '';
		$requestParams = '"';
		$params = '';
		$functionForUpdater = '';
		$successFunction='';		
		
		if($grid) {
			if($action["attributes"]["updater"] === "true") {
				$action["attributes"]["post"] = "true";			
				$updater = new ImmExtjsUpdater(array('url'=>$action["attributes"]["url"],'width' => 500));
				$functionForUpdater = $updater->privateName.'.start();';								
			}
			if($action["attributes"]["forceSelection"] != "false"){
				$noDataInGridFunction = '
					if(!'.$grid->privateName.'.getStore().getCount()){
						Ext.Msg.alert("No Data In Grid","There is no data on grid.");
						return;
					}
				';
				$noItemsSelectedFunction = '								
					if(!'.$grid->privateName.'.getSelectionModel().getCount()){
						Ext.Msg.alert("No items selected","Please select at least one item");
						return;
					}
				';
				if($select == "true"){
					$requestParams = '/selections/"+'.$grid->privateName.'.getSelectionModel().getSelectionsJSON()';
					$params = 'params:{"selections":'.$grid->privateName.'.getSelectionModel().getSelectionsJSON()}, ';
				} 
			}	
		} 
		
		if($action["attributes"]["updater"] != "true"){
			if($action["attributes"]["post"] != "false"){
				$successFunction = '
					Ext.Ajax.request({ 
						url: "'.$action["attributes"]["url"].'",
						method:"post", 
						'.$params.'
						success:function(response, options){
							response=Ext.decode(response.responseText);
							if(response.message){								
								var win = Ext.Msg.show({
								   title:"Success",
								   msg: response.message,
								   buttons: Ext.Msg.OK,
								   fn: function(){
									   	if(response.redirect){
											win.getDialog().suspendEvents();										
											afApp.load(response.redirect,response.load);
										}
								   },
								   icon: Ext.MessageBox.INFO
								});															
							}
							else
							{
								if(response.redirect){
									afApp.load(response.redirect,response.load);
								}
							}
						},
						failure: function(response,options) {
							if(response.message){
								Ext.Msg.alert("Failure",response.message);
							}
						}
					});
				';
			} else {
				$action["attributes"]["loadas"] = isset($action["attributes"]["loadas"])?$action["attributes"]["loadas"]:'center';
				$successFunction = 'afApp.load("'.$action["attributes"]["url"].$requestParams.',"'.$action["attributes"]["loadas"].'")';
			}
		}		
		
		//popup = true will overwrite the successFunction
        
		if(!isset($action['attributes']['popupSettings']))
		{
			$action['attributes']['popupSettings']="";
		}
		
		if(isset($action['attributes']['popup']) && $action['attributes']['popup'] && $action['attributes']['popup'] !=="false") $successFunction = 'afApp.widgetPopup("'.$action["attributes"]["url"].'","","","'.$action['attributes']['popupSettings'].'");';
		
		// Confirm function
		
		if($action["attributes"]["confirm"] != "false" && $action["attributes"]["confirmMsg"]){
			$confirmFunction = '
				Ext.Msg.show({
				   title:"Confirmation Required",
				   msg: "'.$confirmMsg.'",
				   buttons: Ext.Msg.YESNO,
				   fn: function(buttonId){if(buttonId == "yes"){'.$functionForUpdater.$successFunction.'}},
				   icon: Ext.MessageBox.QUESTION								   
				});
			';
		}else{
			$confirmFunction = $functionForUpdater.$successFunction;
		}							
		
		$sourceForButton = $noDataInGridFunction.$noItemsSelectedFunction.$confirmFunction;		
		
		$handlersForMoreActions = array(
				'click'=>array(
					'parameters'=>'field,event',
					'source'=>(isset($action['attributes']['script'])?$action['attributes']['script']:'').";".$sourceForButton
				)
			);
		if(isset($action["handlers"])){
			//sfLoader::loadHelpers(array('ImmExtjsExecuteCustomJS'));
			//setHandler($action);
			
			if(!isset($action["attributes"]["handlers"]["click"])) {
				$action["attributes"]["handlers"]["click"] = array('parameters'=>'field,event','source'=>(isset($action['attributes']['script'])?$action['attributes']['script']:'').";".$sourceForButton);
			} else {
				$action["attributes"]["handlers"]["click"]["source"] .= ((isset($action['attributes']['script'])?$action['attributes']['script']:'').";".$sourceForButton);
			}
			
			$handlersForMoreActions = $action["attributes"]["handlers"];
			
		}						
		
		$parameterForButton = array(
		
			'label'=>$action["attributes"]["label"],
			'icon' => $action["attributes"]["icon"],			
			'iconCls' => $action["attributes"]["iconCls"],
			'listeners'=>$handlersForMoreActions
		);
		
		return ComponentCredential::filter($parameterForButton,$action['attributes']['url']);
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
