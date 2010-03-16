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
		
		if(isset($attributes['url']))
		{	
			$source = 'window.location.href="'.preg_replace('/js=([a-zA-Z0-9]+)\.js/','',$attributes['url']).'"';
			if(isset($attributes['preExecute']) && $attributes['preExecute']){	
				$pe_file = isset($attributes['preExecute'])?$attributes['preExecute']:'';
				sfLoader::loadHelpers("ImmExtjsExecuteCustomJS");				
				$source = preExecuteSource($pe_file,$source);
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