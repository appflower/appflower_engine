<?php
function context_menu($what='',$options=''){
	$cmenu_ip_address = new ImmExtjsMenu(array('stack'=>array("text"=>"foo")));
	$whats = explode(",",$what);
	$wcount = 0;
	foreach($whats as $what){
		$what = trim($what);
		if($what == "ip_address"){				
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'Ping Address','url'=>'/netflow/updateConnectivity?edit[0][test_action_value]=0','ajax'=>'true','param'=>'edit[0][host]'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'Trace Route','url'=>'/netflow/updateConnectivity?edit[0][test_action_value]=1','ajax'=>'true','param'=>'edit[0][host]'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'DNS Lookup','url'=>'/netflow/updateConnectivity?edit[0][test_action_value]=2','ajax'=>'true','param'=>'edit[0][host]'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'FWSource Query','url'=>'/netflow/updateConnectivity?edit[0][test_action_value]=3','ajax'=>'true','param'=>'edit[0][host]'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'FWDestination Qquery','url'=>'/netflow/updateConnectivity?edit[0][test_action_value]=4','ajax'=>'true','param'=>'edit[0][host]'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'Sans IP Query','url'=>'/netflow/updateConnectivity?edit[0][test_action_value]=5','ajax'=>'true','param'=>'edit[0][host]'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'WhoIs Query','url'=>'/netflow/updateConnectivity?edit[0][test_action_value]=6','ajax'=>'true','param'=>'edit[0][host]'));$ping->end();
			$wcount++;
			if($wcount <count($whats)){
				$cmenu_ip_address->addMember("-");
			}			
		}
		if($what == "local_server"){				
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'Show Server Status','url'=>'/eventmanagement/showServerStatus?','param'=>'ip_address'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'Show Server Details','url'=>'/eventmanagement/showServer?','param'=>'ip_address'));$ping->end();
			$ping = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'Edit Server','url'=>'/eventmanagement/editServer?','param'=>'ip_address'));$ping->end();
			$wcount++;
			if($wcount <count($whats)){
				$cmenu_ip_address->addMember("-");
			}			
		}
		if($what == "port"){				
			$menu = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'FwPort Query','url'=>'/netflow/portLookUp?edit[0][action_value]=0','ajax'=>'true','param'=>'edit[0][port]'));$menu->end();
			$menu = new ImmExtjsMenuItem($cmenu_ip_address,array('label'=>'Sans Port Query','url'=>'/netflow/portLookUp?edit[0][action_value]=1','ajax'=>'true','param'=>'edit[0][port]'));$menu->end();			
			$wcount++;
			if($wcount <count($whats)){
				$cmenu_ip_address->addMember("-");
			}			
		}
	}
	if(is_array($options)){
		if(count($whats)){
			$cmenu_ip_address->addMember("-");
		}
		$c = 0;
		foreach($options as $option){			
			if($option == "grid"){
				grid_menu($cmenu_ip_address);		
			}	
			$c++;
			if($c < count($options)){
				$cmenu_ip_address->addMember("-");
			}		
		}		
	}
	$cmenu_ip_address->end();
	return $cmenu_ip_address; 
}
/*
 * Context menu for grid
 */
function grid_menu($container){
	
	$item = new ImmExtjsMenuItem($container,array('label'=>'Toggle cell word wrap','source'=>
		'if('.$container->privateName.'.stack["cellDiv"].style.whiteSpace!="normal"){'.
			$container->privateName.'.stack["cellDiv"].style.whiteSpace="normal";'.
			
		'}else{'.
			$container->privateName.'.stack["cellDiv"].style.whiteSpace="nowrap";'.
		'}'
	));$item->end();
	$item = new ImmExtjsMenuItem($container,array('label'=>'Toggle row word wrap','source'=>
		
		'var divs = '.$container->privateName.'.stack["rowDivs"];
		if('.$container->privateName.'.stack["rowDivs"][0].style.whiteSpace!="normal"){
			
			for(i=0;i<divs.length;i++){
				divs[i].style.whiteSpace = "normal";
			}'.		
			
		'}else{
			for(i=0;i<divs.length;i++){
				divs[i].style.whiteSpace = "nowrap";
			}'.			
		'}'
	));$item->end();
	$item = new ImmExtjsMenuItem($container,array('label'=>'Grid word wrap','source'=>		
			$container->privateName.'.stack["grid"].getView().el.select(".x-grid3-cell-inner").setStyle({"white-space":"normal"})'		
		
	));$item->end();
			
}
function ajax_source($url){
	return 'var mask = new Ext.LoadMask(Ext.getBody(), { msg: "Working.. Please wait..." });
			mask.show();
			Ext.Ajax.request({				
			   url: "'.$url.',
			   method:"POST",
			   success: function(response){
			   
			   		var json = Ext.util.JSON.decode(response.responseText);
			   		var title = "Success";
			   		if(json.title) title = json.title;
			   		if(json.success){
			   			Ext.Msg.alert(title, json.message);
			   		}else{
			   			Ext.Msg.alert("Error", json.message);
			   			mask.hide();
			   			return;
			   		}
			   		if(json.redirect && json.target && json.winProp){
			   			window.open(json.redirect,json.target,json.winProp);
			   		}else if(json.redirect && json.target){
			   			window.open(json.redirect,json.target);
			   		}else if(json.redirect){
			   			afApp.loadCenterWidget(json.redirect);
			   		}
			   		mask.hide();
			   },
			   failure: function(){
			   		Ext.Msg.alert("Error", "Some error has occured. Please try again");
			   		mask.hide();
			   }
			});
			';
}
?>