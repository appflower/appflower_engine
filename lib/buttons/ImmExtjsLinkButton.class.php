<?php
/**
 * extJs Link Button
 *
 */
class ImmExtjsLinkButton extends ImmExtjsButton
{
	/**
	 * default attributes for the button
	 */
	public $attributes=array('disabled'=>false,
							'icon'=>'/images/famfamfam/link.png');
	  						
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		if(isset($attributes['loadas'])) {
			$attributes['load'] = $attributes['loadas'];
			unset($attributes['loadas']);
		}
		$attributes['load'] = isset($attributes['load'])?$attributes['load']:'center';
		
		if(isset($attributes['url']))
		{	
			$source = 'afApp.load("'.preg_replace('/js=([a-zA-Z0-9]+)\.js/','',$attributes['url']).'","'.$attributes['load'].'");';	
			
			if(isset($attributes['preExecute']) && $attributes['preExecute']){	
				$pe_file = isset($attributes['preExecute'])?$attributes['preExecute']:'';
				sfProjectConfiguration::getActive()->loadHelpers("ImmExtjsExecuteCustomJS");
				$source = preExecuteSource($pe_file,$source);
			}	
			if(isset($attributes['handlerSource'])){	
				$source=$attributes['handlerSource'];
			}
			$this->attributes['handler']=$this->immExtjs->asMethod(array(
	  									'parameters'=>'',
	  									'source'=>$source
	  								));		
	  								
	  		unset($attributes['url']);
		}
		
		parent::__construct($containerObject,$attributes);
	}
}
?>
