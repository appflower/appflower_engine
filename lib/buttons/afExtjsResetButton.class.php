<?php
/**
 * extJs Reset Button
 *
 */
class afExtjsResetButton extends afExtjsButton
{
	/**
	 * default attributes for the button
	 */
	public $attributes=array('text'=>'Reset',
							'disabled'=>false,
							'icon'=>'/images/famfamfam/application_form.png');
	
	public function __construct($containerObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->attributes['handler']=$this->afExtjs->asMethod(array(
	  									'parameters'=>'',
	  									'source'=>'Ext.getCmp("'.$containerObject->privateName.'").getForm().reset();'
	  								));		
	  								
	  	
		parent::__construct($containerObject,$attributes);
	}
}
?>