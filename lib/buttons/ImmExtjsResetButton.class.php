<?php
/**
 * extJs Reset Button
 *
 */
class ImmExtjsResetButton extends ImmExtjsButton
{
	/**
	 * default attributes for the button
	 */
	public $attributes=array('text'=>'Reset',
							'disabled'=>false,
							'icon'=>'/images/famfamfam/application_form.png');
	
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->attributes['handler']=$this->immExtjs->asMethod(array(
	  									'parameters'=>'',
	  									'source'=>'Ext.getCmp("'.$containerObject->privateName.'").getForm().reset();'
	  								));		
	  								
	  	
		parent::__construct($containerObject,$attributes);
	}
}
?>