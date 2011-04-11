<?php
/**
 * extJs Submit Button
 *
 */
class afExtjsSubmitButton extends afExtjsButton
{
	/**
	 * default attributes for the button
	 */
	public $attributes=array('text'=>'Submit',
							'disabled'=>false,
	  						'icon'=>'/images/famfamfam/accept.png');
	
	public function __construct($containerObject,$attributes=array(),$submitContainerObject=false)
	{		
		$this->afExtjs=afExtjs::getInstance();		
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'submit/RobustSubmitAction.js')));
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
		  									$this->afExtjs->asAnonymousClass(array(
		  									'url'=>$attributes['action'],
		  									'method'=>$attributes['method']
		  									))
		  									.');';		  									
				if(isset($attributes['preExecute']) && $attributes['preExecute']){	
					$pe_file = isset($attributes['preExecute'])?$attributes['preExecute']:'';
					sfProjectConfiguration::getActive()->loadHelpers("afExtjsExecuteCustomJS");
					$source = preExecuteSource($pe_file,$source);
				}	
				$this->attributes['handler']=$this->afExtjs->asMethod(array(
		  									'parameters'=>'',
		  									'source'=> $source
		  								));
			}
			else {				
				if(!isset($attributes['params']))
				{
					$attributes['params']=array();
				}
				$submitUrl = $attributes['action'];
				if (!StringUtil::startsWith($submitUrl, 'http')) {
					$submitUrl = sfContext::getInstance()->getRequest()->getUriPrefix().sfContext::getInstance()->getRequest()->getRelativeUrlRoot().$submitUrl;
				}				
				$attributes['params']['af_formcfg'] =
					afEditJsonRenderer::buildFormcfg($submitUrl,
						$submitContainerObject->getValidators(),
						ArrayUtil::get($attributes, 'wizard', false));
				unset($attributes['wizard']);
				
				/**
				 * timeout, sets the submit timeout action in milisecs, default to 300000ms(300s)
				 */
				if(!isset($attributes['timeout']))
				{
					$attributes['timeout']='300000';
				}
			
				$source = 'Ext.getCmp("'.$submitContainerObject->attributes['id'].'").getForm().doAction("robustsubmit", '.
		  									$this->afExtjs->asAnonymousClass(array(
		  									'url'=>$submitUrl,
		  									'waitMsg'=>'loading...',
		  									'params'=>$attributes['params'],
		  									
		  									/**
		  									 * set the timeout
		  									 */
		  									'timeout'=>$attributes['timeout'],
		  									'failure'=>$this->afExtjs->asMethod(array(
		  												'parameters'=>'form,action',
		  												'source'=>'var onclose=function(){if(action.result && action.result.redirect){var load=action.result.load || "center";afApp.load(action.result.redirect,load);}}; if(action.result){ if(action.result.message){Ext.Msg.alert("Failure", action.result.message, onclose);}}else{Ext.Msg.alert("Failure", "Some error appeared!", onclose);}')),
		  									'success'=>$this->afExtjs->asMethod(array(
		  												'parameters'=>'form,action',
		  												'source'=>'
									/**
		  							* Test for popuped window
		  							*/
		  							var _form = Ext.getCmp("'.$submitContainerObject->attributes['id'].'");
		  							
		  							var _win = null;
		  							if(_form){
		  								_win = _form.findParentByType("window");		  								
		  							}	  							
		  							/*************************************************************/
		  							var showInstantNotification = function(){
										if(message){
											if(showInWindow){
												var w = new Ext.Window({
													html:message,
													title:"Success",
													bodyStyle:"padding:10px",
													autoScroll:true,
													frame:true,		  										
													resizable:true,
													maximizable:true		  										
												}).show();		  									
												if(w.getBox().width > 600) w.setWidth(600);
												if(w.getBox().height > 400){ w.setHeight(400);w.setWidth(w.getWidth()+20)}
												w.center();		  									
											}else{
												new Ext.ux.InstantNotification({title:"Success",message:message});
											}
										}
		  								if(_win){		  								
		  									if(winProp.hidePopup === false) return;
		  									_win.close();		  									  									
		  									return false;
		  								}
		  							}
		  							/*reload load_widgets store*/
		  							var load_widgets=action.result.load_widgets ||action.options.params.load_widgets;
		  							if(load_widgets && load_widgets.length>0)
		  							afApp.reloadGridsData(load_widgets);				  							
		  							
		  							/*executeAfter - can execute a js code after submit, the js code comes from json response*/
		  							var executeAfter=action.result.executeAfter || false;
		  							if(executeAfter)eval(executeAfter);
		  							
		  							var confirm=action.result.confirm ||action.options.params.confirm; 
		  							var ajax=action.result.ajax ||action.options.params.ajax;
		  							var message=action.result.message ||action.options.params.message;
		  							var redirect=action.result.redirect ||action.options.params.redirect;
		  							var target=action.result.target ||action.options.params.target;
		  							var winProp=action.result.winProp ||action.options.params.winProp;
		  							var load=action.result.load || "center";
									
									var showInWindow=action.result.window ||action.options.params.window;
									var forceRedirect = action.result.forceRedirect;
		  							if(forceRedirect !== false) forceRedirect = true;		  							
		  							winProp = Ext.util.JSON.decode(winProp);		  							
		  							winProp = winProp || {};		  							
		  							Ext.apply(winProp,{forceRedirect:forceRedirect,isPopup:_win});
									
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
														showInstantNotification();
			  											afApp.load(redirect,load,target,winProp);				
			  										}
			  										
			  										return false; 
			  									
			  									}else{ 
			  										return true;
												}
											});
		  								}
		  								else{	  									
		  									if(redirect && (!confirm || confirm == "undefined")){
												showInstantNotification();		  										
		  										if(redirect&&redirect!="undefined"){
													afApp.load(redirect,load,target,winProp);
													return false; 
												}else{ 
													return true;
												}												
												Ext.Msg.buttonText = {yes: "Yes",no: "No"}		  										
		  									}else{
												showInstantNotification();
		  										/*Ext.Msg.alert("Success", message, function(){
													if(!confirm||confirm=="undefined"){
														if(redirect){
															afApp.load(redirect,"center",target,winProp);
														}'.(isset($attributes['afterSuccess'])?$attributes['afterSuccess']:'').'
													}
												});*/
												'.(isset($attributes['afterSuccess'])?$attributes['afterSuccess']:'').'
		  									}
		  								}		  								
		  							}else{
		  								if(redirect){
											showInstantNotification();
		  									afApp.load(redirect,load,target,winProp);
										}'.(isset($attributes['afterSuccess'])?$attributes['afterSuccess']:'').'
		  							} '
		  										  								
		  								))
		  									))
		  									.');';
				if(isset($attributes['preExecute']) && $attributes['preExecute']){	
					$pe_file = isset($attributes['preExecute'])?$attributes['preExecute']:'';
					sfProjectConfiguration::getActive()->loadHelpers("afExtjsExecuteCustomJS");
					$source = preExecuteSource($pe_file,$source);
				}	
				$this->attributes['handler']=$this->afExtjs->asMethod(array(
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
