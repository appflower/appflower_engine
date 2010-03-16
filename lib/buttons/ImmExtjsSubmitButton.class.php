<?php
/**
 * extJs Submit Button
 *
 */
class ImmExtjsSubmitButton extends ImmExtjsButton
{
	/**
	 * default attributes for the button
	 */
	public $attributes=array('text'=>'Submit',
							'disabled'=>false,
	  						'icon'=>'/images/famfamfam/accept.png');
	
	public function __construct($containerObject,$attributes=array(),$submitContainerObject=false)
	{		
		$this->immExtjs=ImmExtjs::getInstance();		
		$pe = isset($attributes['preExecute'])?$attributes['preExecute']:'';
		if(isset($attributes['label']))
		{
			$this->attributes['text']=$attributes['label'];
			unset($attributes['label']);
		}
		
		if(isset($attributes['action']))
		{
			$this->attributes['url'] = $attributes['action'];
            $attributes['action']=UrlUtil::addParam($attributes['action'],
                '_csrf_token', sfContext::getInstance()->getRequest()->getAttribute('_csrf_token'));
			if(!isset($attributes['method']))
			{
				$attributes['method']='post';
			}
		
			$submitContainerObject=($submitContainerObject)?$submitContainerObject:$containerObject;
			
			if(isset($submitContainerObject->attributes['classic'])&&$submitContainerObject->attributes['classic'])
			{
				$source = 'Ext.getCmp("'.$submitContainerObject->attributes['id'].'").submit('.
		  									$this->immExtjs->asAnonymousClass(array(
		  									'url'=>$attributes['action'],
		  									'method'=>$attributes['method']
		  									))
		  									.');';
				if(isset($attributes['preExecute']) && $attributes['preExecute']){	
					$pe_file = isset($attributes['preExecute'])?$attributes['preExecute']:'';
					sfLoader::loadHelpers("ImmExtjsExecuteCustomJS");				
					$source = preExecuteSource($pe_file,$source);
				}	
				$this->attributes['handler']=$this->immExtjs->asMethod(array(
		  									'parameters'=>'',
		  									'source'=> $source
		  								));
			}
			else {				
				
				if(!isset($attributes['params']))
				{
					$attributes['params']=array();
				}
				
				/**
				 * timeout, sets the submit timeout action in milisecs, default to 300000ms(300s)
				 */
				if(!isset($attributes['timeout']))
				{
					$attributes['timeout']='300000';
				}
				$source = 'Ext.getCmp("'.$submitContainerObject->attributes['id'].'").getForm().submit('.
		  									$this->immExtjs->asAnonymousClass(array(
		  									'url'=>$attributes['action'],
		  									'waitMsg'=>'loading...',
		  									'params'=>$attributes['params'],
		  									/**
		  									 * set the timeout
		  									 */
		  									'timeout'=>$attributes['timeout'],
		  									'failure'=>$this->immExtjs->asMethod(array(
		  												'parameters'=>'form,action',
		  												'source'=>'var onclose=function(){if(action.result && action.result.redirect){window.location.href=action.result.redirect;}}; if(action.result){ if(action.result.message){Ext.Msg.alert("Failure", action.result.message, onclose);}}else{Ext.Msg.alert("Failure", "Some error appeared!", onclose);}')),
		  									'success'=>$this->immExtjs->asMethod(array(
		  												'parameters'=>'form,action',
		  												'source'=>'
		  							var normalRedirect = function(location,target,winProp){		  							
		  								if(target && winProp){
		  									window.open(location,target,winProp);
		  								}else if(target){
		  									window.open(location,target);
		  								}else{
		  									window.location.href=location;
		  								}
		  							}
		  							var confirm=action.result.confirm ||action.options.params.confirm; 
		  							var ajax=action.result.ajax ||action.options.params.ajax;
		  							var message=action.result.message ||action.options.params.message;
		  							var redirect=action.result.redirect ||action.options.params.redirect;
		  							var target=action.result.target ||action.options.params.target;
		  							var winProp=action.result.winProp ||action.options.params.winProp;
		  							var win;
		  							
		  							if(message){
		  							
		  								if(confirm){
		  									Ext.Msg.confirm("Confirmation",message, function(btn){
		  									
			  									if (btn=="yes"&&redirect&&redirect!="undefined"){ 
			  										
			  										if(ajax)
			  										{
			  											Ext.Ajax.request({ 
				  											url: redirect, 
				  											method:"post",
				  											success:function(response, options){
				  												response=Ext.decode(response.responseText);
																if (!response.success) {
				  													Ext.Msg.alert("Failure",response.message||"Unable to do the operation.");
																	return;
				  												}
				  												if(response.message){
				  													Ext.Msg.alert("Success",response.message);
				  												}
				  											}
			  											});
			  										}
			  										else
			  										{
			  											normalRedirect(redirect,target,winProp);
			  											//window.location.href=redirect;
			  											
			  										}
			  										
			  										return false; 
			  									
			  									}else{ 
			  										return true;
												}
											});
		  								}
		  								else{
		  								
		  									/*
		  									* If redirection is present but not the confirmation, the message dialog can be replaced 
		  									* with a confirmation dialog to provide the user the options to redirect or not to redirect.
		  									* Since user has to click one OK on message dialog anyway, if it is replaced with confirmation
		  									* the OK on confirmation will act as OK on message dialog, with an extra option not to redirect
		  									* by clicking CANCEL. User can add multiple items without being redirected.
		  									*
		  									* This is useful for the case of ajax widget popups, for example in combo box widget popups, where
		  									* after adding item it automatically redirects the page, making popups useless.
		  									*/
		  									if(redirect && (!confirm || confirm == "undefined")){
		  										Ext.Msg.buttonText = {yes: "Ok",no: "Stay Here"}
		  										Ext.Msg.show({
												   title:"Success",
												   msg: message,
												   buttons: Ext.Msg.YESNO,
												   
												   fn: function(btn){		  									
													   if (btn=="yes"&&redirect&&redirect!="undefined"){
													   		normalRedirect(redirect,target,winProp); 
														   //window.location.href=redirect;
														   return false; 
													   }else{ 
													  	 return true;
													   }
												   },												  
												   icon: Ext.MessageBox.QUESTION
												});
												Ext.Msg.buttonText = {yes: "Yes",no: "No"}		  										
		  									}else{
		  										Ext.Msg.alert("Success", message, function(){
													if(!confirm||confirm=="undefined"){
														if(redirect){
															normalRedirect(redirect,target,winProp);
															//window.location.href=redirect;
														}'.(isset($attributes['afterSuccess'])?$attributes['afterSuccess']:'').'
													}
												});
		  									}
		  								}		  								
		  							}else{
		  								if(redirect){
		  									normalRedirect(redirect,target,winProp);
		  									//window.location.href=redirect;
										}'.(isset($attributes['afterSuccess'])?$attributes['afterSuccess']:'').'
		  							} '
		  										  								
		  								))
		  									))
		  									.');';
				if(isset($attributes['preExecute']) && $attributes['preExecute']){	
					$pe_file = isset($attributes['preExecute'])?$attributes['preExecute']:'';
					sfLoader::loadHelpers("ImmExtjsExecuteCustomJS");				
					$source = preExecuteSource($pe_file,$source);
				}	
				$this->attributes['handler']=$this->immExtjs->asMethod(array(
		  									'parameters'=>'',
		  									'source'=>$source
		  								));		
		  								
		  		unset($attributes['action']);
		  		unset($attributes['afterSuccess']);
		  		unset($attributes['params']);
		  		unset($attributes['timeout']);
	  		
			}
		}
		
		parent::__construct($containerObject,$attributes);
	}
}
?>
